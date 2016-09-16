<?php

namespace frontend\controllers;

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

            if ($session->has('deliver-location')) {
                $location = $session->get('deliver-location');
                $condition .= ' AND (wvl.area_id IN(' .$location. ')) ';
            }

            if ($session->has('deliver-date')) {
                $date = date('Y-m-d', strtotime($session->get('deliver-date')));
                $condition .= " AND (wv.vendor_id NOT IN(SELECT vendor_id FROM `whitebook_vendor_blocked_date` where block_date = '$date')) ";
            }

            $vendor = implode(',',$ActiveVendors);
            $q  = "select wvi.item_price_per_unit, wvi.item_name, wvi.item_id, wv.vendor_id, wv.vendor_name, wv.vendor_name_ar, wvi.slug from whitebook_vendor_item as wvi ";
            $q .= " left join whitebook_vendor as wv ON wvi.vendor_id = wv.vendor_id";
            $q .= " left join whitebook_image as wi ON wvi.item_id = wi.item_id";
            $q .= " left join whitebook_category as wc ON wc.category_id = wvi.child_category";
            $q .= " left join whitebook_vendor_location as wvl ON wv.vendor_id = wvl.vendor_id";
            $q .= " where (wvi.trash = 'Default') AND (wvi.item_approved = 'Yes') ";
            $q .= " AND (wvi.item_status = 'Active') AND (wvi.type_id = 2) ";
            $q .=   $condition;
            $q .= " AND (wvi.item_for_sale = 'Yes')";
            if ($vendor) {
                $q .= " AND (wvi.vendor_id IN ($vendor))";
            }
            $q .= " AND (wvi.category_id = $Category->category_id) group by wvi.item_id";
            $imageData = Vendoritem::findBySql($q)->limit(12)->all();
        }

        /* END CATEGORY */

        foreach ($imageData as $data) {
            $items[] = $data['item_id'];
        }

        $get_unique_themes = array();
        if (!empty($items)) {
            $theme_names = Themes::loadthemename_item($items);
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

        $vendor = Vendoritem::find()
            ->select('{{%vendor}}.vendor_id, {{%vendor}}.vendor_name, {{%vendor}}.vendor_name_ar, {{%vendor}}.slug')
            ->join('INNER JOIN', '{{%vendor}}', '{{%vendor_item}}.vendor_id = {{%vendor}}.vendor_id')
            ->leftJoin('{{%category}}', '{{%category}}.category_id = {{%vendor_item}}.child_category')
            ->where(['{{%vendor_item}}.vendor_id' => $ActiveVendors])
            ->andWhere(['{{%vendor}}.vendor_status' => "Active"])
            ->andWhere(['{{%vendor}}.approve_status' => "Yes"])
            ->andWhere(['{{%vendor_item}}.item_status' => "Active"])
            ->andWhere(['{{%vendor_item}}.item_approved' => "Yes"])
            ->andWhere(['{{%vendor_item}}.trash' => "Default"])
            ->andWhere(['{{%vendor_item}}.item_for_sale' =>'Yes'])
            ->groupBy('{{%vendor_item}}.vendor_id')
            ->asArray()
            ->all();
        /* END get current category to load sub category */
        /* END GET VENDORS */

        if (Yii::$app->user->isGuest) {

            return $this->render('product_list', [
                'model' => $model, 
                'TopCategories' => $TopCategories,
                'imageData' => $imageData,
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
                'imageData' => $imageData,
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
        if (Yii::$app->request->isAjax) {

            $result = '';
            $data = Yii::$app->request->post();
            $condition = 'AND (wvi.trash = "Default") ';
            $join = '';
            if ($data['slug'] != '') {
            if ($data['location'] != '') {
                $location = explode('+', $data['location']);
                $condition .= ' AND (wvl.area_id IN(' . implode(',', $location) . ')) ';
            }
            /* CATEGORY FILTER */
            if ($data['item_ids'] != '') {
                $ids = explode('+', $data['item_ids']);
                $item_ids = implode('","', $ids);
                $condition .= ' AND (wc.slug IN("' . $item_ids . '")) ';
            }
            if ($data['date'] != '') {
                $date = date('Y-m-d', strtotime($data['date']));
                $condition .= " AND (wv.vendor_id NOT IN(SELECT vendor_id FROM `whitebook_vendor_blocked_date` where block_date = '$date')) ";
            }
            /* THEMES FILTER */
            if ($data['themes'] != '') {
                $theme = explode('+', $data['themes']);
                foreach ($theme as $key => $value) {
                    $themes[] = Themes::find()->select('theme_id')->where(['slug' => [$value]])->asArray()->all();
                }
                $all_valid_themes = array();
                foreach ($themes as $key => $value) {
                    $get_themes = Vendoritemthemes::find()->select('theme_id, item_id')
                        ->where(['trash' => "Default"])
                        ->andWhere(['theme_id' => [$value[0]['theme_id']]])
                        ->asArray()
                        ->all();
                    foreach ($get_themes as $key => $value) {
                        $all_valid_themes[] = $value['item_id'];
                    }
                }
                $all_valid_themes = (count($all_valid_themes) == 1) ? $all_valid_themes[0] : implode('","', $all_valid_themes);
                /* END Multiple themes match comma seperate values in table*/
                $condition .= ' AND wvi.item_id IN("' . $all_valid_themes . '")';
            }
            if ($data['vendor'] != '') {
                $vendor = explode('+', $data['vendor']);
                $v = implode('","', $vendor);
                $condition .= ' AND (wv.slug IN("' . $v . '") AND wv.vendor_id IS NOT NULL) ';
            }
            /* BEGIN PRICE FILTER */
            if ($data['price'] != '') {
                $price = explode('+', $data['price']);
                foreach ($price as $key => $value) {
                    $prices[] = $value;
                    $price_val = explode('-', $value);
                    $price_val1[] = ' AND (wvi.item_price_per_unit between ' . $price_val[0] . ' and ' . $price_val[1] . ') ';
                }
                $condition1 = implode(' OR ', $price_val1);
                $condition .= str_replace('OR AND', 'OR', $condition1);
            }
            /* END PRICE FILTER */
            $model1 = Category::find()->select(['category_id', 'category_name'])->where(['slug' => $data['slug']])->asArray()->one();
            $active_vendors = Vendor::loadvalidvendorids($model1['category_id']);
            if (!is_null($model1)) {
                $vendor_ids = implode(',', $active_vendors);
                $category_id = $model1['category_id'];
                $q  = "select wvi.item_price_per_unit, wvi.item_name, wvi.item_id, wv.vendor_id, wv.vendor_name, wv.vendor_name_ar, wvi.slug from whitebook_vendor_item as wvi ";
                $q .= " left join whitebook_vendor as wv ON wvi.vendor_id = wv.vendor_id";
                $q .= " left join whitebook_category as wc ON wc.category_id = wvi.child_category";
                $q .= " left join whitebook_vendor_location as wvl ON wv.vendor_id = wvl.vendor_id";
                $q .= " where (wvi.item_approved = 'Yes') AND (wvi.item_status = 'Active') AND (wvi.type_id = 2) ";
                $q .=   $condition;
                $q .= " AND (wvi.item_for_sale = 'Yes') AND (wvi.vendor_id IN ($vendor_ids))";
                $q .= " AND (wvi.category_id = $category_id) group by wvi.item_id";
                $result = Vendoritem::findBySql($q)->limit(12)->all();
            }
        }
                $customer_events_list = [];
                if (!Yii::$app->user->isGuest) {
                    $usermodel = new Users();
                    $customer_events_list = $usermodel->get_customer_wishlist_details(Yii::$app->user->identity->customer_id);
                }
                return $this->renderPartial('product_list_ajax', [
                  'imageData' => $result,
                  'customer_events_list' => $customer_events_list
                ]);
        }
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

        \Yii::$app->view->registerMetaTag([
            'property' => 'og:title',
            'content' => 'Whitebook Application - ' . ucfirst($model->vendor->vendor_name)
        ]);

        \Yii::$app->view->registerMetaTag([
            'property' => 'og:url',
            'content' => Url::toRoute(["shop/product", 'slug' => $model->slug], true)
        ]);

        \Yii::$app->view->registerMetaTag(['property' => 'og:image', 'content' => $baselink]);
        \Yii::$app->view->registerMetaTag(['property' => 'og:image:width', 'content' => '200']);
        \Yii::$app->view->registerMetaTag(['property' => 'og:image:height', 'content' => '200']);

        \Yii::$app->view->registerMetaTag([
            'property' => 'og:site_name',
            'content' => 'Whitebook Application - ' . ucfirst($model->vendor->vendor_name) . ' - ' . ucfirst($model->item_name)
        ]);

        \Yii::$app->view->registerMetaTag([
            'property' => 'og:description',
            'content' => trim(strip_tags($model->item_description))
        ]);

        if (Yii::$app->user->isGuest) {
            return $this->render('product_detail', [
                'AvailableStock' => $AvailableStock,
                'model' => $model,
                'similiar_item' => $Similar->similiar_details(),
                'vendor_area' => []
            ]);

        } else {
            $user = new Users();
            $customer_events_list = $user->get_customer_wishlist_details(Yii::$app->user->identity->customer_id);

            return $this->render('product_detail', [
                'model' => $model,
                'similiar_item' => $Similar->similiar_details(),
                'AvailableStock' => $AvailableStock,
                'customer_events_list' => $customer_events_list,
                'vendor_area' => Vendorlocation::findAll(['vendor_id' => $model->vendor_id])
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
