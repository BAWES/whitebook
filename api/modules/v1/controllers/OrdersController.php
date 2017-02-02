<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\rest\Controller;
use common\models\Order;

class OrdersController extends Controller
{

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
		$actions['options'] = [
			'class' => 'yii\rest\OptionsAction',
			// optional:
			'collectionOptions' => ['GET', 'POST', 'HEAD', 'OPTIONS'],
			'resourceOptions' => ['GET', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
		];
		return $actions;
	}


	public function actionListOrder($offset = 0) {

		$limit = Yii::$app->params['limit'];
		$orderList = [];
		$orders = Order::find()
			->select('order_id,created_datetime,order_payment_method,order_total_with_delivery')
			->where('customer_id = ' . Yii::$app->user->getId())
			->andWhere('order_transaction_id != ""')
			->orderBy('created_datetime DESC')
			->asArray()
			->offset($offset)
		    ->limit($limit)
		    ->all();
		foreach ($orders as $order ) {
            $orderList[] = $order + ['count'=>Order::itemCount($order['order_id'])];
        }
        return $orderList;
	}

	//View order detail 
	public function actionOrderDetail($order_id) {

		$orderDetail = [];
		if ($order_id) {
            $order = \common\models\Suborder::find()->where(['order_id' => $order_id])->one();

            if ($order) {
                $orderDetail['order'] = $order;
                $q = 'SELECT `item`.`item_name`,`slot`.`timeslot_start_time`,`slot`.`timeslot_end_time`, `image`.`image_path`, 
                      `purchase`.`suborder_id`, `purchase`.`timeslot_id`, `purchase`.`item_id`, `purchase`.`area_id`, 
                      `purchase`.`address_id`, `purchase`.`purchase_delivery_address`, `purchase`.`purchase_delivery_date`, 
                      `purchase`.`purchase_price_per_unit`, `purchase`.`purchase_customization_price_per_unit`, 
                      `purchase`.`purchase_quantity`, `purchase`.`purchase_total_price` FROM `whitebook_suborder_item_purchase` as `purchase` 
                      left join `whitebook_image` as `image` on image.item_id = purchase.item_id 
                      left join `whitebook_vendor_delivery_timeslot` as `slot` on `slot`.`timeslot_id` = `purchase`.`timeslot_id` 
                      left join `whitebook_vendor_item` as `item` on `item`.`item_id`= `purchase`.item_id
                      where `purchase`.`suborder_id` = '.$order->suborder_id.' group by `image`.item_id';
                $orderDetail['items'] = Yii::$app->db->createCommand($q)->queryAll();
                return $orderDetail;
            } else {
				return [
					'operation' => 'error',
					'message' => 'Invalid order id'
				];
			}
		} else {
			return [
				'operation' => 'error',
				'message' => 'Invalid order id'

			];
		}
	}
}