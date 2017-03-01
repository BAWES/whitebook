<?php

namespace console\controllers;

use Yii;
use yii\helpers\Console;
use yii\db\Expression;

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
