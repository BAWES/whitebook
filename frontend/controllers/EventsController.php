<?php

namespace frontend\controllers;

use Yii;
use yii\db\Query;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\Response;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\helpers\Arrayhelper;
use yii\web\NotFoundHttpException;
use arturoliveira\ExcelView;
use common\models\City;
use common\models\Events;
use common\models\Vendor;
use common\models\Country;
use common\models\Location;
use common\models\Siteinfo;
use common\models\LoginForm;
use common\models\CategoryNote;
use common\models\CustomerAddress;
use common\models\VendorItemThemes;
use common\models\FeatureGroupItem;
use common\models\VendorItemToCategory;
use common\models\CustomerAddressResponse;
use frontend\models\Users;
use frontend\models\Themes;
use frontend\models\Website;
use frontend\models\Wishlist;
use frontend\models\Customer;
use frontend\models\VendorItem;
use frontend\models\AddressType;
use frontend\models\EventItemlink;
use frontend\models\EventInvitees;
use frontend\models\AddressQuestion;
use frontend\models\EventInviteesSearch;

/**
 * EventinviteesController implements the CRUD actions for EventInvitees model.
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
     * Lists all EventInvitees models.
     *
     * @return mixed
     */

    public function actionIndex($type = '', $events ='', $thingsilike ='')
    {
        \Yii::$app->view->title = Yii::$app->params['SITE_NAME'].' | Events';
        \Yii::$app->view->registerMetaTag(['name' => 'description', 'content' => Yii::$app->params['META_DESCRIPTION']]);
        \Yii::$app->view->registerMetaTag(['name' => 'keywords', 'content' => Yii::$app->params['META_KEYWORD']]);

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

        $vendorlist = VendorItem::find()
            ->select('{{%vendor}}.vendor_name, {{%vendor}}.vendor_name_ar, {{%vendor}}.vendor_id')
            ->joinWith('vendor')
            ->where(['IN', '{{%vendor_item}}.item_id', $arr_item_id])
            ->asArray()
            ->all();

        $themelist =  VendorItemThemes::find()
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

        $provider = new \yii\data\ArrayDataProvider([
            'allModels' => $customer_events,
            'sort' => [
                //'attributes' => ['city_name', 'address_type_id', 'location'],
            ],
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('index', [
            'event_type' => $event_type,
            'customer_event_type' => $customer_event_type,
            'provider' => $provider,
            'customer_wishlist' => $customer_wishlist,
            'customer_wishlist_count' => $customer_wishlist_count,
            'vendor' => $vendor,
            'customer_unique_events' => $customer_unique_events,
            'categorylist' => $categorylist,
            'vendorlist' => $vendorlist,
            'themelist' => $themelist,
            'slug' => (isset($_REQUEST['slug'])) ? $_REQUEST['slug'] : 'events',
            'events' => $events,
            'thingsilike' => $thingsilike,
        ]);
    }

    public function actionDetail($slug)
    {
        if (Yii::$app->user->isGuest) {
            Yii::$app->session->set('show_login_modal', 1);//to display login modal
            return $this->goHome();
        }

        $event_details = Events::findOne(['customer_id' => Yii::$app->user->identity->customer_id, 'slug' => $slug]);

        \Yii::$app->view->title = Yii::$app->params['SITE_NAME'].' | '.$event_details->event_name;
        \Yii::$app->view->registerMetaTag(['name' => 'description', 'content' => Yii::$app->params['META_DESCRIPTION']]);
        \Yii::$app->view->registerMetaTag(['name' => 'keywords', 'content' => Yii::$app->params['META_KEYWORD']]);


        if (empty($event_details)) {
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        }

        $customer_events_list = Users::get_customer_wishlist_details(Yii::$app->user->identity->customer_id);

        $eventitem_details = EventItemlink::find()->select(['{{%event_item_link}}.item_id'])
            ->innerJoin('{{%vendor_item}}', '{{%vendor_item}}.item_id = {{%event_item_link}}.item_id')
            ->Where(['{{%vendor_item}}.item_status'=>'Active',
                '{{%vendor_item}}.trash'=>'Default',
                '{{%vendor_item}}.item_for_sale'=>'Yes',
                '{{%vendor_item}}.type_id'=>'2',
                '{{%event_item_link}}.event_id' => $event_details->event_id])
            ->asArray()
            ->all();

        $searchModel = new EventInviteesSearch();

        $dataProvider = $searchModel->loadsearch(Yii::$app->request->queryParams, $event_details->event_id);

        /* Load level 1 category */
        $cat_exist = \frontend\models\Category::find()
            ->where(['category_level' => 0, 'category_allow_sale' =>'Yes', 'trash' =>'Default'])
            ->orderBy(new \yii\db\Expression('FIELD (category_name, "Venues", "Invitations", "Food & Beverages", "Decor", "Supplies", "Entertainment", "Services", "Others", "Gift favors")'))
            ->asArray()
            ->all();

        return $this->render('detail', [
            'slug' => $slug,
            'event_details' => $event_details,
            'customer_events_list' => $customer_events_list,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'cat_exist'=>$cat_exist
        ]);
    }

    public function actionAddInvitee()
    {
        if (Yii::$app->request->isAjax) {

            $data = Yii::$app->request->post();

            if ($data['action'] == 'new') {
                $exist = EventInvitees::find()
                    ->select('invitees_id')
                    ->where([
                        'event_id' => $data['event_id'],
                        'email' => $data['email']
                    ])
                    ->count();

                // Check count
                if ($exist == 0) {

                    $event_invite = new EventInvitees;
                    $event_invite->name = $data['name'];
                    $event_invite->email = $data['email'];
                    $event_invite->event_id = $data['event_id'];
                    $event_invite->customer_id = Yii::$app->user->identity->customer_id;
                    $event_invite->phone_number = $data['phone_number'];
                    echo $event_invite->save(false);
                    exit;

                } else {
                    echo 2;
                }
            } else {
                $user = EventInvitees::findOne($data['invitees_id']);
                $user->name = $data['name'];
                $user->email = $data['email'];
                $user->phone_number = $data['phone_number'];
                echo $user->save(false);
            }
        }
    }

    public function actionDeleteInvitee($id)
    {
        $event = EventInvitees::findOne($id);
        $slug = $event->event->slug;
        $event->delete();
        Yii::$app->session->setFlash('success','Invitee Deleted Successfully');
        return $this->redirect(['events/detail','slug'=> $event->event->slug]);
    }

    public function actionInviteeDetails()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $event_invite = EventInvitees::find()->where(['invitees_id'=>$data['id']])->asArray()->one();
            return json_encode($event_invite);
        }
    }

    public function actionExport($id)
    {
        if (Yii::$app->user->isGuest) {
            Yii::$app->session->set('show_login_modal', 1);//to display login modal
            return $this->goHome();
        }
        $searchModel = new EventInviteesSearch();
        $dataProvider = $searchModel->loadsearch(Yii::$app->request->queryParams, $id);

        ExcelView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'filename' => 'Invites list',
            'fullExportType' => 'xls', //can change to html,xls,csv and so on
            'grid_mode' => 'export',
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'name',
                'email',
                'phone_number',
            ],
        ]);
    }

    public function actionEvent_slider()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        } else {
            return $this->renderPartial('events_slider');
        }
    }

    public function actionEventDetails()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $edit_eventinfo = Events::find()->where(['event_id' => $data['event_id']])->asArray()->all();
            return $this->renderPartial('edit_event', array('edit_eventinfo' => $edit_eventinfo));
        }
    }

    /**
     * Update events
     */
    public function actionUpdateEvent()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $request = Yii::$app->request;

        if ($request->post('event_name') && $request->post('event_type') && $request->post('event_date')) {
            $model = Events::findOne($request->post('event_id'));

            if ($model) {
                $model->event_name = $request->post('event_name');
                $model->event_date = date('Y-m-d', strtotime($request->post('event_date')));
                $model->event_type = $request->post('event_type');
                $model->no_of_guests = $request->post('no_of_guests');

                $string = str_replace(' ', '-', $request->post('event_name')); // Replaces all spaces with hyphens.
                $slug = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
                $model->slug = $slug.'-'.time();
                $model->save();

                return Url::to(['events/detail', 'slug' => $model->slug]);
            }
        }
    }

    /**
     * Insert items to events
     */
    public function actionAddEventItem()
    {
        if (Yii::$app->request->isAjax) {
            $request = Yii::$app->request;

            if ($request->post('event_id') && $request->post('item_id')) {

                $model = new Users();
                $event_id = $request->post('event_id');
                $item_id = $request->post('item_id');

                $item = VendorItem::findOne($item_id);

                if(Yii::$app->language == 'en') {
                    $item_name = $item->item_name;
                }else{
                    $item_name = $item->item_name_ar;
                }

                $event_name = Html::encode($request->post('event_name'));

                $customer_id = Yii::$app->user->identity->customer_id;
                $insert_item_to_event = $model->insert_item_to_event($item_id, $event_id);

                if ($insert_item_to_event == Events::EVENT_ADDED_SUCCESS) {

                    return json_encode([
                        'status' => Events::EVENT_ADDED_SUCCESS,
                        'message' => Yii::t('frontend', '{item_name} has been added to {event_name}',
                            [
                                'item_name' => $item_name,
                                'event_name' => $event_name,
                            ])
                    ]);

                } elseif ($insert_item_to_event == Events::EVENT_ALREADY_EXIST) {

                    return json_encode([
                        'status' => Events::EVENT_ALREADY_EXIST,
                        'message' => Yii::t('frontend', '{item_name} already exist with {event_name}',
                            [
                                'item_name' => $item_name,
                                'event_name' => $event_name,
                            ])
                    ]);
                }
            }
        }
    }

    /**
     * Create events
     */
    public function actionCreateEvent()
    {
        if (Yii::$app->request->isAjax) {

            $request = Yii::$app->request;

            if ($request->post('event_name') && $request->post('event_type') && $request->post('event_date')) {

                $event_name = $request->post('event_name');
                $event_date = $request->post('event_date');

                Yii::$app->session->set('event_name', $event_name);

                // Creating event start
                $customer_id = Yii::$app->user->identity->customer_id;
                $event_date1 = date('Y-m-d', strtotime($event_date));
                $string = str_replace(' ', '-', $event_name); // Replaces all spaces with hyphens.
                $slug = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
                $slug = $slug.'-'.time();
                $check = Events::find()
                    ->select('event_id')
                    ->where(['customer_id' => $customer_id, 'event_name' => $event_name])
                    ->asArray()
                    ->all();

                if (count($check) > 0) {
                    $result = Events::EVENT_ALREADY_EXIST;
                } else {
                    $event_modal = new Events;
                    $event_modal->customer_id = $customer_id;
                    $event_modal->event_name = $event_name;
                    $event_modal->event_date = $event_date1;
                    $event_modal->event_type = $request->post('event_type');
                    $event_modal->no_of_guests = $request->post('no_of_guests');
                    $event_modal->slug = $slug;
                    $event_modal->save();
                    $result = $event_modal->event_id;
                }

                // Creating event end

                if ($result == Events::EVENT_ALREADY_EXIST) {

                    return Events::EVENT_ALREADY_EXIST;

                } else {

                    if ($request->post('item_id') && ($request->post('item_id') > 0)) {

                        $item_id = $request->post('item_id');
                        $event_id = $event_modal->event_id;

                        $item = VendorItem::find()
                            ->where(['item_id' => $item_id])
                            ->one();

                        if(Yii::$app->language == 'en') {
                            Yii::$app->session->set('item_name', $item->item_name);
                        }else{
                            Yii::$app->session->set('item_name', $item->item_name_ar);
                        }
                        
                        $check = EventItemlink::find()
                            ->select(['link_id'])
                            ->where(['event_id' => $event_id])
                            ->andwhere(['item_id' => $item_id])
                            ->count();

                        if ($check > 0) {
                            return EventItemlink::EVENT_ITEM_LINK_EXIST;
                        } else {
                            $event_date = date('Y-m-d H:i:s');
                            $event_item_modal = new EventItemlink;
                            $event_item_modal->event_id = $event_id;
                            $event_item_modal->item_id = $item_id;
                            $event_item_modal->link_datetime = $event_date;
                            $event_item_modal->created_datetime = $event_date;
                            $event_item_modal->modified_datetime = $event_date;
                            $event_item_modal->save();

                            return EventItemlink::EVENT_ITEM_CREATED;
                        }
                    }

                    return Events::EVENT_CREATED;
                }
            }
        }
    }

    public function actionDeleteEvent($id)
    {
        Events::deleteAll('event_id='.$id);
        Yii::$app->session->setFlash('success','Events Deleted Successfully');
        $this->redirect(['events/index']);
    }

    public function actionMarkComplete() 
    {
        if (!Yii::$app->request->isAjax) {
            die();
        }

        $event_id = Yii::$app->request->post('event_id');
        $category_id = Yii::$app->request->post('category_id');

        if(!$event_id || !$category_id) {
            die();
        }
        
        EventItemlink::markComplete($event_id, $category_id);  

        $categories = \frontend\models\Category::find()
            ->where(['category_level' => 0, 'category_allow_sale' =>'Yes', 'trash' =>'Default'])
            ->orderBy(new \yii\db\Expression('FIELD (category_name, "Venues", "Invitations", "Food & Beverages", "Decor", "Supplies", "Entertainment", "Services", "Others", "Gift favors")'))
            ->asArray()
            ->all();

        $progress = $this->renderPartial('_progress', [
            'categories' => $categories, 
            'event_id' => $event_id
        ], true);

        Yii::$app->response->format = Response::FORMAT_JSON;

        return [
            'progress' => $progress,
            'btn_text' => Yii::t('frontend', 'Mark Incomplete')
        ];
    }

    public function actionMarkIncomplete() 
    {
        if (!Yii::$app->request->isAjax) {
            die();
        }

        $event_id = Yii::$app->request->post('event_id');
        $category_id = Yii::$app->request->post('category_id');

        if(!$event_id || !$category_id) {
            die();
        }
        
        EventItemlink::markIncomplete($event_id, $category_id);   

        $categories = \frontend\models\Category::find()
            ->where(['category_level' => 0, 'category_allow_sale' =>'Yes', 'trash' =>'Default'])
            ->orderBy(new \yii\db\Expression('FIELD (category_name, "Venues", "Invitations", "Food & Beverages", "Decor", "Supplies", "Entertainment", "Services", "Others", "Gift favors")'))
            ->asArray()
            ->all();

        $progress = $this->renderPartial('_progress', [
            'categories' => $categories, 
            'event_id' => $event_id
        ], true);

        Yii::$app->response->format = Response::FORMAT_JSON;

        return [
            'progress' => $progress,
            'btn_text' => Yii::t('frontend', 'Mark Complete')
        ];
    }

    /** 
     * Save categoory note for customer 
     */
    public function actionSaveNote()
    {
        if (Yii::$app->user->isGuest) 
        {
            die();
        }

        $category_id = Yii::$app->request->post('category_id');        
        $event_id = Yii::$app->request->post('event_id');        
        $note = Yii::$app->request->post('note');

        $model = CategoryNote::find()
            ->where([
                'category_id' => $category_id,
                'customer_id' => Yii::$app->user->getId(),
                'event_id' => $event_id
            ])
            ->one();

        if(!$model) 
        {
            $model = new CategoryNote;
            $model->event_id = $event_id;
            $model->category_id = $category_id;
            $model->customer_id = Yii::$app->user->getId();
        }

        $model->note = $note;
        $model->save();

        Yii::$app->response->format = Response::FORMAT_JSON;

        return [
            'note' => $note
        ];
    }
}
