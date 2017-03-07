<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use common\models\Vendor;
use common\models\VendorAccountPayable;
use backend\models\VendorAccountPayableSearch;

/**
 * PayableController implements the CRUD actions for VendorAccountPayable model.
 */
class PayableController extends Controller
{
    /**
     * Lists all VendorAccountPayable models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new VendorAccountPayableSearch();
        $searchModel->vendor_id = Yii::$app->user->getId();

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