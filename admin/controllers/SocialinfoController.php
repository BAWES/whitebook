<?php

namespace backend\modules\admin\controllers;

use Yii;
use common\models\Socialinfo;
use common\models\Authitem;
use common\models\SocialinfoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * SocialinfoController implements the CRUD actions for Socialinfo model.
 */
class SocialinfoController extends Controller
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
                       'actions' => ['create', 'update', 'index', 'view', 'delete'],
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
     * Lists all Socialinfo models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $access = Authitem::AuthitemCheck('4', '7');
        if (yii::$app->user->can($access)) {
            $model = Socialinfo::find()->all();
            foreach ($model as $key => $val) {
                $id = $val['store_social_id'];
            }
            if (count($model) == 1) {
                return $this->redirect(['socialinfo/update/', 'store_social_id' => $id]);
            } else {
                $this->redirect('socialinfo/create');
            }

            $searchModel = new SocialinfoSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
        } else {
            echo Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');

            return $this->redirect(['site/index']);
        }
    }

    /**
     * Displays a single Socialinfo model.
     *
     * @param int $store_social_id
     * @param int $store_id
     *
     * @return mixed
     */
    public function actionView($store_social_id, $store_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($store_social_id, $store_id),
        ]);
    }

    /**
     * Creates a new Socialinfo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $access = Authitem::AuthitemCheck('1', '7');
        if (yii::$app->user->can($access)) {
            $model = new Socialinfo();
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                echo Yii::$app->session->setFlash('success', 'Social info created successfully!');

                return $this->redirect(['index']);
            } else {
                return $this->render('create', [
                'model' => $model,
            ]);
            }
        } else {
            echo Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');

            return $this->redirect(['site/index']);
        }
    }

    /**
     * Updates an existing Socialinfo model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param int $store_social_id
     * @param int $store_id
     *
     * @return mixed
     */
    public function actionUpdate($store_social_id)
    {
        $access = Authitem::AuthitemCheck('2', '7');
        if (yii::$app->user->can($access)) {
            $model = $this->findModel($store_social_id);

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                echo Yii::$app->session->setFlash('success', 'Social info updated successfully!');

                return $this->redirect(['index']);
            } else {
                return $this->render('update', [
                'model' => $model,
            ]);
            }
        } else {
            echo Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');

            return $this->redirect(['site/index']);
        }
    }

    /**
     * Deletes an existing Socialinfo model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param int $store_social_id
     * @param int $store_id
     *
     * @return mixed
     */
    public function actionDelete($store_social_id, $store_id)
    {
        $access = Authitem::AuthitemCheck('3', '7');
        if (yii::$app->user->can($access)) {
            $this->findModel($store_social_id, $store_id)->delete();

            return $this->redirect(['index']);
        } else {
            echo Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');

            return $this->redirect(['site/index']);
        }
    }

    /**
     * Finds the Socialinfo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param int $store_social_id
     * @param int $store_id
     *
     * @return Socialinfo the loaded model
     *
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($store_social_id)
    {
        if (($model = Socialinfo::findOne(['store_social_id' => $store_social_id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
