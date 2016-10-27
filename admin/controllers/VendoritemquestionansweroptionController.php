<?php

namespace admin\controllers;

use Yii;
use common\models\VendorItemQuestionAnswerOption;
use common\models\VendoritemquestionansweroptionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\VendorItemQuestion;
use yii\helpers\ArrayHelper;
use admin\models\AccessControlList;

/**
 * VendoritemquestionansweroptionController implements the CRUD actions for VendorItemQuestionAnswerOption model.
 */
class VendoritemquestionansweroptionController extends Controller
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
     * Lists all VendorItemQuestionAnswerOption models.
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
     * Displays a single VendorItemQuestionAnswerOption model.
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
     * Creates a new VendorItemQuestionAnswerOption model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new VendorItemQuestionAnswerOption();
        $question = VendorItemQuestion::find()->select(['question_id', 'question_text'])->all();
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
     * Updates an existing VendorItemQuestionAnswerOption model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param string $id
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $question = VendorItemQuestion::find()->select(['question_id', 'question_text'])->all();
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
     * Deletes an existing VendorItemQuestionAnswerOption model.
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
     * Finds the VendorItemQuestionAnswerOption model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param string $id
     *
     * @return VendorItemQuestionAnswerOption the loaded model
     *
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = VendorItemQuestionAnswerOption::findOne($id)) !== null) {
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
            $command = VendorItemQuestionAnswerOption::deleteAll('answer_id='.$data['option']);
            if ($command) {
                echo 'Option deleted successfully';
                die;
            }
        }
    }
}
