<?php

namespace admin\controllers;

use Yii;
use common\models\VendorPayment;
use common\models\VendorPaymentSearch;
use common\models\Vendor;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * VendorPaymentController implements the CRUD actions for VendorPayment model.
 */
class VendorPaymentController extends Controller
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
     * Lists all VendorPayment models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new VendorPaymentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single VendorPayment model.
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
     * Creates a new VendorPayment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new VendorPayment();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            //if payment type is order add payable 
            
            if($model->type == VendorPayment::TYPE_ORDER) {
                $vendor = Vendor::findOne($model->vendor_id);
                $vendor->vendor_payable += $model->amount;
                $vendor->save();
            }

            //if payment type is transfer reduce payable 
            
            if($model->type == VendorPayment::TYPE_TRANSFER) {
                $vendor = Vendor::findOne($model->vendor_id);
                $vendor->vendor_payable -= $model->amount;
                $vendor->save();
            }

            return $this->redirect(['index']);
        } else {

            $vendors = Vendor::find()->all();

            return $this->render('create', [
                'model' => $model,
                'vendors' => ArrayHelper::map($vendors, 'vendor_id', 'vendor_name')
            ]);
        }
    }

    /**
     * Updates an existing VendorPayment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {

            $vendors = Vendor::find()->all();

            return $this->render('update', [
                'model' => $model,
                'vendors' => ArrayHelper::map($vendors, 'vendor_id', 'vendor_name')
            ]);
        }
    }
     */
    
    /**
     * Deletes an existing VendorPayment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        //if payment type is order 
        
        if($model->type == VendorPayment::TYPE_ORDER) {
            $vendor = Vendor::findOne($model->vendor_id);
            $vendor->vendor_payable -= $model->amount;
            $vendor->save();
        }

        //if payment type is transfer 
        
        if($model->type == VendorPayment::TYPE_TRANSFER) {
            $vendor = Vendor::findOne($model->vendor_id);
            $vendor->vendor_payable += $model->amount;
            $vendor->save();
        }

        $model->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the VendorPayment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return VendorPayment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = VendorPayment::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}