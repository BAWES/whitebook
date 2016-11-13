<?php

namespace admin\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use admin\models\AccessControlList;
use admin\models\AuthItem;
use admin\models\LocationSearch;
use common\models\Country;
use common\models\Location;
use common\models\City;
use common\models\CustomerAddress;
use common\models\CustomerCart;
use common\models\VendorLocation;

/**
 * LocationController implements the CRUD actions for Location model.
 */
class LocationController extends Controller
{
    public function init()
    {
        parent::init();
        if (Yii::$app->user->isGuest) { // chekck the admin logged in
            //$this->redirect('login');
            $url = Yii::$app->urlManager->createUrl(['admin/site/login']);
            Yii::$app->getResponse()->redirect($url);
        }
    }

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
     * Lists all Location models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LocationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Location model.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Location model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Location();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Location info created successfully!');

            return $this->redirect(['index']);
        } else {

            $countries = Country::find()
                ->where(['trash'=>'Default','country_status'=>'Active'])
                ->orderBy('country_name')
                ->all();

            $country = ArrayHelper::map($countries, 'country_id', 'country_name');

            return $this->render('create', [
            'model' => $model, 'country' => $country,
        ]);
        }
    }

    /**
     * Updates an existing Location model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Area info updated successfully!');

            return $this->redirect(['index']);
        } else {
            $country = Country::loadcountry();
            $cities = City::find()->all();
            $city = ArrayHelper::map($cities, 'city_id', 'city_name');

            return $this->render('update', [
            'model' => $model, 'city' => $city, 'country' => $country,
        ]);
        }
    }

    /**
     * Deletes an existing Location model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        //remove customer address
        CustomerAddress::deleteAll(['area_id' => $id]);
        CustomerCart::deleteAll(['area_id' => $id]);
        VendorLocation::deleteAll(['area_id' => $id]);

        return $this->redirect(['index']);
    }

    /**
     * Finds the Location model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param int $id
     *
     * @return Location the loaded model
     *
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Location::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    public function actionCity()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
        }
        $city = City::find()->select('city_id,city_name')->where(['country_id' => $data['country_id']])->all();
        $options = '<option value="">Select</option>';
        if (!empty($city)) {
            foreach ($city as $key => $val) {
                $options .=  '<option value="'.$val['city_id'].'">'.$val['city_name'].'</option>';
            }
        }
        echo $options;
        die;
    }

    public function actionBlock()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
        }
        $status = ($data['status'] == 'Active' ? 'Deactive' : 'Active');
        $command=Location::updateAll(['status' => $status],'id= '.$data['lid']);
        
        if ($status == 'Active') {
            return \yii\helpers\Url::to('@web/uploads/app_img/active.png');
        } else {
            return \yii\helpers\Url::to('@web/uploads/app_img/inactive.png');
        }
    }
    public function actionArea()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
        }
        $location = Location::find()->select('id,location')->where(['city_id' => $data['city_id']])->all();
        echo  '<option value="">Select</option>';
        foreach ($location as $key => $val) {
            echo  '<option value="'.$val['id'].'">'.$val['location'].'</option>';
        }
    }
}
