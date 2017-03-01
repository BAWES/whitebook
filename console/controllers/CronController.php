<?php

namespace console\controllers;

use common\models\Customer;
use Yii;
use yii\helpers\Url;
use yii\helpers\Console;
use yii\helpers\ArrayHelper;
use yii\db\Expression;
use common\models\Vendor;
use common\models\OrderRequestStatus;
use common\models\SuborderItemPurchase;
use common\models\VendorOrderAlertEmails;


/**
 * All Cron actions related to this project
 */
class CronController extends \yii\console\Controller {

    /**
     * Used for testing only
     */
    public function actionIndex(){
        $this->stdout("Sample Output \n", Console::FG_RED, Console::BOLD);
    }

    /**
     * Method called by cron once a day
     */
    public function actionDaily() {

        return self::EXIT_CODE_NORMAL;
    }

    /**
     * Method called by cron every minute
     */
    public function actionEveryMinute() {

        return self::EXIT_CODE_NORMAL;
    }

    /**
     * Method called by cron once a week
     */
    public function actionWeeklyEmail(){
        //Code here

        return self::EXIT_CODE_NORMAL;
    }

    /** 
     * Send mail to vendor + admin if customer have not paid 
     * within 24 hour after booking 
     */
    public function actionBookingExpire()
    {
        //list all booking with status as pending and placed before 24 hours 

        $requests = OrderRequestStatus::find()
            ->where('expired_on < NOW()')
            ->andWhere([
                    'request_status' => 'Approved'
                ])
            ->all();

        foreach ($requests as $key => $request) 
        {
            $vendor = Vendor::findOne($request->vendor_id);

            //get items 

            $items = SuborderItemPurchase::find()
                ->select('{{%vendor_item}}.item_id, {{%vendor_item}}.item_name')
                ->innerJoin('{{%vendor_item}}', '{{%vendor_item}}.item_id = {{%suborder_item_purchase}}.item_id')
                ->innerJoin('{{%suborder}}', '{{%suborder}}.suborder_id = {{%suborder_item_purchase}}.suborder_id')
                ->where([
                    '{{%suborder}}.suborder_id' => $request->suborder_id
                ])
                ->asArray()
                ->all();

            $items = implode(', ', ArrayHelper::map($items, 'item_id', 'item_name'));

            // to vendor 

            $message = 'Hello '.$vendor->vendor_name.',';
            $message .= '<br/><br/>  The booking is cancelled now for '.$items.' on '.date('d/m/Y h:i A').' because customer have not paid within 24 hour.<br />';
            $message .= '<br/> Request Token : '.$request->request_token;
            $message .= '<br/> Order ID : '.$request->order_id;
            $message .= '<br/> Sub Order ID : '.$request->suborder_id;

            //get all vendor alert email 

            $emails = VendorOrderAlertEmails::find()
                ->where(['vendor_id' => $request->vendor_id])
                ->all();

            $emails = ArrayHelper::getColumn($emails, 'email_address');

            Yii::$app->mailer->compose()
                ->setFrom(Yii::$app->params['supportEmail'])
                ->setTo($emails)
                ->setSubject('Order request rejected!')
                ->setHtmlBody($message)
                ->send();

            // to admin 
            
            $message = 'Hello Admin,';
            $message .= '<br/><br/>  The booking is cancelled now for '.$items.' on '.date('d/m/Y h:i A').' because customer have not paid within 24 hour.<br />';
            $message .= '<br/> Request Token : '.$request->request_token;
            $message .= '<br/> Order ID : '.$request->order_id;
            $message .= '<br/> Sub Order ID : '.$request->suborder_id;

            Yii::$app->mailer->compose()
                ->setFrom(Yii::$app->params['supportEmail'])
                ->setTo(Yii::$app->params['adminEmail'])
                ->setSubject('Order request rejected!')
                ->setHtmlBody($message)
                ->send();

            //set request status to `Declined` 

            $request->request_status = 'Declined';
            $request->request_note = 'Payment not complete withing within 24 hour';
            $request->save();
        }
    }


    /*
     * cron job for booking expire alert before 1 hour;
     * Run php yii cron/alert-before-booking-expire
     */
    public function actionAlertBeforeBookingExpire() {
        $model = new OrderRequestStatus();
        $model->bookingBeforeExpireAlert();
    }

    /*
     * cron job for booking expire alert before 1 hour;
     * Run php yii cron/alert-after-booking-expire
     */
    public function actionAlertAfterBookingExpire() {
        $model = new OrderRequestStatus();
        $model->bookingAfterExpiredAlert();
    }
}
