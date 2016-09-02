<?php

namespace frontend\controllers;

use Yii;
use common\models\Order;
use common\models\Suborder;
use common\models\SuborderItemPurchase;
use yii\data\Pagination;

class OrdersController extends BaseController
{
	public function actionIndex() {

		$query = Order::find()
			->where('customer_id = ' . Yii::$app->user->getId())
			->andWhere('order_transaction_id != ""');

		// create a pagination object with the total count
		$pagination = new Pagination(['totalCount' => $query->count()]);

		// limit the query using the pagination and retrieve the orders
		$orders = $query->offset($pagination->offset)
		    ->limit($pagination->limit)
		    ->all();

		return $this->render('index', [
			'orders' => $orders,
			'pagination' => $pagination
		]);
	}

	//View order detail 
	public function actionView() {

		$order_id = Yii::$app->request->get('order_id');

		$order = Order::findOne($order_id);
		
		$suborder = Suborder::find()
			->where(['order_id' => $order_id])
			->all();
			
		return $this->render('view', [
			'order' => $order,
			'suborder' => $suborder
		]);
	}
}