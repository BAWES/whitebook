<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\rest\Controller;
use \common\models\CustomerCart;
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

        // Bearer Auth checks for Authorize: Bearer <Token> header to login the user
        $behaviors['authenticator'] = [
            'class' => \yii\filters\auth\HttpBearerAuth::className(),
        ];
        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
        $behaviors['authenticator']['except'] = ['options'];

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
     * Method to return list of all cart items
     * in cart table
     * @return array|\yii\db\ActiveRecord[]
     */
    public function actionListing()
    {
        return $this->listing();
    }

    /**
     * Method to return list of cart items
     * only to class methods
     * @return array|\yii\db\ActiveRecord[]
     */
    private function listing()
    {
        return CustomerCart::items();
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
    public function actionAdd() {
        $data = [];
        $data["item_id"] = Yii::$app->request->getBodyParam('item_id');
        $data["timeslot_id"] = Yii::$app->request->getBodyParam('timeslot_id');
        $data["delivery_date"] = Yii::$app->request->getBodyParam('delivery_date');
        $data["quantity"] = Yii::$app->request->getBodyParam('quantity');
        $data["area_id"] = Yii::$app->request->getBodyParam('area_id');

        if (empty($data["item_id"])) {
            return [
                "operation" => "error",
                "message" => "Invalid Item ID"
            ];
        } else if (empty($data["timeslot_id"])) {
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

        if($this->validateItem($data)) {

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

            $cart = CustomerCart::find()
                ->where([
                    'item_id' => $data['item_id'],
                    'area_id'   => $location,
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

                return [
                    "operation" => "success",
                    "message" => "Item Added To Cart Successfully ",
                    "total-cart-items" => CustomerCart::item_count()
                ];

            } else {

                return [
                    "operation" => "error",
                    "message" => array_merge($this->errors, $cart->getErrors())
                ];
            }

        } else {
            return [
                "operation" => "error",
                "message" => $this->errors
            ];
        }
    }

    /**
     * method to update cart item after
     * validate
     * @return array
     */
    public function actionUpdate(){

        $data = [];
        $data["item_id"] = Yii::$app->request->getBodyParam('item_id');
        $data["timeslot_id"] = Yii::$app->request->getBodyParam('timeslot_id');
        $data["delivery_date"] = Yii::$app->request->getBodyParam('delivery_date');
        $data["quantity"] = Yii::$app->request->getBodyParam('quantity');
        $data["area_id"] = Yii::$app->request->getBodyParam('area_id');
        $data["cart_id"] = Yii::$app->request->getBodyParam('cart_id');

        if (empty($data["item_id"])) {
            return [
                "operation" => "error",
                "message" => "Invalid Item ID"
            ];
        } else if (empty($data["timeslot_id"])) {
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
        } else if (empty($data["cart_id"])) {
            return [
                "operation" => "error",
                "message" => "Invalid Cart ID"
            ];
        }


        if($this->validateItem($data)) {
            $cart = CustomerCart::findOne($data['cart_id']);
            if ($cart) {
                $cart->cart_delivery_date = $data['delivery_date'];
                $cart->timeslot_id  =   $data['timeslot_id'];
                $cart->cart_quantity =  $data['quantity'];
                $cart->cart_delivery_date = date('Y-m-d', strtotime($data['delivery_date']));
                $cart->modified_datetime  = date('Y-d-m h:i:s');

                if ($cart->save()) {

                    return [
                        "operation" => "success",
                        "message" => "Cart Updated Successfully ",
                        "total-cart-items" => CustomerCart::item_count()
                    ];

                } else {
                    return [
                        "operation" => "error",
                        "message" => Yii::t('frontend','Error while updateing cart')
                    ];
                }
            }
        } else {
            return [
                "operation" => "error",
                "message" => $this->errors
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
    public function actionRemove() {

        $cartID = Yii::$app->request->getBodyParam('cart_id');
        if ($cartID) {
            $cartData = CustomerCart::findOne($cartID);
            if ($cartData) {
                $cartData->delete();
                return [
                    "operation" => "success",
                    "message" => "Cart Item Deleted Successfully",
                    "total-cart-items" => CustomerCart::item_count()
                ];
            } else {
                return [
                    "operation" => "error",
                    "message" => "Invalid Cart ID"
                ];
            }
        } else {
            return [
                "operation" => "error",
                "message" => "Invalid Cart ID"
            ];
        }
    }
}
