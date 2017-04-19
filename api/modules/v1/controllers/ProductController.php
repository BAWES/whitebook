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
use common\models\VendorItemMenu;

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
            'final-price',
            'product-delivery-time-slot'
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
        $requestedTheme = '',
        $event_time = ''
    )
    {
        $products = [];
        $limit = Yii::$app->params['limit'];

        if ($requestedVendor) {
            $arr_vendor_slugs = $requestedVendor;
        }else{
            $arr_vendor_slugs = [];
        }


        if ($category_id != 'all') {
            $Category = \common\models\Category::findOne($category_id);
        } else {
            $Category = '';
        }

        $ActiveVendors = Vendor::loadvalidvendorids(
            false, //current category
            $arr_vendor_slugs, //only selected from filter
            '', //who available today
            ''//delivery on location available
        );

        $item_query = CategoryPath::find()
            ->selectedFields()
            ->categoryJoin()
            ->itemJoin()
            ->priorityItemJoin()
            ->vendorJoin()
            ->defaultItems()
            ->approved()
            ->active();

        if ($forSale) {
            $item_query->sale();
        }


        $item_query->vendorIDs($ActiveVendors);

        //price filter
        if ($requestedMinPrice && $requestedMaxPrice) {
            $item_query->price($requestedMinPrice,$requestedMaxPrice);
        }

        //theme filter
        if ($requestedTheme) {

            $item_query->itemThemeJoin();
            $item_query->themeJoin();
            $item_query->themeSlug($requestedTheme);

        }//if themes

        //event time
        if($event_time) {
            $item_query->workingTimeJoin();
        }

        //category filter
        $cats = '';

        if($Category)
        {
            $cats = $Category->category_id;
        }

        if($cats)
        {
            $item_query->categoryIDs($cats);
        }

        if ($requestedLocation) {

            if (is_numeric($requestedLocation)) {
                $location = $requestedLocation;
            } else {
                $end = strlen($requestedLocation);
                $from = strpos($requestedLocation, '_') + 1;
                $address_id = substr($requestedLocation, $from, $end);

                $location = \common\models\CustomerAddress::findOne($address_id)->area_id;
            }

            $item_query->deliveryLocation($location);
        }

        if ($requestedDeliverDate) {
            $date = date('Y-m-d', strtotime($requestedDeliverDate));
            $item_query->deliveryDate($date);
        }

        if (!empty($event_time)) {

            $delivery_date = $requestedDeliverDate;

            if($delivery_date)
                $working_day = date('D', strtotime($delivery_date));
            else
                $working_day = date('D');

            $event_time = date('H:i:s', strtotime($event_time));

            $item_query->eventTime($event_time,$working_day);
        }

        $item_query_result = $item_query
            ->groupBy('{{%vendor_item}}.item_id')
            ->orderByExpression()
            ->asArray()
            ->offset($offset)
            ->limit($limit)
            ->all();

        if ($item_query_result) {

            foreach ($item_query_result as $item) {
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
        $itemData = VendorItem::find()->where(['item_id'=>$product_id])->with(['images','vendor'])->asArray()->one();
        if ($itemData) {
            $return['menu'] = VendorItemMenu::find()->with('vendorItemMenuItems')->item($product_id)->menu('options')->asArray()->all();
            $return['addons'] = VendorItemMenu::find()->with('vendorItemMenuItems')->item($product_id)->menu('addons')->asArray()->all();
            $return['item'] = VendorItem::find()->where(['item_id' => $product_id])->with(['images', 'vendor'])->asArray()->one();
            return $return;
        } else {
            return [
                "operation" => "error",
                "code" => "0",
                'message' => 'Invalid Item ID'
            ];
        }
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
                    "code" => "0",
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
                        "code" => "1",
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
    public function actionProductDeliveryTimeSlot($vendor_id, $event_date)
    {
        if (empty($vendor_id) || !isset($vendor_id)) {
            return [
                "operation" => "error",
                "code" => "0",
                'message' => 'Invalid Vendor ID'
            ];
        }

        if (empty($event_date) || !isset($event_date)) {
            return [
                "operation" => "error",
                "code" => "0",
                'message' => 'Invalid Event Date'
            ];
        }

        $string = $event_date;
        $timestamp = strtotime($string);
        $slots = [];

        $vendor_timeslot = \common\models\VendorWorkingTiming::find()
            ->select(['working_id','working_start_time','working_end_time'])
            ->vendor($vendor_id)
            ->workingDay(date("l", $timestamp))
            ->defaultTiming()
            ->asArray()
            ->all();

        if ($vendor_timeslot) {

            foreach ($vendor_timeslot as $key => $value) {
                $slots = array_merge($slots, $this->slots($value['working_start_time'], $value['working_end_time']));
            }
            return $slots;
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
        $item_id = Yii::$app->request->get('item_id');

        $item = VendorItem::findOne($item_id);

        if (empty($item)) {
            return [
                "operation" => "error",
                "code" => "0",
                'message' => 'Invalid Item ID'
            ];
        }

        $total = ($item->item_base_price) ? $item->item_base_price : 0;

        $price_chart = VendorItemPricing::find()
            ->item($item['item_id'])
            ->defaultItem()
            ->quantityRange(Yii::$app->request->get('quantity'))
            ->orderBy('pricing_price_per_unit DESC')
            ->one();

        if ($item->included_quantity > 0) {
            $included_quantity = $item->included_quantity;
        } else {
            $included_quantity = 1;
        }

        if ($price_chart) {
            $unit_price = $price_chart->pricing_price_per_unit;
        } else {
            $unit_price = $item->item_price_per_unit;
        }

        $actual_item_quantity = Yii::$app->request->get('quantity') - $included_quantity;

        $total += $unit_price * $actual_item_quantity;

        $menu_items = Yii::$app->request->get('menu_item');

        if(!is_array($menu_items)) {
            $menu_items = [];
        }


        foreach ($menu_items as $key => $value) {

            $menu_item = VendorItemMenuItem::findOne($value['menu_item_id']);
            $total += $menu_item->price * $value['quantity'];
        }
        return $total;
    }

    /*
     * method provide time slots interval between two time slots
     */
    private function slots($startTime = '11:00:00', $endTime = '22:45:00'){

        $slots = [];
        if ($startTime && $endTime) {

            $from = strtotime($startTime);
            $to ='';

            if($endTime == '00:00:00') {
                $endTime = '24:00:00';
            }

            while ($from < strtotime($endTime)) {

                $to = strtotime("+30 minutes", $from);

                if ($to > strtotime($endTime)) {
                    $slots[] = date('h:i A', $from);// . '-' . date('H:i:s',strtotime($endTime));
                    break;
                }

                $slots[] = date('h:i A', $from);// . ' - ' . date('h:i A',$to);

                $from = $to;
            }
        }

        return $slots;
    }
}
