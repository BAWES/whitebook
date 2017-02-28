<?php

namespace frontend\controllers;

use common\models\OrderRequestStatus;
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
            ->innerJoin('{{%order_request_status}}', '{{%order}}.order_id = {{%order_request_status}}.order_id')
            ->where([
            		'{{%order}}.customer_id' => Yii::$app->user->getId()
            	])
            ->orderBy('{{%order_request_status}}.created_datetime DESC');

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
            ->where(['{{%order}}.customer_id'=>Yii::$app->user->getId()])
            ->orderBy('{{%order}}.created_datetime DESC');

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

	public function actionRequestedProducts($request_id) {

		\Yii::$app->view->title = Yii::$app->params['SITE_NAME'].' | Orders';
		\Yii::$app->view->registerMetaTag(['name' => 'description', 'content' => Yii::$app->params['META_DESCRIPTION']]);
		\Yii::$app->view->registerMetaTag(['name' => 'keywords', 'content' => Yii::$app->params['META_KEYWORD']]);

		if (Yii::$app->user->isGuest) {
			Yii::$app->session->set('show_login_modal', 1);//to display login modal
	        return $this->redirect(['/site/index']);
	    }

        $query = OrderRequestStatus::find()
            ->where(['order_id'=>$request_id])
            ->orderBy('created_datetime DESC');

		// create a pagination object with the total count
		$pagination = new Pagination(['totalCount' => $query->count()]);

		// limit the query using the pagination and retrieve the orders
		$orders = $query->offset($pagination->offset)
		    ->limit($pagination->limit)
		    ->all();

		return $this->render('requested-product', [
			'orders' => $orders,
			'pagination' => $pagination
		]);
	}

	//View order detail 

	public function actionView() {

		\Yii::$app->view->title = Yii::$app->params['SITE_NAME'].' | Orders Detail';
		\Yii::$app->view->registerMetaTag(['name' => 'description', 'content' => Yii::$app->params['META_DESCRIPTION']]);
		\Yii::$app->view->registerMetaTag(['name' => 'keywords', 'content' => Yii::$app->params['META_KEYWORD']]);
	    
		$order_uid = Yii::$app->request->get('order_uid');

		if(!$order_uid) {
			return $this->render('track');
		}

		$order = Order::find()
			->where([
				'order_uid' => $order_uid
			])
			->one();
		
		if (!$order) {
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        }

		$suborder = Suborder::find()
			->where(['order_id' => $order->order_id])
			->all();
			
		return $this->render('view', [
			'order' => $order,
			'suborder' => $suborder
		]);
	}

	public function actionViewRequest($request_id) {

		\Yii::$app->view->title = Yii::$app->params['SITE_NAME'].' | Orders Detail';
		\Yii::$app->view->registerMetaTag(['name' => 'description', 'content' => Yii::$app->params['META_DESCRIPTION']]);
		\Yii::$app->view->registerMetaTag(['name' => 'keywords', 'content' => Yii::$app->params['META_KEYWORD']]);


		if (Yii::$app->user->isGuest) {
			Yii::$app->session->set('show_login_modal', 1);//to display login modal
	        return $this->redirect(['/site/index']);
	    }

		$order = Order::find()
			->where([
				'order_id' => $request_id,
				'customer_id' => Yii::$app->user->getId()
			])
			->one();

		if (!$order) {
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        }

		$suborder = Suborder::find()
			->where(['order_id' => $request_id])
			->all();

		return $this->render('view-request', [
			'order' => $order,
			'suborder' => $suborder
		]);
	}
}