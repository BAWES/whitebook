<?php

namespace admin\controllers;

use Yii;
use common\models\Socialinfo;
use admin\models\AuthItem;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use admin\models\AccessControlList;

/**
 * SocialinfoController implements the CRUD actions for Socialinfo model.
 */
class SocialInfoController extends Controller
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
     * Lists all Socialinfo models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $model = Socialinfo::find()->all();
        foreach ($model as $key => $val) {
            $id = $val['store_social_id'];
        }
        if (count($model) == 1) {
            return $this->redirect(['social-info/update/', 'store_social_id' => $id]);
        } else {
            $this->redirect('social-info/create');
        }

        $searchModel = new SocialinfoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
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
        $model = new Socialinfo();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Social info created successfully!');

            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
            'model' => $model,
        ]);
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
        $model = $this->findModel($store_social_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Social info updated successfully!');

            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
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
        $this->findModel($store_social_id, $store_id)->delete();
        return $this->redirect(['index']);
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
