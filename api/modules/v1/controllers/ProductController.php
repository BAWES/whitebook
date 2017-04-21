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
        $requestedCategories = ''
    )
    {
        $theme_id = Yii::$app->request->get("theme_id");

        $vendor_id = Yii::$app->request->get("vendor_id");
        
        $limit = Yii::$app->params['limit'];

        $item_query = CategoryPath::find()
            ->selectedFields()
            ->categoryJoin()
            ->itemJoin()
            ->priorityItemJoin()
            ->vendorJoin()
            ->defaultItems()
            ->approvedItems()
            ->activeItems();

        if ($forSale) {
            $item_query->saleItems();
        }

        if($vendor_id)
        {
            $vendors = Vendor::find()
                ->where(['in', 'vendor_id', $vendor_id])
                ->andWhere([
                    'trash' => 'Default',
                    'approve_status' => 'Yes'
                ])
                ->all();

            $vendor_ids = ArrayHelper::map($vendors, 'vendor_id', 'vendor_id');

            $item_query->byVendorIDs($vendor_ids);
        }
        
        //price filter
        if ($requestedMinPrice && $requestedMaxPrice) {
            $price_condition = [];
            $arr_min_max = explode('-', $data['price']);
            $item_query->byPrice($arr_min_max[0],$arr_min_max[1]);
        }

        //theme filter
        if ($theme_id) 
        {
            $item_query->itemThemeJoin();
            $item_query->themeJoin();
            $item_query->byThemeIDs($theme_id);
        }//if themes

        //category filter
        
        if($category_id && $category_id != 'all')
        {
            $item_query->byCategoryIDs($category_id);
        }

        if ($requestedLocation) 
        {
            $item_query->byDeliveryLocation($requestedLocation);
        }

        if ($requestedDeliverDate) 
        {
            $date = date('Y-m-d', strtotime($requestedDeliverDate));
            $item_query->byDeliveryDate($date);
        }

        $products = $item_query
            ->groupBy('{{%vendor_item}}.item_id')
            ->orderByExpression()
            ->offset($offset)
            ->limit($limit)
            ->asArray()
            ->all();

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
}
