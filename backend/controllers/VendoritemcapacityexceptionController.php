<?php

namespace backend\controllers;

use Yii;
use backend\models\VendorItemCapacityException;
use backend\models\VendorItemCapacityExceptionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Vendor;
use yii\filters\AccessControl;

/**
 * VendorItemCapacityExceptionController implements the CRUD actions for VendorItemCapacityException model.
 */
class VendorItemCapacityExceptionController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                  //  'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [//allow authenticated users only
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all VendorItemCapacityException models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new VendorItemCapacityExceptionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $startdate = VendorItemCapacityException::find()->select('exception_date')
    		->where(['trash'=>'Default'])
    		->orderby(['exception_date' => SORT_ASC])
    		->asArray()
    		->all();
	    $startdate = date('Y-m-d', strtotime($startdate[0]['exception_date']));

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'startdate' => $startdate,
        ]);
    }

    /**
     * Displays a single VendorItemCapacityException model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new VendorItemCapacityException model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new VendorItemCapacityException();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->exception_date = date('Y-m-d', strtotime($model->exception_date));
			$model->save();
            Yii::$app->session->setFlash('success', "Exception date created successfully!");
            return $this->redirect(['index']);
		} else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing VendorItemCapacityException model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            $model->exception_date =Yii::$app->formatter->asDate($model->exception_date, 'php:Y-m-d');
			$model->save();
            Yii::$app->session->setFlash('success', "Exception date updated successfully!");
            
            return $this->redirect(['index']);
        } else {
            $model->exception_date = date( 'd-m-Y', strtotime( $model->exception_date ));
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing VendorItemCapacityException model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', "Exception date deleted successfully!");
        return $this->redirect(['index']);
    }


    /**
     * Finds the VendorItemCapacityException model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return VendorItemCapacityException the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = VendorItemCapacityException::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
