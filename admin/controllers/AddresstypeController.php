<?php

namespace admin\controllers;

use yii\helpers\ArrayHelper;
use Yii;
use admin\models\Addresstype;
use admin\models\Admin;
use admin\models\Authitem;
use admin\models\AddresstypeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * AddresstypeController implements the CRUD actions for Addresstype model.
 */
class AddresstypeController extends Controller
{
    public function init()
    {
        parent::init();
        if (Yii::$app->user->isGuest) { // chekck the admin logged in
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

    /**
     * Lists all Addresstype models.
     *
     * @return mixed
     */
    public function actionIndex()
    {		
        $access = Authitem::AuthitemCheck('4', '14');

        if (yii::$app->user->can($access)) {

            $searchModel = new AddresstypeSearch();
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
     * Displays a single Addresstype model.
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
     * Creates a new Addresstype model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $access = Authitem::AuthitemCheck('1', '14');

        if (yii::$app->user->can($access)) {
            $model = new Addresstype();

            if($model->load(Yii::$app->request->post()) && $model->validate())
            {            
                $model->status = (Yii::$app->request->post()['Addresstype']['status']) ? 'Active' : 'Deactive';
                $model->save();
                
                Yii::$app->session->setFlash('success', 'Address Type created successfully!');
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
     * Updates an existing Addresstype model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param string $id
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $access = Authitem::AuthitemCheck('2', '14');
        
        if (yii::$app->user->can($access)) {
        
            $model = $this->findModel($id);
        
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
        
                $model->status = (Yii::$app->request->post()['Addresstype']['status']) ? 'Active' : 'Deactive';
                $model->save();
                
                Yii::$app->session->setFlash('success', 'Address Type Updated successfully!');
                return $this->redirect(['index']);

            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);

                return $this->redirect(['site/index']);
            }
        }
    }

    /**
     * Deletes an existing Addresstype model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param string $id
     *
     * @return mixed
     */
    public function actionDelete($id)
    {
        $access = Authitem::AuthitemCheck('3', '14');

        if (yii::$app->user->can($access)) {
            $model = $this->findModel($id);
            $model->trash = 'Deleted';
            $model->load(Yii::$app->request->post());
            $model->save();  // equivalent to $model->update();
            
            Yii::$app->session->setFlash('success', 'Address Type Deleted successfully!');

            return $this->redirect(['index']);

        } else {
            
            Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');
            return $this->redirect(['site/index']);
        }
    }

    /**
     * Finds the Addresstype model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param string $id
     *
     * @return Addresstype the loaded model
     *
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Addresstype::findOne($id)) !== null) {
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
        
        $command = Addresstype::updateAll(['status' => $status], 'type_id= '.$data['cid']);
        
        if ($status == 'Active') {
            return \yii\helpers\Url::to('@web/uploads/app_img/active.png');
        } else {
            return \yii\helpers\Url::to('@web/uploads/app_img/inactive.png');
        }
    }
}
