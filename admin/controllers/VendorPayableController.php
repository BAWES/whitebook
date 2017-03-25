<?php

namespace admin\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use admin\models\AccessControlList;
use admin\models\VendorSearch;

/**
 * VendorPayableController implements the CRUD actions for Vendor model.
 */
class VendorPayableController extends Controller
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
                //    'delete' => ['POST'],
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
     * Lists all Vendor models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new VendorSearch();
        $dataProvider = $searchModel->searchPayable(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
