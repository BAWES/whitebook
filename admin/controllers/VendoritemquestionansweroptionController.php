<?php

namespace admin\controllers;

use Yii;
use common\models\Vendoritemquestionansweroption;
use common\models\VendoritemquestionansweroptionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Vendoritemquestion;
use yii\helpers\ArrayHelper;

/**
 * VendoritemquestionansweroptionController implements the CRUD actions for Vendoritemquestionansweroption model.
 */
class VendoritemquestionansweroptionController extends Controller
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
                    'delete' => ['post'],
                ],

            ],
        ];
    }

    /**
     * Lists all Vendoritemquestionansweroption models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new VendoritemquestionansweroptionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Vendoritemquestionansweroption model.
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
     * Creates a new Vendoritemquestionansweroption model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Vendoritemquestionansweroption();
        $question = Vendoritemquestion::find()->select(['question_id', 'question_text'])->all();
        $questions = ArrayHelper::map($question, 'question_id', 'question_text');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model, 'questions' => $questions,
            ]);
        }
    }

    /**
     * Updates an existing Vendoritemquestionansweroption model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param string $id
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $question = Vendoritemquestion::find()->select(['question_id', 'question_text'])->all();
        $questions = ArrayHelper::map($question, 'question_id', 'question_text');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model, 'questions' => $questions,
            ]);
        }
    }

    /**
     * Deletes an existing Vendoritemquestionansweroption model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param string $id
     *
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Vendoritemquestionansweroption model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param string $id
     *
     * @return Vendoritemquestionansweroption the loaded model
     *
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Vendoritemquestionansweroption::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionDeletequestionoptions()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
        }
        if ($data['option'] != '') {
            $command = Vendoritemquestionansweroption::deleteAll('answer_id='.$data['option']);
            if ($command) {
                echo 'Option deleted successfully';
                die;
            }
        }
    }
}
