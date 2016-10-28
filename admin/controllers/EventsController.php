<?php

namespace admin\controllers;

use admin\models\Events;
use admin\models\EventsSearch;
use common\models\EventItemlink;
use frontend\models\EventInvitees;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use admin\models\Customer;
use admin\models\AuthItem;
use common\models\CustomerAddress;
use common\models\CustomerAddressResponse;
use yii\data\ArrayDataProvider;

/**
 * CustomerController implements the CRUD actions for Customer model.
 */
class EventsController extends Controller
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
                    //'delete' => ['post'],
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
                       'actions' => ['create','update', 'index', 'view', 'delete'],
                       'allow' => true,
                       'roles' => ['@'],
                   ],
               ],
            ],
        ];
    }

    /**
     * Lists all Customer models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new EventsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $count = $dataProvider->getTotalCount();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'count' => $count,
        ]);
    }

    public function actionAddress_delete()
    {
        $address_id = yii::$app->request->post('address_id');

        CustomerAddressResponse::deleteAll('address_id = ' . $address_id);
        CustomerAddress::deleteAll('address_id = ' . $address_id);
    }


    /**
     * Displays a single Customer model.
     *
     * @param string $id
     *
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        $providerItems = new ArrayDataProvider([
            'allModels' => $model->items,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        $providerInvitees = new ArrayDataProvider([
            'allModels' => $model->invitees,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'providerItems' => $providerItems,
            'providerInvitees' => $providerInvitees
        ]);
    }

    /**
     * Updates an existing Customer model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param string $id
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
            $model->save();
            Yii::$app->session->setFlash('success', 'Event detail updated successfully!');
            Yii::info('[Event Updated] Admin updated Event '.$model->event_name.' information', __METHOD__);
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
              'model' => $model
            ]);
        }
    }

    
      /**
       * Deletes an existing Customer model.
       * If deletion is successful, the browser will be redirected to the 'index' page.
       *
       * @param string $id
       *
       * @return mixed
       */
    public function actionDelete($id)
    {
        //Event Item Link
        EventItemlink::deleteAll(['event_id' => $id]);
        EventInvitees::deleteAll(['event_id' => $id]);

        if ($this->findModel($id)->delete()) {
            Yii::$app->session->setFlash('success', 'Event deleted successfully!');
            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the Customer model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param string $id
     *
     * @return Customer the loaded model
     *
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Events::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
