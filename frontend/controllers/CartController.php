<?php

namespace frontend\controllers;

use common\models\VendorWorkingTiming;
use Yii;
use yii\helpers\Url;
use yii\web\Response;
use frontend\models\Vendor;
use common\models\VendorItem;
use common\models\City;
use common\models\ItemType;
use common\models\CustomerCart;
use common\models\DeliveryTimeSlot;
use common\models\VendorItemMenu;
use common\models\VendorItemMenuItem;
use common\models\CustomerCartMenuItem;
use yii\filters\AccessControl;

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
                        'actions' => ['index','update-cart-item-popup','update-cart-item','add', 'update', 'validation-product-available', 'get-delivery-timeslot', 'save-delivery-timeslot'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['validation-product-available', 'get-delivery-timeslot', 'save-delivery-timeslot'],
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

        return $this->render('index', [
            'items' => $items
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

        return $this->renderPartial('edit_cart', [
            'item' => $item,
            'model' => $model,
            'menu' => $menu,
            'addons' => $addons,
            'vendor_timeslot' => $vendor_timeslot
        ]);
    }

    public function actionUpdateCartItem(){
        if(Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            $data = Yii::$app->request->post();

            if($this->validate_item($data)) {

                $cart = CustomerCart::findOne($data['cart_id']);
                
                if ($cart) {
                    $cart->cart_delivery_date = $data['delivery_date'];
                    $cart->working_id  =   $data['working_id'];
                    $cart->cart_quantity =  $data['quantity'];
                    $cart->cart_delivery_date = date('Y-m-d', strtotime($data['delivery_date']));
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
    }

    /*  
     *  Add product to cart  
     ----------------------------
        - Show product options on get request 
        - On post validate options and add item to cart 
     */
    public function actionAdd() {

        if(Yii::$app->request->isGet) {
            
            $cities = City::find()
                    ->where('status="Active" AND trash="Default"')
                    ->all();

            return $this->renderPartial('add', [
              'cities' => $cities,
              'item_id' => Yii::$app->request->get('item_id')
            ]);    
        }

        Yii::$app->response->format = Response::FORMAT_JSON;

        $data = Yii::$app->request->post();

        if($this->validate_item($data)) {
            
            $cart = CustomerCart::find()
                ->where([
                    'item_id' => $data['item_id'],
                    'area_id'   => isset($data['area_id'])?$data['area_id']:'',
                    'working_id' => isset($data['working_id'])?$data['working_id']:'',
                    'cart_delivery_date' => date('Y-m-d', strtotime($data['delivery_date']))
                ])
                ->one();

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
                $cart->working_id = isset($data['working_id'])?$data['working_id']:'';
                $cart->cart_delivery_date = date('Y-m-d', strtotime($data['delivery_date']));
                $cart->cart_customization_price_per_unit = 0;
                $cart->cart_quantity = $data['quantity'];
                $cart->cart_datetime_added = date('Y-d-m h:i:s');
                $cart->cart_valid = 'yes';
                $cart->trash = 'Default';
            }
            
            if(!empty($data['female_service'])) {
                $cart->female_service = $data['female_service'];
            }

            if(!empty($data['special_request'])) {
                $cart->special_request = $data['special_request'];
            }

            if($cart->save()) {
                
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

        $this->errors = CustomerCart::validate_item($data);

        return !$this->errors;
    }


    public function actionValidationProductAvailable() {

        if (!Yii::$app->request->isAjax) {
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        }

        Yii::$app->response->format = 'json';

        $json = [];

        $data = Yii::$app->request->post();

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

//        //validate to add product to cart
//        if ($data['quantity'] > ($item->item_amount_in_stock)) {
//
//            return Yii::t('frontend', 'Item is not available on selected date');
//        }

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

        $purchased_result = Yii::$app->db->createCommand('select sum(ip.purchase_quantity) as purchased from whitebook_suborder_item_purchase ip inner join whitebook_suborder so on so.suborder_id = ip.suborder_id where ip.item_id = "' . $data['item_id'] . '" AND ip.trash = "Default" AND so.trash ="Default" AND so.status_id != 0 AND DATE(ip.purchase_delivery_date) = DATE("' . date('Y-m-d', strtotime($data['delivery_date'])) . '")')->queryOne();

        if ($purchased_result) {
            $purchased = $purchased_result['purchased'];
        } else {
            $purchased = 0;
        }

        if ($item_type_name != 'Product' && $purchased > $capacity) 
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

        return $json;
    }

    /*
        Update item quantity 
    */
    public function actionUpdate() {
        $quantity = Yii::$app->request->post('quantity');

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

        $deliver_timeslot = Yii::$app->session->get('deliver-timeslot');

        Yii::$app->session->set('deliver-date', $data['sel_date']);

        $vendor_timeslot = VendorWorkingTiming::find()
            ->select(['working_id','working_start_time','working_end_time'])
            ->where(['vendor_id' => $data['vendor_id']])
            ->andwhere(['working_day' => date("l", $timestamp)])
            ->asArray()
            ->all();

        if ($vendor_timeslot) {

            foreach ($vendor_timeslot as $key => $value) {

                if($deliver_timeslot == $value['working_id']) {
                    $selected = 'selected';
                } else {
                    $selected = '';
                }

                if (strtotime($data['sel_date']) == (strtotime($data['currentDate']))) {
                    if (strtotime($data['time']) < strtotime($value['working_start_time'])) {
                        $start = date('g:i A', strtotime($value['working_start_time']));
                        $end = date('g:i A', strtotime($value['working_end_time']));
                        echo '<option value="' . $value['working_id'] . '" '.$selected.'>' . $start . ' - ' . $end . '</option>';
                    }
                } else {
                    $start = date('g:i A', strtotime($value['working_start_time']));
                    $end = date('g:i A', strtotime($value['working_end_time']));
                    echo '<option value="' . $value['working_id'] . '" '.$selected.'>' . $start . ' - ' . $end . '</option>';
                }
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
}



