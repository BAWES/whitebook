<?php
namespace imageserver\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Session;
use common\models\Package;
use common\models\Category;
use common\models\Siteinfo;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\helpers\Security;
use yii\web\UploadedFile;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error', 'recoverypassword'],
                        'allow' => true,
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
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
        return $this->render('index', [
        ]);
    }


}
