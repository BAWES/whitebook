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
            $this->redirect(['site/index']);
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

                $cart = new CustomerCart();
                $cart->customer_id = Yii::$app->user->getId();
                $cart->item_id = $data['item_id'];
                $cart->area_id = isset($data['area_id'])?$data['area_id']:'';
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


            foreach ($vendor_timeslot as $key => $value) {
                if (strtotime($data['sel_date']) == (strtotime($data['currentDate']))) {
                    if (strtotime($data['time']) < strtotime($value['timeslot_start_time'])) {
                        $start = date('g:i A', strtotime($value['timeslot_start_time']));
                        $end = date('g:i A', strtotime($value['timeslot_end_time']));
                        echo '<option value="' . $value['timeslot_id'] . '">' .$start . ' - ' . $end . '</option>';
                    }
                } else {
                    $start = date('g:i A', strtotime($value['timeslot_start_time']));
                    $end = date('g:i A', strtotime($value['timeslot_end_time']));
                    echo '<option value="' . $value['timeslot_id'] . '">' . $start . ' - ' . $end . '</option>';
                }
            }
        }
    }
}



