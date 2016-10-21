<?php

namespace admin\controllers;

use common\models\FeatureGroupItem;
use Yii;
use common\models\Vendor;
use admin\models\FeatureGroup;
use common\models\BlockedDate;
use admin\models\FeatureGroupSearch;
use yii\web\Controller;
use admin\models\AuthItem;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * FeaturegroupController implements the CRUD actions for FeatureGroup model.
 */
class FeaturegroupController extends Controller
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
                    //'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all FeatureGroup models.
     *
     * @return mixed
     */
    public function actionIndex()
    {   
        $access = AuthItem::AuthitemCheck('4', '17');
        
        if (yii::$app->user->can($access)) {
            
            $searchModel = new FeatureGroupSearch();
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
     * Displays a single FeatureGroup model.
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
     * Creates a new FeatureGroup model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $access = AuthItem::AuthitemCheck('1', '17');
        if (yii::$app->user->can($access)) {
            $model = new FeatureGroup();

            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                $model->group_name = strtolower($model->group_name);
                $model->save();
                Yii::$app->session->setFlash('success', 'Feature group added successfully!');

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
     * Updates an existing FeatureGroup model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param string $id
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $access = AuthItem::AuthitemCheck('2', '17');
        if (yii::$app->user->can($access)) {
            $model = $this->findModel($id);

            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                $model->group_name = strtolower($model->group_name);
                $model->save();
                Yii::$app->session->setFlash('success', 'Feature group updated successfully!');

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
     * Deletes an existing FeatureGroup model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param string $id
     *
     * @return mixed
     */
    public function actionDelete($id)
    {
        $access = AuthItem::AuthitemCheck('3', '17');
        
        if (yii::$app->user->can($access)) {
            
            $this->findModel($id)->delete();
            
            FeatureGroupItem::deleteAll(['group_id'=>$id]); // delete all products
            
            Yii::$app->session->setFlash('success', 'Feature group deleted successfully!');

            return $this->redirect(['index']);

        } else {
            Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');

            return $this->redirect(['site/index']);
        }
    }

    /**
     * Finds the FeatureGroup model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param string $id
     *
     * @return FeatureGroup the loaded model
     *
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FeatureGroup::findOne($id)) !== null) {
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
        
        $command=FeatureGroup::updateAll(['group_status' => $status],'group_id= '.$data['id']);
        
        if ($status == 'Active') {
            return \yii\helpers\Url::to('@web/uploads/app_img/active.png');
        } else {
            return \yii\helpers\Url::to('@web/uploads/app_img/inactive.png');
        }
    }
}
