<?php

namespace frontend\controllers;

use yii;
use yii\web\Controller;
use frontend\models\Website;

/**
 * Category controller.
 */
class BaseController extends Controller
{

    public $customer_id;
    public function init()
    {
        parent::init();

        $model = new Website();

        $general_settings = $model->get_general_settings();
        if(!Yii::$app->user->isGuest)
        Yii::$app->params['CUSTOMER_NAME'] = Yii::$app->user->identity->customer_name;
    }

    public function printdata($table)
    {

     var_dump($table->prepare(Yii::$app->db->queryBuilder)->createCommand()->rawSql);die;
    }
}
