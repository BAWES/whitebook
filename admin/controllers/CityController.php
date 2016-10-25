<?php

namespace admin\controllers;

use Yii;
use admin\models\CitySearch;
use admin\models\Admin;
use admin\models\AuthItem;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use common\models\Country;
use common\models\City;
use common\models\CustomerAddress;
use common\models\CustomerCart;
use common\models\Location;
use common\models\VendorLocation;

/**
* CityController implements the CRUD actions for City model.
*/
class CityController extends Controller
{
    /**
    * Lists all City models.
    *
    * @return mixed
    */
    public function init()
    {
        parent::init();
        if (Yii::$app->user->isGuest) { 
            $url = Yii::$app->urlManager->createUrl(['admin/site/login']);
            Yii::$app->getResponse()->redirect($url);
        }
    }

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    //'delete' => ['post'],
                ],
            ],

            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['create', 'update', 'index', 'view', 'delete', 'block'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],

        ];
    }

    public function actionIndex()
    {
        $access = AuthItem::AuthitemCheck('4', '12');

        if (yii::$app->user->can($access)) {
        
            $searchModel = new CitySearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);

        } else {
            
            Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');
            return $this->redirect(['site/index']);
        }
    }

    /**
    * Displays a single City model.
    *
    * @param int $city_id
    * @param int $country_id
    *
    * @return mixed
    */
    public function actionView($city_id, $country_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($city_id, $country_id),
        ]);
    }

    /**
    * Creates a new City model.
    * If creation is successful, the browser will be redirected to the 'view' page.
    *
    * @return mixed
    */
    public function actionCreate()
    {
        $access = AuthItem::AuthitemCheck('1', '12');
        
        if (yii::$app->user->can($access)) {
            
            $model = new City();
            $model->status = 'Active';

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                
                Yii::$app->session->setFlash('success', 'Governorate info created successfully!');
                return $this->redirect(['index']);
            
            } else {

                $countries = Country::loadcountry();
                $country = ArrayHelper::map($countries, 'country_id', 'country_name');

                return $this->render('create', [
                    'model' => $model, 'country' => $country,
                ]);
            }
        
        } else {
            
            Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');
            return $this->redirect(['site/index']);
        }
    }

    /**
    * Updates an existing City model.
    * If update is successful, the browser will be redirected to the 'view' page.
    *
    * @param int $city_id
    * @param int $country_id
    *
    * @return mixed
    */
    public function actionUpdate($city_id, $country_id)
    {
        $access = AuthItem::AuthitemCheck('2', '12');
        
        if (yii::$app->user->can($access)) {
            
            $model = $this->findModel($city_id, $country_id);
            
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                
                Yii::$app->session->setFlash('success', 'Governorate info updated successfully!');

                return $this->redirect(['index']);
            
            } else {

                $countries = Country::loadcountry();
                $country = ArrayHelper::map($countries, 'country_id', 'country_name');

                return $this->render('update', [
                    'model' => $model, 
                    'country' => $country,
                ]);
            }

        } else {
            
            Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');
            return $this->redirect(['site/index']);
        }
    }

    /**
    * Deletes an existing City model.
    * If deletion is successful, the browser will be redirected to the 'index' page.
    *
    * @param int $city_id
    * @param int $country_id
    *
    * @return mixed
    */
    public function actionDelete($city_id, $country_id)
    {
        $access = AuthItem::AuthitemCheck('3', '12');

        if (yii::$app->user->can($access)) {
        
            $this->findModel($city_id, $country_id)->delete();

            //delete all customer address 
            CustomerAddress::deleteAll(['city_id' => $city_id]);

            //delete customer cart - area_id 
            CustomerCart::deleteAll('area_id in (select area_id from {{%location}} 
                where city_id = "'.$city_id.'")');

            //delete all location - city_id 
            Location::deleteAll(['city_id' => $city_id]);

            //delete all vendor location - city_id 
            VendorLocation::deleteAll(['city_id' => $city_id]);

            Yii::$app->session->setFlash('success', 'Governorate deleted successfully!');
            return $this->redirect(['index']);

        } else {
            
            Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');
            return $this->redirect(['site/index']);
        }
    }

    /**
    * Finds the City model based on its primary key value.
    * If the model is not found, a 404 HTTP exception will be thrown.
    *
    * @param int $city_id
    * @param int $country_id
    *
    * @return City the loaded model
    *
    * @throws NotFoundHttpException if the model cannot be found
    */
    protected function findModel($city_id, $country_id)
    {
        if (($model = City::findOne(['city_id' => $city_id, 'country_id' => $country_id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionBlock()
    {
        if (!Yii::$app->request->isAjax) {
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        }

        $data = Yii::$app->request->post();
        
        $status = ($data['status'] == 'Active' ? 'Deactive' : 'Active');
        
        $command=\common\models\City::updateAll(['status' => $status],'city_id= '.$data['cid']);
        
        if ($status == 'Active') {
            return \yii\helpers\Url::to('@web/uploads/app_img/active.png');
        } else {
            return \yii\helpers\Url::to('@web/uploads/app_img/inactive.png');
        }
    }
}
