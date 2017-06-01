<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\rest\Controller;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use common\models\Customer;
use common\models\CustomerCart;
use common\models\CustomerAddress;
use common\models\Country;
use common\models\PaymentGateway;
use common\models\CustomerCartMenuItem;
use common\models\Booking;
use frontend\models\AddressType;

/**
 * Class CheckoutController
 * @package api\modules\v1\controllers
 */
class CheckoutController extends Controller
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
	 * method to list cart items with error
	 * if any
	 * @return array|\yii\db\ActiveRecord[]
     */
	public function actionListCartItems()
	{
		$model = new CustomerCart();
		$items = CustomerCart::items();
		$errors = [];
		if ($items) {
		    foreach ($items as $item) {

				$errors[] = $model->validate_item([
					'item_id' => $item['item_id'],
					'delivery_date' => $item['cart_delivery_date'],
					'timeslot_end_time' => $item['time_slot'],
					'area_id' => $item['area_id'],
					'quantity' => $item['cart_quantity']
				], true);
			}
			if ($errors) {
				return [
					"operation" => "error",
					"code" => "0",
					"message" => $errors,
					"cart-list" => $items
				];
			} else {
				return $items;
			}
		} else {
			return [];
		}
	}

	/**
	 * method to Display form to select delivery address
	 * @return array
     */
	public function actionAddress(){
		$addresses = [];
		$customer_id = Yii::$app->user->getId();
		$addresses['user_address'] = CustomerAddress::find()
			->select('whitebook_city.city_name, whitebook_city.city_name_ar, whitebook_location.location,
                whitebook_location.location_ar, whitebook_customer_address.*')
			->leftJoin('whitebook_location', 'whitebook_location.id = whitebook_customer_address.area_id')
			->leftJoin('whitebook_city', 'whitebook_city.city_id = whitebook_customer_address.city_id')
			->where('customer_id = :customer_id', [':customer_id' => $customer_id])
			->asArray()
			->all();
		$addresses['countries'] = Country::loadcountry();
		$addresses['address_type'] = AddressType::loadAddresstype();
		return $addresses;
	}

	/**
	 * method to Display payment methods
	 * @return array|\yii\db\ActiveRecord[]
     */
	public function actionPaymentGetawayList(){

        return $payment_gateway = PaymentGateway::find()
            ->where(['status' => 1])
            ->all();
	}

	/**
	 * method to show order id and clear cart items
	 * @return array
     */
	public function actionSuccess() {
        CustomerCart::deleteAll('customer_id = "'.Yii::$app->user->getId().'"');

		$order_id = Yii::$app->session->get('order_id',111111);

		return [
			"operation" => "success",
			"code" => "1",
			"message" => 'Order Successfully Completed',
			"order_id" => $order_id,
		];
    }

    public function actionCartItemWithAddress() {
        $cartItemsWithAddress = [];
	    $items = CustomerCart::find()
            ->select('
                {{%customer_cart}}.area_id, 
                {{%customer_cart}}.cart_id, 
                {{%customer_cart}}.cart_delivery_date, 
                {{%customer_cart}}.item_id, 
                {{%vendor_item}}.item_name,
            ')
            ->joinWith('item',false)
            ->where([
                '{{%customer_cart}}.customer_id' => Yii::$app->user->getId(),
                '{{%customer_cart}}.cart_valid' => 'yes',
                '{{%customer_cart}}.trash' => 'Default',
                '{{%vendor_item}}.trash' => 'Default',
                '{{%vendor_item}}.item_for_sale' => 'Yes',
                '{{%vendor_item}}.item_status' => 'Active',
                '{{%vendor_item}}.item_approved' => 'Yes',
            ])
            ->asArray()
            ->all();
        foreach ($items as $item) {
            $addresses = CustomerAddress::find()
                ->select('
                {{%location}}.location, 
                {{%city}}.city_name, 
                {{%customer_address}}.address_id, 
                {{%customer_address}}.city_id, 
                {{%customer_address}}.area_id, 
                {{%customer_address}}.customer_id, 
                {{%customer_address}}.address_name,
                {{%customer_address}}.address_data,
                ')
                ->joinWith('location',false)
                ->joinWith('city',false)
                ->where([
                    '{{%customer_address}}.customer_id' => Yii::$app->user->id,
                    '{{%customer_address}}.trash' => 'Default',
                    '{{%location}}.id' => $item['area_id'],
                    '{{%location}}.status' => 'Active',
                    '{{%location}}.trash' => 'Default',
                    '{{%city}}.status' => 'Active',
                    '{{%city}}.trash' => 'Default'])
                ->asArray()
                ->all();
            $cartItemsWithAddress[] = array_merge($item + ['address'=>$addresses]);
        }
        return $cartItemsWithAddress;
    }

    /**
     * List delivery area 
     */
    public function actionDeliveryArea()
    {
    	$cities = \common\models\City::find()->where([
    		'trash' => 'Default',
    		'status' => 'Active'
    	])->with('locations')->all();

        $result = [];

        foreach ($cities as $city) 
        {
            $city_name = \common\components\LangFormat::format($city->city_name,$city->city_name_ar);
            
            $result[$city_name] = [];
            
            foreach ($city->locations as $location) {
                
                if ($location->trash != 'Default' || $location->status !='Active') 
                	continue;
                
                $result[$city_name][] = [
                	'id' => $location->id,
				    'country_id' => $location->country_id,
				    'city_id' => $location->city_id,
				    'location' => $location->location,
				    'location_ar' => $location->location_ar
                ];
            }
        }

        return $result;
    }

    public function actionConfirm() 
    {    	
        $area_id = Yii::$app->request->getBodyParam('delivery-location');
        $cart_delivery_date = date('Y-m-d', strtotime(Yii::$app->request->getBodyParam('delivery-date')));
        $time_slot = Yii::$app->request->getBodyParam('event_time');

        $items = CustomerCart::items();
		
		foreach ($items as $item) {
            $menu_items = CustomerCartMenuItem::find()->cartID($item['cart_id'])->all();

			$error = CustomerCart::validate_item([
    			'item_id' => $item['item_id'],
                'time_slot' => $time_slot,
    			'delivery_date' => $cart_delivery_date,
                'area_id' => $area_id,
    			'quantity' => $item['cart_quantity'],
                'menu_item' => ArrayHelper::map($menu_items, 'menu_item_id', 'quantity')
    		], true);

    		if($error) {
    			return [
    				'operation' => 'error',
    				'message' => 'Can not proceed as cart item(s) not valid',
    			];
    		}
		}
    	
        if(Yii::$app->user->isGuest)
        {
            $customer_id = 0;
            $customer_name = Yii::$app->request->getBodyParam('customer_name');
            $customer_lastname = Yii::$app->request->getBodyParam('customer_lastname');
            $customer_email = Yii::$app->request->getBodyParam('customer_email');
            $customer_mobile = Yii::$app->request->getBodyParam('customer_mobile');
        }
        else
        {
            $customer = Customer::findOne(Yii::$app->user->getId());

            $customer_id = $customer->customer_id;
            $customer_name = $customer->customer_name;
            $customer_lastname = $customer->customer_last_name;
            $customer_email = $customer->customer_email;
            $customer_mobile = $customer->customer_mobile;
        }

        //address

        $address_id = Yii::$app->request->getBodyParam("address_id");

        if(empty($address_id) || empty($area_id) || empty($cart_delivery_date) || empty($time_slot))
        {
        	return [
				'operation' => 'error',
				'message' => 'Delivery info missing',
			];
        }

        if(Yii::$app->user->isGuest && (empty($customer_name) || empty($customer_lastname) || empty($customer_email) || empty($customer_mobile)))
        {
        	return [
				'operation' => 'error',
				'message' => 'Customer info missing',
			];
        }

        $arr_booking_id = Booking::checkoutConfirm(
        	$area_id,
            $cart_delivery_date,
            $time_slot,
            $customer_id,
            $customer_name,
            $customer_lastname,
            $customer_email,
            $customer_mobile,
            $address_id
        );

        return [
        	'operation' => 'success',
        	'arr_booking_id' => $arr_booking_id
        ];
    }
}
