<?php

namespace admin\controllers;

use Yii;
use admin\models\Faq;
use admin\models\AuthItem;
use admin\models\FaqSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use admin\models\FaqGroup;

/**
 * FaqController implements the CRUD actions for Faq model.
 */
class FaqController extends Controller
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
        $access = AuthItem::AuthitemCheck('4', '15');
        
        if (yii::$app->user->can($access)) {
            $searchModel = new FaqSearch();
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
        $access = AuthItem::AuthitemCheck('1', '15');
        if (yii::$app->user->can($access)) {
            $model = new Faq();
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
				
				$max_sort = Faq::find()
                ->select('max(sort) as sort')
				->where(['trash' => 'default'])
				->one();
                $sort = ($max_sort['sort'] + 1);
                $model->sort = $sort;
                $model->save();
                Yii::$app->session->setFlash('success', 'FAQ Created successfully!');

                return $this->redirect(['index']);
            } else {
                
                $faq_group_drdwn = array();

                $query = FaqGroup::find()->all();

                foreach ($query as $row) {
                    $faq_group_drdwn[$row->faq_group_id] = $row['group_name'];
                }

                return $this->render('create', [
                    'model' => $model,
                    'faq_group_drdwn' => $faq_group_drdwn
                ]);
            }
        } else {
            Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');

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
        $access = AuthItem::AuthitemCheck('2', '15');
        if (yii::$app->user->can($access)) {
            $model = $this->findModel($id);
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                Yii::$app->session->setFlash('success', 'FAQ Updated successfully!');

                return $this->redirect(['index']);
            } else {

                $faq_group_drdwn = array();

                $query = FaqGroup::find()->all();

                foreach ($query as $row) {
                    $faq_group_drdwn[$row->faq_group_id] = $row['group_name'];
                }

                return $this->render('update', [
                    'model' => $model,
                    'faq_group_drdwn' => $faq_group_drdwn
                ]);
            }
        } else {
            Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');

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
        $access = AuthItem::AuthitemCheck('3', '15');
        if (yii::$app->user->can($access)) {
            $model = $this->findModel($id);
            $model->trash = 'Deleted';
            $model->load(Yii::$app->request->post());
            $model->save();

            Yii::$app->session->setFlash('success', 'FAQ Deleted successfully!');

            return $this->redirect(['index']);
        } else {
            Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');

            return $this->redirect(['site/index']);
        }
    }

    public function actionSort_faq()
    {
        $request = Yii::$app->request;

        $sort = $request->post('sort_val');
        $faq_id = $request->post('faq_id');

        $command=Faq::updateAll(['sort' => $sort],'faq_id= '.$faq_id);

        if ($command) {
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
        
        $command=Faq::updateAll(['faq_status' => $status],'faq_id= '.$data['id']);
        
        if ($status == 'Active') {
            return \yii\helpers\Url::to('@web/uploads/app_img/active.png');
        }

        return \yii\helpers\Url::to('@web/uploads/app_img/inactive.png');
    }
}
