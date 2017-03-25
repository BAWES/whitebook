<?php

namespace admin\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\helpers\ArrayHelper;
use yii\filters\VerbFilter;
use admin\models\AccessControlList;
use admin\models\BookingSearch;
use common\models\Booking;
use common\models\Vendor;


/**
 * BookingRequestController implements the CRUD actions for Booking model.
 */
class BookingRequestController extends Controller
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
                //    'delete' => ['POST'],
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
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $arr_vendor = ArrayHelper::map(
            Vendor::find()->all(), 
            'vendor_id', 
            'vendor_name'
        );

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'arr_vendor' => $arr_vendor
        ]);
    }

    /**
     * Displays a single Booking model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
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

        return $this->redirect(['index']);
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

        return $this->redirect(['index']);
    }

    /**
     * Finds the Booking model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
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
}

