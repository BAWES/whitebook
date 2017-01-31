<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\rest\Controller;
use yii\helpers\Url;
use common\models\CustomerCart;
use common\models\CustomerAddress;
use common\models\Country;
use common\models\PaymentGateway;
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
					'timeslot_end_time' => $item['timeslot_end_time'],
					'area_id' => $item['area_id'],
					'quantity' => $item['cart_quantity']
				], true);
			}
			if ($errors) {
				return [
					"operation" => "error",
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
			"operation" => "error",
			"message" => 'Order Successfully Completed',
			"order_id" => $order_id,
		];
    }
}
