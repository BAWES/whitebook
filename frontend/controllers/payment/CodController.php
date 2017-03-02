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
        
        $request_id = Yii::$app->session->get('request_id');

        $order_id = Yii::$app->session->get('order_id');

        $sub_order = Suborder::findOne($order_id);

        if(!$sub_order) {
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        }

        //if paid already 

        if($sub_order->suborder_transaction_id) {
            $this->redirect(['site/index']);
        }

        $gateway_total = $gateway->percentage * ($sub_order->suborder_total_with_delivery / 100);

        //update payment detail 
        
        $sub_order->suborder_payment_method = $gateway['name'];
        $sub_order->suborder_transaction_id = '-'; 
        $sub_order->suborder_gateway_percentage = $gateway['percentage'];
        $sub_order->suborder_gateway_fees = $gateway['fees'];
        $sub_order->suborder_gateway_total = $gateway_total;
        $sub_order->save();

        //add payment to vendor wallet 

        $payment = new VendorAccountPayable;
        $payment->vendor_id = $suborder->vendor_id;
        $payment->amount = $suborder->suborder_vendor_total;
        $payment->description = 'Suborder #'.$suborder->suborder_id.' got paid.';
        $payment->save();

        //send order emails
        Order::sendOrderPaidEmails($request_id);

        //redirect to order success 
        $this->redirect(['payment/success']);
    }
}