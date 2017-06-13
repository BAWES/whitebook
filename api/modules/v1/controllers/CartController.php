<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\rest\Controller;
use yii\helpers\ArrayHelper;
use common\models\CustomerCart;
use common\models\CustomerCartMenuItem;
use common\models\VendorItem;
use common\models\VendorItemPricing;
use api\models\Customer;
use common\components\CFormatter;

/**
 * Auth controller provides the initial access token that is required for further requests
 * It initially authorizes via Http Basic Auth using a base64 encoded username and password
 */
class CartController extends Controller
{

    /**
     * @var array
     */
    private $errors = array();

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

        /*
        // Bearer Auth checks for Authorize: Bearer <Token> header to login the user
        ($behaviors['authenticator'] = [
            'class' => \yii\filters\auth\HttpBearerAuth::className(),
        ];
        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
        $behaviors['authenticator']['except'] = ['options'];
        */

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

    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);
        
        //for guest
        Yii::$app->session->set('_user', Yii::$app->request->get('cart-session-id'));

        //for login customer 
        $authHeader = Yii::$app->request->getHeaders()->get('Authorization');
        if ($authHeader !== null && preg_match('/^Bearer\s+(.*?)$/', $authHeader, $matches)) {
            $customer = Customer::findIdentityByAccessToken($matches[1]);
            Yii::$app->user->loginByAccessToken($customer);
        }
    }
        
    /**
     * Return list of all cart items
     * in cart table
     * @return array|\yii\db\ActiveRecord[]
     */
    public function actionList()
    {
        $items = CustomerCart::items();
        $subTotal = $total = $delivery_charge = 0;
        $result = [];
        $delivery = [];
        $options = [];
        $errors = [];
        $cartItems['items'] = [];
        $cartItems['summary'] = [];

        $area_id = Yii::$app->request->get('area_id');
        $time_slot = Yii::$app->request->get('time_slot');
        $delivery_date = Yii::$app->request->get('delivery_date');
        
        foreach ($items as $key => $value) 
        {
            $vendor_name = CustomerCart::getVendorDetail($value['vendor_id'])->vendor_name;
            
            $vendors[$value['vendor_id']] = [
                'vendor' => $vendor_name
            ];

            unset($value['item']);
            unset($value['image']);

            $value['options'] = CustomerCartMenuItem::find()
                ->select('{{%vendor_item_menu_item}}.price, {{%vendor_item_menu_item}}.menu_item_id, {{%vendor_item_menu_item}}.menu_id, {{%vendor_item_menu_item}}.menu_item_name, {{%vendor_item_menu_item}}.menu_item_name_ar, {{%customer_cart_menu_item}}.quantity')
                ->joinVendorItemMenuItem()
                ->joinVendorItemMenu()
                ->cartID($value['cart_id'])
                ->andWhere(['menu_type' => 'options'])
                ->asArray()
                ->all();

            $value['addons'] = CustomerCartMenuItem::find()
                ->select('{{%vendor_item_menu_item}}.price, {{%vendor_item_menu_item}}.menu_item_id, {{%vendor_item_menu_item}}.menu_id, {{%vendor_item_menu_item}}.menu_item_name, {{%vendor_item_menu_item}}.menu_item_name_ar, {{%customer_cart_menu_item}}.quantity')
                ->joinVendorItemMenuItem()
                ->joinVendorItemMenu()
                ->cartID($value['cart_id'])
                ->andWhere(['menu_type' => 'addons'])
                ->asArray()
                ->all();

            // price chart 

            $price_chart = VendorItemPricing::find()
                ->where(['item_id' => $value['item_id'], 'trash' => 'Default'])
                ->andWhere(['<=', 'range_from', $value['cart_quantity']])
                ->andWhere(['>=', 'range_to', $value['cart_quantity']])
                ->orderBy('pricing_price_per_unit DESC')
                ->one();

            if ($price_chart) {
                $value['item_price_per_unit'] = $price_chart->pricing_price_per_unit;
            } 

            // get final item total 
            $vendorDetail = CustomerCart::getVendorDetail($value['vendor_id']);
            $value['total'] = VendorItem::itemFinalPrice(
                        $value['item_id'], 
                        $value['cart_quantity'], 
                        array_merge($value['addons'], $value['options'])
                    );
            $value['vendor']= $vendorDetail->vendor_name;
            $subTotal += $value['total'];


            $questionAnswers = \common\models\CustomerCartItemQuestionAnswer::getCartQuestionAnswer($value['cart_id']);
            if($questionAnswers)
            {
                $q=0;
                foreach($questionAnswers as $answer) {
                    $options[$q] = ['question'=>$answer->question->question,'answer'=>$answer->answer];
                    $q++;
                }
                $value['customs'] = $options;
            } else {
                $value['customs'] = [];
            }

            //get item errors

            $menu_items = array_merge($value['options'], $value['addons']);

            $item_errors = CustomerCart::validate_item([
                'item_id' => $value['item_id'],
                'time_slot' => $time_slot,
                'delivery_date' => $delivery_date,
                'area_id' => $area_id,
                'quantity' => $value['cart_quantity'],
                'menu_item' => ArrayHelper::map($menu_items, 'menu_item_id', 'quantity')
            ], true);

            $value['errors'] = $this->formateErrors($item_errors);

            if($item_errors)
                $errors[] = $item_errors;

            $result[] = $value;
        }

        if ($result) {
            $i=0;
            foreach ($vendors as $key => $vendor) {
                $charge = \common\models\Booking::getDeliveryCharges('', $key, $area_id);
                $delivery_charge += (int)$charge;
                $delivery[$i] = ['vendor'=>$vendor['vendor'],'charges'=>\common\components\CFormatter::format($charge)];
                $i++;
            }

            $cartItems['items'] = $result;
            $cartItems['errors'] = $errors;
            $cartItems['summary']['subtotal'] = CFormatter::format($subTotal);
            $cartItems['summary']['delivery_vendors'] = $delivery;
            $cartItems['summary']['delivery_charges'] = CFormatter::format($delivery_charge);
            $cartItems['summary']['total'] = CFormatter::format($subTotal + $delivery_charge);
        }

        return $cartItems;
    }

    private function formateErrors($item_errors) 
    {   
        unset($item_errors['cart_quantity_remain']);
        
        $result = [];

        foreach ($item_errors as $key => $value) 
        {
            foreach ($value as $ek => $err) {
                $result[] = $err;
            }            
        }

        return $result;
    }

    /**
     * method to return cart count
     * @return int|string
     */
    public function actionCartCount()
    {
        return CustomerCart::item_count();
    }

    /**
     * method to add item to cart
     * after validate
     * @return array
     */
//    public function actionAdd() {
//        $data = [];
//        $data["item_id"] = Yii::$app->request->getBodyParam('item_id');
//        $data["timeslot_id"] = Yii::$app->request->getBodyParam('timeslot_id');
//        $data["delivery_date"] = Yii::$app->request->getBodyParam('delivery_date');
//        $data["quantity"] = Yii::$app->request->getBodyParam('quantity');
//        $data["area_id"] = Yii::$app->request->getBodyParam('area_id');
//
//        if (empty($data["item_id"])) {
//            return [
//                "operation" => "error",
//                "message" => "Invalid Item ID"
//            ];
//        } else if (empty($data["timeslot_id"])) {
//            return [
//                "operation" => "error",
//                "message" => "Invalid Time Slot"
//            ];
//        } else if (empty($data["delivery_date"])) {
//            return [
//                "operation" => "error",
//                "message" => "Invalid Delivery Date"
//            ];
//        } else if (empty($data["quantity"])) {
//            return [
//                "operation" => "error",
//                "message" => "Invalid Quantity"
//            ];
//        } else if (empty($data["area_id"])) {
//            return [
//                "operation" => "error",
//                "message" => "Invalid Area"
//            ];
//        }
//
//        if($this->validateItem($data)) {
//
//            if (isset($data['area_id']) && $data['area_id'] != '') {
//                $deliverlocation = $data['area_id'];
//                if (is_numeric($deliverlocation)) {
//                    $location = $deliverlocation;
//                } else {
//                    $end = strlen($deliverlocation);
//                    $from = strpos($deliverlocation, '_') + 1;
//                    $address_id = substr($deliverlocation, $from, $end);
//                    $location = \common\models\CustomerAddress::findOne($address_id)->area_id;
//                }
//            } else {
//                $location = '';
//            }
//
//            $cart = CustomerCart::find()
//                ->where([
//                    'item_id' => $data['item_id'],
//                    'area_id'   => $location,
//                    'timeslot_id' => isset($data['timeslot_id'])?$data['timeslot_id']:'',
//                    'cart_delivery_date' => date('Y-m-d', strtotime($data['delivery_date']))
//                ])
//                ->one();
//            /*
//                product already available in cart
//                Just need to update quantity
//            */
//            if($cart) {
//
//                if ($data['quantity']) {
//                    $quantity = $data['quantity'];
//                } else {
//                    $quantity = 1;
//
//                }
//
//                $cart->cart_quantity = $cart->cart_quantity + $quantity;
//            }
//
//            /*
//                Product not available in cart
//             */
//            if(!$cart) {
//
//                $cart = new CustomerCart();
//                $cart->customer_id = Yii::$app->user->getId();
//                $cart->item_id = $data['item_id'];
//                $cart->area_id = $location;
//                $cart->timeslot_id = isset($data['timeslot_id'])?$data['timeslot_id']:'';
//                $cart->cart_delivery_date = date('Y-m-d', strtotime($data['delivery_date']));
//                $cart->cart_customization_price_per_unit = 0;
//                $cart->cart_quantity = $data['quantity'];
//                $cart->cart_datetime_added = date('Y-d-m h:i:s');
//                $cart->cart_valid = 'yes';
//                $cart->trash = 'Default';
//            }
//
//            if($cart->save()) {
//
//                return [
//                    "operation" => "success",
//                    "message" => "Item Added To Cart Successfully ",
//                ];
//
//            } else {
//
//                return [
//                    "operation" => "error",
//                    "message" => array_merge($this->errors, $cart->getErrors())
//                ];
//            }
//
//        } else {
//            return [
//                "operation" => "errors",
//                "message" => $this->getErrorMessage($this->errors)
//            ];
//        }
//    }

    /**
     * method to update cart item after
     * validate
     * @return array
     */
//    public function actionUpdate(){
//
//        $data = [];
//        $data["item_id"] = Yii::$app->request->getBodyParam('item_id');
//        $data["timeslot_id"] = Yii::$app->request->getBodyParam('timeslot_id');
//        $data["delivery_date"] = Yii::$app->request->getBodyParam('delivery_date');
//        $data["quantity"] = Yii::$app->request->getBodyParam('quantity');
//        $data["area_id"] = Yii::$app->request->getBodyParam('area_id');
//        $data["cart_id"] = Yii::$app->request->getBodyParam('cart_id');
//
//        if (empty($data["item_id"])) {
//            return [
//                "operation" => "error",
//                "message" => "Invalid Item ID"
//            ];
//        } else if (empty($data["timeslot_id"])) {
//            return [
//                "operation" => "error",
//                "message" => "Invalid Time Slot"
//            ];
//        } else if (empty($data["delivery_date"])) {
//            return [
//                "operation" => "error",
//                "message" => "Invalid Delivery Date"
//            ];
//        } else if (empty($data["quantity"])) {
//            return [
//                "operation" => "error",
//                "message" => "Invalid Quantity"
//            ];
//        } else if (empty($data["area_id"])) {
//            return [
//                "operation" => "error",
//                "message" => "Invalid Area"
//            ];
//        } else if (empty($data["cart_id"])) {
//            return [
//                "operation" => "error",
//                "message" => "Invalid Cart ID"
//            ];
//        }



//
//        if($this->validateItem($data)) {
//            $cart = CustomerCart::findOne($data['cart_id']);
//            if ($cart) {
//                $cart->cart_delivery_date = $data['delivery_date'];
//                $cart->timeslot_id  =   $data['timeslot_id'];
//                $cart->cart_quantity =  $data['quantity'];
//                $cart->cart_delivery_date = date('Y-m-d', strtotime($data['delivery_date']));
//                $cart->modified_datetime  = date('Y-d-m h:i:s');
//
//                if ($cart->save()) {
//                    return [
//                        "operation" => "success",
//                        "message" => "Cart Updated Successfully ",
//                        "total-cart-items" => CustomerCart::item_count()
//                    ];
//                } else {
//                    return [
//                        "operation" => "error",
//                        "message" => Yii::t('frontend','Error while updateing cart')
//                    ];
//                }
//            }
//        } else {
//            return [
//                "operation" => "error",
//                "message" => $this->errors
//            ];
//        }
//    }

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

        $data = [];
        $data["item_id"] = Yii::$app->request->getBodyParam('item_id');
        $data["time_slot"] = Yii::$app->request->getBodyParam('time_slot');
        $data["delivery_date"] = Yii::$app->request->getBodyParam('delivery_date');
        $data["quantity"] = Yii::$app->request->getBodyParam('quantity');
        $data["area_id"] = Yii::$app->request->getBodyParam('area_id');
        $data["menu_item"] = Yii::$app->request->getBodyParam('menu_item');
        $data["female_service"] = Yii::$app->request->getBodyParam('female_service');
        $data["special_request"] = Yii::$app->request->getBodyParam('special_request');
        $data["answer"] = Yii::$app->request->getBodyParam('answer');

        if (empty($data["item_id"])) {
            return [
                "operation" => "error",
                "message" => "Invalid Item ID"
            ];
        } else if (empty($data["time_slot"])) {
            return [
                "operation" => "error",
                "message" => "Invalid Time Slot"
            ];
        } else if (empty($data["delivery_date"])) {
            return [
                "operation" => "error",
                "message" => "Invalid Delivery Date"
            ];
        } else if (empty($data["quantity"])) {
            return [
                "operation" => "error",
                "message" => "Invalid Quantity"
            ];
        } else if (empty($data["area_id"])) {
            return [
                "operation" => "error",
                "message" => "Invalid Area"
            ];
        }

        //remove menu item with 0 quantity

        if(empty($data['menu_item'])) {
            $data['menu_item'] = [];
        }

        foreach ($data['menu_item'] as $key => $value)
        {
            if($value == 0)
                unset($data['menu_item'][$key]);
        }

        if($this->validateItem($data)) {

            $query = CustomerCart::find()
                ->item($data['item_id']);
                
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

                if(sizeof($arr_cart_menu_items) != sizeof($data['menu_item'])) {
                    $cart = false;
                }

                //check menu item quantity is same for 1 quantity of item in cart and we trying to add
                // so we can add cart item with p quantity to cart item item with q quantity if both ahve
                // same no of menu items

                foreach ($arr_cart_menu_items as $key => $value) {
                    if(!$cart || empty($data['menu_item'][$key]) || $data['menu_item'][$key]/$data['quantity'] != $value/$cart->cart_quantity){
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

                if(Yii::$app->user->isGuest) {
                    $cart_session_id = \common\models\Customer::currentUser();
                } else {
                    $cart_session_id = null;
                }

                $cart = new CustomerCart();
                $cart->customer_id = Yii::$app->user->getId();
                $cart->item_id = $data['item_id'];
                $cart->cart_customization_price_per_unit = 0;
                $cart->cart_quantity = $data['quantity'];
                $cart->cart_datetime_added = date('Y-d-m h:i:s');
                $cart->cart_session_id = $cart_session_id;
                $cart->cart_valid = 'yes';
                $cart->trash = 'Default';

                if(!$cart->save())
                {
                    return [
                        'operation' => 'error',
                        'code' => '0',
                        'message' => array_merge($this->errors, $cart->getErrors())
                    ];
                }

                // add menu

                foreach ($data['menu_item'] as $key => $value) {

                    $mi = \common\models\VendorItemMenuItem::findOne($key);

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
                        $cartItemAnswers = new \common\models\CustomerCartItemQuestionAnswer();
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

                return [
                    "operation" => "success",
                    "code" => "1",
                    "message" => "Item added to cart successfully"
                ];

            } else {

                return [
                    'operation' => 'error',
                    'code' => '2',
                    'message' => array_merge($this->errors, $cart->getErrors())
                ];
            }

        } else {
            return [
                'operation' => 'error',
                'code' => '3',
                'message' => $this->errors
            ];
        }
    }
    /**
     * @param $data
     * @return bool
     * method to validate cart items
     */
    private function validateItem($data) {
        $this->errors = CustomerCart::validate_item($data);
        return !$this->errors;
    }

    /**
     * method to remove cart from cart table
     * @return array|\yii\db\ActiveRecord[]
     * @throws \Exception
     */
    public function actionRemove($cart_id) {

        $cartID = $cart_id;
        if ($cartID) {
            $cartData = CustomerCart::findOne($cartID);
            if ($cartData) {
                $cartData->delete();
                return [
                    "operation" => "success",
                    "code" => "1",
                    "message" => "Cart Item Deleted Successfully"
                ];
            } else {
                return [
                    "operation" => "error",
                    "code" => "0",
                    "message" => "Invalid Cart ID"
                ];
            }
        } else {
            return [
                "operation" => "error",
                "code" => "0",
                "message" => "Invalid Cart ID"
            ];
        }
    }

    public function getErrorMessage($error) {
        $list = '';
        if ($error['cart_delivery_date']) {
            return implode(',',$error['cart_delivery_date']);
        } else if ($error['cart_quantity']) {
            foreach($this->errors['cart_quantity'] as $error) {
               $list .= (is_array($error)) ? implode(',',$error) : $error;
            }
            return $list;
        } else if ($error['area_id']) {
            return implode(',',$error['area_id']);
        } else if ($error['timeslot_id']) {
            return implode(',',$error['timeslot_id']);
        }
    }

    public static function getDeliveryAddress(){

        if(Yii::$app->user->isGuest) {
            return [];
        }

        $area_id = self::findOne(['customer_id' => Yii::$app->user->getId()])->area_id;

        $result = CustomerAddress::find()
            ->joinWith('location')
            ->joinWith('city')
            ->where([
                '{{%customer_address}}.customer_id' => Yii::$app->user->getId(),
                '{{%customer_address}}.trash' => 'Default',
                '{{%location}}.id' => $area_id,
                '{{%location}}.status' => 'Active',
                '{{%location}}.trash' => 'Default',
                '{{%city}}.status' => 'Active',
                '{{%city}}.trash' => 'Default'])
            ->asArray()
            ->all();

        return $result;
    }

    /**
     * Get cart session id to perform cart operations 
     */
    public function actionCartSessionId() 
    {        
        return [
            'cart_session_id' => \common\models\Customer::currentUser()
        ];
    }
}
