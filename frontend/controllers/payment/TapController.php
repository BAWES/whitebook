<?php

namespace frontend\controllers\payment;

use Yii;
use yii\web\Controller;
use yii\helpers\Url;
use common\models\Order;
use common\models\PaymentGateway;
use common\models\Customer;

class TapController extends Controller
{
    private $tap_merchantid = '1230';
    private $tap_username = 'test';
    private $tap_password = '123tap';
    private $tap_api_key = '1tap7';

    public function actionIndex()
    {
        $gateway = PaymentGateway::find()->where(['code' => 'tap', 'status' => 1])->one();

        if(!$gateway) {
            $this->redirect(['checkout/index']);
        }

        //place order with 0 - missing order 
        $order_id = Order::place_order($gateway['name'], $gateway['percentage'], $gateway['order_status_id']);

        Yii::$app->session->set('order_id', $order_id);

        if (!$gateway['under_testing']) {
            $data['action'] = 'https://www.gotapnow.com/webpay.aspx';
        } else {
            $data['action'] = 'http://live.gotapnow.com/webpay.aspx';
        }

        $order = Order::findOne($order_id);
        $customer = Customer::findOne($order->customer_id);
        
        $data['meid'] = $this->tap_merchantid;
        $data['uname'] = $this->tap_username;
        $data['pwd'] = $this->tap_password;
        
        $data['itemprice1'] = $order->order_total_with_delivery;
        $data['itemname1'] ='Order ID - '.$order_id;
        $data['currencycode'] = 'KWD';
        $data['ordid'] = $order_id;

        $data['cstemail'] = $customer->customer_email;
        $data['cstname'] = $customer->customer_name;
        $data['cstmobile'] = $customer->customer_mobile;
        $data['cntry'] = 'KW';

        $data['returnurl'] = Url::toRoute(['payment/tap/callback', 'hashcd' => md5($order_id . $order->order_total_with_delivery . 'KWD' . $this->tap_password)], true);

        return $this->renderPartial('index', $data);
    }

    public function actionCallback() {
        
        $request = Yii::$app->request->get();

        $order_id = $request['trackid'];

        $order_info = Order::findOne($order_id);

        if ($order_info) {
            $error = '';
            
            $key = $this->tap_merchantid;
            $refid = $request['ref'];
            
            $str = 'x_account_id'.$key.'x_ref'.$refid.'x_resultSUCCESSx_referenceid'.$order_id.'';
            $hashstring = hash_hmac('sha256', $str, $this->tap_api_key);//'1tap7'
            $responsehashstring = $request['hash'];
                
            if ($hashstring != $responsehashstring) {
                $error = Yii::t('frontend', 'Unable to locate or update your order status');
            } else if ($request['result'] != 'SUCCESS') {
                $error = Yii::t('frontend', 'Payment was declined by Tap');
            }
        } else {
            $error = Yii::t('frontend', 'Unable to locate or update your order status');
        }

        if ($error) {
           
            return $this->render('error', [
                'message' => $error
            ]);
            
        } else {
            
            //gateway info 
            $gateway = PaymentGateway::find()->where(['code' => 'cod', 'status' => 1])->one();

            //load order 
            $order = Order::findOne($order_id);

            //update status 
            $order->order_transaction_id = $request['ref'];
            $order->save(false);

            Yii::$app->session->set('order_id', $order_id);

            //redirect to order success 
            $this->redirect(['checkout/success']);
        }
    }
}