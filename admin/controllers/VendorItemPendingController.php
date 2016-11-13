<?php

namespace admin\controllers;

use Yii;
use yii\web\Controller;
use admin\models\AccessControlList;
use yii\filters\VerbFilter;
use common\models\VendorItemSearch;

/**
* VendorItemPendingController implements the CRUD actions for Vendoritem model.
*/
class VendorItemPendingController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                 //   'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'allow' => AccessControlList::can()
                    ],
                ],
            ],            
        ];
    }

    /**
    * Lists all Vendoritem models.
    *
    * @return mixed
    */
    public function actionIndex()
    {
        $searchModel = new VendorItemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, 'Pending');

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }    
}
