<?php

namespace admin\controllers;

use Yii;
use admin\models\AuthItem;
use admin\models\Role;
use admin\models\RoleSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use admin\models\AccessControlList;

/**
 * RoleController implements the CRUD actions for Role model.
 */
class RoleController extends Controller
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
     * Lists all Role models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $access = AuthItem::AuthitemCheck('4', '10');
        
        if (yii::$app->user->can($access)) {
            $searchModel = new RoleSearch();
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
     * Displays a single Role model.
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
     * Creates a new Role model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Role();
      
        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            $access_control = Yii::$app->request->post('access_control');

            if(!$access_control) {
                $access_control = array();
            }

            foreach ($access_control as $key => $value) {
                foreach ($value as $method) {
                    $access = new AccessControlList();
                    $access->role_id = $model->role_id;
                    $access->controller = $key;
                    $access->method = $method;
                    $access->save();
                }
            }

            Yii::$app->session->setFlash('success', 'New user role created successfully!');

            return $this->redirect(['index']);

        } else {

            //list controller - method 
            $action_list = Role::actionList();

            return $this->render('create', [
                'model' => $model,
                'action_list' => $action_list
            ]);
        }
    }

    /**
     * Updates an existing Role model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param string $id
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
            $model = $this->findModel($id);
     
            if ($model->load(Yii::$app->request->post()) && $model->save()) {

                //remove all old 
                AccessControlList::deleteAll(['role_id' => $id]);

                //insert new 
                $access_control = Yii::$app->request->post('access_control');

                if(!$access_control) {
                    $access_control = array();
                }

                foreach ($access_control as $key => $value) {
                    foreach ($value as $method) {
                        $access = new AccessControlList();
                        $access->role_id = $model->role_id;
                        $access->controller = $key;
                        $access->method = $method;
                        $access->save();
                    }
                }

                Yii::$app->session->setFlash('success', 'User role updated successfully!');

                return $this->redirect(['index']);

            } else {

                //list controller - method 
                $action_list = Role::actionList();

                //role access list 
                $role_access_list = array();

                $result = AccessControlList::findAll(['role_id' => $id]);

                foreach ($result as $key => $value) {
                    $role_access_list[$value->controller][] = $value->method;
                }

                return $this->render('update', [
                    'model' => $model,
                    'action_list' => $action_list,
                    'role_access_list' => $role_access_list
                ]);
            }
    }

    /**
     * Deletes an existing Role model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param string $id
     *
     * @return mixed
     */
    public function actionDelete($id)
    {
        $access = AuthItem::AuthitemCheck('3', '10');
        if (yii::$app->user->can($access)) {
            $this->findModel($id)->delete();

            //delete all access control 
            AccessControlList::deleteAll(['role_id' => $id]);

            Yii::$app->session->setFlash('success', 'User role deleted successfully!');

            return $this->redirect(['index']);
        } else {
            Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');

            return $this->redirect(['site/index']);
        }
    }

    /**
     * Finds the Role model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param string $id
     *
     * @return Role the loaded model
     *
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Role::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
