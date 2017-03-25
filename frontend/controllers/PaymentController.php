<?php

namespace frontend\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Response;
use yii\helpers\ArrayHelper;
use common\models\Booking;
use common\models\PaymentGateway;

class PaymentController extends BaseController
{
    private $errors = array();

    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex($token)
    {
        \Yii::$app->view->title = Yii::$app->params['SITE_NAME'].' | Cart';
        \Yii::$app->view->registerMetaTag(['name' => 'description', 'content' => Yii::$app->params['META_DESCRIPTION']]);
        \Yii::$app->view->registerMetaTag(['name' => 'keywords', 'content' => Yii::$app->params['META_KEYWORD']]);

        $booking = Booking::findOne(['booking_token' => $token]);

        if(!$booking) {
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        }

        //if paid already 

        if($booking->transaction_id) {
            $this->redirect(['booking/view', 'booking_token' => $booking->booking_token]);
        }

        Yii::$app->session->set('booking_id', $booking->booking_id);

        $cod = PaymentGateway::find()->where(['code' => 'cod', 'status' => 1])->one();

        $tap = PaymentGateway::find()->where(['code' => 'tap', 'status' => 1])->one();

        return $this->render('index', [
            'items' => $booking->bookingItems,
            'cod' => $cod,
            'tap' => $tap
        ]);
    }

    public function actionSuccess() {

        $booking_id = Yii::$app->session->get('booking_id');

        $booking = Booking::findOne(['booking_id' => $booking_id]);

        if(!$booking) {
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        }
        
        return $this->render('success', [
            'booking_id' => $booking_id,
            'lnk_track' => Url::to(['booking/view', 'booking_token' => $booking->booking_token])
        ]);
    }
}



