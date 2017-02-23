<?php

namespace backend\controllers;

use Yii;
use common\models\OrderRequestStatus;
use common\models\OrderRequestStatusSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OrderRequestStatusController implements the CRUD actions for OrderRequestStatus model.
 */
class OrderRequestStatusController extends Controller
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
        $searchModel = new OrderRequestStatusSearch();
        $searchModel->vendor_id = Yii::$app->user->id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
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

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            
            //if accepted
            if($model->request_status == 'Approved') {
                OrderRequestStatus::approved($model);
            }

            //if reject
            if($model->request_status == 'Declined') {
                OrderRequestStatus::declined($model);
            }

            Yii::$app->session->setFlash('success','Request Status changed successfully');

            return $this->redirect(['index']);
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
        if (($model = OrderRequestStatus::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

