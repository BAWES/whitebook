<?php

namespace frontend\controllers;

use Yii;
use yii\web\Response;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use frontend\models\Vendor;
use common\models\VendorItem;
use common\models\City;
use common\models\Location;
use common\models\ItemType;
use common\models\Customer;
use common\models\CustomerCart;
use common\models\VendorItemMenu;
use common\models\VendorItemMenuItem;
use common\models\CustomerCartMenuItem;
use common\models\VendorWorkingTiming;

class CartController extends BaseController
{
    private $errors = array();

    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index','update-cart-item-popup','update-cart-item','add', 'update', 'validation-product-available', 'get-delivery-timeslot', 'save-delivery-timeslot','slots'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['index','update-cart-item-popup','update-cart-item','add', 'update', 'validation-product-available', 'get-delivery-timeslot', 'save-delivery-timeslot','slots'],
                        'allow' => true,
                        'roles' => ['?'],
                    ]
                ],
            ],
        ];
    }

    //list all products

    public function actionIndex()
    {
        \Yii::$app->view->title = Yii::$app->params['SITE_NAME'].' | Cart';
        \Yii::$app->view->registerMetaTag(['name' => 'description', 'content' => Yii::$app->params['META_DESCRIPTION']]);
        \Yii::$app->view->registerMetaTag(['name' => 'keywords', 'content' => Yii::$app->params['META_KEYWORD']]);

        $items = CustomerCart::items();

        $vendor_area = Location::findAll(['trash' => 'Default']);

        if(Yii::$app->language == 'en')
        {
            $vendor_area =  \yii\helpers\ArrayHelper::map($vendor_area, 'id', 'location', 'cityName' );    
        }
        else
        {
            $vendor_area =  \yii\helpers\ArrayHelper::map($vendor_area, 'id', 'location_ar', 'cityName' );
        }

        return $this->render('index', [
            'items' => $items,
            'vendor_area' => $vendor_area
        ]);
    }

    public function actionUpdateCartItemPopup(){
        
        if(!Yii::$app->request->isAjax) {
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        }
        
        $item = CustomerCart::findOne(Yii::$app->request->post('id'));
        
        if(!$item) {
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        }

        $model = VendorItem::findOne($item->item_id);

        if(!$model) {
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        }

        $menu = VendorItemMenu::findAll([
            'item_id' => $model->item_id,
            'menu_type' => 'options'
        ]);

        $addons = VendorItemMenu::findAll([
            'item_id' => $model->item_id,
            'menu_type' => 'addons'
        ]);

        //get timeslots 
        $vendor_timeslot = VendorWorkingTiming::find()
            ->select(['working_id','working_start_time','working_end_time'])
            ->where([
                'vendor_id' => $model->vendor_id,
                'working_day' => date("l", strtotime($item->cart_delivery_date))
            ])
            ->asArray()
            ->all();
        $slots = [];

        if ($vendor_timeslot) {

            foreach ($vendor_timeslot as $key => $value) {
                $slots = array_merge($slots,$this->slots($value['working_start_time'],$value['working_end_time']));
            }

        }

        return $this->renderPartial('edit_cart', [
            'item' => $item,
            'model' => $model,
            'menu' => $menu,
            'addons' => $addons,
            'vendor_timeslot' => $slots
        ]);
    }

    public function actionUpdateCartItem()
    {
        if(!Yii::$app->request->isAjax) {
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        }

        Yii::$app->response->format = Response::FORMAT_JSON;

        $data = Yii::$app->request->post();

        if($this->validate_item($data)) {

            $cart = CustomerCart::findOne($data['cart_id']);
            
            if ($cart) {

                //$cart->cart_delivery_date = $data['delivery_date'];
                //$cart->time_slot =   $data['time_slot'];
                //$cart->cart_delivery_date = date('Y-m-d', strtotime($data['delivery_date']));
                //$cart->cart_quantity =  $data['quantity'];
                $cart->modified_datetime  = date('Y-d-m h:i:s');

                if(!empty($data['female_service'])) {
                    $cart->female_service = $data['female_service'];
                }

                if(!empty($data['special_request'])) {
                    $cart->special_request = $data['special_request'];
                }

                if ($cart->save()) {

                    // remove old 

                    CustomerCartMenuItem::deleteAll(['cart_id' => $cart->cart_id]);

                    // add menu 

                    if(empty($data['menu_item'])) {
                        $data['menu_item'] = [];
                    }
                    
                    foreach ($data['menu_item'] as $key => $value) {

                        if($value > 0) {
                                                    
                            $mi = VendorItemMenuItem::findOne($key);

                            $cart_menu_item = new CustomerCartMenuItem;
                            $cart_menu_item->cart_id = $cart->cart_id;
                            $cart_menu_item->menu_id = $mi->menu_id;
                            $cart_menu_item->menu_item_id = $mi->menu_item_id;
                            $cart_menu_item->quantity = $value;
                            $cart_menu_item->save();   
                        }
                    }

                    Yii::$app->getSession()->setFlash('success', Yii::t(
                        'frontend',
                        'Success: Product <a href="{product_link}">{product_name}</a> added to cart!',
                        [
                            'product_link' => Url::to(['browse/detail', 'slug' => $cart->item->slug]),
                            'product_name' => Yii::$app->language == 'en'? $cart->item->item_name : $cart->item->item_name_ar
                        ]
                    ));

                    return [
                        'success' => 1
                    ];
                } else {
                    return [
                        'error' => Yii::t('frontend','Error while updateing cart')
                    ];
                }
            }
            exit;
        } else {
            return [
                'errors' => $this->errors
            ];
        }
    }

    /**  
     * On post validate options and add item to cart 
     */
    public function actionAdd() {

        $data = Yii::$app->request->post();

        //remove menu item with 0 quantity 

        if(empty($data['menu_item'])) {
            $data['menu_item'] = [];
        }

        foreach ($data['menu_item'] as $key => $value)
        {
            if($value == 0)
                unset($data['menu_item'][$key]);
        }

        Yii::$app->response->format = Response::FORMAT_JSON;

        if($this->validate_item($data)) {
            
            $query = CustomerCart::find()
                ->where([
                    'item_id' => $data['item_id'],
                    'area_id'   => isset($data['area_id'])?$data['area_id']:'',
                    'time_slot' => isset($data['time_slot'])?$data['time_slot']:'',
                    'cart_delivery_date' => date('Y-m-d', strtotime($data['delivery_date'])),
                ]);
            
            if(!empty($data['female_service'])){
                $query->andWhere(['female_service' => $data['female_service']]);
            }

            if(!empty($data['special_request'])){
                $query->andWhere(['special_request' => $data['special_request']]);
            }

            if (Yii::$app->user->getId()) {
                $query->andWhere(['customer_id'=>Yii::$app->user->getId()]);
            } else {
                $query->andWhere(['cart_session_id'=>Customer::currentUser()]);
            }

            $cart = $query->one();

            //if available in cart check if have exact menu and quantity combo 

            if($cart) {
                $cart_menu_items = CustomerCartMenuItem::findAll(['cart_id' => $cart->cart_id]);
                    
                $arr_cart_menu_items = ArrayHelper::map($cart_menu_items, 'menu_item_id', 'quantity');

                //if cart menu are same as posted menu_item 

                if(sizeof($arr_cart_menu_items) != sizeof($data['menu_item']))
                    $cart = false;

                //check menu item quantity is same for 1 quantity of item in cart and we trying to add 
                // so we can add cart item with p quantity to cart item item with q quantity if both ahve 
                // same no of menu items 

                foreach ($arr_cart_menu_items as $key => $value) {
                    if(empty($data['menu_item'][$key]) || $data['menu_item'][$key]/$data['quantity'] != $value/$cart->cart_quantity){
                        $cart = false;
                        break;
                    }
                }
            }
            
            /* 
                product already available in cart 
                Just need to update quantity 
            */
            if($cart) {

                if ($data['quantity']) {
                    $quantity = $data['quantity'];
                } else {
                    $quantity = 1;

                }   

                $cart->cart_quantity = $cart->cart_quantity + $quantity;

                //update menu item quantities 

                foreach ($cart_menu_items as $key => $menu_item) {
                    $menu_item->quantity += $data['menu_item'][$menu_item->menu_item_id];
                    $menu_item->save();
                }
            }

            /*
                Product not available in cart 
             */
            if(!$cart) {

                if (isset($data['area_id']) && $data['area_id'] != '') {
                    $deliverlocation = $data['area_id'];
                    if (is_numeric($deliverlocation)) {
                        $location = $deliverlocation;
                    } else {
                        $end = strlen($deliverlocation);
                        $from = strpos($deliverlocation, '_') + 1;
                        $address_id = substr($deliverlocation, $from, $end);
                        $location = \common\models\CustomerAddress::findOne($address_id)->area_id;
                    }
                } else {
                    $location = '';
                }

                $cart = new CustomerCart();
                $cart->customer_id = Yii::$app->user->getId();
                $cart->item_id = $data['item_id'];
                $cart->area_id = $location;
                $cart->time_slot  = isset($data['time_slot'])?$data['time_slot']:'';
                $cart->cart_delivery_date = date('Y-m-d', strtotime($data['delivery_date']));
                $cart->cart_customization_price_per_unit = 0;
                $cart->cart_quantity = $data['quantity'];
                $cart->cart_datetime_added = date('Y-d-m h:i:s');
                $cart->cart_session_id = (!Yii::$app->user->getId()) ? Customer::currentUser() : '';
                $cart->cart_valid = 'yes';
                $cart->trash = 'Default';

                if(!$cart->save())
                {
                    return [
                        'errors' => array_merge($this->errors, $cart->getErrors())
                    ];
                }

                // add menu 
                
                foreach ($data['menu_item'] as $key => $value) {

                    $mi = VendorItemMenuItem::findOne($key);

                    $cart_menu_item = new CustomerCartMenuItem;
                    $cart_menu_item->cart_id = $cart->cart_id;
                    $cart_menu_item->menu_id = $mi->menu_id;
                    $cart_menu_item->menu_item_id = $mi->menu_item_id;
                    $cart_menu_item->quantity = $value;
                    $cart_menu_item->save(); 
                }
            }
            
            if(!empty($data['female_service'])) {
                $cart->female_service = $data['female_service'];
            }

            if(!empty($data['special_request'])) {
                $cart->special_request = $data['special_request'];
            }

            if($cart->save()) {

                $item = VendorItem::findOne($data['item_id']);

                Yii::$app->getSession()->setFlash('success', Yii::t(
                    'frontend', 
                    'Success: Product <a href="{product_link}">{product_name}</a> added to cart!', 
                    [
                        'product_link' => Url::to(['browse/detail', 'slug' => $item->slug]),
                        'product_name' => Yii::$app->language == 'en'? $item->item_name : $item->item_name_ar
                    ]
                ));

                return [
                    'success' => 1
                ];

            } else {

                return [
                    'errors' => array_merge($this->errors, $cart->getErrors())
                ];
            }            
        
        } else {
            return [
                'errors' => $this->errors
            ];
        }
    }

    /*
        Validate cart item availability
    */
    public function validate_item($data) {

        // will change them too
        $this->errors = CustomerCart::validate_item($data);

        return !$this->errors;
    }


    public function actionValidationProductAvailable() {

        // will change them too
        if (!Yii::$app->request->isAjax) {
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        }

        Yii::$app->response->format = 'json';

        $json = [];

        $data = Yii::$app->request->post();

        if(empty($data['item_id'])) {
            $json['error'] = Yii::t('frontend', 'Item ID require!');

            return $json;
        }

        $item = VendorItem::find()->where([
            'item_id' => $data['item_id'],
            'item_for_sale' => 'Yes'
        ])->one();

        if (!$item) {
            $json['error'] = Yii::t('frontend', 'Item not available for sell!');

            return $json;
        }

        //get item type 

        $item_type = ItemType::findOne($item->type_id);

        if($item_type) {
            $item_type_name = $item_type->type_name;
        } else {
            $item_type_name = 'Product';
        }

        // get date after x day then convert it to unix time 
        
        $min_delivery_time = strtotime(date('d-m-Y', strtotime('+'.$item->item_how_long_to_make.' hours')));

        if(strtotime($data['delivery_date']) < $min_delivery_time) 
        {
            $json['error'] = Yii::t('frontend', 'Item notice period {count} hour(s)!', [
                    'count' => $item->item_how_long_to_make
                ]);

            return $json;
        }

        $vendor_id = $item->vendor_id;

        /*
            Check if deliery availabel in selected area
        */
        if (!empty($data['area_id'])) {

            if ($data['area_id'] != '') {
                $deliverlocation = $data['area_id'];
                if (is_numeric($deliverlocation)) {
                    $location = $deliverlocation;
                } else {
                    $end = strlen($deliverlocation);
                    $from = strpos($deliverlocation, '_') + 1;
                    $address_id = substr($deliverlocation, $from, $end);
                    $location = \common\models\CustomerAddress::findOne($address_id)->area_id;
                }
            }

            $delivery_area = CustomerCart::checkLocation($location, $vendor_id);

            if (!$delivery_area) 
            {
                $json['error'] = Yii::t('frontend', 'Delivery not available on selected area');

                return $json;
            }
        }

        //-------------- Start Item Capacity -----------------//
        //default capacity is how many of it they can process per day

        //1) get capacity exception for selected date
        
        $capacity_exception = \common\models\VendorItemCapacityException::findOne([
            'item_id' => $data['item_id'],
            'exception_date' => date('Y-m-d', strtotime($data['delivery_date']))
        ]);

        if ($capacity_exception && $capacity_exception->exception_capacity) {
            $capacity = $capacity_exception->exception_capacity;
        } else {
            $capacity = $item->item_default_capacity;
        }

        //2) get no of item purchased for selected date
        $purchased_result = \common\models\Booking::totalPurchasedItem($data['item_id'],$data['delivery_date']);
        if ($purchased_result) {
            $purchased = $purchased_result['purchased'];
        } else {
            $purchased = 0;
        }

        if ($purchased > $capacity) 
        {
            $json['error'] = Yii::t('frontend', 'Item is not available on selected date');

            return $json;
        }

        //-------------- END Item Capacity -----------------//

        //current date should not in blocked date
        $block_date = \common\models\BlockedDate::findOne([
            'vendor_id' => $vendor_id,
            'block_date' => date('Y-m-d', strtotime($data['delivery_date']))
        ]);

        if ($block_date) 
        {
            $json['error'] = Yii::t('frontend', 'Item is not available on selected date');

            return $json;
        }

        //day should not in week off
        $blocked_days = explode(',', Vendor::findOne($vendor_id)->blocked_days);
        $day = date('N', strtotime($data['delivery_date']));//7-sunday, 1-monday

        if (in_array($day, $blocked_days)) 
        {
            $json['error'] = Yii::t('frontend', 'Item is not available on selected date');

            return $json;
        }

        $json['capacity'] = $capacity;
        $json['price'] = VendorItem::itemFinalPrice($data['item_id'],$data['quantity'],(isset($data['menu_item'])) ? $data['menu_item'] : []);

        return $json;
    }

    /*
        Update item quantity
        function not in use
    */
    public function actionUpdate() {
        $quantity = Yii::$app->request->post('quantity');

        //save delivery info in session 

        Yii::$app->session->set('deliver-location', Yii::$app->request->post('area_id'));
        Yii::$app->session->set('deliver-date', Yii::$app->request->post('delivery_date'));
        Yii::$app->session->set('event_time', Yii::$app->request->post('event_time'));

        foreach ($quantity as $key => $value) {

            $cart = CustomerCart::findOne($key);

            if(!$cart) 
                continue;

            $cart->area_id = Yii::$app->request->post('area_id');
            $cart->time_slot = Yii::$app->request->post('event_time');
            $cart->cart_delivery_date = Yii::$app->request->post('delivery_date');

            if ($value > 0) {
                $cart->cart_quantity = $value;
                $cart->update();
            } else {
                $cart->delete();
            }     

            //validate items 

            $menu_items = CustomerCartMenuItem::find()
                ->select('{{%vendor_item_menu_item}}.price, {{%vendor_item_menu_item}}.menu_item_id, {{%vendor_item_menu_item}}.menu_id, {{%vendor_item_menu_item}}.menu_item_name, {{%vendor_item_menu_item}}.menu_item_name_ar, {{%customer_cart_menu_item}}.quantity')
                ->innerJoin('{{%vendor_item_menu_item}}', '{{%vendor_item_menu_item}}.menu_item_id = {{%customer_cart_menu_item}}.menu_item_id')
                ->innerJoin('{{%vendor_item_menu}}', '{{%vendor_item_menu}}.menu_id = {{%customer_cart_menu_item}}.menu_id')
                ->where(['cart_id' => $cart['cart_id']])
                ->asArray()
                ->all();

            $errors = CustomerCart::validate_item([
                'item_id' => $cart['item_id'],
                'time_slot' => $cart['time_slot'],
                'delivery_date' => $cart['cart_delivery_date'],
                'area_id' => $cart['area_id'],
                'quantity' => $cart['cart_quantity'],
                'menu_item' => ArrayHelper::map(
                        $menu_items, 'menu_item_id', 'quantity'
                    )
            ], true);
       
            // if not valid return to index 

            if($errors) 
            {
                return $this->redirect(['index']);
            }
        }

        //from checkout button 

        $btn_checkout = Yii::$app->request->post('btn_checkout');

        if($btn_checkout) {
            return $this->redirect(['checkout/index']);
        }

        return $this->redirect(['index']);
    }

    /* 
        Get delivery timeslot
    */
    public function actionGetDeliveryTimeslot()
    {
        if (!Yii::$app->request->isAjax) {
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        }

        $data = Yii::$app->request->post();
        $string = $data['sel_date'];
        $timestamp = strtotime($string);
        $slots = [];
        $deliver_timeslot = Yii::$app->session->get('deliver-timeslot');

        Yii::$app->session->set('deliver-date', $data['sel_date']);

        $vendor_timeslot = VendorWorkingTiming::find()
            ->select(['working_id','working_start_time','working_end_time'])
            ->where([
                    'vendor_id' => $data['vendor_id'],
                    'working_day' => date("l", $timestamp),
                    'trash' => 'Default'
                ])
            ->asArray()
            ->all();

        if ($vendor_timeslot) {

            foreach ($vendor_timeslot as $key => $value) {
                $slots = array_merge($slots, $this->slots($value['working_start_time'], $value['working_end_time']));
            }

            foreach($slots as $slot) {
                echo '<option value="' . $slot . '" >' . $slot . '</option>';
            }

        } else {
            echo 0;
            exit;
        }
    }

    /**
      *
      */
    public function actionSaveDeliveryTimeslot()
    {
        if (!Yii::$app->request->isAjax) {
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        }

        $deliver_timeslot = Yii::$app->request->post('deliver-timeslot');

        Yii::$app->session->set('deliver-timeslot', $deliver_timeslot);
    }

    /*
     * method provide time slots interval between two time slots
     */
    private function slots($startTime = '11:00:00', $endTime = '22:45:00'){
        $slots = [];
        if ($startTime && $endTime) {
            $from = date('h:i A', strtotime($startTime));
            $to ='';
            while (strtotime($from) < strtotime($endTime)) {
                $to = strtotime("+30 minutes", strtotime($from));
                if ($to > strtotime($endTime)) {
                    $slots[] = $from;// . '-' . date('H:i:s',strtotime($endTime));
                    break;
                }
                $slots[] = date('h:i A',strtotime($from));// . ' - ' . date('h:i A',$to);
                $from = date('h:i A',$to);
            }
        }
        return $slots;
    }
}



