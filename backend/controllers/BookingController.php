<?php

namespace backend\controllers;

use admin\models\BookingSearch;
use common\models\Booking;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

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
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
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
        $searchModel->vendor_id = Yii::$app->user->id;
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
        if ($model->vendor_id != Yii::$app->user->id) {
            $this->redirect(['index']);
        }

        if ($model->load(Yii::$app->request->post())) {

            $model->setExpiredOn($model); // set expire date

            if ($model->save()) {

                //if accepted
                if (($model->oldAttributes['booking_status'] != $model->booking_status) && $model->booking_status == '1') {
                    Yii::$app->session->setFlash('success', 'Request Status changed successfully');
                    Booking::approved($model);
                }

                //if reject
                if (($model->oldAttributes['booking_status'] != $model->booking_status) && $model->booking_status == '2') {
                    Yii::$app->session->setFlash('success', 'Request Status changed successfully');
                    Booking::rejected($model);
                }

                return $this->redirect(['pending']);
            }
        } else {
            return $this->render('view', [
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
}

