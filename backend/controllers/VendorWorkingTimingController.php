<?php

namespace backend\controllers;

use Yii;
use common\models\VendorWorkingTiming;
use backend\models\VendorWorkingTimingSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * VendorWorkingTimingController implements the CRUD actions for VendorWorkingTiming model.
 */
class VendorWorkingTimingController extends Controller
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
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all VendorWorkingTiming models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new VendorWorkingTimingSearch();
        $searchModel->trash = 'Default';
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single VendorWorkingTiming model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new VendorWorkingTiming model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new VendorWorkingTiming();
        $model->vendor_id = Yii::$app->user->id;
        $model->trash = 'Default';

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->working_start_time = date('H:i:s',strtotime($model->working_start_time));
            $model->working_end_time = date('H:i:s',strtotime($model->working_end_time));
            if ($model->save()) {
                return $this->redirect(['index']);
            }

        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing VendorWorkingTiming model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->working_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing VendorWorkingTiming model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->trash ='Delete';
        $model->save();

        return $this->redirect(['index']);
    }

    /**
     * Finds the VendorWorkingTiming model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return VendorWorkingTiming the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = VendorWorkingTiming::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}