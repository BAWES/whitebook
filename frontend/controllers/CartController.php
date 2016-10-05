<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Response;
use frontend\models\Vendor;
use common\models\Vendoritem;
use common\models\City;
use common\models\Location;
use common\models\CustomerCart;
use common\models\Deliverytimeslot;
use common\models\Order;

class CartController extends BaseController
{
    private $errors = array();

    public function init(){
        if(Yii::$app->user->isGuest) {
            $this->redirect(['/site/index']);
        }
    }

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

    //list all products
    public function actionIndex()
    {
        $items = CustomerCart::items();

        return $this->render('index', [
            'items' => $items
        ]);
    }

    public function actionUpdateCartItemPopup(){
        if(Yii::$app->request->isAjax) {
            $items = CustomerCart::findOne($_REQUEST['id']);
            return $this->renderPartial('edit_cart', [
                'items' => $items
            ]);
        }
    }

    public function actionUpdateCartItem(){
        if(Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            $data = Yii::$app->request->post();

            if($this->validate_item($data)) {
                $cart = CustomerCart::findOne($data['cart_id']);
                if ($cart) {
                    $cart->cart_delivery_date = $data['delivery_date'];
                    $cart->timeslot_id  =   $data['timeslot_id'];
                    $cart->cart_quantity =  $data['quantity'];
                    $cart->cart_delivery_date = date('Y-m-d', strtotime($data['delivery_date']));
                    $cart->modified_datetime  = date('Y-d-m h:i:s');

                    if ($cart->save()) {

                        Yii::$app->getSession()->setFlash('success', Yii::t(
                            'frontend',
                            'Success: Product <a href="{product_link}">{product_name}</a> added to cart!',
                            [
                                'product_link' => Url::to(['shop/product', 'slug' => $cart->item->slug]),
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

    //list all products
    public function actionConfirm()
    {
        $items = CustomerCart::items();
        
        if (Order::confirmOrder()) // Confirming order before payment
        {
            $msg = Order::confirmOrder();
            Yii::$app->session->setFlash('danger',$msg);
            return $this->redirect(Yii::$app->request->referrer);
        }

        return $this->render('confirm', [
            'items' => $items
        ]);
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
                    'timeslot_id' => isset($data['timeslot_id'])?$data['timeslot_id']:'',
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
                $cart->timeslot_id = isset($data['timeslot_id'])?$data['timeslot_id']:'';
                $cart->cart_delivery_date = date('Y-m-d', strtotime($data['delivery_date']));
                $cart->cart_customization_price_per_unit = 0;
                $cart->cart_quantity = $data['quantity'];
                $cart->cart_datetime_added = date('Y-d-m h:i:s');
                $cart->cart_valid = 'yes';
                $cart->trash = 'Default';
            }
            
            if($cart->save()) {
                
                $item = Vendoritem::findOne($data['item_id']);

                Yii::$app->getSession()->setFlash('success', Yii::t(
                    'frontend', 
                    'Success: Product <a href="{product_link}">{product_name}</a> added to cart!', 
                    [
                        'product_link' => Url::to(['shop/product', 'slug' => $item->slug]),
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

        $data = Yii::$app->request->post();

        $item = Vendoritem::find()->where([
            'item_id' => $data['item_id'],
            'item_for_sale' => 'Yes'
        ])->one();

        if(!$item) {
            return Yii::t('frontend', 'Item not available for sell!');
        }

        $vendor_id = $item->vendor_id;

        /*
            Check if deliery availabel in selected area
        */
        if(!empty($data['area_id'])) {

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

            if (!$delivery_area) {
                return Yii::t('frontend', 'Delivery not available on selected area');
            }
        }

        //validate to add product to cart
        if ($data['quantity'] > ($item->item_amount_in_stock)) {

            return Yii::t('frontend', 'Item is not available on selected date');
        }

        //-------------- Start Item Capacity -----------------//
        //default capacity is how many of it they can process per day

        //1) get capacity exception for selected date
        $capacity_exception = \common\models\VendorItemCapacityException::findOne([
            'item_id' => $data['item_id'],
            'exception_date' => date('Y-m-d', strtotime($data['delivery_date']))
        ]);

        if($capacity_exception && $capacity_exception->exception_capacity) {
            $capacity = $capacity_exception->exception_capacity;
        } else {
            $capacity = $item->item_default_capacity;
        }
        
        //2) get no of item purchased for selected date
        $purchased_result = Yii::$app->db->createCommand('select sum(ip.purchase_quantity) as purchased from whitebook_suborder_item_purchase ip inner join whitebook_suborder so on so.suborder_id = ip.suborder_id where ip.item_id = "'.$data['item_id'].'" AND ip.trash = "Default" AND so.trash ="Default" AND so.status_id != 0 AND DATE(ip.purchase_delivery_date) = DATE("' . date('Y-m-d', strtotime($data['delivery_date'])) . '")')->queryOne();

        if($purchased_result) {
            $purchased = $purchased_result['purchased'];
        } else {
            $purchased = 0;
        }

        if(($data['quantity'] + $purchased) > $capacity) {
            return Yii::t('frontend', 'Item is not available on selected date');
        }

        //-------------- END Item Capacity -----------------//

        //current date should not in blocked date
        $block_date = \common\models\Blockeddate::findOne([
            'vendor_id' => $vendor_id,
            'block_date' => date('Y-m-d', strtotime($data['delivery_date']))
        ]);

        if($block_date) {
            return Yii::t('frontend', 'Item not available for selected date .');
        }

        //day should not in week off
        $blocked_days = explode(',', Vendor::findOne($vendor_id)->blocked_days);
        $day = date('N', strtotime($data['delivery_date']));//7-sunday, 1-monday

        if(in_array($day, $blocked_days)) {
            return Yii::t('frontend', 'Item not available for selected date .');
        }

        return 1;
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
    public function actionGetdeliverytimeslot()
    {
        if (Yii::$app->request->isAjax) {

            $data = Yii::$app->request->post();
            $string = $data['sel_date'];
            $timestamp = strtotime($string);

            $vendor_timeslot = Deliverytimeslot::find()
            ->select(['timeslot_id','timeslot_start_time','timeslot_end_time'])
            ->where(['vendor_id' => $data['vendor_id']])
            ->andwhere(['timeslot_day' => date("l", $timestamp)])
            ->asArray()->all();
            if ($vendor_timeslot) {

                foreach ($vendor_timeslot as $key => $value) {
                    if (strtotime($data['sel_date']) == (strtotime($data['currentDate']))) {
                        if (strtotime($data['time']) < strtotime($value['timeslot_start_time'])) {
                            $start = date('g:i A', strtotime($value['timeslot_start_time']));
                            $end = date('g:i A', strtotime($value['timeslot_end_time']));
                            echo '<option value="' . $value['timeslot_id'] . '">' . $start . ' - ' . $end . '</option>';
                        }
                    } else {
                        $start = date('g:i A', strtotime($value['timeslot_start_time']));
                        $end = date('g:i A', strtotime($value['timeslot_end_time']));
                        echo '<option value="' . $value['timeslot_id'] . '">' . $start . ' - ' . $end . '</option>';
                    }
                }
            } else {
                echo 0;
                exit;
            }
        }
    }
}



