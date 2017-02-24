<?php

namespace frontend\controllers\payment;

use Yii;
use yii\web\Controller;
use common\models\Order;
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

        $order = Order::findOne($order_id);

        if(!$order) {
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        }

        //if paid already 

        if($order->order_transaction_id) {
            $this->redirect(['site/index']);
        }

        $gateway_total = $gateway->percentage * ($order->order_total_with_delivery / 100);

        //update payment detail 
        
        $order->order_payment_method = $gateway['name'];
        $order->order_transaction_id = '-'; 
        $order->order_gateway_percentage = $gateway['percentage'];
        $order->order_gateway_fees = $gateway['fees'];
        $order->order_gateway_total = $gateway_total;
        $order->save();

        //send order emails
        Order::sendOrderPaidEmails($request_id);

        //redirect to order success 
        $this->redirect(['payment/success']);
    }
}