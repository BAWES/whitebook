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
        $this->customer_id = Yii::$app->session->get('customer_id');
        $customer_email = Yii::$app->session->get('customer_email');
        $customer_name = Yii::$app->session->get('customer_name');

        Yii::$app->params['CUSTOMER_ID'] = $this->customer_id;
        Yii::$app->params['CUSTOMER_EMAIL'] = $customer_email;
        Yii::$app->params['CUSTOMER_NAME'] = $customer_name;


    }
}
