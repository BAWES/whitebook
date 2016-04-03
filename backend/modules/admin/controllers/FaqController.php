<?php

namespace backend\modules\admin\controllers;

use Yii;
use common\models\Faq;
use common\models\Authitem;
use common\models\FaqSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * FaqController implements the CRUD actions for Faq model.
 */
class FaqController extends Controller
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
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                  //  'delete' => ['post'],
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
                       'actions' => ['create', 'update', 'index', 'view', 'delete', 'block', 'sort_faq'],
                       'allow' => true,
                       'roles' => ['@'],
                   ],
               ],
            ],
        ];
    }

    /**
     * Lists all Faq models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $access = Authitem::AuthitemCheck('4', '15');
        if (yii::$app->user->can($access)) {
            $searchModel = new FaqSearch();
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
     * Displays a single Faq model.
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
     * Creates a new Faq model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $access = Authitem::AuthitemCheck('1', '15');
        if (yii::$app->user->can($access)) {
            $model = new Faq();
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                $max_sort = $model->findBysql("SELECT MAX(`sort`) as sort FROM `whitebook_faq` where trash = 'Default'")->asArray()->all();
                $sort = ($max_sort[0]['sort'] + 1);
                $model->sort = $sort;
                $model->save();
                echo Yii::$app->session->setFlash('success', 'FAQ Created successfully!');

                return $this->redirect(['index']);
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
     * Updates an existing Faq model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $access = Authitem::AuthitemCheck('2', '15');
        if (yii::$app->user->can($access)) {
            $model = $this->findModel($id);
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                echo Yii::$app->session->setFlash('success', 'FAQ Updated successfully!');

                return $this->redirect(['index']);
            } else {
                return $this->render('update', [
                'model' => $model,
            ]);
            }
        } else {
            echo Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');

            return $this->redirect(['site/index']);
        }
    }

    /**
     * Deletes an existing Faq model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function actionDelete($id)
    {
        $access = Authitem::AuthitemCheck('3', '15');
        if (yii::$app->user->can($access)) {
            $model = $this->findModel($id);
            $model->trash = 'Deleted';
            $model->load(Yii::$app->request->post());
            $model->save();

            echo Yii::$app->session->setFlash('success', 'FAQ Deleted successfully!');

            return $this->redirect(['index']);
        } else {
            echo Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');

            return $this->redirect(['site/index']);
        }
    }

    public function actionSort_faq()
    {
        $sort = $_POST['sort_val'];
        $faq_id = $_POST['faq_id'];
        $command = \Yii::$app->DB->createCommand(
        'UPDATE whitebook_faq SET sort="'.$sort.'" WHERE faq_id='.$faq_id);

        if ($command->execute()) {
            Yii::$app->session->setFlash('success', 'FAQ sort order updated successfully!');
            echo 1;
            exit;
        } else {
            echo 0;
            exit;
        }
    }

    /**
     * Finds the Faq model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param int $id
     *
     * @return Faq the loaded model
     *
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Faq::findOne($id)) !== null) {
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
        $command = \Yii::$app->db->createCommand('UPDATE whitebook_faq SET faq_status="'.$status.'" WHERE faq_id='.$data['id']);
        $command->execute();
        if ($status == 'Active') {
            return \yii\helpers\Url::to('@web/uploads/app_img/active.png');
        }

        return \yii\helpers\Url::to('@web/uploads/app_img/inactive.png');
    }
}
