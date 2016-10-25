<?php
namespace admin\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use admin\models\AuthItem;
use admin\models\CountrySearch;
use common\models\Country;
use common\models\City;
use common\models\CustomerAddress;
use common\models\CustomerCart;
use common\models\Location;
use common\models\VendorLocation;

/**
 * CountryController implements the CRUD actions for Country model.
 */
class CountryController extends Controller
{
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
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
            //        'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Country models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $access = AuthItem::AuthitemCheck('4', '11');
        
        if (yii::$app->user->can($access)) {
            
            $searchModel = new CountrySearch();
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
     * Displays a single Country model.
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
     * Creates a new Country model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $access = AuthItem::AuthitemCheck('1', '11');

        if (yii::$app->user->can($access)) {
        
            $model = new Country();

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                
                Yii::$app->session->setFlash('success', 'Country info created successfully!');

                return $this->redirect(['index']);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }

        } else {
            
            Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');

            return $this->redirect(['site/index']);
        }
    }

    /**
     * Updates an existing Country model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $access = AuthItem::AuthitemCheck('2', '11');
        
        if (yii::$app->user->can($access)) {
        
            $model = $this->findModel($id);

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                
                Yii::$app->session->setFlash('success', 'Country info updated successfully!');
                return $this->redirect(['index']);

            } else {

                return $this->render('update', [
                    'model' => $model,
                ]);
            }

        } else {
            
            Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');

            return $this->redirect(['site/index']);
        }
    }

    /**
     * Deletes an existing Country model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function actionDelete($id)
    {
        $access = AuthItem::AuthitemCheck('3', '11');

        if (yii::$app->user->can($access)) {
        
            $this->findModel($id)->delete();

            $cities = City::findAll(['country_id' => $id]);

            //delete all customer address 
            CustomerAddress::deleteAll(['country_id' => $id]);

            //delete all cities 
            City::deleteAll(['country_id' => $id]);

            //delete all location 
            Location::deleteAll(['country_id' => $id]);

            foreach ($cities as $key => $value) {
                
                //delete customer cart - area_id 
                CustomerCart::deleteAll('area_id in (select area_id from {{%location}} 
                    where city_id = "'.$value->city_id.'")');

                //delete all vendor location - city_id 
                VendorLocation::deleteAll(['city_id' => $value->city_id]);
            }

            Yii::$app->session->setFlash('success', 'Country deleted successfully!');

            return $this->redirect(['index']);

        } else {
            
            Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');

            return $this->redirect(['site/index']);
        }
    }

    /**
     * Finds the Country model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param int $id
     *
     * @return Country the loaded model
     *
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Country::findOne($id)) !== null) {
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
        
        $command = Country::updateAll(['country_status' => $status],'country_id= '.$data['cid']);
        
        if ($status == 'Active') {
            return \yii\helpers\Url::to('@web/uploads/app_img/active.png');
        } else {
            return \yii\helpers\Url::to('@web/uploads/app_img/inactive.png');
        }
    }
}
