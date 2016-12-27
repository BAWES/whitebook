<?php

namespace admin\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use admin\models\Themes;
use admin\models\ThemesSearch;
use admin\models\AuthItem;
use common\models\VendorItemThemes;
use admin\models\AccessControlList;

/**
 * ThemesController implements the CRUD actions for Themes model.
 */
class ThemesController extends Controller
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
     * Lists all Themes models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ThemesSearch();
        $searchModel->trash = 'Default';
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
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
        $model = new Themes();
        $model->scenario = 'insert';
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->theme_name = strtolower($model->theme_name);
            $model->save();
            Yii::$app->session->setFlash('success', 'Theme added successfully!');

            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
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
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->theme_name = strtolower($model->theme_name);
            $model->save();

            Yii::$app->session->setFlash('success', 'Theme updated successfully!');

            return $this->redirect(['index']);
        } else {

            return $this->render('update', [
                'model' => $model,
            ]);
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
        $model = $this->findModel($id);
        $model->trash = 'Deleted';
        $model->save(false);

        //delete vendor item theme
        VendorItemThemes::deleteAll(['theme_id' => $id]);

        Yii::$app->session->setFlash('success', 'Theme deleted successfully!');

        return $this->redirect(['index']);
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
