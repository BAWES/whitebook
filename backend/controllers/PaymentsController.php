<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use common\models\Vendor;
use common\models\VendorPayment;
use backend\models\VendorPaymentSearch;
use \mPDF;

/**
 * PaymentsController implements the CRUD actions for VendorPayment model.
 */
class PaymentsController extends Controller
{
    /**
     * Lists all VendorPayment models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new VendorPaymentSearch();
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
     * Updates an existing VendorPayment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDetail($id) 
    {
        $this->layout = 'pdf';

        $model = $this->findModel($id);

        $bookings = VendorPayment::find()
            ->select('{{%booking}}.booking_id, {{%booking}}.customer_name, {{%booking}}.customer_lastname, {{%booking}}.customer_mobile, {{%booking}}.total_with_delivery, {{%booking}}.commission_total')
            ->innerJoin('{{%booking}}', '{{%booking}}.booking_id = {{%vendor_payment}}.booking_id')
            ->where(['transfer_id' => $model->payment_id])
            ->asArray()
            ->all();

        $orders_by_payment_methods = VendorPayment::find()
            ->select('count({{%booking}}.booking_id) as total, sum({{%booking}}.total_with_delivery) as total_sale, {{%booking}}.payment_method')
            ->innerJoin('{{%booking}}', '{{%booking}}.booking_id = {{%vendor_payment}}.booking_id')
            ->where([
                    'transfer_id' => $model->payment_id
                ])
            ->groupBy('payment_method')
            ->asArray()
            ->all();

        $content = $this->render('detail_report', [
            'model' => $model,
            'bookings' => $bookings,
            'orders_by_payment_methods' => $orders_by_payment_methods
        ]);

        $stylesheet = file_get_contents(Url::to('@web/themes/default/css/pdf.css', true));

        $mpdf = new mPDF();
        $mpdf->WriteHTML($stylesheet, 1);
        $mpdf->WriteHTML($content);
        $mpdf->Output();        
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
     * Finds the VendorPayment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return VendorPayment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $model = VendorPayment::findOne([
                'payment_id' => $id, 
                'vendor_id' => Yii::$app->user->getId()
            ]);

        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
