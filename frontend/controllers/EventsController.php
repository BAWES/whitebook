<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Eventinvitees;
use frontend\models\EventinviteesSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use frontend\models\Users;
use frontend\models\Vendoritem;
use common\models\Events;

use yii\db\Query;
use yii\helpers\Arrayhelper;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\Controller;
use arturoliveira\ExcelView;
use common\models\Country;
use common\models\Location;
use common\models\CustomerAddress;
use common\models\CustomerAddressResponse;
use common\models\Siteinfo;
use common\models\Featuregroupitem;
use common\models\LoginForm;
use common\models\Vendor;
use common\models\City;
use frontend\models\Website;
use frontend\models\Eventitemlink;
use frontend\models\Wishlist;
use frontend\models\Addresstype;
use frontend\models\AddressQuestion;
use frontend\models\Customer;
use frontend\models\Themes;
use common\models\VendorItemToCategory;
use common\models\Vendoritemthemes;

/**
 * EventinviteesController implements the CRUD actions for Eventinvitees model.
 */
class EventsController extends BaseController
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

    public function actionIndex($type = '', $events ='', $thingsilike ='')
    {
        if (Yii::$app->user->isGuest) {
            Yii::$app->session->set('show_login_modal', 1);//to display login modal
            return $this->goHome();
        }

        $request = Yii::$app->request;

        $events = ($request->get('slug') == 'events' ? 'active' : '');
        $thingsilike = ($request->get('slug') ==  'thingsilike' ?  'active' : '');

        $customer_id = Yii::$app->user->getId();

        $website_model = new Website();
        $event_type = $website_model->get_event_types();

        $model = new Users();
        $event_limit = 8;
        $wish_limit = 6;
        $offset = 0;

        $customer_events = Events::find()
            ->select(['{{%events}}.*'])
            ->INNERJOIN('{{%event_type}}', '{{%event_type}}.type_name = {{%events}}.event_type')
            ->where(['{{%events}}.customer_id'=>$customer_id])
            ->andwhere(['{{%event_type}}.trash'=>'Default'])
            ->orderby(['{{%events}}.event_date' => SORT_ASC])
            ->limit($event_limit)
            ->offset($offset)
            ->asArray()
            ->all();

        $price = $vendor = $avail_sale = $theme = '';


        $themes = $model->get_themes();
        $customer_unique_events = $website_model->getCustomerEvents($customer_id);
        $customer_event_type = $website_model->get_user_event_types($customer_id);

        $wishlist = Wishlist::find()
            ->where(['customer_id' => $customer_id])
            ->all();

        $arr_item_id = Arrayhelper::map($wishlist, 'item_id', 'item_id');

        $categorylist = VendorItemToCategory::find()
            ->select('{{%category}}.category_id, {{%category}}.category_name, {{%category}}.category_name_ar')
            ->joinWith('category')
            ->where(['IN', '{{%vendor_item_to_category}}.item_id', $arr_item_id])
            ->asArray()
            ->all();

        $vendorlist = Vendoritem::find()
            ->select('{{%vendor}}.vendor_name, {{%vendor}}.vendor_name_ar, {{%vendor}}.vendor_id')
            ->joinWith('vendor')
            ->where(['IN', '{{%vendor_item}}.item_id', $arr_item_id])
            ->asArray()
            ->all();

        $themelist =  Vendoritemthemes::find()
            ->select('{{%theme}}.theme_id, {{%theme}}.theme_name, {{%theme}}.theme_name_ar')
            ->joinWith('themeDetail')
            ->where(['trash' => 'default'])
            ->where(['IN', '{{%vendor_item_theme}}.item_id', $arr_item_id])
            ->asArray()
            ->all();

        $avail_sale = $category_id = $vendor = $theme = '';

        $customer_wishlist = $model->get_customer_wishlist(
            $customer_id, $wish_limit, $offset, $category_id, $price, $vendor, $avail_sale, $theme);

        $customer_wishlist_count = $model->get_customer_wishlist_count(
            $customer_id, $category_id, $price, $vendor, $avail_sale, $theme);

        $user_events = Events::find()
            ->where(['customer_id' => Yii::$app->user->identity->customer_id])
            ->asArray()
            ->all();

        return $this->render('index', [
            'event_type' => $event_type,
            'customer_event_type' => $customer_event_type,
            'customer_events' => $customer_events,
            'customer_wishlist' => $customer_wishlist,
            'customer_wishlist_count' => $customer_wishlist_count,
            'vendor' => $vendor,
            'customer_unique_events' => $customer_unique_events,
            'categorylist' => $categorylist,
            'vendorlist' => $vendorlist,
            'themelist' => $themelist,
            'slug' => 'events',
            'events' => $events,
            'thingsilike' => $thingsilike,
        ]);
    }

//    public function actionIndex()
//    {
//        if (Yii::$app->request->isAjax) {
//            $data = Yii::$app->request->post();
//
//            $searchModel = new EventinviteesSearch();
//
//            $dataProvider = $searchModel->search(
//                Yii::$app->request->queryParams,
//                $data['search_val'],
//                $data['event_id']
//            );
//
//            return $this->renderPartial('/users/invitee_search_details', [
//              'searchModel' => $searchModel,
//              'dataProvider' => $dataProvider,
//            ]);
//        }
//    }

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
        if (!Yii::$app->request->isAjax) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $data = Yii::$app->request->post();
        
        $exist = Eventinvitees::find()
            ->select('invitees_id')
            ->where([
                'event_id' => $data['event_id'],
                'email' => $data['email']
            ])
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

        } else {
              echo 2;
        }
    }

    public function actionUpdateinvitees()
    {
        if (!Yii::$app->request->isAjax) {
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        }

        $data = Yii::$app->request->post();

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
    }

    public function actionInviteedetails()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $event_invite = Eventinvitees::find()->where(['invitees_id'=>$data['id']]);
            return json_encode($details[0]);
        }
    }

    public function actionAddevent()
    {
        if (!Yii::$app->request->isAjax) {
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        }
      
        $data = Yii::$app->request->post();

        $model = Vendoritem::find()
            ->select(['{{%vendor_item}}.item_id','{{%vendor_item}}.item_price_per_unit','{{%vendor_item}}.item_name','{{%vendor}}.vendor_name',
                '{{%image}}.image_path'])
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
