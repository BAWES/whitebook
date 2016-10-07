<?php

namespace frontend\controllers;

use common\models\CustomerAddress;
use Yii;
use yii\helpers\Url;
use frontend\models\Users;
use frontend\models\Website;
use frontend\models\Vendoritem;
use frontend\models\Themes;
use frontend\models\Vendor;
use common\models\Category;
use common\models\Vendoritemthemes;
use common\models\City;
use common\models\Location;
use common\models\Vendorlocation;
use common\models\CategoryPath;

/**
 * Category controller.
 */
class ShopController extends BaseController
{
    public function init()
    {
        parent::init();
    }

    public function actionIndex()
    {

        \Yii::$app->view->title = 'The White Book | Plan';
        \Yii::$app->view->registerMetaTag(['name' => 'description', 'content' => Yii::$app->params['META_DESCRIPTION']]);
        \Yii::$app->view->registerMetaTag(['name' => 'keywords', 'content' => Yii::$app->params['META_KEYWORD']]);
        $city = \common\models\City::findAll(['trash'=>'Default']);
        return $this->render('index',['city'=>$city]);
    }

    public function actionProducts($slug)
    {
        $session = Yii::$app->session;
        $condition = '';
        $model = new Website();
        $imageData = '';
        if ($slug != '') {

            $Category = Category::findOne(['slug' => $slug]);

            if (empty($Category)) {
                throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
            }

            \Yii::$app->view->title = ($Category->category_meta_title) ? $Category->category_meta_title : Yii::$app->params['SITE_NAME'].' | '.$Category->category_name;
            \Yii::$app->view->registerMetaTag(['name' => 'description', 'content' => ($Category->category_meta_description) ? $Category->category_meta_description : Yii::$app->params['META_DESCRIPTION']]);
            \Yii::$app->view->registerMetaTag(['name' => 'keywords', 'content' => ($Category->category_meta_keywords) ? $Category->category_meta_keywords : Yii::$app->params['META_KEYWORD']]);

            $TopCategories = Category::find()
                ->where(['category_level' => 0, 'trash' => 'Default'])
                ->orderBy('sort')
                ->asArray()
                ->all();

            $ActiveVendors = Vendor::loadvalidvendorids($Category->category_id);


            $vendor = implode(',', $ActiveVendors);

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
                    '{{%vendor_location}}',
                    '{{%vendor_item}}.vendor_id = {{%vendor_location}}.vendor_id'
                )
                ->leftJoin('{{%image}}', '{{%vendor_item}}.item_id = {{%image}}.item_id')
                ->leftJoin('{{%vendor}}', '{{%vendor_item}}.vendor_id = {{%vendor}}.vendor_id')
                ->where([
                    '{{%vendor_item}}.trash' => 'Default',
                    '{{%category_path}}.path_id' => $Category->category_id,
                    '{{%vendor_item}}.item_approved' => 'Yes',
                    '{{%vendor_item}}.item_status' => 'Active'                    
                ]);
                    
            $item_query->andWhere(['in', '{{%vendor_item}}.vendor_id', $ActiveVendors]);

            if ($session->has('deliver-location')) {

                if (is_numeric($session->get('deliver-location'))) {
                    $location = $session->get('deliver-location');
                } else {
                    $end = strlen($session->get('deliver-location'));
                    $from = strpos($session->get('deliver-location'), '_') + 1;
                    $address_id = substr($session->get('deliver-location'), $from, $end);

                    $location = CustomerAddress::findOne($address_id)->area_id;
                }
                $item_query->andWhere(['in', '{{%vendor_location}}.area_id', $location]);
            }


            if ($session->has('deliver-date')) {
                $date = date('Y-m-d', strtotime($session->get('deliver-date')));
                $condition .= " ({{%vendor}}.vendor_id NOT IN(SELECT vendor_id FROM `whitebook_vendor_blocked_date` where block_date = '".$date."')) ";
            }

            $item_query->andWhere($condition);

            $items = $item_query
                ->groupBy('{{%vendor_item}}.item_id')
                ->orderBy('{{%image}}.vendorimage_sort_order', SORT_ASC)
                ->asArray()
                ->all();
        }

        $get_unique_themes = array();

        if (!empty($items)) {

            $ids = [];

            foreach ($items as $data) {
                $ids[] = $data['item_id'];
            }

            $theme_names = Themes::loadthemename_item($ids);
            $single_themes[] = array();
            $multi_themes[] = array();
            foreach ($theme_names as $themes) {
                if (is_numeric($themes['theme_id'])) {
                    $single_themes[] = $themes['theme_id'];
                }
                if (!is_numeric($themes['theme_id'])) {
                    $multi_themes[] = explode(',', $themes['theme_id']);
                }
            }
            foreach ($multi_themes as $multiple) {
                foreach ($multiple as $key) {
                    $get_unique_themes[] = $key;
                }
            }
            if (count($single_themes)) {
                foreach ($single_themes as $single) {
                    if (!empty($single)) {
                        $get_unique_themes[] = $single;
                    }
                }
            }
            $get_unique_themes = array_unique($get_unique_themes);
        }

        if (Yii::$app->language == "en") {
            $themes = Themes::load_all_themename($get_unique_themes, 'theme_name');
        } else {
            $themes = Themes::load_all_themename($get_unique_themes, 'theme_name_ar');
        }

        $vendor = Vendor::find()
            ->select('{{%vendor}}.vendor_id, {{%vendor}}.vendor_name, {{%vendor}}.vendor_name_ar, {{%vendor}}.slug')
            ->where(['IN', '{{%vendor}}.vendor_id', $ActiveVendors])
            ->asArray()
            ->all();

        if (Yii::$app->user->isGuest) {

            return $this->render('product_list', [
                'model' => $model, 
                'TopCategories' => $TopCategories,
                'items' => $items,
                'themes' => $themes, 
                'vendor' => $vendor, 
                'slug' => $slug,
                'Category' => $Category
            ]);

        } else {
            $usermodel = new Users();
            
            $customer_events_list = $usermodel->get_customer_wishlist_details(Yii::$app->user->identity->id);
            
            return $this->render('product_list', [
                'model' => $model, 
                'TopCategories' => $TopCategories,
                'items' => $items,
                'themes' => $themes, 
                'vendor' => $vendor,
                'Category' => $Category,
                'slug' => $slug, 
                'customer_events_list' => $customer_events_list
            ]);
        }
    }

    public function actionLoadItems()
    {
        if (!Yii::$app->request->isAjax) {
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        }

        $session = Yii::$app->session;

        $data = Yii::$app->request->get();

        //items only from active vendors 
        $top_category = Category::find()
            ->select(['category_id', 'category_name'])
            ->where(['slug' => $data['slug']])
            ->asArray()
            ->one();

        // delivery filter 
        if ($data['location'] != '') {
            $session->set('deliver-location', $data['location']);
            $deliverlocation = $session->get('deliver-location');
            if (is_numeric($deliverlocation)) {
                $location = $deliverlocation;
            } else {
                $end = strlen($deliverlocation);
                $from = strpos($deliverlocation, '_') + 1;
                $address_id = substr($deliverlocation, $from, $end);
                $location = CustomerAddress::findOne($address_id)->area_id;
            }
        }else{
            $location = '';
        }
       
        if ($data['date'] != '') {
            $session->set('deliver-date', $data['date']);
            $date = date('Y-m-d', strtotime($data['date']));
            $block_date = $date;
        }else{
            $block_date = '';
        }

        //vendor filter
        if ($data['vendor'] != '') {
            $arr_vendor_slugs = explode('+', $data['vendor']);
        }else{
            $arr_vendor_slugs = [];
        }

        $active_vendors = Vendor::loadvalidvendorids(
            $top_category['category_id'], //current category 
            $arr_vendor_slugs, //only selected from filter 
            $block_date, //who available today 
            $location//delivery on location available 
        );

        $items_query = CategoryPath::find()
            ->select('{{%vendor_item}}.item_for_sale, {{%vendor_item}}.slug, {{%vendor_item}}.item_id, {{%vendor_item}}.item_id, {{%vendor_item}}.item_name, {{%vendor_item}}.item_name_ar, {{%vendor_item}}.item_price_per_unit, {{%vendor}}.vendor_name, {{%vendor}}.vendor_name_ar, {{%image}}.image_path')
            ->leftJoin(
                '{{%vendor_item_to_category}}', 
                '{{%vendor_item_to_category}}.category_id = {{%category_path}}.category_id'
            )
            ->leftJoin(
                '{{%vendor_item}}',
                '{{%vendor_item}}.item_id = {{%vendor_item_to_category}}.item_id'
            )
            ->leftJoin('{{%image}}', '{{%vendor_item}}.item_id = {{%image}}.item_id')
            ->leftJoin('{{%vendor}}', '{{%vendor_item}}.vendor_id = {{%vendor}}.vendor_id')
            ->where([
                '{{%vendor_item}}.trash' => 'Default',
                '{{%vendor_item}}.item_approved' => 'Yes',
                '{{%vendor_item}}.item_status' => 'Active',
                '{{%vendor_item}}.vendor_id' => $active_vendors
            ]);

        //price filter 
        if ($data['price'] != '') {

            $price_condition = [];

            foreach (explode('+', $data['price']) as $key => $value) {
                $arr_min_max = explode('-', $value);
                $price_condition[] = '{{%vendor_item}}.item_price_per_unit between '.$arr_min_max[0].' and '.$arr_min_max[1];
            }

            $items_query->andWhere(implode(' OR ', $price_condition));
        }

        //theme filter 
        if ($data['themes'] != '') {

            $theme = explode('+', $data['themes']);

            foreach ($theme as $key => $value) {
                $themes[] = Themes::find()
                    ->select('theme_id')
                    ->where(['slug' => [$value]])
                    ->asArray()
                    ->all();
            }

            $all_valid_themes = array();

            foreach ($themes as $key => $value) {
                $get_themes = Vendoritemthemes::find()
                    ->select('theme_id, item_id')
                    ->where(['trash' => "Default"])
                    ->andWhere(['theme_id' => [$value[0]['theme_id']]])
                    ->asArray()
                    ->all();

                foreach ($get_themes as $key => $value) {
                    $all_valid_themes[] = $value['item_id'];
                }
            }

            if (count($all_valid_themes)==1) {
                $all_valid_themes = $all_valid_themes[0];
            } else {
                $all_valid_themes = implode('","', $all_valid_themes);
            }

            $items_query->andWhere('{{%vendor_item}}.item_id IN("'.$all_valid_themes.'")');

        }//if themes 

        //category filter 
        if ($data['item_ids'] != '') {
            $items_query->andWhere('{{%category_path}}.path_id IN (select category_id from {{%category}} where slug IN ("'.str_replace('+', '","', $data['item_ids']).'") and trash = "Default")');
        }

        $items_query
            ->groupBy('{{%vendor_item}}.item_id')
            ->orderBy('{{%image}}.vendorimage_sort_order', SORT_ASC);
        
        $items = $items_query->asArray()->all();

        $customer_events_list = array();

        if (!Yii::$app->user->isGuest) {
            $usermodel = new Users();
            $customer_events_list = $usermodel->get_customer_wishlist_details(
                Yii::$app->user->identity->customer_id
            );
        }

        return $this->renderPartial('product_list_ajax', [
            'items' => $items, 
            'customer_events_list' => $customer_events_list
        ]);
    }

    public function actionLoadMoreItems()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            if ($data['slug'] != '') {
                /* BEGIN CATEGORY*/
                $model1 = Category::find()->select(['category_id', 'category_name'])->where(['slug' => $data['slug']])->asArray()->one();
                if (empty($model1)) {
                    throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
                }
            }

            $limit = $data['limit'];

            $imageData = Vendoritem::find()
                    ->select('{{%vendor_item}}.slug, wi.image_path, {{%vendor_item}}.item_price_per_unit, {{%vendor_item}}.item_name, {{%vendor_item}}.child_category, wvi.item_id, wv.vendor_name')
                    ->leftJoin('{{%image}} as wi', '{{%vendor_item}}.item_id = wi.item_id')
                    ->leftJoin('{{%vendor}} as wv', '{{%vendor_item}}.vendor_id = wv.vendor_id')
                    ->leftJoin('{{%category}} as wc', 'wc.category_id = {{%vendor_item}}.child_category')
                    ->where(['{{%vendor_item}}.trash' => "Default"])
                    ->andWhere(['{{%vendor_item}}.item_approved' => "Yes"])
                    ->andWhere(['{{%vendor_item}}.item_status' => "Active"])
                    ->andWhere(['{{%vendor_item}}.item_for_sale' => "Yes"])
                    ->groupBy('{{%vendor_item}}.item_id')
                    ->having(['{{%vendor_item}}.category_id'=>$model1['category_id']])
                    ->limit(4)
                    //limit 4 offset '.$limit.'
                    ->asArray()
                    ->all();
            return $this->renderPartial('product_list_ajax', ['imageData' => $imageData]);
        }
    }

    public function actionProduct($slug)
    {

        $model = Vendoritem::findOne(['slug' => $slug]);

        if (empty($model)) {
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        }

        $Similar = new \common\models\Featuregroupitem();
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
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
            //$baselink = Yii::getAlias("@s3/vendor_item_images_530/") . 'no_image.jpg';
        }

        /* BEGIN DELIVERY AREAS --VENDOR */

        \Yii::$app->view->registerMetaTag(['property' => 'og:title', 'content' => Yii::$app->name.' - ' . ucfirst($model->vendor->vendor_name)]);
        \Yii::$app->view->registerMetaTag(['property' => 'fb:app_id', 'content' => 157333484721518]);
        \Yii::$app->view->registerMetaTag(['property' => 'og:url', 'content' => Url::toRoute(["shop/product", 'slug' => $model->slug], true)]);
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
            return $this->render('product_detail', [
                'AvailableStock' => $AvailableStock,
                'model' => $model,
                'similiar_item' => $Similar->similiar_details(),
                'vendor_area' => [],
                'my_addresses' => []
            ]);

        } else {
                $vendor_area = Vendorlocation::findAll(['vendor_id' => $model->vendor_id]);
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

            return $this->render('product_detail', [
                'model' => $model,
                'similiar_item' => $Similar->similiar_details(),
                'AvailableStock' => $AvailableStock,
                'customer_events_list' => $customer_events_list,
                'vendor_area' => $vendor_area_list,
                'my_addresses' => $my_addresses,
            ]);
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
            $item = Vendoritem::findOne($_POST['item_id']);
            if ($item) {
                $exist = \common\models\Blockeddate::findOne(['vendor_id' => $item->vendor_id, 'block_date' => date('Y-m-d', strtotime($selectedDate))]);
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
