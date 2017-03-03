<?php

namespace console\controllers;

use common\models\Booking;
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
        $model = new Booking();
        $model->bookingExpire();
    }


    /*
     * cron job for booking expire alert before 1 hour;
     * Run php yii cron/alert-before-booking-expire
     */
    public function actionAlertBeforeBookingExpire() {
        $model = new Booking();
        $model->bookingBeforeExpireAlert();
    }
}
