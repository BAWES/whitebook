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

        //place order     
        $order_id = Order::place_order($gateway['name'], $gateway['percentage'], $gateway['fees'], $gateway['order_status_id'], '-');

        Yii::$app->session->set('order_id', $order_id);

        //redirect to order success 
        $this->redirect(['checkout/success']);
    }
}