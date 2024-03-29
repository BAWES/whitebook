<?php

namespace admin\controllers;

use Yii;
use common\models\Booking;
use admin\models\BookingSearch;
use admin\models\AccessControlList;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use kartik\mpdf\Pdf;

/**
 * BookingController implements the CRUD actions for Booking model.
 */
class BookingController extends Controller
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
     * Lists all Booking models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BookingSearch();
        $dataProvider = $searchModel->searchAll(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Booking model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionInvoice($id)
    {
        $this->layout = 'pdf';

        $content = $this->render('invoice', [
            'model' => $this->findModel($id),
        ]);

        $pdf = new Pdf([
            // A4 paper format
            'format' => Pdf::FORMAT_A4, 
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT, 
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER, 
            // your html content input
            'content' => $content,  
            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:38px}', 
             // set mPDF properties on the fly
            'options' => [],//['title' => 'Booking #'.$id],
             // call mPDF methods on the fly
            'methods' => [ 
                'SetHeader'=>['Booking #'.$id], 
                'SetFooter'=>['{PAGENO}'],
            ]
        ]);    

        return $pdf->render();     
    }
    
    /**
     * Deletes an existing Booking model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        Booking::deleteAll('booking_id = ' . $id);

        //delete item menu
        BookingItemMenu::deleteAll('booking_item_id IN (select booking_item_id from whitebook_booking_item WHERE booking_id="'.$id.'")');

        //delete items 
        BookingItem::deleteAll('booking_id = ' . $id);

        return $this->redirect(['index']);
    }

    /**
     * Finds the Booking model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Booking the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Booking::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionBookingPayment()
    {
        if (!Yii::$app->request->isAjax) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        if (Yii::$app->request->post('mode') != '0') {
            
            $id = Yii::$app->request->post('booking_id');

            $model = $this->findModel($id);
            $model->payment_method = Yii::$app->request->post('mode');
            $model->transaction_id = '-';
            $model->booking_status = Booking::STATUS_ACCEPTED;
            $model->save(false);

            // add payment entry for vendor 

            Booking::addPayment($model);

            //Booking::sendBookingPaidEmails($id);
            
            echo 'payment_method_' . $id; // class id to show result

        }
    }

    /*
     * booking status change from mail link
     */
    public function actionStatus($token, $action){

        $booking = Booking::find()->inactiveBooking()->token($token)->one();
        $_oldBookingStatus = $booking->booking_status;

        if ($booking) {
            $booking->booking_status = ($action) ? $action : Booking::STATUS_REJECTED;
            if($booking->save(false)) {
                if (($_oldBookingStatus != $booking->booking_status) && $booking->booking_status == Booking::STATUS_ACCEPTED) {
                    Yii::$app->session->setFlash('success', 'Request Status changed successfully');
                    Booking::approved($booking);
                }

                //if reject
                if (($_oldBookingStatus != $booking->booking_status) && $booking->booking_status ==  Booking::STATUS_REJECTED) {
                    Yii::$app->session->setFlash('success', 'Request Status changed successfully');
                    Booking::rejected($booking);
                }
            }
            return $this->redirect(['index']);

        } else { // in case invalid booking
            Yii::$app->session->setFlash('danger', 'Invalid token ID');
            return $this->redirect(['index']);
        }
    }

}
