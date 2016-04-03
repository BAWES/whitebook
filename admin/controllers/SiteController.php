<?php
namespace admin\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\helpers\Html;
/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        $vendorLink = Url::to("vendor/default/login");
        $adminLink = Url::to("admin/site/login");

        echo Html::a("Link to Vendor Backend", $vendorLink); //vendor/default/login
        echo "<br/>";
        echo Html::a("Link to Admin Backend", $adminLink); //vendor/default/login
    }
}
