<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use frontend\models\Users;
use frontend\models\Website;
use common\models\SubCategory;
use common\models\Category;
use common\models\Vendoritem;
use common\models\Vendoritemthemes;
use common\models\CategoryPath;
use frontend\models\Themes;
use frontend\models\Vendor;

/**
 * Category controller.
 */
class PlanController extends BaseController
{
    public function init()
    {
        parent::init();
    }

    public function actionPlans()
    {
        \Yii::$app->view->title = 'The White Book | Plan';
        \Yii::$app->view->registerMetaTag(['name' => 'description', 'content' => Yii::$app->params['META_DESCRIPTION']]);
        \Yii::$app->view->registerMetaTag(['name' => 'keywords', 'content' => Yii::$app->params['META_KEYWORD']]);
        $db = Yii::$app->db;// or Category::getDb()
      /*  $category = $db->cache(function ($db) use ($id) {
            return Category::find()->select('category_id,category_name, slug')->where(['category_level'=>0,'category_allow_sale'=>'yes','trash'=>'Default'])->asArray()->all();
        }, CACHE_TIMEOUT);*/
        $category = Category::find()->select('category_id,category_name, slug')->where(['category_level' => 0, 'category_allow_sale' => 'yes', 'trash' => 'Default'])->asArray()->all();

        return $this->render('plans', ['category' => $category]);
    }

    public function actionPlan($slug = '')
    {
        $model = new Website();

        if ($slug == '') {
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        }

        $category_model = Category::find()
            ->select(['category_id', 'category_name_ar', 'category_name'])
            ->where(['slug' => $slug])
            ->asArray()
            ->one();

        if (empty($category_model)) {
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        }
         
        $seo_content = Website::SEOdata('category', 'category_id', $category_model['category_id'], array('category_name', 'category_meta_title', 'category_meta_keywords', 'category_meta_description'));

        \Yii::$app->view->title = ($seo_content[0]['category_meta_title']) ? $seo_content[0]['category_meta_title'] : Yii::$app->params['SITE_NAME'].' | '.$seo_content[0]['category_name'];
        \Yii::$app->view->registerMetaTag(['name' => 'description', 'content' => ($seo_content[0]['category_meta_description']) ? $seo_content[0]['category_meta_description'] : Yii::$app->params['META_DESCRIPTION']]);
        \Yii::$app->view->registerMetaTag(['name' => 'keywords', 'content' => ($seo_content[0]['category_meta_keywords']) ? $seo_content[0]['category_meta_keywords'] : Yii::$app->params['META_KEYWORD']]);

        $top_categories = Category::find()
                ->where(['category_level' => 0, 'trash' => 'Default'])
                ->orderBy('sort')
                ->asArray()
                ->all();
                
        /* BEGIN GET VENDORS */
        $active_vendors = Vendor::loadvalidvendorids($category_model['category_id']);        

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
            ->leftJoin('{{%image}}', '{{%vendor_item}}.item_id = {{%image}}.item_id')
            ->leftJoin('{{%vendor}}', '{{%vendor_item}}.vendor_id = {{%vendor}}.vendor_id')
            ->where([
                '{{%vendor_item}}.trash' => 'Default',
                '{{%category_path}}.path_id' => $category_model['category_id'],
                '{{%vendor_item}}.item_approved' => 'Yes',
                '{{%vendor_item}}.item_status' => 'Active',
            ]);            
        
        $item_query->andWhere(['in', '{{%vendor_item}}.vendor_id', $active_vendors]);

        $items = $item_query->groupBy('{{%vendor_item}}.item_id')
            ->orderBy('{{%image}}.vendorimage_sort_order', SORT_ASC)
            ->asArray()
            ->all();

        $get_unique_themes = array();

        if (!empty($items)) {

            //get array of item_id 
            $item_ids = [];

            foreach($items as $item) {
                $item_ids[] = $item['item_id'];
            }

            $theme_names = Themes::loadthemename_item($item_ids);
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

        if(Yii::$app->language == "en"){
            $themes = Themes::load_all_themename($get_unique_themes, 'theme_name');
        }else{
            $themes = Themes::load_all_themename($get_unique_themes, 'theme_name_ar');
        }

        $vendor = Vendoritem::find()
            ->select('{{%vendor}}.vendor_id, {{%vendor}}.vendor_name, {{%vendor}}.vendor_name_ar, {{%vendor}}.slug')
            ->join('INNER JOIN', '{{%vendor}}', '{{%vendor_item}}.vendor_id = {{%vendor}}.vendor_id')
            ->leftJoin('{{%category}}', '{{%category}}.category_id = {{%vendor_item}}.child_category')
            ->where(['{{%vendor_item}}.vendor_id' => $active_vendors])
            ->andWhere(['{{%vendor}}.vendor_status' => "Active"])
            ->andWhere(['{{%vendor}}.approve_status' => "Yes"])
            ->andWhere(['{{%vendor_item}}.item_status' => "Active"])
            ->andWhere(['{{%vendor_item}}.item_approved' => "Yes"])
            ->andWhere(['{{%vendor_item}}.trash' => "Default"])
            ->groupBy('{{%vendor_item}}.vendor_id')
            ->asArray()
            ->all();
        
        if (Yii::$app->user->isGuest) {
            $usermodel = new Users();            
            $customer_events_list = $usermodel->get_customer_wishlist_details(Yii::$app->user->identity->id);
        } else {
            $customer_events_list = [];
        }

        return $this->render('product_list', [
            'model' => $model, 
            'top_categories' => $top_categories,
            'items' => $items,
            'themes' => $themes, 
            'vendor' => $vendor, 
            'category_id' => $category_model['category_id'],
            'slug' => $slug, 
            'customer_events_list' => $customer_events_list
        ]);
    }

    public function actionLoaditems()
    {
        if (!Yii::$app->request->isAjax) {
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        }

        $data = Yii::$app->request->post();

        //items only from active vendors 
        $top_category = Category::find()
            ->select(['category_id', 'category_name'])
            ->where(['slug' => $data['slug']])
            ->asArray()
            ->one();

        $active_vendors = Vendor::loadvalidvendorids($top_category['category_id']);

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

        //vendor filter
        if ($data['vendor'] != '') {
            $items_query->andWhere(['in', '{{%vendor}}.slug', explode('+', $data['vendor'])]);
        }

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

    public function actionLoadsearchitems()
    {
        if (!Yii::$app->request->isAjax) {
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        }

        $data = Yii::$app->request->post();

        $active_vendors = Vendor::loadvalidvendorids();

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
            ]);

        $item_query->andWhere(['in', '{{%vendor_item}}.vendor_id', $active_vendors]);


        //search query 
        if ($data['search'] != '') {
            $values = $this->qstring($data['search']);
            $search = $data['search'];
            $items_query->andWhere(['like', '{{%vendor_item}}.item_name', $search]);
        }

        //vendor filter
        if ($data['vendor'] != '') {
            $items_query->andWhere(['in', '{{%vendor}}.slug', explode('+', $data['vendor'])]);
        }

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

    public function actionLoadmoreitems()
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

    /* BEGIN VENDOR PROFILE PAGE LOAD ITEMS BASED ON VENDOR */
    public function actionLoadvendoritems()
    {
        if (!Yii::$app->request->isAjax) {
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        }

        $data = Yii::$app->request->post();

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
            ]);

        //search query 
        if (!empty($data['search'])) {
            $values = $this->qstring($data['search']);
            $search = $data['search'];
            $items_query->andWhere(['like', '{{%vendor_item}}.item_name', $search]);
        }

        //vendor filter
        if ($data['slug'] != '') {
            $items_query->andWhere(['in', '{{%vendor}}.slug', explode('+', $data['slug'])]);
        }

        //price filter 
         if (!empty($data['price']) != '') {

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
        if ($data['category_name'] != '') {
            $items_query->andWhere('{{%category_path}}.path_id IN (select category_id from {{%category}} where slug IN ("'.str_replace('+', '","', $data['category_name']).'") and trash = "Default")');
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
    /* END VENDOR PROFILE PAGE LOAD ITEMS BASED ON VENDOR */

    /* BEGIN LOAD SEARCH RESULTS PAGE ITEMS */
    public function actionLoadsearchresultitems()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $condition = '';
            $join = '';
            if (!empty($data['slug'])) {
                $cat_item_details = \frontend\models\Category::category_search_details($data['slug']);
                if (!empty($cat_item_details)) {
                    $cat_id = $cat_item_details[0]['category_id'];
                    $join .= ' (wvi.category_id  = ("'.$cat_id.'")
   or wvi.subcategory_id  = ("'.$cat_id.'") or wvi.child_category  = ("'.$cat_id.'") OR wvi.item_name
   LIKE "%'.$data['slug'].'%" )';
                }

                if (!empty($data['vendor'])) {
                        $vendor = explode('+', $data['vendor']);
                    //print_r($vendor);die;
                    foreach ($vendor as $key => $val) {
                        $vendor_ids[] = Vendor::Vendorid_item($val)['vendor_id'];
                    }
                    //print_r($vendor_ids);die;
                    $v = implode('","', $vendor_ids);

                   // $active_vendors = Vendor::loadvalidvendors();
                    $condition .= ' AND wvi.vendor_id IN("'.$v.'")';
                }

            /* THEMES FILTER */
            if ($data['themes'] != '') {
                $theme = explode('+', $data['themes']);
                foreach ($theme as $key => $value) {
                    $themes[] = Themes::find()->select('theme_id')->where(['slug'=>[$value]])->asArray()->all();
                }

                $all_valid_themes = array();
                foreach ($themes as $key => $value) {
                    $get_themes = Vendoritemthemes::find()->select('theme_id, item_id')
                                    ->where(['trash'=>"Default"])
                                    ->andWhere(['theme_id'=>[$value[0]['theme_id']]])
                                    ->asArray()
                                    ->all();
                foreach ($get_themes as $key => $value) {
                     $all_valid_themes[] = $value['item_id'];
                 }
                }

                if (count($all_valid_themes) <= 1) {
                    $all_valid_themes = $all_valid_themes[0];
                } else {
                    //$all_valid_themes = implode('","', $all_valid_themes);
                    $all_valid_themes = implode(',', $all_valid_themes);
                }
             /* END Multiple themes match comma seperate values in table*/

          //  $join .= ' inner join whitebook_theme as wt ON wt.slug REGEXP "'.$theme_ids.'" ';
              $condition .= ' AND wvi.item_id IN('.$all_valid_themes.') ';
            }

             /* BEGIN PRICE FILTER */
            if ($data['price'] != '') {
                $price = explode('+', $data['price']);
                foreach ($price as $key => $value) {
                    $prices[] = $value;
                    $price_val = explode('-', $value);
                    $price_val1[] = 'AND (wvi.item_price_per_unit between '.$price_val[0].' and '.$price_val[1].')';
                }
                $condition1 = implode(' OR ', $price_val1);
                $condition .= str_replace('OR AND', 'OR', $condition1);
            }

            /* END PRICE FILTER */

                $q = 'select wvi.category_id, wi.image_path, wvi.item_price_per_unit, wvi.item_name, wvi.slug, wvi.child_category, wvi.item_id, wv.vendor_name, wvi.category_id, count(*) as total from whitebook_vendor_item as wvi ';
                $q .= 'left join whitebook_image as wi on wvi.item_id = wi.item_id left join whitebook_vendor as wv on wvi.vendor_id = wv.vendor_id ';
                $q .= 'where wvi.trash = "Default" AND wvi. item_approved = "Yes" AND wvi.item_status = "Active" AND wvi.type_id = "2" AND wvi.item_for_sale = "Yes" AND wi.module_type = "vendor_item" AND wv.slug = "'.$data["slug"].'" ';
                $q .= $condition;
                $q .= 'group by wi.item_id LIMIT 12';

                $result = Vendoritem::findBySql($q)->asArray()->all();

                if (Yii::$app->user->isGuest) {
                    $customer_events_list = [];
                } else {
                    $customer_id = Yii::$app->user->identity->customer_id;
                    $usermodel = new Users();
                    $customer_events_list = $usermodel->get_customer_wishlist_details($customer_id);
                }
                return $this->renderPartial('loaditems', ['imageData' => $result, 'customer_events_list' => $customer_events_list]);
            }
        }
    }
    /* END LOAD SEARCH RESULTS PAGE ITEMS */
    public function actionToggle()
    {
        $model = new SubCategory();

        return  $this->render('toggle', ['model' => $model]);
    }

    public function qstring($val)
    {
        $cat = explode('+', $val);
       // $categories = implode('","', $cat);
        print_r($cat);die;
        return $ids = '"'.$cat.'"';
    }

    public function actionAddevent()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $model = Vendoritem::find()->where('item_id='.$data['item_id'])->asArray()->all();
            $this->renderPartial('addevent', array('model' => $model));
        }
    }
}
