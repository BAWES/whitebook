<?php

namespace admin\controllers;

use Yii;
use common\models\Admin;
use admin\models\AuthItem;
use admin\models\Cms;
use admin\models\CmsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use admin\models\AccessControlList;

/**
 * CmsController implements the CRUD actions for Cms model.
 */
class CmsController extends Controller
{
    public function init()
    {
        parent::init();
        if (Yii::$app->user->isGuest) { 
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
     * Lists all Cms models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $model = new Cms();
        $searchModel = new CmsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }

    /**
     * Displays a single Cms model.
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
     * Creates a new Cms model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $access = AuthItem::AuthitemCheck('1', '27');
        
        if (yii::$app->user->can($access)) {
        
            $model = new Cms();

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                
                Yii::$app->session->setFlash('success', 'New static page created successfully!');
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
     * Updates an existing Cms model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $access = AuthItem::AuthitemCheck('2', '27');

        if (yii::$app->user->can($access)) {
            
            $model = $this->findModel($id);
            
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                
                Yii::$app->session->setFlash('success', 'Static page updated successfully!');

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
     * Deletes an existing Cms model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function actionDelete($id)
    {
        $access = AuthItem::AuthitemCheck('3', '27');
        
        if (yii::$app->user->can($access)) {
        
            $this->findModel($id)->delete();
            
            Yii::$app->session->setFlash('success', 'Static page deleted successfully!');

            return $this->redirect(['index']);

        } else {
            Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');

            return $this->redirect(['site/index']);
        }
    }

    /**
     * Finds the Cms model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param int $id
     *
     * @return Cms the loaded model
     *
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Cms::findOne($id)) !== null) {
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
        
        $command = Cms::updateAll(['page_status' => $status],'page_id= '.$data['id']);

        if ($status == 'Active') {
            return \yii\helpers\Url::to('@web/uploads/app_img/active.png');
        } else {
            return \yii\helpers\Url::to('@web/uploads/app_img/inactive.png');
        }
    }
}
