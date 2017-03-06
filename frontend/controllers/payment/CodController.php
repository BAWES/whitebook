<?php

namespace frontend\controllers\payment;

use Yii;
use yii\web\Controller;
use common\models\Booking;
use common\models\PaymentGateway;

class CodController extends Controller
{
    private $errors = array();

    public function actionIndex()
    {
        $gateway = PaymentGateway::find()->where(['code' => 'cod', 'status' => 1])->one();

        if(!$gateway) {
            $this->redirect(['checkout/index']);
        }
        
        $booking_id = Yii::$app->session->get('booking_id');

        $booking = Booking::findOne($booking_id);

        if(!$booking) {
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        }

        $gateway_total = $gateway->percentage * ($booking->total_with_delivery / 100);

        //update payment detail 
        
        $booking->payment_method = $gateway['name'];
        $booking->transaction_id = '-'; 
        $booking->gateway_percentage = $gateway['percentage'];
        $booking->gateway_fees = $gateway['fees'];
        $booking->gateway_total = $gateway_total;
        $booking->save();

        //add payment to vendor wallet 

        /*$payment = new VendorAccountPayable;
        $payment->vendor_id = $suborder->vendor_id;
        $payment->amount = $suborder->suborder_vendor_total;
        $payment->description = 'Suborder #'.$suborder->suborder_id.' got paid.';
        $payment->save();*/

        //send order emails
        Booking::sendBookingPaidEmails($booking_id);

        //redirect to order success 
        $this->redirect(['payment/success']);
    }
}