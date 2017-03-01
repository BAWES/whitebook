<?php

namespace console\controllers;

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
     * Method called by cron every 5 minutes or so
     */
    public function actionMinute() {
        //Code here

        return self::EXIT_CODE_NORMAL;
    }


    /**
     * Method called by cron once a day
     */
    public function actionDailyEmail(){
        //Code here
            /*
    *  BEGIN Cron Job  for if pending items of items table
    */

        $model = Yii::$app->db->createCommand('SELECT item_name FROM `whitebook_vendor_item` WHERE item_approved="Pending" and item_status = "Active" and trash = "Default"');
        $vendor = $model->queryAll();
        $i = 1;
        $message = 'Items waiting for an approval - Pending items <br/><br/>';
        $message .= '<table class="tftable" border="1">
            <tr><th>S.No</th><th>Product Names</th></tr>';
        foreach ($vendor as $key => $value) {
            $message .= '<tr><td>'.$i.'</td><td>'.$value['item_name'].'</td></tr>';
            ++$i;
        }
        $message .= '</table>';

        Yii::$app->mailer->compose([
                "html" => "mailfolder/mail"
                    ], [
                "message" => $message,
                "user" => "Admin"
            ])
            ->setFrom(Yii::$app->params['supportEmail'])
            ->setTo(Yii::$app->params['adminEmail'])
            ->setSubject('PENDING-PRODUCTS')
            ->send();    
        /*
        *  END Cron Job  for if pending items of items table
        */

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
     *  Cron Job  for if Vendor package expire with in two days
     */
    public function actionCron()
    {
        $model = Yii::$app->db->createCommand('SELECT vendor_id, vendor_contact_email, vendor_password, vendor_contact_email, vendor_end_date from whitebook_vendor
        where vendor_status="Active" and expire_notification = 0 and
        vendor_end_date = DATE_ADD(CURDATE(), INTERVAL 2 DAY)');

        $vendor = $model->queryAll();
        foreach ($vendor as $data => $vendor_data) {
        $command = Yii::$app->db->createCommand('UPDATE whitebook_vendor SET expire_notification=1 WHERE vendor_id='.$vendor_data['vendor_id']);
        $command->execute();
        $send = Yii::$app->mailer->compose()
        ->setFrom('a.mariyappan88@gmail.com')
        ->setTo($vendor_data['vendor_contact_email'])
        ->setSubject('Welcome to Whitebook')
        ->setTextBody('Your package will be expired with in two days. Kindly update your package. Thanks')
        ->send();
        }
    }
}
