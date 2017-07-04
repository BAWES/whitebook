<?php
namespace api\modules\v1\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use common\models\Booking;
use common\models\PaymentGateway;

/**
 * Tap Payment Gateway controller 
 */
class TapController extends Controller
{
    private $tap_merchantid = '';
    private $tap_username = '';
    private $tap_password = '';
    private $tap_api_key = '';

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // remove authentication filter for cors to work
        unset($behaviors['authenticator']);

        // Allow XHR Requests from our different subdomains and dev machines
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
            'cors' => [
                'Origin' => Yii::$app->params['allowedOrigins'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['*'],
                'Access-Control-Allow-Credentials' => null,
                'Access-Control-Max-Age' => 86400,
                'Access-Control-Expose-Headers' => [
                    'X-Pagination-Current-Page',
                    'X-Pagination-Page-Count',
                    'X-Pagination-Per-Page',
                    'X-Pagination-Total-Count'
                ],
            ],
        ];

        // Bearer Auth checks for Authorize: Bearer <Token> header to login the user
        $behaviors['authenticator'] = [
            'class' => \yii\filters\auth\HttpBearerAuth::className(),
        ];
        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
        $behaviors['authenticator']['except'] = [
            'options',
            'index',
            'callback',
            'success',
            'error'
        ];

        return $behaviors;
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        $actions = parent::actions();
        $actions['options'] = [
            'class' => 'yii\rest\OptionsAction',
            // optional:
            'collectionOptions' => ['GET', 'POST', 'HEAD', 'OPTIONS'],
            'resourceOptions' => ['GET', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
        ];
        return $actions;
    }

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
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        }

        $booking_token = Yii::$app->request->get('booking_token');

        if (!$gateway['under_testing']) {
            $data['action'] = 'https://www.gotapnow.com/webpay.aspx';
            //https://www.gotapnow.com/webservice/paygatewayservice.svc
        } else {
            $data['action'] = 'http://live.gotapnow.com/webpay.aspx';
        }

        $booking = Booking::find()
            ->where(['booking_token' => $booking_token])
            ->one();

        if(!$booking) {
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        }

        $arr_item_name = ArrayHelper::map($booking->bookingItems, 'booking_item_id', 'item_name');

        $data['meid'] = $this->tap_merchantid;
        $data['uname'] = $this->tap_username;
        $data['pwd'] = $this->tap_password;

        $data['itemprice1'] = $booking->total_with_delivery;
        $data['itemname1'] = implode(', ', $arr_item_name);
        $data['currencycode'] = 'KWD';
        $data['ordid'] = $booking->booking_id;

        $data['cstemail'] = $booking->customer_email;
        $data['cstname'] = $booking->customer_name;
        $data['cstmobile'] = $booking->customer_mobile;
        $data['cntry'] = 'KW';

        $data['returnurl'] = Url::toRoute(['tap/callback', 'hashcd' => md5($booking->booking_id . $booking->total_with_delivery . 'KWD' . $this->tap_password)], true);

        return $this->renderPartial('index', $data);
    }

    /**
     * Callback page to get parameters in mobile app 
     */
    public function actionCallback() {

        $request = Yii::$app->request->get();

        $booking_id = $request['trackid'];

        $booking = Booking::findOne($booking_id);

        if(!$booking) {
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        }

        $error = '';
        
        $key = $this->tap_merchantid;
        $refid = $request['ref'];
        
        $str = 'x_account_id'.$key.'x_ref'.$refid.'x_resultSUCCESSx_referenceid'.$booking_id.'';
        $hashstring = hash_hmac('sha256', $str, $this->tap_api_key);//'1tap7'
        $responsehashstring = $request['hash'];
            
        if ($hashstring != $responsehashstring) {
            $error = Yii::t('api', 'Unable to locate or update your booking status');
        } else if ($request['result'] != 'SUCCESS') {
            $error = Yii::t('api', 'Payment was declined by Tap');
        }
   
        if ($error) 
        {
           return $this->redirect(['error']);                        
        } 
        else 
        {            
            //gateway info 
            $gateway = PaymentGateway::find()->where(['code' => 'tap', 'status' => 1])->one();

            if($request['crdtype'] == 'KNET') {
                $booking->payment_method = 'Tap - Paid with KNET';
                $booking->gateway_fees = $gateway->fees;
                $booking->gateway_percentage = 0;
                $booking->gateway_total = $gateway->fees;//fixed price fee 
            } else {
                $booking->payment_method = 'Tap - Paid with Creditcard/Debitcard';
                $booking->gateway_fees = 0;
                $booking->gateway_percentage = $gateway->percentage;
                $booking->gateway_total = $gateway->percentage * ($booking->total_with_delivery / 100);
            }

            //update status 
            $booking->transaction_id = $request['ref'];
            $booking->save(false);

            //add payment to vendor wallet 
            Booking::addPayment($booking);
    
            //send order emails
            Booking::sendBookingPaidEmails($booking_id);

            //redirect to order success 
            return $this->redirect(['success']);  
        }
    }

    /**
     * Payment success
     */
    public function actionSuccess() 
    {

    }

    /**
     * Payment error
     */
    public function actionError() 
    {

    }
}