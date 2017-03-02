<?php

namespace admin\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use common\models\VendorAccountPayable;
use common\models\VendorAccountPayableSearch;
use common\models\Vendor;

/**
 * PaymentsController implements the CRUD actions for VendorAccountPayable model.
 */
class PayableController extends Controller
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
     * Lists all VendorAccountPayable models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new VendorAccountPayableSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $total = $searchModel->total(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'total' => $total
        ]);
    }

    /**
     * Displays a single VendorAccountPayable model.
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
     * Creates a new VendorAccountPayable model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new VendorAccountPayable();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {

            $vendors = Vendor::findAll(['trash' => 'Default']);

            return $this->render('create', [
                'model' => $model,
                'vendors' => ArrayHelper::map($vendors, 'vendor_id', 'vendor_name')
            ]);
        }
    }

    /**
     * Updates an existing VendorAccountPayable model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {

            $vendors = Vendor::findAll(['trash' => 'Default']);

            return $this->render('update', [
                'model' => $model,
                'vendors' => ArrayHelper::map($vendors, 'vendor_id', 'vendor_name')
            ]);
        }
    }

    /**
     * Deletes an existing VendorAccountPayable model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the VendorAccountPayable model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return VendorAccountPayable the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = VendorAccountPayable::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
