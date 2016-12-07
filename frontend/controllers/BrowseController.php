<?php

namespace frontend\controllers;

use Yii;
use yii\helpers\Url;
use yii\db\Expression;
use yii\helpers\VarDumper;
use yii\helpers\ArrayHelper;
use yii\data\ArrayDataProvider;
use frontend\models\VendorItem;
use frontend\models\Users;
use frontend\models\Website;
use frontend\models\Vendor;
use common\models\Events;
use common\models\VendorLocation;
use common\models\Category;
use common\models\VendorItemThemes;
use common\models\Location;
use common\models\CategoryPath;
use common\models\CustomerAddress;
use common\components\LangFormat;
use common\models\VendorPhoneNo;

/**
* Site controller.
*/
class BrowseController extends BaseController
{
    /**
    * {@inheritdoc}
    */
    public function init()
    {
        parent::init();
    }


    public function actionCategories(){
        \Yii::$app->view->title = 'The White Book | Categories';
        \Yii::$app->view->registerMetaTag(['name' => 'description', 'content' => Yii::$app->params['META_DESCRIPTION']]);
        \Yii::$app->view->registerMetaTag(['name' => 'keywords', 'content' => Yii::$app->params['META_KEYWORD']]);
        $city = \common\models\City::findAll(['trash'=>'Default']);
        return $this->render('categories',['city'=>$city]);
    }

    public function actionList($slug)
    {
        $slug = strtolower($slug);
        $session = Yii::$app->session;
        $condition = '';
        $model = new Website();
        $data = Yii::$app->request->get();
        $themes = [];

        if ($slug != 'all') {
            $Category = Category::findOne(['slug' => $slug]);

            if (empty($Category)) {
                throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
            }
        } else {
            $Category = '';
        }
        
        \Yii::$app->view->title = (isset($Category->category_meta_title)) ? $Category->category_meta_title : (isset($Category->category_name)) ? Yii::$app->params['SITE_NAME'] . ' | ' . $Category->category_name : Yii::$app->params['SITE_NAME'] .' | All Products';
        \Yii::$app->view->registerMetaTag(['name' => 'description', 'content' => (isset($Category->category_meta_description)) ? $Category->category_meta_description : Yii::$app->params['META_DESCRIPTION']]);
        \Yii::$app->view->registerMetaTag(['name' => 'keywords', 'content' => (isset($Category->category_meta_keywords)) ? $Category->category_meta_keywords : Yii::$app->params['META_KEYWORD']]);

        if ((isset($data['location']) && $data['location'] != '')) {
            $session->set('deliver-location', $data['location']);
        } else {
            unset($_SESSION['deliver-location']);
        }

        if (isset($data['date']) && $data['date'] != '') {
            $session->set('deliver-date', $data['date']);
            $date = date('Y-m-d', strtotime($data['date']));
            $block_date = $date;
        }else{
            $block_date = '';
        }

        if (isset($data['vendor']) && $data['vendor'] != '') {
            $arr_vendor_slugs = $data['vendor'];
        }else{
            $arr_vendor_slugs = [];
        }

        $ActiveVendors = Vendor::loadvalidvendorids(
            false, //current category
            $arr_vendor_slugs, //only selected from filter
            '', //who available today
            ''//delivery on location available
        );

        $item_query = CategoryPath::find()
            ->select('{{%vendor_item}}.item_for_sale, {{%vendor_item}}.slug, {{%vendor_item}}.item_id, {{%vendor_item}}.item_id, {{%vendor_item}}.item_name, {{%vendor_item}}.item_name_ar, {{%vendor_item}}.item_price_per_unit, {{%vendor}}.vendor_name, {{%vendor}}.vendor_name_ar, {{%image}}.image_path')
            ->leftJoin(
                '{{%vendor_item_to_category}}',
                '{{%vendor_item_to_category}}.category_id = {{%category_path}}.category_id'
            )
            ->leftJoin(
                '{{%vendor_item}}',
                '{{%vendor_item}}.item_id = {{%vendor_item_to_category}}.item_id'
            )
            ->leftJoin(
                '{{%priority_item}}',
                '{{%priority_item}}.item_id = {{%vendor_item}}.item_id'
            )
            ->leftJoin('{{%image}}', '{{%vendor_item}}.item_id = {{%image}}.item_id')
            ->leftJoin('{{%vendor}}', '{{%vendor_item}}.vendor_id = {{%vendor}}.vendor_id')
            ->where([
                '{{%vendor_item}}.trash' => 'Default',
                '{{%vendor_item}}.item_approved' => 'Yes',
                '{{%vendor_item}}.item_status' => 'Active',
            ]);

        if (isset($data['for_sale']) && $data['for_sale'] != '') {
            $item_query->andWhere(['{{%vendor_item}}.item_for_sale' => 'Yes']);
        }

        $item_query->andWhere(['in', '{{%vendor_item}}.vendor_id', $ActiveVendors]);

        //price filter
        if (isset($data['price']) && $data['price'] != '') {

            $price_condition = [];

            $arr_min_max = explode('-', $data['price']);
            $price_condition[] = '{{%vendor_item}}.item_price_per_unit between '.$arr_min_max[0].' and '.$arr_min_max[1];

            $item_query->andWhere(implode(' OR ', $price_condition));
        }

        //theme filter
        if (isset($data['themes']) && count($data['themes'])>0) {

            $item_query->leftJoin('{{%vendor_item_theme}}', '{{%vendor_item}}.item_id = {{%vendor_item_theme}}.item_id');
            $item_query->leftJoin('{{%theme}}', '{{%theme}}.theme_id = {{%vendor_item_theme}}.theme_id');
            $item_query->andWhere(['IN', '{{%theme}}.slug', $data['themes']]);

        }//if themes

        //category filter
        if ($slug != 'all') {
            $cats = $Category->slug;
            $categories = [];

            if (isset($data['category']) && count($data['category']) > 0) {
                $categories = array_merge($categories, $data['category']);
                $cats = implode("','", $categories);
            }
            $q = "{{%category_path}}.path_id IN (select category_id from {{%category}} where slug IN ('$cats') and trash = 'Default')";
            $item_query->andWhere($q);
        }

        if ($session->has('deliver-location')) {

            if (is_numeric($session->get('deliver-location'))) {
                $location = $session->get('deliver-location');
            } else {
                $end = strlen($session->get('deliver-location'));
                $from = strpos($session->get('deliver-location'), '_') + 1;
                $address_id = substr($session->get('deliver-location'), $from, $end);

                $location = CustomerAddress::findOne($address_id)->area_id;
            }

            $item_query->andWhere('EXISTS (SELECT 1 FROM {{%vendor_location}} WHERE {{%vendor_location}}.area_id="'.$location.'" AND {{%vendor_item}}.vendor_id = {{%vendor_location}}.vendor_id)');
        }


        if ($session->has('deliver-date')) {
            $date = date('Y-m-d', strtotime($session->get('deliver-date')));
            $condition .= " ({{%vendor}}.vendor_id NOT IN(SELECT vendor_id FROM `whitebook_vendor_blocked_date` where block_date = '".$date."')) ";
        }

        $item_query->andWhere($condition);

        $expression = new Expression(
            "CASE 
                WHEN
                    `whitebook_priority_item`.priority_level IS NULL 
                    OR whitebook_priority_item.status = 'Inactive' 
                    OR whitebook_priority_item.trash = 'Deleted' 
                    OR DATE(whitebook_priority_item.priority_start_date) > DATE(NOW()) 
                    OR DATE(whitebook_priority_item.priority_end_date) < DATE(NOW()) 
                THEN 2 
                WHEN `whitebook_priority_item`.priority_level = 'Normal' THEN 1 
                WHEN `whitebook_priority_item`.priority_level = 'Super' THEN 0 
                ELSE 2 
            END, {{%vendor_item}}.sort");

        $items = $item_query
            ->groupBy('{{%vendor_item}}.item_id')
            ->orderBy($expression)
            ->asArray()
            ->all();

        $provider = new ArrayDataProvider([
            'allModels' => $items,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        $get_unique_themes = array();

        if (!empty($items)) {

            $item_ids = ArrayHelper::map($items, 'item_id', 'item_id');

            $themes = VendorItemThemes::find()
                ->select(['theme_id'])
                ->with('themeDetail')
                ->where("trash='default' and item_id IN(".implode(',', array_keys($item_ids)).")")
                ->groupBy('theme_id')
                ->asArray()
                ->all();
        }

        $vendor = Vendor::find()
            ->select('{{%vendor}}.vendor_id, {{%vendor}}.vendor_name, {{%vendor}}.vendor_name_ar, {{%vendor}}.slug')
            ->where(['IN', '{{%vendor}}.vendor_id', $ActiveVendors])
            ->asArray()
            ->all();

        $TopCategories = Category::find()
            ->where(['category_level' => 0, 'trash' => 'Default'])
            ->orderBy('sort')
            ->asArray()
            ->all();

        if (Yii::$app->user->isGuest) {
            $customer_events_list = [];
        } else {
            $usermodel = new Users();
            $customer_events_list = $usermodel->get_customer_wishlist_details(Yii::$app->user->identity->id);
        }


        if (Yii::$app->request->isAjax) {
            return $this->renderPartial('@frontend/views/common/items', [
                'items' => $provider,
                'customer_events_list' => $customer_events_list
            ]);
        }

        return $this->render('list', [
            'model' => $model,
            'TopCategories' => $TopCategories,
            'items' => $items,
            'themes' => $themes,
            'provider' => $provider,
            'vendor' => $vendor,
            'Category' => $Category,
            'slug' => $slug,
            'customer_events_list' => $customer_events_list
        ]);
    }

    public function actionDetail($slug)
    {
        $model = VendorItem::findOne(['slug' => $slug]);

        if (empty($model)) {
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        }

        \Yii::$app->view->title = Yii::$app->params['SITE_NAME'] .' | '.LangFormat::format($model->item_name,$model->item_name_ar);
        \Yii::$app->view->registerMetaTag(['name' => 'description', 'content' => LangFormat::format(strip_tags($model->item_description),strip_tags($model->item_description_ar))]);
        \Yii::$app->view->registerMetaTag(['name' => 'keywords', 'content' => Yii::$app->params['META_KEYWORD']]);

        if (
            $model->item_approved == 'Yes' &&
            $model->trash == 'Default' &&
            $model->item_status == 'Active' &&
            $model->type_id == '2' &&
            $model->item_for_sale == 'Yes' &&
            $model->item_amount_in_stock > 0
        ) {
            $AvailableStock = true;
        } else {
            $AvailableStock = false;
        }

        $output = \common\models\Image::find()->select(['image_path'])
            ->where(['item_id' => $model['item_id']])
            ->orderby(['vendorimage_sort_order' => SORT_ASC])
            ->asArray()
            ->one();

        if (!empty($model->images[0])) {
            $baselink = Yii::getAlias("@s3/vendor_item_images_530/") . $model->images[0]['image_path'];
        } else {
            $baselink = Url::to("@web/images/item-default.png");
        }

        \Yii::$app->view->registerMetaTag(['property' => 'og:title', 'content' => Yii::$app->name.' - ' . ucfirst($model->vendor->vendor_name)]);
        \Yii::$app->view->registerMetaTag(['property' => 'fb:app_id', 'content' => 157333484721518]);
        \Yii::$app->view->registerMetaTag(['property' => 'og:url', 'content' => Url::toRoute(["browse/detail", 'slug' => $model->slug], true)]);
        \Yii::$app->view->registerMetaTag(['property' => 'og:image', 'content' => $baselink]);
        \Yii::$app->view->registerMetaTag(['property' => 'og:image:width', 'content' => '200']);
        \Yii::$app->view->registerMetaTag(['property' => 'og:image:height', 'content' => '200']);
        \Yii::$app->view->registerMetaTag(['property' => 'og:site_name', 'content' => Yii::$app->name.' - ' . ucfirst($model->item_name) .' from '. ucfirst($model->vendor->vendor_name)]);
        \Yii::$app->view->registerMetaTag(['property' => 'og:description', 'content' => trim(strip_tags($model->item_description))]);
        \Yii::$app->view->registerMetaTag(['property' => 'twitter:card', 'content' => 'summary_large_image']);

        \Yii::$app->view->registerMetaTag([
            'property' => 'og:description',
            'content' => trim(strip_tags($model->item_description))
        ]);

        if (Yii::$app->user->isGuest) {

            return $this->render('detail', [
                'AvailableStock' => $AvailableStock,
                'model' => $model,
                'similiar_item' => VendorItem::more_from_vendor($model),
                'vendor_area' => [],
                'phones' => VendorPhoneNo::findAll(['vendor_id' => $model->vendor_id]),
                'my_addresses' => []
            ]);

        } else {

            $vendor_area = VendorLocation::findAll(['vendor_id' => $model->vendor_id]);
            $vendor_area_list =  \yii\helpers\ArrayHelper::map($vendor_area, 'area_id', 'locationName','cityName' );
            $area_ids = \yii\helpers\ArrayHelper::map($vendor_area, 'area_id', 'area_id' );

            $customer_id = Yii::$app->user->getId();

            $my_addresses =  \common\models\CustomerAddress::find()
                ->select(['{{%location}}.id,{{%customer_address}}.address_id, {{%customer_address}}.address_name'])
                ->leftJoin('{{%location}}', '{{%location}}.id = {{%customer_address}}.area_id')
                ->where(['{{%customer_address}}.trash'=>'Default'])
                ->andwhere(['{{%customer_address}}.customer_id' => $customer_id])
                ->andwhere(['{{%location}}.id' => $area_ids])
                ->groupby(['{{%location}}.id'])
                ->asArray()
                ->all();

            $myaddress_area_list =  \yii\helpers\ArrayHelper::map($my_addresses, 'address_id', 'address_name');

            if (count($myaddress_area_list)>0) {

                // add prefix to address id ex: address_14,address_15
                $myNewArray = array_combine(
                    array_map(function($key){ return 'address_'.$key; }, array_keys($myaddress_area_list)),
                    $myaddress_area_list
                );

                $combined_myaddress = array(
                    Yii::t('frontend', 'My Addresses') => $myNewArray
                );

                $vendor_area_list = $combined_myaddress + $vendor_area_list;
            }

            $user = new Users();

            $customer_events_list = $user->get_customer_wishlist_details(Yii::$app->user->identity->customer_id);

            return $this->render('detail', [
                'model' => $model,
                'phones' => VendorPhoneNo::findAll(['vendor_id' => $model->vendor_id]),
                'similiar_item' => VendorItem::more_from_vendor($model),
                'AvailableStock' => $AvailableStock,
                'customer_events_list' => $customer_events_list,
                'vendor_area' => $vendor_area_list,
                'my_addresses' => $my_addresses,
            ]);
        }
    }

    public function actionEvent_slider()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        } else {
            return $this->renderPartial('events_slider');
        }
    }

    public function actionEventdetails()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $edit_eventinfo = Events::find()->where(['event_id' => $data['event_id']])->asArray()->all();
            return $this->renderPartial('edit_event', array('edit_eventinfo' => $edit_eventinfo));
        }
    }


    public function actionGetLocationList(){
        if (Yii::$app->request->isAjax) {

            if (Yii::$app->language == "en") {
                $name = 'location';
            } else {
                $name = 'location_ar';
            }
            $area = \common\models\Location::find()->where(['status'=>'Active', 'trash' => 'Default', 'city_id' => $_POST['city_id']])->orderBy('city_id')->all();
            if ($area) {
                echo \yii\helpers\Html::dropDownList('Location','',\yii\helpers\ArrayHelper::map($area ,'id',$name),['prompt'=>'Please Select Location','class'=>'selectpicker required trigger','id'=>'Location']);
            } else {
                echo \yii\helpers\Html::dropDownList('Location','',[],['prompt'=>'Please Select Location','class'=>'selectpicker required trigger','id'=>'Location']);
            }
            exit;
        }
    }

    /*
     *  function use for ajax call and
     *  return product availbility on the base of
     *  area and date
     */
    public function actionProductAvailable()
    {
        if (Yii::$app->request->isAjax) {
            $AreaName = '';
            $location = Location::findOne($_POST['area_id']);
            if ($location) {
                if (Yii::$app->language == "en") {
                    $AreaName = $location->location;
                } else {
                    $AreaName = $location->location_ar;
                }
            }

            if ($_POST['delivery_date'] == '') {
                $selectedDate = date('Y-m-d');
            } else {
                $selectedDate = $_POST['delivery_date'];
            }
            $item = VendorItem::findOne($_POST['item_id']);
            if ($item) {
                $exist = \common\models\BlockedDate::findOne(['vendor_id' => $item->vendor_id, 'block_date' => date('Y-m-d', strtotime($selectedDate))]);
                $date = date('d-m-Y', strtotime($selectedDate));
                if ($exist) {
                    echo "<i class='fa fa-warning' style='color: Red; font-size: 19px;' aria-hidden='true'></i> Item Not Available for on this date '$date' for this location '$AreaName' ";
                } else {
                    echo "<i class='fa fa-check-circle' style='color: Green; font-size: 19px;' aria-hidden='true'></i> Item Available on this date '$date' for this location '$AreaName' ";
                }
            }
            exit;
        }
    }

    public function actionLocationDateSelection()
    {
        $location_name = $_REQUEST['location_name'];
        $delivery_date = $_REQUEST['delivery_date'];

        if (trim($_REQUEST['delivery_date']) == '') {
            echo 'date';
            exit;
        } else {
            $session = Yii::$app->session;
            $session->set('deliver-location', $location_name);
            $session->set('deliver-date', $delivery_date);
        }
    }
}
