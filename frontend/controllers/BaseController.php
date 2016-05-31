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
    public function init()
    {
        parent::init();

        $model = new Website();

        $general_settings = $model->get_general_settings();
    }

    public function printdata($table)
    {
     var_dump($table->prepare(Yii::$app->db->queryBuilder)->createCommand()->rawSql);die;
    }
}
