<?php

namespace frontend\controllers;

use Yii;
use frontend\models\EventInvitees;
use frontend\models\EventInviteesSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use frontend\models\Users;
use frontend\models\VendorItem;
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
use common\models\FeatureGroupItem;
use common\models\LoginForm;
use common\models\Vendor;
use common\models\City;
use frontend\models\Website;
use frontend\models\EventItemlink;
use frontend\models\Wishlist;
use frontend\models\AddressType;
use frontend\models\AddressQuestion;
use frontend\models\Customer;
use frontend\models\Themes;
use common\models\VendorItemToCategory;
use common\models\VendorItemThemes;

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
        EventInvitees::findOne($id)->delete();
        Yii::$app->session->setFlash('success','Invitee Deleted Successfully');
        return $this->redirect(['events/detail','slug'=>$_REQUEST['slug']]);
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
}
