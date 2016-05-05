<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Eventinvitees;
use frontend\models\EventinviteesSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use frontend\models\Users;

/**
 * EventinviteesController implements the CRUD actions for Eventinvitees model.
 */
class EventinviteesController extends BaseController
{
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
     * Lists all Eventinvitees models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();

            $searchModel = new EventinviteesSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $data['search_val'], $data['event_id']);

            return $this->renderPartial('/users/invitee_search_details', [
              'searchModel' => $searchModel,
              'dataProvider' => $dataProvider,
            ]);
        }
    }

    /**
     * Displays a single Eventinvitees model.
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
     * Creates a new Eventinvitees model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Eventinvitees();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->invitees_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Eventinvitees model.
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
            return $this->redirect(['view', 'id' => $model->invitees_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Eventinvitees model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function actionDelete($id)
    {
        if (Yii::$app->request->isAjax) {
            //echo $id; die;
            return $this->findModel($id)->delete();
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Eventinvitees model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param int $id
     *
     * @return Eventinvitees the loaded model
     *
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Eventinvitees::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionAddinvitees()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
        }
        $exist = Eventinvitees::find()->select('invitees_id')->where(['event_id ='.$data['event_id']])
        ->andWhere(['email'=>$data['email']])
        ->count();
        // Check count
         if ($exist == 0) {
                $event_invite = new Eventinvitees;
                $event_invite->name = $data['name'];
                $event_invite->email = $data['email'];
                $event_invite->event_id = $data['event_id'];
                $event_invite->customer_id = Yii::$app->user->identity->customer_id;
                $event_invite->phone_number = $data['phone_number'];
                $eventinvitees->save();
              if ($insert) {
                  $customer_info = Users::get_user_details(Yii::$app->user->identity->customer_id);
                  $to = $data['email'];
                  $message = 'Hi '.$data['name'].',<br/><br/> '.$customer_info[0]['customer_name'].' is invite you '.$data['event_name'].' event ';
                  $subject = 'Event Invitation';
                  $content = 'test';              
              } else {
                  echo 'not';
              }
          } else {
              echo 2;
              die;
          }
    }

    public function actionUpdateinvitees()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
        }
        $event_invite = Eventinvitees::findOne($data['invitees_id']);
        $event_invite->name = $data['name'];
        $event_invite->email = $data['email'];
        $event_invite->phone_number = $data['phone_number'];
        $event_invite->save();
        if ($event_invite) {
            echo 'done';
        } else {
            echo 'not';
        }
        die;
    }

    public function actionInviteedetails()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $event_invite = Eventinvitees::find()->where(['invitees_id'=>$data['id']]);
            return json_encode($details[0]);
        }
    }
}
