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
		return Order::find()
			->where('customer_id = ' . Yii::$app->user->getId())
			->andWhere('order_transaction_id != ""')
			->orderBy('created_datetime DESC')
			->offset($offset)
		    ->limit($limit)
		    ->all();

	}

	//View order detail 
	public function actionOrderDetail($order_id) {

		$orderDetail = [];
		if ($order_id) {
			$order = Order::findOne($order_id);
			if ($order) {
                $subOrder = \common\models\Suborder::find()->where(['order_id' => $order_id])->one();
				$orderDetail['order'] = $order;
                $orderDetail['items'] = $order->subOrderItems($subOrder->suborder_id);
                $orderDetail['suborder'] = $subOrder;
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