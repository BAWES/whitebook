<?php

namespace admin\controllers;

use Yii;
use common\models\Vendor;
use common\models\VendorPayment;
use common\models\VendorPaymentSearch;
use common\models\VendorOrderAlertEmails;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use kartik\mpdf\Pdf;
use \mPDF;

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
        $model = $this->findModel($id);

        $bookings = VendorPayment::find()
            ->where(['transfer_id' => $model->payment_id])
            //->innerJoin('{{%booking}}', '{{%booking}}.booking_id = {{%vendor_payment}}.booking_id');
            ->all();

        return $this->render('view', [
            'model' => $model,
            'bookings' => $bookings
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

        $model->type = VendorPayment::TYPE_TRANSFER;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            //mark selected booking as paid 

            $bookings = Yii::$app->request->post('bookings');

            foreach ($bookings as $key => $value) {
                VendorPayment::updateAll(['transfer_id' => $model->payment_id], ['booking_id' => $value]);
            }

            //send mail to vendor to notify payment transfer report available 

            $emails = VendorOrderAlertEmails::find()
                ->where(['vendor_id' => $model->vendor_id])
                ->all();

            $emails = ArrayHelper::getColumn($emails, 'email_address');
        
            Yii::$app->mailer->compose("vendor/payment-report",
                [
                    "model" => $model,
                    "vendor" => $model->vendor
                ])
                ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->params['SITE_NAME']])
                ->setTo($emails)
                ->setSubject('Payment report available')
                ->send();

            return $this->redirect(['index']);

        } else {

            $vendors = Vendor::find()->all();

            $model->vendor_id = Yii::$app->request->get('vendor_id');

            return $this->render('create', [
                'model' => $model,
                'vendors' => ArrayHelper::map($vendors, 'vendor_id', 'vendor_name')
            ]);
        }
    }

    /**
     * send unpaid booking list for a vendor 
     * @param integer vendor_id 
     * @return array 
     */
    public function actionUnpaid()
    {
        $unpaid_bookings = VendorPayment::find()
            ->where([
                    'type' => VendorPayment::TYPE_ORDER,
                    'vendor_id' => Yii::$app->request->post('vendor_id')
                ])
            ->andWhere('transfer_id IS NULL')
            ->all();

        Yii::$app->response->format = 'json';

        return [
            'unpaid_bookings' => $unpaid_bookings
        ];
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
            ->where(['transfer_id' => $model->payment_id])
            ->groupBy('payment_method')
            ->asArray()
            ->all();

        $content = $this->render('detail_report', [
            'model' => $model,
            'bookings' => $bookings,
            'orders_by_payment_methods' => $orders_by_payment_methods
        ]);

        $stylesheet = file_get_contents(Url::to('@web/themes/default/css/pdf.css', true));

        $prefix = Yii::getAlias('@runtime/mpdf') . DIRECTORY_SEPARATOR;
        
        definePath('_MPDF_TEMP_PATH', "{$prefix}tmp");
        definePath('_MPDF_TTFONTDATAPATH', "{$prefix}ttfontdata");

        $mpdf = new mPDF();
        $mpdf->WriteHTML($stylesheet, 1);
        $mpdf->WriteHTML($content);
        $mpdf->Output();        
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
