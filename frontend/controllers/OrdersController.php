<?php

namespace frontend\controllers;


class OrdersController extends BaseController
{
	public function actionIndex() {

		$orders = '';
		return $this->render('index', [
			'orders' => $orders
		]);
	}
}