<?php

namespace admin\controllers;

use admin\models\Events;
use admin\models\EventsSearch;
use common\models\EventItemlink;
use frontend\models\EventInvitees;
use Yii;
use yii\base\Model;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use arturoliveira\ExcelView;
use admin\models\Customer;
use admin\models\AuthItem;
use admin\models\AddressType;
use admin\models\AddressQuestion;
use admin\models\CustomerSearch;
use common\models\City;
use common\models\Country;
use common\models\Location;
use common\models\CustomerAddress;
use common\models\CustomerAddressResponse;
use common\models\CustomerCart;
use common\models\Order;
use common\models\Suborder;
use common\models\SuborderItemPurchase;

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
        $access = AuthItem::AuthitemCheck('4', '26');
        
        if (yii::$app->user->can($access)) {
            
            $searchModel = new EventsSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            $count = $dataProvider->getTotalCount();

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'count' => $count,
            ]);

        } else {
            
            Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');

            return $this->redirect(['site/index']);
        }
    }

    public function actionAddress_delete()
    {
        $access = AuthItem::AuthitemCheck('4', '26');
        
        if (yii::$app->user->can($access)) {
          
          $address_id = yii::$app->request->post('address_id');

          CustomerAddressResponse::deleteAll('address_id = ' . $address_id);
          CustomerAddress::deleteAll('address_id = ' . $address_id);
        }
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
        return $this->render('view', [
            'model' => $this->findModel($id)
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
        $access = AuthItem::AuthitemCheck('2', '26');
        if (yii::$app->user->can($access)) {
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
        } else {
            Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');
            return $this->redirect(['site/index']);
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
        $access = AuthItem::AuthitemCheck('3', '26');
        if (yii::$app->user->can($access)) {

            //Event Item Link
            EventItemlink::deleteAll(['event_id' => $id]);
            EventInvitees::deleteAll(['event_id' => $id]);

            if ($this->findModel($id)->delete()) {
                Yii::$app->session->setFlash('success', 'Event deleted successfully!');
                return $this->redirect(['index']);
            }

        } else {

            Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');
            return $this->redirect(['site/index']);
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
