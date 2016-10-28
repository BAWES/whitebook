<?php

namespace admin\controllers;

use Yii;
use common\models\Siteinfo;
use admin\models\AuthItem;
use yii\web\Controller;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use admin\models\AccessControlList;

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
     * Lists all Siteinfo models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $model = Siteinfo::find()->all();

        foreach ($model as $key => $val) {
         $first_id = $val['id'];
        }

        if (count($model) == 1) {
            $this->redirect(['update', 'id' => $first_id]);
        } else {
            $this->redirect('create');
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
            Yii::$app->session->setFlash('success', 'Application info created successfully!');
            Yii::info('[Site] '. Yii::$app->user->identity->admin_name .' created site information.', __METHOD__);

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
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
        $model = $this->findModel($id);
        $model->scenario = 'update';
        $base = Yii::$app->basePath;

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->save();
            Yii::$app->session->setFlash('success', 'Application info updated successfully!');
            Yii::info('[Site] '. Yii::$app->user->identity->admin_name .' updated site information.', __METHOD__);

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
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
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
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
