<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\VendorLocation;
use common\models\Location;
use common\models\Country;
use common\models\City;
use backend\models\VendorLocationSearch;

/**
 * VendorlocationController implements the CRUD actions for vendorlocation model.
 */
class VendorLocationController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
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
    
    public function actionIndex()
    {
        $searchModel = new VendorLocationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionBulk()
    {
        $vendor_id = Yii::$app->user->getId();

        if (Yii::$app->request->isPost) {

            //remove all old values 
            VendorLocation::deleteAll(['vendor_id' => $vendor_id]);

            $selected = Yii::$app->request->post('selected');
            $area = Yii::$app->request->post('area');

            if(!$selected) {
                $selected = array();
            }

            foreach ($selected as $area_id => $value) {
                
                $location = Location::findOne($area_id);

                //add vendor location 
                $vl = new VendorLocation;
                $vl->vendor_id = $vendor_id;
                $vl->city_id = $location->city_id;
                $vl->area_id = $area_id;
                $vl->delivery_price = $area[$area_id];
                $vl->save();
            }

            Yii::$app->session->setFlash('success', 'Success: Delivery area updated successfully!');

            return $this->redirect(['index']);            
        } 
   
        return $this->render('bulk', [
            'vendor_id' => $vendor_id
        ]);
    }

    /**
     * Creates a new VendorLocation model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $vendor_id = Yii::$app->user->getId();

        $model = new VendorLocation();

        if ($model->load(Yii::$app->request->post())) {

            //get city id 
            $location = Location::findOne($model->area_id);

            $model->city_id = $location->city_id;
            $model->vendor_id = Yii::$app->user->getId();

            //check if already exists 
            $exists = VendorLocation::findOne(['vendor_id' => $vendor_id, 'area_id' => $model->area_id]);

            if($exists) {
                Yii::$app->session->setFlash('danger', 'Warning: Please select other area, Area already added!');
            }

            if($model->save() && !$exists){
                return $this->redirect(['index']);
            }
            
        } 
   
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing VendorLocation model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $vendor_id = Yii::$app->user->getId();

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {

            //get city id 
            $location = Location::findOne($model->area_id);

            $model->city_id = $location->city_id;

            //check if already exists 
            $exists = VendorLocation::find()
                ->where(['vendor_id' => $vendor_id, 'area_id' => $model->area_id])
                ->andWhere(['!=', 'id', $id])
                ->count();

            if($exists) {
                Yii::$app->session->setFlash('danger', 'Warning: Please select other area, Area already added!');
            }

            if($model->save() && !$exists){
                return $this->redirect(['index']);    
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing vendorlocation model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the vendorlocation model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return vendorlocation the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $vendor_id = Yii::$app->user->getId();

        if (($model = vendorlocation::findOne(['id' => $id, 'vendor_id' => $vendor_id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
