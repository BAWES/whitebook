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

		\Yii::$app->view->title = Yii::$app->params['SITE_NAME'].' | Orders';
		\Yii::$app->view->registerMetaTag(['name' => 'description', 'content' => Yii::$app->params['META_DESCRIPTION']]);
		\Yii::$app->view->registerMetaTag(['name' => 'keywords', 'content' => Yii::$app->params['META_KEYWORD']]);

		if (Yii::$app->user->isGuest) {
			Yii::$app->session->set('show_login_modal', 1);//to display login modal			
	        return $this->redirect(['/site/index']);
	    }

		$query = Order::find()
			->where('customer_id = ' . Yii::$app->user->getId())
			->andWhere('order_transaction_id != ""')
			->orderBy('created_datetime DESC');

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

	public function actionRequestOrder() {

		\Yii::$app->view->title = Yii::$app->params['SITE_NAME'].' | Orders';
		\Yii::$app->view->registerMetaTag(['name' => 'description', 'content' => Yii::$app->params['META_DESCRIPTION']]);
		\Yii::$app->view->registerMetaTag(['name' => 'keywords', 'content' => Yii::$app->params['META_KEYWORD']]);

		if (Yii::$app->user->isGuest) {
			Yii::$app->session->set('show_login_modal', 1);//to display login modal
	        return $this->redirect(['/site/index']);
	    }

		$query = Order::find()
			->where('customer_id = ' . Yii::$app->user->getId())
			->orderBy('created_datetime DESC');

		// create a pagination object with the total count
		$pagination = new Pagination(['totalCount' => $query->count()]);

		// limit the query using the pagination and retrieve the orders
		$orders = $query->offset($pagination->offset)
		    ->limit($pagination->limit)
		    ->all();

		return $this->render('request', [
			'orders' => $orders,
			'pagination' => $pagination
		]);
	}

	//View order detail 
	public function actionView() {

		\Yii::$app->view->title = Yii::$app->params['SITE_NAME'].' | Orders Detail';
		\Yii::$app->view->registerMetaTag(['name' => 'description', 'content' => Yii::$app->params['META_DESCRIPTION']]);
		\Yii::$app->view->registerMetaTag(['name' => 'keywords', 'content' => Yii::$app->params['META_KEYWORD']]);


		if (Yii::$app->user->isGuest) {
			Yii::$app->session->set('show_login_modal', 1);//to display login modal			
	        return $this->redirect(['/site/index']);
	    }
	    
		$order_id = Yii::$app->request->get('order_id');

		$order = Order::find()
			->where([
				'order_id' => $order_id,
				'customer_id' => Yii::$app->user->getId()
			])
			->one();
		
		if (!$order) {
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        }

		$suborder = Suborder::find()
			->where(['order_id' => $order_id])
			->all();
			
		return $this->render('view', [
			'order' => $order,
			'suborder' => $suborder
		]);
	}
}