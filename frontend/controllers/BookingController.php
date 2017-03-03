<?php

namespace frontend\controllers;

use Yii;
use common\models\Booking;
use yii\data\Pagination;

class BookingController extends BaseController
{
	public function actionIndex() {

		\Yii::$app->view->title = Yii::$app->params['SITE_NAME'].' | Booking';
		\Yii::$app->view->registerMetaTag(['name' => 'description', 'content' => Yii::$app->params['META_DESCRIPTION']]);
		\Yii::$app->view->registerMetaTag(['name' => 'keywords', 'content' => Yii::$app->params['META_KEYWORD']]);

		if (Yii::$app->user->isGuest) {
			Yii::$app->session->set('show_login_modal', 1);//to display login modal			
	        return $this->redirect(['/site/index']);
	    }

        $query = Booking::find()
            ->where(['customer_id'=>Yii::$app->user->getId(),'booking_status'=>[1,2]])
            ->orderBy('created_datetime DESC');

		// create a pagination object with the total count
		$pagination = new Pagination(['totalCount' => $query->count()]);

		// limit the query using the pagination and retrieve the orders
		$orders = $query->offset($pagination->offset)
		    ->limit($pagination->limit)
		    ->all();

		return $this->render('index', [
			'bookings' => $orders,
			'pagination' => $pagination
		]);
	}

	public function actionPending() {

		\Yii::$app->view->title = Yii::$app->params['SITE_NAME'].' | Booking';
		\Yii::$app->view->registerMetaTag(['name' => 'description', 'content' => Yii::$app->params['META_DESCRIPTION']]);
		\Yii::$app->view->registerMetaTag(['name' => 'keywords', 'content' => Yii::$app->params['META_KEYWORD']]);

		if (Yii::$app->user->isGuest) {
			Yii::$app->session->set('show_login_modal', 1);//to display login modal
	        return $this->redirect(['/site/index']);
	    }

        $query = Booking::find()
            ->where(['customer_id'=>Yii::$app->user->getId(),'booking_status'=>0])
            ->orderBy('created_datetime DESC');

		// create a pagination object with the total count
		$pagination = new Pagination(['totalCount' => $query->count()]);

		// limit the query using the pagination and retrieve the orders
		$booking = $query->offset($pagination->offset)
		    ->limit($pagination->limit)
		    ->all();

		return $this->render('index', [
			'bookings' => $booking,
			'pagination' => $pagination
		]);
	}

	public function actionView($booking_id) {

		\Yii::$app->view->title = Yii::$app->params['SITE_NAME'].' | Booking Detail';
		\Yii::$app->view->registerMetaTag(['name' => 'description', 'content' => Yii::$app->params['META_DESCRIPTION']]);
		\Yii::$app->view->registerMetaTag(['name' => 'keywords', 'content' => Yii::$app->params['META_KEYWORD']]);


		if (Yii::$app->user->isGuest) {
			Yii::$app->session->set('show_login_modal', 1);//to display login modal
	        return $this->redirect(['/site/index']);
	    }

		$booking = Booking::find()
			->where([
				'booking_id' => $booking_id,
				'customer_id' => Yii::$app->user->getId()
			])
			->one();

		if (!$booking) {
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        }

		return $this->render('view', [
			'booking' => $booking,
		]);
	}

	public function actionViewPending($booking_id) {

		\Yii::$app->view->title = Yii::$app->params['SITE_NAME'].' | Booking Detail';
		\Yii::$app->view->registerMetaTag(['name' => 'description', 'content' => Yii::$app->params['META_DESCRIPTION']]);
		\Yii::$app->view->registerMetaTag(['name' => 'keywords', 'content' => Yii::$app->params['META_KEYWORD']]);


		if (Yii::$app->user->isGuest) {
			Yii::$app->session->set('show_login_modal', 1);//to display login modal
	        return $this->redirect(['/site/index']);
	    }

		$booking = Booking::find()
			->where([
				'booking_id' => $booking_id,
				'customer_id' => Yii::$app->user->getId()
			])
			->one();

		if (!$booking) {
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        }

		return $this->render('view', [
			'booking' => $booking,
		]);
	}

	public function actionMail() {
        $booking = Booking::findOne(1);
        return $this->render('view-request', [
            'user'  => $booking->customer_name,
            'booking' => $booking,
            'vendor' => $booking->vendor
        ]);
    }
}