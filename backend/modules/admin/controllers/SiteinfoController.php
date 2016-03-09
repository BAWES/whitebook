<?php

namespace backend\modules\admin\controllers;

use Yii;
use backend\models\Siteinfo;
use backend\models\Authitem;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\filters\AccessControl;

/**
 * SiteinfoController implements the CRUD actions for Siteinfo model.
 */
class SiteinfoController extends Controller
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
     * Lists all Siteinfo models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $access = Authitem::AuthitemCheck('4', '10');
        if (yii::$app->user->can($access)) {
            $model = Siteinfo::find()->all();
            foreach ($model as $key => $val) {
                $first_id = $val['id'];
            }

            if (count($model) == 1) {
                $this->redirect('siteinfo/update?id='.$first_id);
            } else {
                $this->redirect('siteinfo/create');
            }
        } else {
            echo Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');

            return $this->redirect(['site/index']);
        }
    }

    /**
     * Displays a single Siteinfo model.
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
     * Creates a new Siteinfo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $access = Authitem::AuthitemCheck('1', '10');
        if (yii::$app->user->can($access)) {
            $model = new Siteinfo();
            $base = Yii::$app->basePath;

            if ($model->load(Yii::$app->request->post())) {
                $site_logo = UploadedFile::getInstance($model, 'site_logo');
                $site_favicon = UploadedFile::getInstance($model, 'site_favicon');

                if (!empty($site_logo)) {
                    // check if uploaded file is set or not

                 $len = rand(1, 1000);
                    $site_logo->saveAs($base.'/web/uploads/app_img/'.$site_logo->baseName.'_'.$len.'.'.$site_logo->extension);
                    $model->site_logo = $site_logo->baseName.'_'.$len.'.'.$site_logo->extension;
                }

                if (!empty($site_favicon)) {
                    // check if uploaded file is set or not

                 $len = rand(1, 1000);
                    $site_favicon->saveAs($base.'/web/uploads/app_img/'.$site_favicon->baseName.'_'.$len.'.'.$site_favicon->extension);
                    $model->site_favicon = $site_favicon->baseName.'_'.$len.'.'.$site_favicon->extension;
                }

                $model->save();
                echo Yii::$app->session->setFlash('success', 'Application info created successfully!');
                Yii::$app->newcomponent->activity('Admin', 'created site information.');

                return $this->redirect(['view', 'id' => $model->id]);
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
     * Updates an existing Siteinfo model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $access = Authitem::AuthitemCheck('2', '10');
        if (yii::$app->user->can($access)) {
            $model = $this->findModel($id);
            $model->scenario = 'update';
            $base = Yii::$app->basePath;

            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                $site_logo = UploadedFile::getInstance($model, 'site_logo');
                $site_favicon = UploadedFile::getInstance($model, 'site_favicon');

                if (!empty($site_logo)) {
                    // check if uploaded file is set or not

                    if (file_exists($_SERVER['DOCUMENT_ROOT'].'/web/uploads/app_img/'.$model->site_logo)) {
                        unlink($base.'/web/uploads/app_img/'.$model->site_logo);
                    }
                    $len = rand(1, 1000);
                    $site_logo->saveAs($base.'/web/uploads/app_img/'.$site_logo->baseName.'_'.$len.'.'.$site_logo->extension);
                    $model->site_logo = $site_logo->baseName.'_'.$len.'.'.$site_logo->extension;
                } else {
                    $model->site_logo = Siteinfo::findone($model->id)->site_logo;
                }

                if (!empty($site_favicon)) {
                    // check if uploaded file is set or not

                 unlink($base.'/web/uploads/app_img/'.$model->site_favicon);
                    $len = rand(1, 1000);
                    $site_favicon->saveAs($base.'/web/uploads/app_img/'.$site_favicon->baseName.'_'.$len.'.'.$site_favicon->extension);
                    $model->site_favicon = $site_favicon->baseName.'_'.$len.'.'.$site_favicon->extension;
                } else {
                    $model->site_favicon = Siteinfo::findone($model->id)->site_favicon;
                }

                $model->save();
                echo Yii::$app->session->setFlash('success', 'Application info updated successfully!');
                Yii::$app->newcomponent->activity('Admin', 'updated site information.');

                return $this->redirect(['view', 'id' => $model->id]);
            }

            return $this->render('update', [
                    'model' => $model,
                ]);
        } else {
            echo Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');

            return $this->redirect(['site/index']);
        }
    }

    /**
     * Deletes an existing Siteinfo model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function actionDelete($id)
    {
        $access = Authitem::AuthitemCheck('3', '10');
        if (yii::$app->user->can($access)) {
            $this->findModel($id)->delete();

            return $this->redirect(['index']);
        } else {
            echo Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');

            return $this->redirect(['site/index']);
        }
    }

    /**
     * Finds the Siteinfo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param int $id
     *
     * @return Siteinfo the loaded model
     *
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Siteinfo::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
