<?php

namespace frontend\controllers;

use common\models\CustomerCartItemQuestionAnswer;
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
                        'actions' => ['index','update-cart-item-popup','update-cart-item','add', 'update', 'validation-product-available', 'get-delivery-timeslot', 'save-delivery-timeslot','slots', 'remove'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['index','update-cart-item-popup','update-cart-item','add', 'update', 'validation-product-available', 'get-delivery-timeslot', 'save-delivery-timeslot','slots', 'remove'],
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

        $vendor_area = Location::find()->defaultLocations()->all();

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
        
        $delivery_date = Yii::$app->session->get('delivery-date');

        $item = CustomerCart::findOne(Yii::$app->request->post('id'));
        
        if(!$item) {
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        }

        $model = VendorItem::findOne($item->item_id);

        if(!$model) {
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        }

        $menu = VendorItemMenu::find()->item($model->item_id)->menu('options')->all();
        $addons = VendorItemMenu::find()->item($model->item_id)->menu('addons')->all();

        //get timeslots

        $vendor_timeslot = VendorWorkingTiming::find()
            ->vendor($model->vendor_id)
            ->workingDay(date("l", strtotime($delivery_date)))
            ->defaultTiming()
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

                    CustomerCartItemQuestionAnswer::deleteAll(['cart_id' => $cart->cart_id,'item_id'=>$data['item_id']]);

                    if (isset($data['answer'])) {
                        // add answers
                        foreach ($data['answer'] as $key => $answer) {
                            if (!empty($answer)) {
                                $cartItemAnswers = new CustomerCartItemQuestionAnswer();
                                $cartItemAnswers->question_id = $key;
                                $cartItemAnswers->answer = $answer;
                                $cartItemAnswers->cart_id = $cart->cart_id;
                                $cartItemAnswers->item_id = $data['item_id'];
                                $cartItemAnswers->created_datetime = date('Y-m-d H:i:s');
                                $cartItemAnswers->modified_datetime = date('Y-m-d H:i:s');
                                $cartItemAnswers->save(false);
                            }
                        }
                    }

                    Yii::$app->getSession()->setFlash('success', Yii::t(
                        'frontend',
                        'Success: Product <a href="{product_link}">{product_name}</a> updated in cart successfully',
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

        $area_id = empty($data['area_id'])?'':$data['area_id'];
        $time_slot = empty($data['time_slot'])?'':$data['time_slot'];
        $delivery_date = empty($data['delivery_date'])?'':date('Y-m-d', strtotime($data['delivery_date']));

        Yii::$app->session->set('delivery-location', $area_id);
        Yii::$app->session->set('delivery-date', $delivery_date);
        Yii::$app->session->set('event_time', $time_slot);

        Yii::$app->response->format = Response::FORMAT_JSON;

        //remove menu item with 0 quantity 

        if(empty($data['menu_item'])) {
            $data['menu_item'] = [];
        }

        foreach ($data['menu_item'] as $key => $value)
        {
            if($value == 0)
                unset($data['menu_item'][$key]);
        }

        if($this->validate_item($data)) {
            
            $query = CustomerCart::find()
                ->item($data['item_id']);
                //->area($area_id)
                //->timeSlot($time_slot)
                //->deliveryDate($delivery_date);

            if(!empty($data['female_service'])){
                $query->femaleService($data['female_service']);
            }

            if (!empty($data['special_request'])) {
                $query->request($data['special_request']);
            }
                
            $query->user();

            $cart = $query->one();

            //if available in cart check if have exact menu and quantity combo 

            if($cart) {
                $cart_menu_items = CustomerCartMenuItem::find()->cartID($cart->cart_id)->all();
                    
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

            if (isset($data['answer'])) {
                // add answers
                foreach ($data['answer'] as $key => $value) {
                    if (!empty($value)) {
                        $cartItemAnswers = new CustomerCartItemQuestionAnswer();
                        $cartItemAnswers->question_id = $key;
                        $cartItemAnswers->answer = $value;
                        $cartItemAnswers->cart_id = $cart->cart_id;
                        $cartItemAnswers->item_id = $data['item_id'];
                        $cartItemAnswers->created_datetime = date('Y-m-d H:i:s');
                        $cartItemAnswers->modified_datetime = date('Y-m-d H:i:s');
                        $cartItemAnswers->save(false);
                    }
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

        $data['area_id'] = Yii::$app->session->get('delivery-location');
        $data['delivery_date'] = Yii::$app->session->get('delivery-date');
        $data['time_slot'] = Yii::$app->session->get('event_time');

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

        if(!isset($data['area_id']) && (isset($data['area_id']) && $data['area_id'] == '')) {
            $json['error'] = Yii::t('frontend', 'Please Select area!');

            return $json;
        }

        if(empty($data['item_id'])) {
            $json['error'] = Yii::t('frontend', 'Item ID require!');

            return $json;
        }

        $item = VendorItem::findOne($data['item_id']);

        if (!$item) {
            $json['error'] = Yii::t('frontend', 'Item not available for sell!');

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

        //get item type

        $item_type = ItemType::findOne($item->type_id);

        if($item_type) {
            $item_type_name = $item_type->type_name;
        } else {
            $item_type_name = 'Product';
        }

        $i = -1; //-1 to start with selected date

        while(true)
        {
            $i++;

            //check upto 7 days

            if($i == 7)
                break;

            $timestamp = strtotime($data['delivery_date']) + ($i * 24 * 60 * 60);

            $delivery_date = date('Y-m-d', $timestamp);

            //check timeslot available on selected date

            $timeslot = VendorWorkingTiming::find()
                ->defaultTiming()
                ->vendor($item->vendor_id)
                ->workingDay(date('l', strtotime($delivery_date)))
                ->one();

            if(!$timeslot)
            {
                if($i == 0)
                    $json['error'] = Yii::t('frontend', 'Delivery timeslot not available');

                continue;
            }

            if($item->notice_period_type == 'Hour' && !empty($data['time_slot']))
            {
                $min_delivery_time = strtotime('+'.$item->item_how_long_to_make.' hours');
                $delivery_time = strtotime($delivery_date.' '.$data['time_slot']);

                if($delivery_time < $min_delivery_time)
                {
                    if($i == 0)
                        $json['error'] = Yii::t('frontend', 'Item notice period {count} hour(s)!', [
                            'count' => $item->item_how_long_to_make
                        ]);

                    continue;
                }
            }

            if($item->notice_period_type == 'Day' && !empty($delivery_date))
            {
                //compare timestamp of date

                $min_delivery_time = strtotime(date('Y-m-d', strtotime('+'.$item->item_how_long_to_make.' days')));
                $delivery_time = strtotime($delivery_date);

                if($delivery_time < $min_delivery_time)
                {
                    if($i == 0)
                        $json['error'] = Yii::t('frontend', 'Item notice period {count} day(s)!', [
                            'count' => $item->item_how_long_to_make
                        ]);

                    continue;
                }
            }

            //-------------- Start Item Capacity -----------------//
            //default capacity is how many of it they can process per day

            //1) get capacity exception for selected date

            $capacity_exception = \common\models\VendorItemCapacityException::find()
                ->item($data['item_id'])
                ->exceptionDate($delivery_date)
                ->one();

            if ($capacity_exception && $capacity_exception->exception_capacity) {
                $capacity = $capacity_exception->exception_capacity;
            } else {
                $capacity = $item->item_default_capacity;
            }

            //2) get no of item purchased for selected date
            $purchased_result = \common\models\Booking::totalPurchasedItem($data['item_id'], $delivery_date);

            if ($purchased_result) {
                $purchased = $purchased_result['purchased'];
            } else {
                $purchased = 0;
            }

            if ($purchased >= $capacity)
            {
                if($i == 0)
                    $json['error'] = Yii::t('frontend', 'Item is not available on selected date');

                continue;
            }

            //-------------- END Item Capacity -----------------//

            //current date should not in blocked date
            $block_date = \common\models\BlockedDate::find()
                ->vendor($vendor_id)
                ->blockedDate($delivery_date)
                ->one();

            if ($block_date)
            {
                if($i == 0)
                    $json['error'] = Yii::t('frontend', 'Item is not available on selected date');

                continue;
            }

            //day should not in week off
            $blocked_days = explode(',', Vendor::findOne($vendor_id)->blocked_days);
            $day = date('N', strtotime($delivery_date));//7-sunday, 1-monday

            if (in_array($day, $blocked_days))
            {
                //return error only for selected date

                if($i == 0)
                    $json['error'] = Yii::t('frontend', 'Item is not available on selected date');

                continue;
            }

            // we are lucky! Item available for selected date

            if($i == 0)
            {
                $json['date'] = $delivery_date;
                $json['capacity'] = $capacity;
                $json['price'] = VendorItem::itemFinalPrice($data['item_id'], $data['quantity'], (isset($data['menu_item'])) ? $data['menu_item'] : []);
            }
            else //available for other date
            {
                $json['error'] = 'Item available on '.date('d-m-Y', strtotime($delivery_date));
            }

            break;
        }

        return $json;
    }


    /*
        Update item quantity
        function not in use
    */
    public function actionUpdate() {
        $quantity = Yii::$app->request->post('quantity');

        //save delivery info in session 

        Yii::$app->session->set('delivery-location', Yii::$app->request->post('area_id'));
        Yii::$app->session->set('delivery-date', Yii::$app->request->post('delivery_date'));
        Yii::$app->session->set('event_time', Yii::$app->request->post('event_time'));

        foreach ($quantity as $key => $value) {

            $cart = CustomerCart::findOne($key);

            if(!$cart) 
                continue;

            if ($value > 0) {
                $cart->cart_quantity = $value;
                $cart->update();
            } else {
                $cart->delete();
            }     

            //validate items 

            $menu_items = CustomerCartMenuItem::find()
                ->select('{{%vendor_item_menu_item}}.price, {{%vendor_item_menu_item}}.menu_item_id, {{%vendor_item_menu_item}}.menu_id, {{%vendor_item_menu_item}}.menu_item_name, {{%vendor_item_menu_item}}.menu_item_name_ar, {{%customer_cart_menu_item}}.quantity')
                ->joinVendorItemMenuItem()
                ->joinVendorItemMenu()
                ->cartID($cart['cart_id'])
                ->asArray()
                ->all();

            $errors = CustomerCart::validate_item([
                'item_id' => $cart['item_id'],
                'time_slot' => Yii::$app->session->get('event_time'),
                'delivery_date' => Yii::$app->session->get('delivery-date'),
                'area_id' => Yii::$app->session->get('delivery-location'),
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

    /**
     * Remove item from cart
     */
    public function actionRemove() {
        $cart_id = Yii::$app->request->get('cart_id');

        $query = CustomerCart::find()
            ->where([
                'cart_id' => $cart_id
            ]);

        if (!Yii::$app->user->isGuest) {
            $query->andWhere(['{{%customer_cart}}.customer_id'=>Yii::$app->user->getId()]);
        } else {
            $query->andWhere(['{{%customer_cart}}.cart_session_id'=>Customer::currentUser()]);
        }

        $cart = $query->one();

        if($cart)
        {
            $cart->delete();
        } else {
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');    
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

        $event_time = Yii::$app->session->get('event_time');

        Yii::$app->session->set('delivery-date', $data['sel_date']);

        $vendor_timeslot = VendorWorkingTiming::find()
            ->select(['working_id','working_start_time','working_end_time'])
            ->vendor($data['vendor_id'])
            ->workingDay(date("l", $timestamp))
            ->defaultTiming()
            ->asArray()
            ->all();

        if ($vendor_timeslot) {

            foreach ($vendor_timeslot as $key => $value) {
                $slots = array_merge($slots, $this->slots($value['working_start_time'], $value['working_end_time']));
            }

            foreach($slots as $slot) {

                if($slot == $event_time)
                    $selected = 'selected';
                else
                    $selected = '';

                echo '<option value="' . $slot . '" '.$selected.'>' . $slot . '</option>';
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

        Yii::$app->session->set('event_time', $deliver_timeslot);
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



