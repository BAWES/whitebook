<?php

namespace admin\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use admin\models\AccessControlList;
use admin\models\AddressType;
use admin\models\AddressQuestion;
use admin\models\AddressQuestionSearch;
use common\models\CustomerAddressResponse;

/**
* AddressquestionController implements the CRUD actions for AddressQuestion model.
*/
class AddressQuestionController extends Controller
{
    public function init()
    {
        parent::init();

        if (Yii::$app->user->isGuest) { 
            $url = Yii::$app->urlManager->createUrl(['site/login']);
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
                //   'delete' => ['POST'],
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
        $request = Yii::$app->request;

        $sort = $request->post('sort_val');
        $ques_id = $request->post('ques_id');

        $command = AddressQuestion::updateAll(
            ['sort' => $sort],
            'ques_id= '.$ques_id
        );

        if ($command) {
            Yii::$app->session->setFlash('success', 'Questions sort order updated successfully!');
            echo 1;
        } else {
            echo 0;
        }
    }

    public function actionBlock()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
        }

        $status = ($data['status'] == 'Active' ? 'Deactive' : 'Active');
        
        $command = AddressQuestion::updateAll(['status' => $status],'ques_id= '.$data['cid']);
        
        if ($status == 'Active') {
            return \yii\helpers\Url::to('@web/uploads/app_img/active.png');
        } else {
            return \yii\helpers\Url::to('@web/uploads/app_img/inactive.png');
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
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            
            Yii::$app->session->setFlash('success', 'Address Question created successfully!');

            return $this->redirect(['index']);
        
        } else {
            
            $addresstype = AddressType::loadAddresstype();

            return $this->render('create', [
                'model' => $model, 
                'addresstype' => $addresstype,
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
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            
            Yii::$app->session->setFlash('success', 'Address Question updated successfully!');
            
            return $this->redirect(['index']);

        } else {

            $addresstype = AddressType::loadAddress();

            return $this->render('update', [
                'model' => $model, 
                'addresstype' => $addresstype, 
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

        //delete related data 
        CustomerAddressResponse::deleteAll(['address_type_question_id' => $id]);

        Yii::$app->session->setFlash('success', 'Address Question Deleted successfully!');

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
