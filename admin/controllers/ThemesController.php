<?php

namespace admin\controllers;

use Yii;
use admin\models\Themes;
use admin\models\ThemesSearch;
use admin\models\Authitem;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ThemesController implements the CRUD actions for Themes model.
 */
class ThemesController extends Controller
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
                       'actions' => ['create', 'update', 'index', 'view', 'delete', 'block'],
                       'allow' => true,
                       'roles' => ['@'],
                   ],
               ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                   // 'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Themes models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $access = Authitem::AuthitemCheck('4', '20');
        if (yii::$app->user->can($access)) {
            $searchModel = new ThemesSearch();
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
     * Displays a single Themes model.
     *
     * @param string $id
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
     * Creates a new Themes model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $access = Authitem::AuthitemCheck('1', '20');
        if (yii::$app->user->can($access)) {
            $model = new Themes();
            $model->scenario = 'insert';
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                $model->theme_name = strtolower($model->theme_name);               
                $model->save();
                echo Yii::$app->session->setFlash('success', 'Theme added successfully!');

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
     * Updates an existing Themes model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param string $id
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $access = Authitem::AuthitemCheck('2', '20');

        if (yii::$app->user->can($access)) {

            $model = $this->findModel($id);

            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                $model->theme_name = strtolower($model->theme_name);
                $model->save();

                echo Yii::$app->session->setFlash('success', 'Theme updated successfully!');

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
          * Deletes an existing Themes model.
          * If deletion is successful, the browser will be redirected to the 'index' page.
          *
          * @param string $id
          *
          * @return mixed
          */
         public function actionDelete($id)
         {
             $access = Authitem::AuthitemCheck('3', '20');
             if (yii::$app->user->can($access)) {
                 $model = $this->findModel($id);
                 $model->trash = 'Deleted';
                 $model->load(Yii::$app->request->post());
                 $model->save();
                 echo Yii::$app->session->setFlash('success', 'Theme deleted successfully!');

                 return $this->redirect(['index']);
             } else {
                 echo Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');

                 return $this->redirect(['site/index']);
             }
         }

    /**
     * Finds the Themes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param string $id
     *
     * @return Themes the loaded model
     *
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Themes::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionBlock()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
        }
        $status = ($data['status'] == 'Active' ? 'Deactive' : 'Active');
		$command=Themes::updateAll(['theme_status' => $status],'theme_id= '.$data['id']);
        if ($status == 'Active') {
            return \yii\helpers\Url::to('@web/uploads/app_img/active.png');
        } else {
            return \yii\helpers\Url::to('@web/uploads/app_img/inactive.png');
        }
    }
}
