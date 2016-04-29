<?php

namespace admin\controllers;

use Yii;
use admin\models\Admin;
use admin\models\Authitem;
use admin\models\AdminSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
* AdminController implements the CRUD actions for Admin model.
*/
class AdminController extends Controller
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
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['create', 'update', 'index', 'view', 'delete', 'block', 'galleryitem'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    //    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
    * Lists all Admin models.
    *
    * @return mixed
    */
    public function actionIndex()
    {
        $access = Authitem::AuthitemCheck('4', '9');
        if (yii::$app->user->can($access)) {
            $searchModel = new AdminSearch();
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
    * Displays a single Admin model.
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
    * Creates a new Admin model.
    * If creation is successful, the browser will be redirected to the 'view' page.
    *
    * @return mixed
    */
    public function actionCreate()
    {
        $access = Authitem::AuthitemCheck('1', '9');
        if (yii::$app->user->can($access)) {
            $model = new Admin();
            $role = Admin::roles();

            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                $model->admin_password = Yii::$app->getSecurity()->generatePasswordHash($model->admin_password);
                $model->save();
                echo Yii::$app->session->setFlash('success', 'New admin user created successfully!');

                return $this->redirect(['index']);
            } else {
                return $this->render('create', [
                    'model' => $model, 'role' => $role,
                ]);
            }
        } else {
            echo Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');

            return $this->redirect(['site/index']);
        }
    }

    /**
    * Updates an existing Admin model.
    * If update is successful, the browser will be redirected to the 'view' page.
    *
    * @param string $id
    *
    * @return mixed
    */
    public function actionUpdate($id)
    {
        $access = Authitem::AuthitemCheck('2', '9');
        if (yii::$app->user->can($access)) {
            $model = $this->findModel($id);
            $role = Admin::roles();
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                echo Yii::$app->session->setFlash('success', 'New admin user updated successfully!');

                return $this->redirect(['index']);
            } else {
                return $this->render('update', [
                    'model' => $model, 'role' => $role,
                ]);
            }
        } else {
            echo Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');

            return $this->redirect(['site/index']);
        }
    }

    /**
    * Deletes an existing Admin model.
    * If deletion is successful, the browser will be redirected to the 'index' page.
    *
    * @param string $id
    *
    * @return mixed
    */
    public function actionDelete($id)
    {
        $access = Authitem::AuthitemCheck('3', '9');
        if (yii::$app->user->can($access)) {
            $this->findModel($id)->delete();
            echo Yii::$app->session->setFlash('success', 'Admin user deleted successfully!');

            return $this->redirect(['index']);
        } else {
            echo Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');

            return $this->redirect(['site/index']);
        }
    }

    /**
    * Finds the Admin model based on its primary key value.
    * If the model is not found, a 404 HTTP exception will be thrown.
    *
    * @param string $id
    *
    * @return Admin the loaded model
    *
    * @throws NotFoundHttpException if the model cannot be found
    */
    protected function findModel($id)
    {
        if (($model = Admin::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionGalleryitem()
    {
        $model = new admin();
        if ($model->load(Yii::$app->request->post())) {
            $model->attachImage($_FILES['Admin']['tmp_name']['file'][0]);
            $images = $model->getImages();
            foreach ($images as $img) {
                //retun url to full image
                $img->getUrl();

                //return url to proportionally resized image by width
                $img->getUrl('300x');

                //return url to proportionally resized image by height
                $img->getUrl('x300');

                //return url to resized and cropped (center) image by width and height
                $img->getUrl('200x300');
            }
            $image = $model->getImage();
            var_dump($image);
            die;

            if ($image) {
                //get path to resized image
                echo $image->getPath('400x300');
                die;
                //path to original image
                $image->getPathToOrigin();

                //will remove this image and all cache files
                $model->removeImage($image);
            }
        } else {
            return $this->render('gallery', ['model' => $model]);
        }
    }
}
