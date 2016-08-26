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

        if ($slug != '') {
             
            $model1 = Category::find()->select(['category_id', 'category_name_ar', 'category_name'])->where(['slug' => $slug])->asArray()->one();

            if (empty($model1)) {
                throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
            }
             
            $seo_content = Website::SEOdata('category', 'category_id', $model1['category_id'], array('category_name', 'category_meta_title', 'category_meta_keywords', 'category_meta_description'));

            \Yii::$app->view->title = ($seo_content[0]['category_meta_title']) ? $seo_content[0]['category_meta_title'] : Yii::$app->params['SITE_NAME'].' | '.$seo_content[0]['category_name'];
            \Yii::$app->view->registerMetaTag(['name' => 'description', 'content' => ($seo_content[0]['category_meta_description']) ? $seo_content[0]['category_meta_description'] : Yii::$app->params['META_DESCRIPTION']]);
            \Yii::$app->view->registerMetaTag(['name' => 'keywords', 'content' => ($seo_content[0]['category_meta_keywords']) ? $seo_content[0]['category_meta_keywords'] : Yii::$app->params['META_KEYWORD']]);

            /* BEGIN CATEGORY EXIST OR NOT*/
            if (empty($model1)) {
                $imageData = '';
            }
            /* END CATEGORY EXIST OR NOT*/

            $top_categories = Category::find()
                    ->where(['category_level' => 0, 'trash' => 'Default'])
                    ->orderBy('sort')
                    ->asArray()
                    ->all();
                    
            /* BEGIN GET VENDORS */
            $active_vendors = Vendor::loadvalidvendorids($model1['category_id']);        

            if (!is_null($model1)) {

                $imageData = Vendoritem::find()
                    ->select(['{{%image}}.image_path, {{%vendor_item}}.item_price_per_unit, {{%vendor_item}}.item_name,
                        {{%vendor_item}}.slug, {{%vendor_item}}.child_category, {{%vendor_item}}.item_id,
                        {{%vendor}}.vendor_name'])
                    ->leftJoin('{{%image}}', '{{%vendor_item}}.item_id = {{%image}}.item_id')
                    ->leftJoin('{{%vendor}}', '{{%vendor_item}}.vendor_id = {{%vendor}}.vendor_id')
                    ->leftJoin('{{%category}}', '{{%category}}.category_id = {{%vendor_item}}.child_category')
                    ->where(['{{%vendor_item}}.trash' => "Default"])
                    ->andWhere(['{{%vendor_item}}.item_approved' => "Yes"])
                    ->andWhere(['{{%vendor_item}}.item_status' => "Active"])
                    ->andWhere(['{{%vendor_item}}.vendor_id' => $active_vendors])
                    ->andWhere(['{{%vendor_item}}.category_id' => $model1['category_id']])
                    ->groupBy('{{%vendor_item}}.item_id')
                    ->asArray()
                    ->all();

            }
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

        if(Yii::$app->language == "en"){
            $themes = Themes::load_all_themename($get_unique_themes, 'theme_name');
        }else{
            $themes = Themes::load_all_themename($get_unique_themes, 'theme_name_ar');
        }

        /* VENDOR HAVIG ATLEAST ONE PRODUCT */
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
        /* END get current category to load sub category */

        /* END GET VENDORS */
        if (Yii::$app->user->isGuest) {

            return $this->render('product_list', [
                'model' => $model, 
                'top_categories' => $top_categories,
                'imageData' => $imageData,
                'themes' => $themes, 
                'vendor' => $vendor, 
                'slug' => $slug,
                'category_id' => $model1['category_id']
            ]);

        } else {
            $usermodel = new Users();
            
            $customer_events_list = $usermodel->get_customer_wishlist_details(Yii::$app->user->identity->id);
            
            return $this->render('product_list', [
                'model' => $model, 
                'top_categories' => $top_categories,
                'imageData' => $imageData,
                'themes' => $themes, 
                'vendor' => $vendor, 
                'category_id' => $model1['category_id'],
                'slug' => $slug, 
                'customer_events_list' => $customer_events_list
            ]);
        }
    }


    public function actionLoaditems()
    {
        if (Yii::$app->request->isAjax) {

            $data = Yii::$app->request->post();
            $condition = '{{%vendor_item}}.trash = "Default"';
            $join = '';
            if ($data['slug'] != '') {

                  /* CATEGORY FILTER */
            if ($data['item_ids'] != '') {
                $condition .= ' AND {{%category}}.slug IN("'.$data['item_ids'].'")';
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

                if (count($all_valid_themes)==1) {
                    $all_valid_themes = $all_valid_themes[0];
                } else {
                    $all_valid_themes = implode('","', $all_valid_themes);
                }

             /* END Multiple themes match comma seperate values in table*/
                $condition .= ' AND {{%vendor_item}}.item_id IN("'.$all_valid_themes.'")';
                }

                if ($data['vendor'] != '') {
                    $vendor = explode('+', $data['vendor']);
                    $v = implode('","', $vendor);
                    $condition .= ' AND {{%vendor}}.slug IN("'.$v.'") AND {{%vendor}}.vendor_id IS NOT NULL';
                }
                /* BEGIN PRICE FILTER */
                if ($data['price'] != '') {
                    $price = explode('+', $data['price']);
                    foreach ($price as $key => $value) {
                        $prices[] = $value;
                        $price_val = explode('-', $value);
                        $price_val1[] = ' AND ({{%vendor_item}}.item_price_per_unit between '.$price_val[0].' and '.$price_val[1].')';
                    }
                    $condition1 = implode(' OR ', $price_val1);
                    $condition .= str_replace('OR AND', 'OR', $condition1);
                }
                /* END PRICE FILTER */

                $model1 = Category::find()->select(['category_id', 'category_name'])->where(['slug' => $data['slug']])->asArray()->one();

                $active_vendors = Vendor::loadvalidvendorids($model1['category_id']);
                // echo $condition;die;
                if (!is_null($model1)) {
                $imageData = Vendoritem::find()
                    ->select(['{{%vendor_item}}.category_id','{{%image}}.image_path','{{%vendor_item}}.item_price_per_unit',
                        '{{%vendor_item}}.item_name','{{%vendor_item}}.slug','{{%vendor_item}}.child_category','{{%vendor_item}}.item_id','{{%vendor}}.vendor_name'])
                    ->leftJoin('{{%image}}', '{{%vendor_item}}.item_id = {{%image}}.item_id')
                    ->leftJoin('{{%vendor}}', '{{%vendor_item}}.vendor_id = {{%vendor}}.vendor_id')
                    ->leftJoin('{{%category}}', '{{%category}}.category_id = {{%vendor_item}}.child_category')
                    ->where($condition)
                    ->andWhere(['{{%vendor_item}}.item_approved' => "Yes"])
                    ->andWhere(['{{%vendor_item}}.item_status' => "Active"])
                    ->andWhere(['{{%vendor_item}}.vendor_id' =>$active_vendors])
                    ->andWhere(['{{%vendor_item}}.category_id' => $model1['category_id']])
                    //->andWhere($condition)
                    ->groupBy('{{%vendor_item}}.item_id')
                    ->having(['{{%vendor_item}}.category_id'=>$model1['category_id']])
                    ->limit(12)
                    ->asArray()
                    ->all();
                    }
                }
            }
            $customer_events_list = array();

            if (!Yii::$app->user->isGuest) {
                $usermodel = new Users();
                $customer_events_list = $usermodel->get_customer_wishlist_details(Yii::$app->user->identity->customer_id);
            }

        return $this->renderPartial('product_list_ajax', ['imageData' => $imageData, 'customer_events_list' => $customer_events_list]);
    }

    public function actionLoadsearchitems()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $condition = '{{%vendor_item}}.trash = "Default"';
            $join = '';

            if (!empty($data['slug'])) {
            if ($data['search'] != '') {
                $values = $this->qstring($data['search']);
                $search = $data['search'];
                $condition .= 'AND {{%vendor_item}}.item_name LIKE "%'.$search.'%"';
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
                    $all_valid_themes = implode('","', $all_valid_themes);
                }

            /* END Multiple themes match comma seperate values in table*/

              $condition .= ' AND {{%vendor_item}}.item_id IN("'.$all_valid_themes.'") ';
            }
                if (!empty($data['vendor'])) {
                    $vendor = explode('+', $data['vendor']);
                    $v = implode('","', $vendor);

            /* BEGIN GET VENDORS */
                $active_vendors = Vendor::loadvalidvendors();
                    $condition .= 'AND {{%vendor_item}}.vendor_id IN("'.$active_vendors.'") AND {{%vendor}}.slug IN("'.$v.'") AND {{%vendor}}.vendor_id IS NOT NULL';
                }

            /* BEGIN PRICE FILTER */
            if ($data['price'] != '') {
                $price = explode('+', $data['price']);
                foreach ($price as $key => $value) {
                    $prices[] = $value;
                    $price_val = explode('-', $value);
                    $price_val1[] = 'AND ({{%vendor_item}}.item_price_per_unit between '.$price_val[0].' and '.$price_val[1].')';
                }
                $condition1 = implode(' OR ', $price_val1);
                $condition .= str_replace('OR AND', 'OR', $condition1);
            }
            /* END PRICE FILTER */
            $cat_item_details = $model->category_search_details($search);

            $category = new Category();
                if (!empty($data['search'])) {
                    $cat_item_details = $model->category_search_details($data['search']);
                    $cat_id = $cat_item_details[0]['category_id'];
                } else {
                    $cat_id = '';
                }

            $imageData = Vendoritem::find()
                    ->select(['{{%vendor_item}}.category_id, {{%image}}.image_path, {{%vendor_item}}.item_price_per_unit, {{%vendor_item}}.item_name,{{%vendor_item}}.slug, {{%vendor_item}}.child_category, {{%vendor_item}}.item_id, {{%vendor}}.vendor_name'])
                    ->leftJoin('{{%image}}', '{{%vendor_item}}.item_id = {{%image}}.item_id')
                    ->leftJoin('{{%vendor}}', '{{%vendor_item}}.vendor_id = {{%vendor}}.vendor_id')
                    ->leftJoin('{{%category}}', '{{%category}}.category_id = {{%vendor_item}}.child_category')
                    ->where(['{{%vendor_item}}.trash' => "Default"])
                    ->andWhere(['{{%vendor_item}}.item_approved' => "Yes"])
                    ->andWhere(['{{%vendor_item}}.item_status' => "Active"])
                    ->andWhere(['{{%vendor_item}}.type_id' => "2"])
                    ->andWhere(['{{%vendor_item}}.item_for_sale' => "Yes"])
                    ->andWhere(['wi.module_type' => "vendor_item"])
                    ->andWhere([$condition])
                    ->groupBy('{{%vendor_item}}.item_id')
                    ->having(['{{%vendor_item}}.category_id'=>$model1['category_id']])
                    ->limit(12)
                    ->asArray()
                    ->all();
            }
        }

          $customer_events_list = array();

        if (!Yii::$app->user->isGuest) {
            $usermodel = new Users();
            $customer_events_list = $usermodel->get_customer_wishlist_details(Yii::$app->user->identity->customer_id);
        }

        return $this->renderPartial('product_list_ajax', ['imageData' => $imageData, 'customer_events_list' => $customer_events_list]);
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
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $condition = '{{%vendor_item}}.trash = "Default" ';
            $join = '';
        /* CATEGORY */
        if ($data['category_name'] != '') {
            $category_val = explode('+', $data['category_name']);
            $cat = implode('","', $category_val);

            $condition .= 'AND {{%category}}.slug IN("'.$cat.'")';
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
                $all_valid_themes = implode('","', $all_valid_themes);
            }

            /* END Multiple themes match comma seperate values in table*/

           $condition .= ' AND {{%vendor_item}}.item_id IN("'.$all_valid_themes.'") ';
        }
        /* END THEME FILTER */

        /* BEGIN PRICE FILTER */
            if ($data['price'] != '') {
                $price = explode('+', $data['price']);
                foreach ($price as $key => $value) {
                    $prices[] = $value;
                    $price_val = explode('-', $value);
                    $price_val1[] = 'AND ({{%vendor_item}}.item_price_per_unit between '.$price_val[0].' and '.$price_val[1].')';
                }
                $condition1 = implode(' OR ', $price_val1);
                $condition .= str_replace('OR AND', 'OR', $condition1);
            }
            /* END PRICE FILTER */

            $vendorData = Vendoritem::find()
                ->select(['{{%vendor_item}}.category_id, {{%image}}.image_path, {{%vendor_item}}.item_price_per_unit, {{%vendor_item}}.item_name,{{%vendor_item}}.slug, {{%vendor_item}}.child_category, {{%vendor_item}}.item_id, {{%vendor}}.vendor_name'])
                ->leftJoin('{{%image}}', '{{%vendor_item}}.item_id = {{%image}}.item_id')
                ->leftJoin('{{%vendor}}', '{{%vendor_item}}.vendor_id = {{%vendor}}.vendor_id')
                ->leftJoin('{{%category}}', '{{%category}}.category_id = {{%vendor_item}}.category_id')
                ->where($condition)
                ->andWhere(['{{%vendor_item}}.item_approved' => "Yes"])
                ->andWhere(['{{%vendor_item}}.item_status' => "Active"])
                ->andWhere(['{{%vendor_item}}.type_id' => "2"])
                ->andWhere(['{{%vendor_item}}.item_for_sale' => "Yes"])
                ->andWhere(['{{%vendor}}.slug' => $data['slug']])
                ->groupBy('{{%vendor_item}}.item_id')
                //->limit(12)
                ->asArray()
                ->all();

           if (!Yii::$app->user->isGuest) {
                $usermodel = new Users();
                $customer_events_list = $usermodel->get_customer_wishlist_details(Yii::$app->user->identity->customer_id);

                return $this->renderPartial('product_list_ajax', ['imageData' => $vendorData,'customer_events_list' => $customer_events_list]);
            } else {
                return $this->renderPartial('product_list_ajax', ['imageData' => $vendorData]);
            }
        }
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
                $cat_item_details = Category::category_search_details($data['slug']);
                if (!empty($cat_item_details)) {
                    $cat_id = $cat_item_details[0]['category_id'];
                    $join .= ' ({{%vendor_item}}.category_id  = ("'.$cat_id.'")
   or {{%vendor_item}}.subcategory_id  = ("'.$cat_id.'") or {{%vendor_item}}.child_category  = ("'.$cat_id.'") OR {{%vendor_item}}.item_name
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
                $condition .= ' AND {{%vendor_item}}.vendor_id IN("'.$v.'")';
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
                    $all_valid_themes = implode('","', $all_valid_themes);
                }
             /* END Multiple themes match comma seperate values in table*/

          //  $join .= ' inner join whitebook_theme as wt ON wt.slug REGEXP "'.$theme_ids.'" ';
              $condition .= ' AND {{%vendor_item}}.item_id IN("'.$all_valid_themes.'") ';
            }

             /* BEGIN PRICE FILTER */
            if ($data['price'] != '') {
                $price = explode('+', $data['price']);
                foreach ($price as $key => $value) {
                    $prices[] = $value;
                    $price_val = explode('-', $value);
                    $price_val1[] = 'AND ({{%vendor_item}}.item_price_per_unit between '.$price_val[0].' and '.$price_val[1].')';
                }
                $condition1 = implode(' OR ', $price_val1);
                $condition .= str_replace('OR AND', 'OR', $condition1);
            }
            /* END PRICE FILTER */
            $imageData = Vendoritem::find()
                    ->select('{{%vendor_item}}.category_id, wi.image_path, {{%vendor_item}}.item_price_per_unit, {{%vendor_item}}.item_name,{{%vendor_item}}.slug, {{%vendor_item}}.child_category, wvi.item_id, wv.vendor_name,{{%vendor_item}}.category_id, count(*) as total')
                    ->leftJoin('{{%image}} as wi', '{{%vendor_item}}.item_id = wi.item_id')
                    ->leftJoin('{{%vendor}} as wv', '{{%vendor_item}}.vendor_id = wv.vendor_id')
                    ->where(['{{%vendor_item}}.trash' => "Default"])
                    ->andWhere(['{{%vendor_item}}.item_approved' => "Yes"])
                    ->andWhere(['{{%vendor_item}}.item_status' => "Active"])
                    ->andWhere(['{{%vendor_item}}.type_id' => "2"])
                    ->andWhere(['{{%vendor_item}}.item_for_sale' => "Yes"])
                    ->andWhere(['wi.module_type' => "vendor_item"])
                    ->andWhere([$join.$condition])
                    ->andWhere(['wv.slug' => $data['slug']])
                    ->groupBy('wi.item_id')
                    ->limit(12)
                    ->asArray()
                    ->all();
                $customer_id = Yii::$app->user->identity->customer_id;
                $usermodel = new Users();
                $customer_events_list = $usermodel->get_customer_wishlist_details($customer_id);
                if (!empty($customer_id)) {
                    return $this->renderPartial('product_list_ajax', ['imageData' => $imageData]);
                } else {
                    return $this->renderPartial('product_list_ajax', ['imageData' => $imageData, 'customer_events_list' => $customer_events_list]);
                }
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
