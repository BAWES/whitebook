<?php

namespace api\modules\v1\controllers;

use common\models\Themes;
use Yii;
use yii\helpers\ArrayHelper;
use yii\rest\Controller;
use yii\db\Expression;
use frontend\models\Vendor;
use common\models\CategoryPath;
use common\models\VendorItem;
use common\models\VendorLocation;
use common\models\VendorItemPricing;
use common\models\VendorItemMenuItem;
use common\components\CFormatter;
use api\models\EventItemlink;

/**
 * Auth controller provides the initial access token that is required for further requests
 * It initially authorizes via Http Basic Auth using a base64 encoded username and password
 */
class ProductController extends Controller
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // remove authentication filter for cors to work
        unset($behaviors['authenticator']);

        // Allow XHR Requests from our different subdomains and dev machines
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
            'cors' => [
                'Origin' => Yii::$app->params['allowedOrigins'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['*'],
                'Access-Control-Allow-Credentials' => null,
                'Access-Control-Max-Age' => 86400,
                'Access-Control-Expose-Headers' => [],
            ],
        ];

        // Bearer Auth checks for Authorize: Bearer <Token> header to login the user
        $behaviors['authenticator'] = [
            'class' => \yii\filters\auth\HttpBearerAuth::className(),
        ];
        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
        $behaviors['authenticator']['except'] = [
            'options',
            'category-products',
            'product-detail',
            'load-all-themes',
            'load-all-vendor',
            'final-price'
        ];

        return $behaviors;
    }


    /**
     * @inheritdoc
     */
    public function actions()
    {
        $actions = parent::actions();

        // Return Header explaining what options are available for next request
        $actions['options'] = [
            'class' => 'yii\rest\OptionsAction',
            // optional:
            'collectionOptions' => ['GET', 'POST', 'HEAD', 'OPTIONS'],
            'resourceOptions' => ['GET', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
        ];
        return $actions;
    }


    /**
     * @return array
     */
    public function actionCategoryProducts(
        $offset = 0,
        $category_id,
        $forSale = false,
        $requestedLocation = null,
        $requestedDeliverDate = null,
        $requestedMinPrice = 0,
        $requestedMaxPrice = 0,
        $requestedCategories = '',
        $requestedVendor = '',
        $requestedTheme = ''
    )
    {
        $products = [];
        $limit = Yii::$app->params['limit'];

        if ($requestedVendor) {
            $arr_vendor_slugs = $requestedVendor;
        }else{
            $arr_vendor_slugs = [];
        }

        $ActiveVendors = Vendor::loadvalidvendorids(
            false, //current category
            $arr_vendor_slugs, //only selected from filter
            '', //who available today
            ''//delivery on location available
        );

        $item_query = CategoryPath::find()
            ->select('{{%vendor_item}}.item_for_sale, {{%vendor_item}}.slug, {{%vendor_item}}.item_id, {{%vendor_item}}.item_id, {{%vendor_item}}.item_name, {{%vendor_item}}.item_name_ar, {{%vendor_item}}.item_price_per_unit, {{%vendor}}.vendor_id, {{%vendor}}.vendor_name, {{%vendor}}.vendor_name_ar')
            ->leftJoin(
                '{{%vendor_item_to_category}}',
                '{{%vendor_item_to_category}}.category_id = {{%category_path}}.category_id'
            )
            ->leftJoin(
                '{{%vendor_item}}',
                '{{%vendor_item}}.item_id = {{%vendor_item_to_category}}.item_id'
            )
            ->leftJoin(
                '{{%priority_item}}',
                '{{%priority_item}}.item_id = {{%vendor_item}}.item_id'
            )
            ->leftJoin('{{%vendor}}', '{{%vendor_item}}.vendor_id = {{%vendor}}.vendor_id')
            ->where([
                '{{%vendor_item}}.trash' => 'Default',
                '{{%vendor_item}}.item_approved' => 'Yes',
                '{{%vendor_item}}.item_status' => 'Active',
            ]);

        if ($forSale) {
            $item_query->andWhere(['{{%vendor_item}}.item_for_sale' => 'Yes']);
        }

        $item_query->andWhere(['in', '{{%vendor_item}}.vendor_id', $ActiveVendors]);

        //price filter
        if ($requestedMinPrice && $requestedMaxPrice) {

            $price_condition = [];

            $price_condition[] = '{{%vendor_item}}.item_price_per_unit IS NULL';
            $price_condition[] = '{{%vendor_item}}.item_price_per_unit between '.$requestedMinPrice.' and '.$requestedMaxPrice;

            $item_query->andWhere(implode(' OR ', $price_condition));
        }

        //theme filter
        if ($requestedTheme) {

            $item_query->leftJoin('{{%vendor_item_theme}}', '{{%vendor_item}}.item_id = {{%vendor_item_theme}}.item_id');
            $item_query->leftJoin('{{%theme}}', '{{%theme}}.theme_id = {{%vendor_item_theme}}.theme_id');
            $item_query->andWhere(['IN', '{{%theme}}.slug', $requestedTheme]);

        }//if themes

        //category filter
        $cats = '';

        if ($category_id)
        {
            $cats = $category_id;
        }

//        if (isset($requestedCategories) && count($requestedCategories) > 0)
//        {
//            $cats = implode("','",  $requestedCategories);
//        }

        if ($category_id != "all") {
            $q = "{{%category_path}}.path_id IN ('" . $cats . "')";
            $item_query->andWhere($q);
        }

        if ($requestedLocation) {


            if (is_numeric($requestedLocation)) {
                $location = $requestedLocation;
            } else {
                $address_id = substr($requestedLocation, strpos($requestedLocation, '_') + 1, strlen($requestedLocation));

                $location = \common\models\CustomerAddress::findOne($address_id)->area_id;
            }
            $item_query->andWhere('EXISTS (SELECT 1 FROM {{%vendor_location}} WHERE {{%vendor_location}}.area_id="'.$location.'" AND {{%vendor_item}}.vendor_id = {{%vendor_location}}.vendor_id)');
        }

        if ($requestedDeliverDate) {
            $date = date('Y-m-d', strtotime($requestedDeliverDate));
            $condition = " ({{%vendor}}.vendor_id NOT IN(SELECT vendor_id FROM `whitebook_vendor_blocked_date` where block_date = '".$date."')) ";
            $item_query->andWhere($condition);
        }

        $expression = new Expression(
            "CASE
                WHEN
                    `whitebook_priority_item`.priority_level IS NULL
                    OR whitebook_priority_item.status = 'Inactive'
                    OR whitebook_priority_item.trash = 'Deleted'
                    OR DATE(whitebook_priority_item.priority_start_date) > DATE(NOW())
                    OR DATE(whitebook_priority_item.priority_end_date) < DATE(NOW())
                THEN 2
                WHEN `whitebook_priority_item`.priority_level = 'Normal' THEN 1
                WHEN `whitebook_priority_item`.priority_level = 'Super' THEN 0
                ELSE 2
            END, {{%vendor_item}}.sort");

        $listing = $item_query
            ->groupBy('{{%vendor_item}}.item_id')
            ->orderBy($expression)
            ->asArray()
            ->offset($offset)
            ->limit($limit)
            ->all();

        if ($listing) {

            foreach ($listing as $item) {
                $image = \common\models\Image::find()
                ->where(['item_id' => $item['item_id']])
                ->orderBy(['vendorimage_sort_order' => SORT_ASC])
                ->one();
                if ($image) {
                    $products[] = $item + ['image'=>$image->image_path];
                } else {
                    $products[] = $item;
                }
            }
        }

        return $products;
    }

    /*
     * Method to view product detail
     */
    public function actionProductDetail($product_id)
    {
        return VendorItem::find()->where(['item_id'=>$product_id])->with(['images','vendor'])->asArray()->one();
    }

    /*
     * Method to add item in event
     */
    public function actionAddProductToEvent()
    {
        $item_id = Yii::$app->request->getBodyParam("item_id");
        $event_id = Yii::$app->request->getBodyParam("event_id");
        $event_name = Yii::$app->request->getBodyParam("event_name");
        if ($event_id && $item_id) {

            $model = new \frontend\models\Users();
            $customer_id = Yii::$app->user->getId();
            $item = VendorItem::findOne($item_id);
            $item_name = $item->item_name;

            $check = EventItemlink::find()->select('link_id')
                ->where(['event_id'=>$event_id])
                ->andWhere(['item_id'=>$item_id])
                ->count();
            if ($check > 0) {
                return [
                    "operation" => "error",
                    'message' => Yii::t('frontend', '{item_name} already exist with {event_name}',
                        [
                            'item_name' => $item_name,
                            'event_name' => $event_name,
                        ])
                ];
            } else {
                $event_date = date('Y-m-d H:i:s');
                $command = new EventItemlink();
                $command->event_id = $event_id;
                $command->item_id = $item_id;
                $command->link_datetime = $event_date;
                $command->created_datetime = $event_date;
                $command->modified_datetime = $event_date;
                $command->modified_by = $customer_id;
                if($command->save())
                {
                    return [
                        "operation" => "success",
                        'message' => Yii::t('frontend', '{item_name} has been added to {event_name}',
                            [
                                'item_name' => $item_name,
                                'event_name' => $event_name,
                            ])
                    ];
                }
            }
        }
    }

    public function actionProductAreas($vendor_id = '') {

        $userAddress = [];
        $customer_id = Yii::$app->user->getId();

        if ($vendor_id) {
            $vendor_area = VendorLocation::find()
                ->select(['{{%vendor_location}}.area_id,{{%location}}.location'])
                ->leftJoin('{{%location}}', '{{%location}}.id = {{%vendor_location}}.area_id')
                ->where(['{{%vendor_location}}.vendor_id' => $vendor_id])
                ->asArray()
                ->all();
        } else {
            $vendor_area = VendorLocation::find()
                ->select(['{{%vendor_location}}.area_id,{{%location}}.location'])
                ->leftJoin('{{%location}}', '{{%location}}.id = {{%vendor_location}}.area_id')
                ->asArray()
                ->all();
        }

        if ($customer_id) {
            $area_ids = \yii\helpers\ArrayHelper::map($vendor_area, 'area_id', 'area_id');

            $my_addresses = \common\models\CustomerAddress::find()
                ->select(['{{%location}}.id,{{%customer_address}}.address_id, {{%customer_address}}.address_name'])
                ->leftJoin('{{%location}}', '{{%location}}.id = {{%customer_address}}.area_id')
                ->where(['{{%customer_address}}.trash' => 'Default'])
                ->andwhere(['{{%customer_address}}.customer_id' => $customer_id])
                ->andwhere(['{{%location}}.id' => $area_ids])
                ->groupby(['{{%location}}.id'])
                ->asArray()
                ->all();

            foreach ($my_addresses as $address) {
                $userAddress[] = ['area_id' => 'address_' . $address['address_id'], 'location' => $address['address_name']];
            }
            return $userAddress + $vendor_area;
        }

        return $userAddress;
    }

    /*
     * Product delivery Time Slot
    */
    public function actionProductDeliveryTimeSlot($vendor_id, $date, $time, $current_date)
    {
        $list = [];
        if (empty($vendor_id) || !isset($vendor_id)) {
            return [
                "operation" => "error",
                'message' => 'Invalid Vendor ID'
            ];
        }

        if (empty($date) || !isset($date)) {
            return [
                "operation" => "error",
                'message' => 'Invalid Date'
            ];
        }

        if (empty($time) || !isset($time)) {
            return [
                "operation" => "error",
                'message' => 'Invalid Date'
            ];
        }

        if (empty($current_date) || !isset($current_date)) {
            return [
                "operation" => "error",
                'message' => 'Invalid Current Date'
            ];
        }

        $string = $date;
        $timestamp = strtotime($string);

        $vendor_timeslot = \common\models\DeliveryTimeSlot::find()
            ->select(['timeslot_id','timeslot_start_time','timeslot_end_time'])
            ->where(['vendor_id' => $vendor_id])
            ->andwhere(['timeslot_day' => date("l", $timestamp)])
            ->asArray()->all();

        if ($vendor_timeslot) {
            foreach ($vendor_timeslot as $key => $value) {
                if (strtotime($date) == (strtotime($current_date))) {
                    if (strtotime($time) < strtotime($value['timeslot_start_time'])) {
                        $start = date('g:i A', strtotime($value['timeslot_start_time']));
                        $end = date('g:i A', strtotime($value['timeslot_end_time']));
                        $list[] = array(
                            'id'=>$value['timeslot_id'],
                            'value'=>$start . ' - ' . $end
                        );
                    }
                } else {
                    $start = date('g:i A', strtotime($value['timeslot_start_time']));
                    $end = date('g:i A', strtotime($value['timeslot_end_time']));
                    $list[] = array(
                        'id'=>$value['timeslot_id'],
                        'value'=>$start . ' - ' . $end
                    );
                }
            }
            return $list;
        } else {
            return [];
        }
    }

    public function actionItemCapacity($product_id, $deliver_date) {
        $model = VendorItem::find()->where(['item_id'=>$product_id])->one();
        $capacity = $model->item_default_capacity;

        if (isset($model->vendorItemCapacityExceptions) && count($model->vendorItemCapacityExceptions)>0) {

            $exceptionDate = \yii\helpers\ArrayHelper::map($model->vendorItemCapacityExceptions, 'exception_date', 'exception_capacity');

            if (isset($exceptionDate) && count($exceptionDate) > 0) {
                if ($deliver_date && isset($exceptionDate[date('Y-m-d',strtotime($deliver_date))])) {
                    $capacity = $exceptionDate[date('Y-m-d',strtotime($deliver_date))];
                }
            }
        }
        return $capacity;
    }

    public function actionLoadAllThemes() {
        return Themes::findAll(['theme_status'=>'Active','trash'=>'Default']);
    }

    public function actionLoadAllVendor() {
        return $query = Vendor::find()
            ->andWhere(['{{%vendor}}.trash'=>'Default'])
            ->andWhere(['{{%vendor}}.approve_status'=>'Yes'])
            ->andWhere(['{{%vendor}}.vendor_status'=>'Active'])
            ->orderby(['{{%vendor}}.vendor_name' => SORT_ASC])
            ->groupby(['{{%vendor}}.vendor_id'])
            ->asArray()
            ->all();
    }

    public function actionFinalPrice() 
    {
        $item_id = Yii::$app->request->post('item_id');

        $item = VendorItem::findOne($item_id);

        if (empty($item)) 
        {
           return [
                "operation" => "error",
                "message" => "Package not found",
            ];
        }
        
        $total = ($item->item_base_price) ? $item->item_base_price : 0;

        $price_chart = VendorItemPricing::find()
            ->item($item['item_id'])
            ->defaultItem()
            ->quantityRange(Yii::$app->request->post('quantity'))
            ->orderBy('pricing_price_per_unit DESC')
            ->one();

        if ($item->item_minimum_quantity_to_order > 0) {
            $min_quantity_to_order = $item->item_minimum_quantity_to_order;
        } else {
            $min_quantity_to_order = 1;
        }

        if ($price_chart) {
            $unit_price = $price_chart->pricing_price_per_unit;
        } else {
            $unit_price = $item->item_price_per_unit;
        }

        $actual_item_quantity = Yii::$app->request->post('quantity') - $min_quantity_to_order;

        $total += $unit_price * $actual_item_quantity;

        $menu_items = Yii::$app->request->post('menu_item');

        if(!is_array($menu_items)) {
            $menu_items = [];
        }

        foreach ($menu_items as $key => $value) {
            
            $menu_item = VendorItemMenuItem::findOne($value['menu_item_id']);

            $total += $menu_item->price * $value['quantity'];
        }
        
        Yii::$app->response->format = 'json';

        return [
            "price" => CFormatter::format($total)
        ];
    }
}
