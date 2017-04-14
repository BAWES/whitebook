<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use admin\models\BookingSearch;
use common\models\Booking;
use kartik\mpdf\Pdf;
/**
 * OrderRequestStatusController implements the CRUD actions for OrderRequestStatus model.
 */
class BookingController extends Controller
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
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all OrderRequestStatus models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BookingSearch();
       // $searchModel->vendor_id = Yii::$app->user->id;
        $dataProvider = $searchModel->searchAll(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all OrderRequestStatus models.
     * @return mixed
     */
    public function actionPending()
    {
        $searchModel = new BookingSearch();
        $searchModel->vendor_id = Yii::$app->user->id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('pending', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OrderRequestStatus model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        // restrict invalid access to other request
        if ($model->vendor_id != Yii::$app->user->id && $model->getStatusName() == 'Pending') {
            $this->redirect(['index']);
        }

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionViewPending($id)
    {
        $model = $this->findModel($id);
        $_oldBookingStatus = $model->booking_status;
        // restrict invalid access to other request
        if ($model->vendor_id != Yii::$app->user->id) {
            $this->redirect(['index']);
        }

        if ($model->load(Yii::$app->request->post())) {

            if ($model->save(false)) {

                //if accepted
                if (($_oldBookingStatus != $model->booking_status) && $model->booking_status == Booking::STATUS_ACCEPTED) {
                    Yii::$app->session->setFlash('success', 'Request Status changed successfully');
                    Booking::approved($model);
                }

                //if reject
                if (($_oldBookingStatus != $model->booking_status) && $model->booking_status ==  Booking::STATUS_REJECTED) {
                    Yii::$app->session->setFlash('success', 'Request Status changed successfully');
                    Booking::rejected($model);
                }

                return $this->redirect(['pending']);
            }
        } else {
            return $this->render('view-pending', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    /**
     * Finds the OrderRequestStatus model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return OrderRequestStatus the loaded model
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

    /*
     * booking status change from mail link
     */
    public function actionStatus($token, $action){

        $booking = Booking::findOne([
                'booking_token' => $token,
                'booking_status' => '0',
                'vendor_id' => Yii::$app->user->getId()
            ]);
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

    /**
     * Approve request 
     * @param integer $id
     * @return mixed
     */
    public function actionApprove($id)
    {
        $model = $this->findModel($id);

        Booking::approved($model);

        Yii::$app->session->setFlash('success', 'Booking request approved!');

        return $this->redirect(['pending']);
    }

    /**
     * Reject request 
     * @param integer $id
     * @return mixed
     */
    public function actionReject($id)
    {
        $model = $this->findModel($id);

        Booking::rejected($model);

        Yii::$app->session->setFlash('success', 'Booking request rejected!');

        return $this->redirect(['pending']);
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

}

