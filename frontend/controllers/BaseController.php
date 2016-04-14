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

        Yii::$app->params['IMAGE_UPLOAD_PATH'] = Yii::$app->request->hostInfo.'/backend/web/uploads/';

        Yii::$app->params['CUSTOMER_ID'] = $this->customer_id;
        Yii::$app->params['CUSTOMER_EMAIL'] = $customer_email;
        Yii::$app->params['CUSTOMER_NAME'] = $customer_name;

        Yii::$app->params['IMAGE_UPLOAD_PATH'] = $_SERVER['DOCUMENT_ROOT'].'/backend/web/uploads/';
        Yii::$app->params['SITE_NAME'] = $general_settings[0]['app_name'];
        Yii::$app->params['SITE_DESCRIPTION'] = $general_settings[0]['app_name'];
        Yii::$app->params['META_KEYWORD'] = $general_settings[0]['meta_keyword'];

        Yii::$app->params['META_DESCRIPTION'] = $general_settings[0]['meta_desc'];
        Yii::$app->params['ADMIN_EMAIL'] = $general_settings[0]['email_id'];
        Yii::$app->params['ADMIN_CONTACT'] = $general_settings[0]['phone_number'];

        Yii::$app->params['SITE_LOCATION'] = $general_settings[0]['site_location'];
        Yii::$app->params['CURRENCY_SYMBOL'] = $general_settings[0]['currency_symbol'];
        Yii::$app->params['CURRENCY_CODE'] = $general_settings[0]['currency_symbol'];

        Yii::$app->params['SITE_DESCRIPTION'] = $general_settings[0]['site_copyright'];
        Yii::$app->params['SITE_LOGO'] = Yii::$app->params['IMAGE_UPLOAD_PATH'].'app_img/'.$general_settings[0]['site_logo'];
        Yii::$app->params['SITE_FAVICON'] = Yii::$app->params['IMAGE_UPLOAD_PATH'].'app_img/'.$general_settings[0]['site_favicon'];

        Yii::$app->params['SITE_NOIMAGE'] = Yii::$app->params['IMAGE_UPLOAD_PATH'].'app_img/'.$general_settings[0]['site_noimage'];
        Yii::$app->params['uploadPath'] = realpath(Yii::$app->basePath) . '/uploads/';


    }
}
