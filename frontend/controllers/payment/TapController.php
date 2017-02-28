<?php

namespace frontend\controllers\payment;

use Yii;
use yii\web\Controller;
use yii\helpers\Url;
use common\models\Order;
use common\models\PaymentGateway;
use common\models\Customer;
use common\models\Suborder;
use common\models\OrderRequestStatus;

class TapController extends Controller
{
    private $tap_merchantid;
    private $tap_username;
    private $tap_password;
    private $tap_api_key;

    public function init()
    {
        parent::init();

        $gateway = PaymentGateway::find()->where(['code' => 'tap', 'status' => 1])->one();

        //live credential
        if($gateway['under_testing']) {
            $this->tap_merchantid = Yii::$app->params['tap_test_merchantid'];
            $this->tap_username = Yii::$app->params['tap_test_username'];
            $this->tap_password = Yii::$app->params['tap_test_password'];
            $this->tap_api_key = Yii::$app->params['tap_test_api_key'];
        } else {
            $this->tap_merchantid = Yii::$app->params['tap_merchantid'];
            $this->tap_username = Yii::$app->params['tap_username'];
            $this->tap_password = Yii::$app->params['tap_password'];
            $this->tap_api_key = Yii::$app->params['tap_api_key'];
        }
    }

    public function actionIndex()
    {
        $gateway = PaymentGateway::find()->where(['code' => 'tap', 'status' => 1])->one();

        if(!$gateway) {
            $this->redirect(['checkout/index']);
        }

        $order_id = Yii::$app->session->get('order_id');

        $request_id = Yii::$app->session->get('request_id');

        if (!$gateway['under_testing']) {
            $data['action'] = 'https://www.gotapnow.com/webpay.aspx';
            //https://www.gotapnow.com/webservice/paygatewayservice.svc
        } else {
            $data['action'] = 'http://live.gotapnow.com/webpay.aspx';
        }

        $request = OrderRequestStatus::findOne($request_id);

        if(!$request) {
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        }

        $suborder = Suborder::findOne($request->suborder_id);

        if(!$suborder) {
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        }

        $order = Order::findOne($request->order_id);

        $customer = Customer::findOne($order->customer_id);
        
        $data['meid'] = $this->tap_merchantid;
        $data['uname'] = $this->tap_username;
        $data['pwd'] = $this->tap_password;

        $data['itemprice1'] = $suborder->suborder_total_with_delivery;
        $data['itemname1'] = $suborder->itemPurchased->item->item_name;
        $data['currencycode'] = 'KWD';
        $data['ordid'] = $suborder->suborder_id;

        $data['cstemail'] = $customer->customer_email;
        $data['cstname'] = $customer->customer_name;
        $data['cstmobile'] = $customer->customer_mobile;
        $data['cntry'] = 'KW';

        $data['returnurl'] = Url::toRoute(['payment/tap/callback', 'hashcd' => md5($suborder->suborder_id . $suborder->suborder_total_with_delivery . 'KWD' . $this->tap_password)], true);

        return $this->renderPartial('index', $data);
    }

    public function actionCallback() {

        $request = Yii::$app->request->get();

        $suborder_id = $request['trackid'];

        $suborder = Suborder::findOne($suborder_id);

        if ($suborder) {

            $error = '';
            
            $key = $this->tap_merchantid;
            $refid = $request['ref'];
            
            $str = 'x_account_id'.$key.'x_ref'.$refid.'x_resultSUCCESSx_referenceid'.$suborder_id.'';
            $hashstring = hash_hmac('sha256', $str, $this->tap_api_key);//'1tap7'
            $responsehashstring = $request['hash'];
                
            if ($hashstring != $responsehashstring) {
                $error = Yii::t('frontend', 'Unable to locate or update your order status');
            } else if ($request['result'] != 'SUCCESS') {
                $error = Yii::t('frontend', 'Payment was declined by Tap');
            }
        } else {
            $error = Yii::t('frontend', 'Unable to locate or update your sub order status');
        }

        if ($error) {
           
            return $this->render('error', [
                'message' => $error
            ]);
            
        } else {
            
            //gateway info 
            $gateway = PaymentGateway::find()->where(['code' => 'tap', 'status' => 1])->one();

            if($request['crdtype'] == 'KNET') {
                $suborder->suborder_payment_method = 'Tap - Paid with KNET';
                $suborder->suborder_gateway_fees = $gateway->fees;
                $suborder->suborder_gateway_percentage = 0;
                $suborder->suborder_gateway_total = $gateway->fees;//fixed price fee 
            } else {
                $suborder->suborder_payment_method = 'Tap - Paid with Creditcard/Debitcard';
                $suborder->suborder_gateway_fees = 0;
                $suborder->suborder_gateway_percentage = $gateway->percentage;
                $suborder->suborder_gateway_total = $gateway->percentage * ($suborder->order_total_with_delivery / 100);
            }

            //update status 
            $suborder->suborder_transaction_id = $request['ref'];
            $suborder->save(false);

            $request_id = Yii::$app->session->get('request_id');
            
            //send order emails
            Order::sendOrderPaidEmails($request_id);

            //redirect to order success 
            $this->redirect(['payment/success']);
        }
    }
}