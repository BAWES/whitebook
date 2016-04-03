<?php

namespace admin\controllers;

use Yii;
use common\models\Addresstype;
use common\models\AddressQuestion;
use common\models\AddressQuestionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * AddressquestionController implements the CRUD actions for AddressQuestion model.
 */
class AddressquestionController extends Controller
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
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                   // 'delete' => ['post'],
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
                       'actions' => ['create', 'update', 'index', 'view', 'delete', 'sort_addressquestion', 'block'],
                       'allow' => true,
                       'roles' => ['@'],
                   ],
               ],
           ],
        ];
    }

    /**
     * Lists all AddressQuestion models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AddressQuestionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionSort_addressquestion()
    {
        $sort = $_POST['sort_val'];
        $ques_id = $_POST['ques_id'];
        $command = \Yii::$app->DB->createCommand(
        'UPDATE whitebook_address_question SET sort="'.$sort.'" WHERE ques_id='.$ques_id);

        if ($command->execute()) {
            Yii::$app->session->setFlash('success', 'Questions sort order updated successfully!');
            echo 1;
            exit;
        } else {
            echo 0;
            exit;
        }
    }

    public function actionBlock()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
        }
        $status = ($data['status'] == 'Active' ? 'Deactive' : 'Active');
        $command = \Yii::$app->db->createCommand('UPDATE whitebook_address_question SET status="'.$status.'" WHERE ques_id='.$data['cid']);
        $command->execute();
        if ($status == 'Active') {
            return \Yii::$app->params['appImageUrl'].'active.png';
        } else {
            return \Yii::$app->params['appImageUrl'].'inactive.png';
        }
    }

    /**
     * Displays a single AddressQuestion model.
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
     * Creates a new AddressQuestion model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AddressQuestion();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            foreach ($model->question as $ques) {
                if ($ques) {
                    $models = new AddressQuestion();
                    $models->address_type_id = $model->address_type_id;
                    $models->question = $ques;
                    $models->save();
                }
            }
            echo Yii::$app->session->setFlash('success', 'Address Question created successfully!');

            return $this->redirect(['index']);
        } else {
            $Addresstype = Addresstype::loadAddresstype();

            return $this->render('create', [
                'model' => $model, 'Addresstype' => $Addresstype,
            ]);
        }
    }

    /**
     * Updates an existing AddressQuestion model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $Addresstype = Addresstype::loadAddress();
        $Addressquestion = AddressQuestion::loadAddressquestion($model->address_type_id);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $i = 0;
            foreach ($model->question as $ques) {
                if ($ques && $i < count($Addressquestion)) {
                    $model->address_type_id;
                    $model->question[$i];
                    $command = \Yii::$app->db->createCommand('UPDATE whitebook_address_question SET question="'.$model->question[$i].'",
					 address_type_id="'.$model->address_type_id.'"  WHERE ques_id="'.$Addressquestion[$i]['ques_id'].'"');
                    $command->execute();
                } elseif ($ques) {
                    $models = new AddressQuestion();
                    $models->address_type_id = $model->address_type_id;
                    $models->question = $ques;
                    $models->save();
                }
                ++$i;
            }
            echo Yii::$app->session->setFlash('success', 'Address Question updated successfully!');

            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model, 'Addresstype' => $Addresstype, 'Addressquestion' => $Addressquestion,
            ]);
        }
    }

        /**
         * Deletes an existing AddressQuestion model.
         * If deletion is successful, the browser will be redirected to the 'index' page.
         *
         * @param int $id
         *
         * @return mixed
         */
        public function actionDelete($id)
        {
            $model = $this->findModel($id);
            $model->trash = 'Deleted';
            $model->load(Yii::$app->request->post());
            $model->save();  // equivalent to $model->update();
        echo Yii::$app->session->setFlash('success', 'Address Question Deleted successfully!');

            return $this->redirect(['index']);
        }

    /**
     * Finds the AddressQuestion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param int $id
     *
     * @return AddressQuestion the loaded model
     *
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AddressQuestion::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
