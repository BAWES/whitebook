<?php

namespace backend\controllers;

use Yii;
use common\models\vendorlocation;
use common\models\vendorlocationSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Country;
use common\models\Location;
use common\models\City;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use yii\db\Query;

/**
 * VendorlocationController implements the CRUD actions for vendorlocation model.
 */
class VendorlocationController extends Controller
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

    /**
     * Lists all vendorlocation models.
     * @return mixed
     */
    public function actionIndex()
    {
		$searchModel = new vendorlocationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single vendorlocation model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new vendorlocation model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new vendorlocation();

        if ($model->load(Yii::$app->request->post())) {

            $selected_areas = implode(',',$_POST['location']);
            foreach ($_POST['location'] as $key => $value) {
                 $get_city_id = Location::find()->select('city_id')->where(['id'=>$value])->one();

                 $location_tbl = new Vendorlocation();
                 $location_tbl->vendor_id = Yii::$app->user->getId();
                 $location_tbl->city_id =$get_city_id['city_id'];
                 $location_tbl->area_id = $value;
                 $location_tbl->save();
            }
            return $this->redirect(['view', 'id' => $model->id]);
        } else
        {
            $countries=Country::find()->where(['country_id'=>136])->all();
            $city_tbl = City::find()->where(['country_id' => 136])->all();
            $city=ArrayHelper::map($city_tbl,'city_id','city_name');

			$cities=City::find()->select(['{{%city}}.city_id','{{%city}}.city_name'])
			 ->leftJoin('{{%location}}', '{{%location}}.city_id = {{%city}}.city_id')
			->where(['{{%city}}.status'=>'Active'])
			->andwhere(['{{%location}}.trash'=>'Default'])
			->andwhere(['{{%location}}.status'=>'Active'])
			->groupby(['{{%location}}.city_id'])
			->asArray()
			->all();
		    $country=ArrayHelper::map($countries,'country_id','country_name');
            return $this->render('create', [
                'model' => $model,'country' => $country, 'cities'=>$cities,'city'=>$city,
            ]);
        }
    }

    /**
     * Updates an existing vendorlocation model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		$cities=City::find()->select(['{{%city}}.city_id','{{%city}}.city_name'])
		->leftJoin('{{%location}}', '{{%location}}.city_id = {{%city}}.city_id')
		->where(['{{%city}}.status'=>'Active'])
		->andwhere(['{{%location}}.trash'=>'Default'])
		->andwhere(['{{%location}}.status'=>'Active'])
		->groupby(['{{%location}}.city_id'])
		->asArray()
		->all();
        if ($model->load(Yii::$app->request->post())) {
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            $countries=Country::find()->all();
            $country=ArrayHelper::map($countries,'country_id','country_name');
            $city_all=City::find()->all();
            $city=ArrayHelper::map($city_all,'city_id','city_name');
            return $this->render('update', [
                'model' => $model,'city' => $city, 'country' => $country, 'cities' => $cities,
            ]);
        }
    }

    public function actionEdit()
    {
        $model= new Vendorlocation;

         if ($model->load(Yii::$app->request->post())) {

            if(empty($_POST['location']))
            {
                Vendorlocation::deleteAll('vendor_id = :vendor_id', [':vendor_id' => 0]); // this is dummy record
                Vendorlocation::deleteAll('vendor_id = :vendor_id', [':vendor_id' => Yii::$app->user->getId()]);
            }
            else
            {

            $selected_areas = implode(',',$_POST['location']);
            Vendorlocation::deleteAll('vendor_id = :vendor_id', [':vendor_id' => Yii::$app->user->getId()]);

            foreach ($_POST['location'] as $key => $value) {
				
                 $get_city_id = Location::find()->select('city_id')->where(['id'=>$value])->one();

                 $vendor_location_tbl = new Vendorlocation;
                 $vendor_location_tbl->vendor_id = Yii::$app->user->getId();
                 $vendor_location_tbl->city_id = $get_city_id['city_id'];
                 $vendor_location_tbl->area_id = $value;
                 $vendor_location_tbl->validate();

                 //print_r ($vendor_location_tbl->getErrors());die;
                 $vendor_location_tbl->save();
			}
            $model->save();
            }
            Vendorlocation::deleteAll('vendor_id = :vendor_id', [':vendor_id' => 0]); // this is dummy record
            echo Yii::$app->session->setFlash('success', "Area info updated successfully!");
            return $this->redirect(['edit']);

        }
        	$cities=City::find()->select(['{{%city}}.*'])
		->leftJoin('{{%location}}', '{{%location}}.city_id = {{%city}}.city_id')
		->where(['{{%city}}.status'=>'Active'])
		->andwhere(['{{%location}}.trash'=>'Default'])
		->andwhere(['{{%location}}.status'=>'Active'])
		->groupby(['{{%location}}.city_id'])
		->asArray()
		->all();
	
            return $this->render('edit', [
                'model' => $model, 'cities' => $cities,
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
        if (($model = vendorlocation::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
