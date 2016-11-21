<?php

namespace frontend\controllers;

use Yii;
use yii\db\Expression;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use common\models\Events;
use frontend\models\EventInvitees;
use frontend\models\EventInviteesSearch;
use frontend\models\Users;
use frontend\models\VendorItem;

/**
 * EventInviteesController implements the CRUD actions for EventInvitees model.
 */
class EventInviteesController extends BaseController
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
     * Lists all EventInvitees models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();

            $searchModel = new EventInviteesSearch();

            $dataProvider = $searchModel->search(
                Yii::$app->request->queryParams,
                $data['search_val'], 
                $data['event_id']
            );

            return $this->renderPartial('/users/invitee_search_details', [
              'searchModel' => $searchModel,
              'dataProvider' => $dataProvider,
            ]);
        }
    }

    /**
     * Displays a single EventInvitees model.
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
     * Creates a new EventInvitees model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new EventInvitees();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->invitees_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing EventInvitees model.
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
     * Deletes an existing EventInvitees model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function actionDelete($id)
    {
        if (Yii::$app->request->isAjax) {
            return $this->findModel($id)->delete();
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the EventInvitees model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param int $id
     *
     * @return EventInvitees the loaded model
     *
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = EventInvitees::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionUpdateinvitees()
    {
        if (!Yii::$app->request->isAjax) {
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        }

        $data = Yii::$app->request->post();

        $event_invite = EventInvitees::findOne($data['invitees_id']);
        $event_invite->name = $data['name'];
        $event_invite->email = $data['email'];
        $event_invite->phone_number = $data['phone_number'];
        $event_invite->save();
        
        if ($event_invite) {
            echo 'done';
        } else {
            echo 'not';
        }
    }

    public function actionInviteedetails()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $event_invite = EventInvitees::find()->where(['invitees_id'=>$data['id']]);
            return json_encode($details[0]);
        }
    }

    public function actionAddevent()
    {
        if (!Yii::$app->request->isAjax) {
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        }
      
        $data = Yii::$app->request->post();

        $model = VendorItem::find()
            ->select(['{{%vendor_item}}.item_id','{{%vendor_item}}.item_price_per_unit','{{%vendor_item}}.item_name','{{%vendor}}.vendor_name', '{{%image}}.image_path'])
            ->leftJoin('{{%image}}', '{{%vendor_item}}.item_id = {{%image}}.item_id')
            ->leftJoin('{{%vendor}}', '{{%vendor}}.vendor_id = {{%vendor_item}}.vendor_id')
            ->andwhere(['{{%vendor_item}}.item_id' => $data['item_id']])
            ->asArray()
            ->all();
            
        $customer_events = Events::find()
            ->where(['customer_id' => Yii::$app->user->identity->customer_id])
            ->asArray()
            ->all();

        return $this->renderPartial('/product/add_event', array(
            'model' => $model, 
            'customer_events' => $customer_events
        ));
    }
}
