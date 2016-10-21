<?php

namespace admin\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use admin\models\AuthItem;
use admin\models\Package;
use admin\models\PackageSearch;
use common\models\VendorPackages;

/**
 * PackageController implements the CRUD actions for Package model.
 */
class PackageController extends Controller
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
                       'actions' => ['create', 'update', 'index', 'view', 'delete', 'block', 'packagedelete', 'packageupdate'],
                       'allow' => true,
                       'roles' => ['@'],
                   ],
               ],
           ],
        ];
    }

    /**
     * Lists all Package models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $access = AuthItem::AuthitemCheck('4', '16');
        if (yii::$app->user->can($access)) {
            $searchModel = new PackageSearch();
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
     * Displays a single Package model.
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
     * Creates a new Package model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $access = AuthItem::AuthitemCheck('1', '16');
        if (yii::$app->user->can($access)) {
            $model = new Package();
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                $model->save();
                $pack = Yii::$app->request->post();
                Yii::$app->session->setFlash('success', 'Package created successfully!');
                Yii::info('[Package Created] '. Yii::$app->user->identity->admin_name .' created new '.$model->package_name.' package', __METHOD__);

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
     * Updates an existing Package model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param string $id
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $access = AuthItem::AuthitemCheck('2', '16');
        if (yii::$app->user->can($access)) {
            $model = $this->findModel($id);
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                Yii::$app->session->setFlash('success', 'Package Updated successfully!');
                Yii::info('[Package Updated] '. Yii::$app->user->identity->admin_name .' updated '.$model->package_name.' package information', __METHOD__);
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
     * Deletes an existing Package model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param string $id
     *
     * @return mixed
     */
    public function actionDelete($id)
    {
        $access = AuthItem::AuthitemCheck('3', '16');
        
        if (yii::$app->user->can($access)) {
            
            $model = $this->findModel($id);
            $model->trash = 'Deleted';
            $model->load(Yii::$app->request->post());
            $model->save();

            //remove from vendor package 
            VendorPackages::deleteAll(['package_id' => $id]);

            Yii::$app->session->setFlash('success', 'Package deleted successfully!');

            return $this->redirect(['index']);
        } else {
            Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');
            return $this->redirect(['site/index']);
        }
    }

    public function actionPackagedelete()
    {
        if (Yii::$app->request->isAjax) {
            $data = VendorPackages::findOne(Yii::$app->request->post('packid'));
            return $data->delete();
        }
    }

    public function actionPackageupdate()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $pack_id=$data['packid'];
            $output= Package::find()->where(['id'=>$pack_id])->asArray()->all();
            foreach ($output as $o) {
                $id = $o['package_id'];
                $start = $o['package_start_date'];
                $start = date('Y-m-d', strtotime($start));
                $end = $o['package_end_date'];
                $end = date('Y-m-d', strtotime($end));
            }
            echo json_encode(array('id' => $id, 'start' => $start, 'end' => $end));
            exit;
        }
    }

    /**
     * Finds the Package model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param string $id
     *
     * @return Package the loaded model
     *
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Package::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionBlock()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $status = ($data['status'] == 'Active' ? 'Deactive' : 'Active');
            Package::updateAll(['package_status' => $status],'package_id= '.$data['id']);

            if ($status == 'Active') {
                return \yii\helpers\Url::to('@web/uploads/app_img/active.png');
            } else {
                return \yii\helpers\Url::to('@web/uploads/app_img/inactive.png');
            }
        }
    }
}
?>
