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

        $query = Booking::find()->currentUser()->orderByDate();

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

	public function actionView() {

		\Yii::$app->view->title = Yii::$app->params['SITE_NAME'].' | Booking Detail';
		\Yii::$app->view->registerMetaTag(['name' => 'description', 'content' => Yii::$app->params['META_DESCRIPTION']]);
		\Yii::$app->view->registerMetaTag(['name' => 'keywords', 'content' => Yii::$app->params['META_KEYWORD']]);

		$booking_token = Yii::$app->request->get('booking_token');

		$booking = Booking::find()->token($booking_token)->one();

        return ($booking) ? $this->render('view', ['booking' => $booking]) : $this->render('track');
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