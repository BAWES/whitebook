<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use yii\rest\Controller;
use common\models\CategoryPath;
use common\models\VendorLocation;
use common\models\VendorItemPricing;
use common\models\VendorItemMenuItem;
use common\models\VendorItemMenu;
use common\models\Themes;
use common\models\Location;
use common\components\CFormatter;
use frontend\models\Vendor;
use api\models\EventItemlink;
use api\models\VendorItem;

/**
 * Auth controller provides the initial access token that is required for further requests
 * It initially authorizes via Http Basic Auth using a base64 encoded username and password
 */
class ProductController extends Controller
{
    /**
     * @return array
     */
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
     * @param int $offset
     * @param $category_id
     * @param bool $forSale
     * @param null $requestedLocation
     * @param null $requestedDeliverDate
     * @param int $requestedMinPrice
     * @param int $requestedMaxPrice
     * @param string $requestedCategories
     * @param string $requestedVendor
     * @param string $requestedTheme
     * @param string $requestedDeliverTime
     * @return array
     */
    public function actionCategoryProducts(
        $offset = 0,
        $category_id = 0,
        $requestedLocation = null,
        $requestedDeliverDate = null,
        $requestedMinPrice = 0,
        $requestedMaxPrice = 0,
        $requestedVendor = '',
        $requestedTheme = '',
        $requestedDeliverTime = ''
    )
    {
        $products = [];
        
        $limit = Yii::$app->params['limit'];

        $vendor_id = $requestedVendor;

        if ($category_id != 'all') {
            $Category = \common\models\Category::findOne($category_id);
        } else {
            $Category = '';
        }
        
        $item_query = CategoryPath::find()
            ->selectedFields()
            ->categoryJoin()
            ->itemJoin()
            ->priorityItemJoin()
            ->vendorJoin()
            ->defaultItems()
            ->approved()
            ->active();
        
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

            $item_query->vendorIDs($vendor_ids);
        }
                
        //price filter
        if ($requestedMinPrice && $requestedMaxPrice) {
            $item_query->price($requestedMinPrice,$requestedMaxPrice);
        }
        
        //theme filter
        if ($requestedTheme)
        {
            $item_query->itemThemeJoin();
            $item_query->themeJoin();
            $item_query->byThemeIDs($requestedTheme);
        }//if themes
        
        //event time
        if($requestedDeliverTime) {
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


        if ($requestedDeliverTime) {
            
            if($requestedDeliverDate)
                $working_day = date('l', strtotime($requestedDeliverDate));
            else 
                $working_day = date('l');

            $event_time = date('H:i:s', strtotime($requestedDeliverTime));
            
            $item_query->eventTime($event_time, $working_day);
        }

        $item_query_result = $item_query
            ->groupBy('{{%vendor_item}}.item_id')
            ->orderByExpression()
            ->asArray()
            ->offset($offset)
            ->limit($limit)
            ->all();

        if ($item_query_result) 
        {
            $customData = ['image'=>'','notice'=>''];
            foreach ($item_query_result as $item) 
            {
                $image = \common\models\Image::find()
                    ->where(['item_id' => $item['item_id']])
                    ->orderBy(['vendorimage_sort_order' => SORT_ASC])
                    ->one();
                    
                if ($image) {
                    $customData['image'] = $image->image_path;
                } else {
                    $customData['image'] = '';
                }

                // for notice period
                $value = $item;
                $notice = '';
                if (isset($value['item_how_long_to_make']) && $value['item_how_long_to_make'] > 0) {
                    if (isset($value['notice_period_type']) && $value['notice_period_type'] == 'Day') {
                        if ($value['item_how_long_to_make'] >= 7) {
                            $notice = Yii::t('api', '{count} week(s)', [
                                'count' => substr(($value['item_how_long_to_make'] / 7), 0, 3)
                            ]);
                        } else {
                            $notice = Yii::t('api', '{count} day(s)', [
                                'count' => $value['item_how_long_to_make']
                            ]);
                        }
                    } else {
                        if ($value['item_how_long_to_make'] >= 24) {
                            $notice = Yii::t('api', '{count} day(s)', [
                                'count' => substr(($value['item_how_long_to_make'] / 24), 0, 3)
                            ]);
                        } else {
                            $notice = Yii::t('api', '{count} hours', [
                                'count' => $value['item_how_long_to_make']
                            ]);
                        }
                    }
                }
                $customData['notice'] = $notice;

                $products[] = $item + $customData;
            }
        }

        return $products;
    }

    /*
     * Method to view product detail
     */
    /**
     * @param $product_id
     * @return array
     */
    public function actionProductDetail($product_id)
    {
        $model = VendorItem::find()
            ->where(['item_id' => $product_id])
            ->one();

        if (!$model) 
        {
            return [
                "operation" => "error",
                "code" => "0",
                'message' => Yii::t('api', 'Invalid Item ID')
            ];
        }

        $menu = VendorItemMenu::find()
            ->with('vendorItemMenuItems')
            ->item($product_id)
            ->menu('options')
            ->asArray()
            ->all();

        $addons = VendorItemMenu::find()
            ->with('vendorItemMenuItems')
            ->item($product_id)
            ->menu('addons')
            ->asArray()
            ->all();
        
        $value = $model;
        
        $notice = '';

        if (isset($value['item_how_long_to_make']) && $value['item_how_long_to_make'] > 0) {
            if (isset($value['notice_period_type']) && $value['notice_period_type'] == 'Day') {
                if ($value['item_how_long_to_make'] >= 7) {
                    $notice = Yii::t('api', '{count} week(s)', [
                        'count' => substr(($value['item_how_long_to_make'] / 7), 0, 3)
                    ]);
                } else {
                    $notice = Yii::t('api', '{count} day(s)', [
                        'count' => $value['item_how_long_to_make']
                    ]);
                }
            } else {
                if ($value['item_how_long_to_make'] >= 24) {
                    $notice = Yii::t('api', '{count} day(s)', [
                        'count' => substr(($value['item_how_long_to_make'] / 24), 0, 3)
                    ]);
                } else {
                    $notice = Yii::t('api', '{count} hours', [
                        'count' => $value['item_how_long_to_make']
                    ]);
                }
            }
        }

        return [
            'item' => $model,
            'price' => $model->price,
            'type' => $model->type,
            'vendor' => $model->vendor,
            'images' => $model->images,
            'videos' => $model->videos,
            'menu' => $menu,
            'addons' => $addons,
            'notice' => $notice,
            'similarItems' => VendorItem::more_from_vendor($model)
        ];
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
                    'message' => Yii::t('api', '{item_name} already exist with {event_name}',
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
                        'message' => Yii::t('api', '{item_name} has been added to {event_name}',
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
                ->select(['{{%vendor_location}}.area_id, {{%location}}.location, {{%location}}.location_ar'])
                ->leftJoin('{{%location}}', '{{%location}}.id = {{%vendor_location}}.area_id')
                ->where(['{{%vendor_location}}.vendor_id' => $vendor_id])
                ->asArray()
                ->all();
        } else {
            $vendor_area = VendorLocation::find()
                ->select(['{{%vendor_location}}.area_id,{{%location}}.location,{{%location}}.location_ar'])
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

        return $vendor_area;
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
                'message' => Yii::t('api', 'Invalid Vendor ID')
            ];
        }

        if (empty($event_date) || !isset($event_date)) {
            return [
                "operation" => "error",
                "code" => "0",
                'message' => Yii::t('api', 'Invalid Event Date')
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

        return [
            'capacity' => $capacity
        ];
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

    public function actionPriceRange()
    {
        $result = VendorItem::find()
            ->select('MIN(item_price_per_unit) as minRange, MAX(item_price_per_unit) as maxRange')
            ->where([
                'item_status' => 'Active',
                'trash' => 'default',
                'item_approved' => 'Yes'
            ])
            ->asArray()
            ->one();

        return [
            'minRange' => floor($result['minRange']),
            'maxRange' => ceil($result['maxRange'])
        ];
    }

    public function actionFilterData()
    {
        $vendors = Vendor::find()
            ->select([
                'vendor_id',
                'vendor_name'
            ])
            ->where([
                '{{%vendor}}.trash' => 'Default',
                '{{%vendor}}.approve_status' => 'Yes',
                '{{%vendor}}.vendor_status' => 'Active'
            ])    
            ->orderby(['{{%vendor}}.vendor_name' => SORT_ASC])
            ->groupby(['{{%vendor}}.vendor_id'])
            ->all();

        $price = VendorItem::find()
            ->select('MIN(item_price_per_unit) as minRange, MAX(item_price_per_unit) as maxRange')
            ->where([
                'item_status' => 'Active',
                'trash' => 'default',
                'item_approved' => 'Yes'
            ])
            ->asArray()
            ->one();

        $areas = Location::find()
            ->select(['{{%location}}.id, {{%location}}.location, {{%location}}.location_ar'])
            ->leftJoin('{{%vendor_location}}', '{{%location}}.id = {{%vendor_location}}.area_id')
            ->asArray()
            ->all();
    
        $themes = Themes::find()
            ->select(['theme_id', 'theme_name', 'theme_name_ar'])
            ->where(['theme_status' => 'Active', 'trash' => 'Default'])
            ->all();

        return [
            'vendors' => $vendors,
            'minRange' => floor($price['minRange']),
            'maxRange' => ceil($price['maxRange']),
            'areas' => $areas,
            'themes' => $themes
        ];
    }

    public function actionFinalPrice()
    {
        $total = VendorItem::itemFinalPrice(
            Yii::$app->request->getBodyParam('item_id'), 
            Yii::$app->request->getBodyParam('quantity'), 
            Yii::$app->request->getBodyParam('menu_item')
        );

        return [
            'total' => CFormatter::format($total)
        ];
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
                    $slots[] = date('g:i A', $from);// . '-' . date('H:i:s',strtotime($endTime));
                    break;
                }

                $slots[] = date('g:i A', $from);// . ' - ' . date('h:i A',$to);

                $from = $to;
            }
        }

        return $slots;
    }
}
