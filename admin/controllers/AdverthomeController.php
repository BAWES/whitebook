<?php

namespace admin\controllers;

use Yii;
use admin\models\AdvertHome;
use admin\models\Admin;
use admin\models\AuthItem;
use admin\models\Image;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * AdverthomeController implements the CRUD actions for AdvertHome model.
 */
class AdverthomeController extends Controller
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
                 //   'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all AdvertHome models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $access = AuthItem::AuthitemCheck('2', '24');
        if (yii::$app->user->can($access)) {

            $model = AdvertHome::find()->all();

            foreach ($model as $key => $val) {
                $first_id = $val['advert_id'];
            }
            if (count($model) == 1) {
                $this->redirect(['adverthome/update','id'=>$first_id]);
            } else {
                $this->redirect(['adverthome/create']);
            }

        } else {
            Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');

            return $this->redirect(['site/index']);
        }
    }

    /**
     * Displays a single AdvertHome model.
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
     * Creates a new AdvertHome model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $access = AuthItem::AuthitemCheck('1', '25');
        if (yii::$app->user->can($access)) {
            $model = new AdvertHome();
            if ($model->load(Yii::$app->request->post()) && ($model->validate())) {
                $model->save();
                Yii::$app->session->setFlash('success', 'Home advertisement script created successfully!');

                return $this->redirect(['view', 'id' => $model->advert_id]);
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
     * Updates an existing AdvertHome model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param string $id
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $access = AuthItem::AuthitemCheck('2', '25');
        if (yii::$app->user->can($access)) {
            $model = $this->findModel($id);
            $imagedata = Image::find()->where('module_type = :status AND item_id = :id', [':id' => $id, ':status' => 'home_ads'])->orderby(['vendorimage_sort_order' => SORT_ASC])->all();
      //  echo '<pre>';print_r ($imagedata);die;
        $model1 = new Image();
            if ($model->load(Yii::$app->request->post()) && ($model->validate())) {
                $model->save();
                Yii::$app->session->setFlash('success', 'Advertisement details updated successfully!');

                return $this->redirect(['update', 'id' => $model->advert_id]);
            } else {
                return $this->render('update', [
                'model' => $model, 'imagedata' => $imagedata,
            ]);
            }
        } else {
            Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');

            return $this->redirect(['site/index']);
        }
    }

    /**
     * Deletes an existing AdvertHome model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param string $id
     *
     * @return mixed
     */
    public function actionDelete($id)
    {
        $access = AuthItem::AuthitemCheck('3', '25');
        if (yii::$app->user->can($access)) {
            $this->findModel($id)->delete();
            $user = Image::deleteAll('module_type = :status AND item_id = :id', [':id' => $id, ':status' => 'home_ads']);
            Yii::$app->session->setFlash('success', 'Home ads deleted successfully!');

            return $this->redirect(['index']);
        } else {
            Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');

            return $this->redirect(['site/index']);
        }
    }

    /**
     * Finds the AdvertHome model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param string $id
     *
     * @return AdvertHome the loaded model
     *
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AdvertHome::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
