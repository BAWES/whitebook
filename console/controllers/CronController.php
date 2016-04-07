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

        return self::EXIT_CODE_NORMAL;
    }

    /**
     * Method called by cron once a week
     */
    public function actionWeeklyEmail(){
        //Code here

        return self::EXIT_CODE_NORMAL;
    }

}
