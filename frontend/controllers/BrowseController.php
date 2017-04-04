<?php

namespace frontend\controllers;

use Yii;
use yii\helpers\Url;
use \common\models\City;
use yii\helpers\ArrayHelper;
use yii\data\ArrayDataProvider;
use frontend\models\VendorItem;
use frontend\models\Users;
use frontend\models\Vendor;
use frontend\models\Website;
use frontend\models\Customer;
use common\models\Events;
use common\models\ItemType;
use common\models\Category;
use common\models\Location;
use common\models\CategoryPath;
use common\models\VendorPhoneNo;
use common\models\VendorItemMenu;
use common\models\VendorLocation;
use common\models\CustomerAddress;
use common\models\VendorItemThemes;
use common\models\VendorItemPricing;
use common\models\VendorItemMenuItem;
use common\components\CFormatter;
use common\components\LangFormat;

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
        $city = City::findAll(['trash'=>'Default']);
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
                return $this->goBack();
            }
        } else {
            $Category = '';
        }
        
        \Yii::$app->view->title = (isset($Category->category_meta_title)) ? $Category->category_meta_title : (isset($Category->category_name)) ? Yii::$app->params['SITE_NAME'] . ' | ' . $Category->category_name : Yii::$app->params['SITE_NAME'] .' | All Products';
        \Yii::$app->view->registerMetaTag(['name' => 'description', 'content' => (isset($Category->category_meta_description)) ? $Category->category_meta_description : Yii::$app->params['META_DESCRIPTION']]);
        \Yii::$app->view->registerMetaTag(['name' => 'keywords', 'content' => (isset($Category->category_meta_keywords)) ? $Category->category_meta_keywords : Yii::$app->params['META_KEYWORD']]);

        if (!empty($data['location'])) {
            $session->set('deliver-location', $data['location']);
        } else {
            unset($_SESSION['deliver-location']);
        }

        if (!empty($data['date'])) {
            $session->set('deliver-date', $data['date']);
            $date = date('Y-m-d', strtotime($data['date']));
            $block_date = $date;
        }else{
            $block_date = '';
        }

        if (!empty($data['event_time'])) {
            $session->set('event_time', $data['event_time']);
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
            ->selectedFields()
            ->categoryJoin()
            ->itemJoin()
            ->priorityItemJoin()
            ->vendorJoin()
            ->defaultItems()
            ->approvedItems()
            ->activeItems();

        if (isset($data['for_sale']) && $data['for_sale'] != '') {
            $item_query->saleItems();
        }


        $item_query->byVendorIDs($ActiveVendors);

        //price filter
        if (isset($data['price']) && $data['price'] != '') {

            $price_condition = [];
            $arr_min_max = explode('-', $data['price']);
            $item_query->byPrice($arr_min_max[0],$arr_min_max[1]);
        }

        //theme filter
        if (!empty($data['themes'][0])) {

            $item_query->itemThemeJoin();
            $item_query->themeJoin();
            $item_query->byThemeSlug($data['themes']);

        }//if themes

        //event time 
        if($session->has('event_time')) {
            $item_query->workingTimeJoin();
        }

        //category filter
        $cats = '';

        if($Category)
        {
            $cats = $Category->category_id;
        }

        if (isset($data['category']) && count($data['category']) > 0)
        {
            $cats = implode("','",  $data['category']);
        }

        if($cats)
        {
            $item_query->byCategoryIDs($cats);
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

            $item_query->byDeliveryLocation($location);
        }

        if ($session->has('deliver-date')) {
            $date = date('Y-m-d', strtotime($session->get('deliver-date')));
            $item_query->byDeliveryDate($date);
        }

        if (!empty($session->get('event_time'))) {
            
            $delivery_date = $session->get('deliver-date');

            if($delivery_date)
                $working_day = date('D', strtotime($delivery_date));
            else 
                $working_day = date('D');

            $event_time = date('H:i:s', strtotime($session->get('event_time')));
            
            $item_query->byEventTime($event_time,$working_day);
        }

        $item_query_result = $item_query
            ->groupBy('{{%vendor_item}}.item_id')
            ->orderByExpression()
            ->asArray()
            ->all();

        /*
        Whenever results within browse belong to multiple vendors, alternate items to show 1 from each vendor.

        # Example:
        5 from candy vendor, 2 from chocolate, one from juice vendor.

        ## Will show in following order:
        candy, chocolate, juice, candy chocolate, candy, candy, candy
        */

        $vendor_chunks = [];
        $vendor_ids = [];

        foreach ($item_query_result as $key => $value)
        {
            $vendor_chunks[$value['vendor_id']][] = $value;
            $vendor_ids[] = $value['vendor_id'];
        }

        //get size of biggest chunk 
        
        $max_size = 0;

        foreach ($vendor_chunks as $key => $value) 
        {
            if(sizeof($value) > $max_size)
            {
                $max_size = sizeof($value);
            }
        }

        //get items from every chunk one by one 

        $items = [];

        for($i = 0; $i < $max_size; $i++)
        {
            foreach ($vendor_chunks as $key => $value) 
            {
                if(isset($value[$i]))
                {
                    $items[] = $value[$i];    
                }            
            }
        }

        $provider = new ArrayDataProvider([
            'allModels' => $items,
            'pagination' => [
                'pageSize' => 50,
            ],
        ]);

        $get_unique_themes = array();

        if (!empty($items)) {

            $item_ids = ArrayHelper::map($items, 'item_id', 'item_id');

            $q = VendorItemThemes::find()
                ->select(['{{%vendor_item_theme}}.theme_id'])
                ->joinWith('themeDetail')
                ->defaultItemThemes()
                ->byItemIDs(implode(',', array_keys($item_ids)))
                ->groupBy('{{%vendor_item_theme}}.theme_id');
            
            if (Yii::$app->language == 'en') {
                $q->orderBy('theme_name');
            } else {
                $q->orderBy('theme_name_ar');
            }

            $themes = $q->asArray()->all();
        }

        //get available vendor in item query result 

        $vendor = Vendor::find()
            ->select('{{%vendor}}.vendor_id, {{%vendor}}.vendor_name, {{%vendor}}.vendor_name_ar, {{%vendor}}.slug')
            ->byVendorID($vendor_ids)
            ->asArray()
            ->all();

        $TopCategories = Category::find()
            ->allParents()
            ->defaultCategories()
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
        $model = VendorItem::find()
                    ->leftJoin('{{%vendor}}', '{{%vendor}}.vendor_id = {{%vendor_item}}.vendor_id')
                    ->bySlug($slug)->defaultItems()->activeItems()
                    ->approvedItems()->byActiveVendor()->approvedVendor()
                    ->defaultVendor()
                    ->one();

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
            $model->item_for_sale == 'Yes'
        ) {
            $AvailableStock = true;
        } else {
            $AvailableStock = false;
        }

        //get item type 

        $item_type = ItemType::findOne($model->type_id);

        if($item_type) {
            $item_type_name = $item_type->type_name;
        } else {
            $item_type_name = 'Product';
        }

        $output = \common\models\Image::find()->select(['image_path'])
            ->itemID($model['item_id'])
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

        $vendor_detail = $model->vendor;

        $phone_icons = [
            'Whatsapp' => 'fa fa-whatsapp',
            'Mobile' => 'fa fa-mobile',
            'Fax' => 'fa fa-fax',
            'Office' => 'fa fa-building'
        ];

        //day off 
        $search = array(0, 1, 2, 3, 4, 5, 6, ',');

        $replace = array(
            Yii::t('frontend', 'Sunday'),
            Yii::t('frontend', 'Monday'),
            Yii::t('frontend', 'Tuesday'),
            Yii::t('frontend', 'Wednesday'),
            Yii::t('frontend', 'Thursday'),
            Yii::t('frontend', 'Friday'),
            Yii::t('frontend', 'Saturday'),
            ', '
        );

        $working_days = ArrayHelper::map(\common\models\VendorWorkingTiming::findAll(['vendor_id'=>$vendor_detail->vendor_id]),'working_day','working_day');
        $txt_day_off = implode(',',array_diff($replace,$working_days));


        if ($vendor_detail->vendor_website && strpos($vendor_detail->vendor_website, 'http://') === false) {
            $vendor_detail->vendor_website = 'http://'.$vendor_detail->vendor_website;
        }

        $price_table = VendorItemPricing::find()->byItemID($model->item_id)->defaultItem()->all();

        $menu = VendorItemMenu::find()->byItemID($model->item_id)->optionMenu()->all();
        $addons = VendorItemMenu::find()->byItemID($model->item_id)->addonMenu()->all();

        $vendor_area = VendorLocation::findAll(['vendor_id' => $model->vendor_id]);
        $vendor_area_list =  \yii\helpers\ArrayHelper::map($vendor_area, 'area_id', 'locationName','cityName' );
        $area_ids = \yii\helpers\ArrayHelper::map($vendor_area, 'area_id', 'area_id' );

        // customer address areas

        if(!Yii::$app->user->isGuest) 
        {
            $my_addresses =  \common\models\CustomerAddress::find()
                ->select(['{{%location}}.id,{{%customer_address}}.address_id, {{%customer_address}}.address_name'])
                ->joinLocation()
                ->byDefaultAddress()
                ->byCustomer(Yii::$app->user->getId())
                ->byLocation($area_ids)
                ->groupby(['{{%location}}.id'])
                ->asArray()
                ->all();

            $customer = Customer::findOne(Yii::$app->user->getId());
        }
        else
        {
            $my_addresses = [];
            $customer = null;
        }

        if($customer) {
            $customer_name = $customer->customer_name.' '.$customer->customer_last_name;
            $customer_phone = $customer->customer_mobile;
            $customer_email = $customer->customer_email;
        } else {
            $customer_name = '';
            $customer_phone = '';
            $customer_email = '';
        }

        $myaddress_area_list =  \yii\helpers\ArrayHelper::map($my_addresses, 'address_id', 'address_name');

        if ($myaddress_area_list) {

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

        // customer event list 
        
        if(Yii::$app->user->isGuest) 
        {
            $customer_events_list = [];    
        }
        else
        {
            $user = new Users();

            $customer_events_list = $user->get_customer_wishlist_details(Yii::$app->user->identity->customer_id);    
        }
        
        return $this->render('detail', [
            'model' => $model,
            'menu' => $menu,
            'addons' => $addons,
            'vendor_detail' => $vendor_detail,
            'phones' => VendorPhoneNo::findAll(['vendor_id' => $model->vendor_id]),
            'phone_icons' => $phone_icons,
            'txt_day_off' => $txt_day_off,
            'similiar_item' => VendorItem::more_from_vendor($model),
            'AvailableStock' => $AvailableStock,
            'customer_events_list' => $customer_events_list,
            'vendor_area' => $vendor_area_list,
            'my_addresses' => $my_addresses,
            'customer_name' => $customer_name,
            'customer_phone' => $customer_phone,
            'customer_email' => $customer_email,
            'price_table' => $price_table
        ]);
    }

    public function actionBooking() 
    {
        $item_id = Yii::$app->request->post('item_id');

        $item = VendorItem::findOne($item_id);

        $phone = Yii::$app->request->post('phone');

        Yii::$app->mailer->compose("customer/booking_support",
            [
                "item" => $item,
                "name" => Yii::$app->request->post('name'),
                "phone" => $phone,
                "email" => Yii::$app->request->post('email')
            ])
            ->setFrom(Yii::$app->params['supportEmail'])
            ->setTo(Yii::$app->params['supportEmail'])
            ->setSubject('Booking support request from "'.$phone.'"')
            ->send();

        Yii::$app->getSession()->setFlash('success', Yii::t(
                    'frontend', 
                    'Thank you for your request, we will get back to you within the next 24 hours.'
                ));

        return $this->redirect(['browse/detail', 'slug' => $item['slug']]);
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
            $area = \common\models\Location::find()->byCityID($_POST['city_id'])->activeLocations()->defaultLocations()->orderBy('city_id')->all();
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
        if (!Yii::$app->request->isAjax) {
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        }

        $data = Yii::$app->request->post();

        $AreaName = '';
        
        $location = Location::findOne($data['area_id']);
        
        if ($location) {
            if (Yii::$app->language == "en") {
                $AreaName = $location->location;
            } else {
                $AreaName = $location->location_ar;
            }
        }

        if ($data['delivery_date'] == '') {
            $selectedDate = date('Y-m-d');
        } else {
            $selectedDate = $data['delivery_date'];
        }

        $item = VendorItem::findOne($data['item_id']);
        
        if ($item) {
            
            $exist = \common\models\BlockedDate::find()->byVendor($item->vendor_id)->byBlockedDate($selectedDate)->one();

            $date = date('d-m-Y', strtotime($selectedDate));
            
            if ($exist) {
                echo "<i class='fa fa-warning' style='color: Red; font-size: 19px;' aria-hidden='true'></i> Item Not Available for on this date '$date' for this location '$AreaName' ";
            } else {
                echo "<i class='fa fa-check-circle' style='color: Green; font-size: 19px;' aria-hidden='true'></i> Item Available on this date '$date' for this location '$AreaName' ";
            }
        }
    }

    public function actionFinalPrice() 
    {
        $item_id = Yii::$app->request->post('item_id');

        $item = VendorItem::findOne($item_id);

        if (empty($item)) {
           throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        }
        
        $total = ($item->item_base_price) ? $item->item_base_price : 0;

        $price_chart = VendorItemPricing::find()
            ->byItemID($item['item_id'])
            ->defaultItem()
            ->byQuantityRange(Yii::$app->request->post('quantity'))
            ->orderBy('pricing_price_per_unit DESC')
            ->one();

        if ($item->item_minimum_quantity_to_order > 0) {
            $min_quantity_to_order = $item->item_minimum_quantity_to_order;
        } else {
            $min_quantity_to_order = 1;
        }

        if ($price_chart) {
            $unit_price = $price_chart->pricing_price_per_unit;
        } else {
            $unit_price = $item->item_price_per_unit;
        }

        $actual_item_quantity = Yii::$app->request->post('quantity') - $min_quantity_to_order;

        $total += $unit_price * $actual_item_quantity;

        $menu_items = Yii::$app->request->post('menu_item');

        if(!is_array($menu_items)) {
            $menu_items = [];
        }

        foreach ($menu_items as $key => $value) {
            
            $menu_item = VendorItemMenuItem::findOne($key);

            $total += $menu_item->price * $value;
        }
        
        Yii::$app->response->format = 'json';

        return [
            'price' => CFormatter::format($total)
        ];
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
