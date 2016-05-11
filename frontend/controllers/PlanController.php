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
        Yii::$app->language = 'en-EN';
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
            /* BEGIN CATEGORY*/
        $model1 = Category::find()->select(['category_id', 'category_name'])->where(['slug' => $slug])->asArray()->one();
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

        /* BEGIN GET VENDORS */
        $active_vendors = Vendor::loadvalidvendorids($model1['category_id']);

        if (!is_null($model1)) {

            $imageData = Vendoritem::find()
                    ->select('wi.image_path, {{%vendor_item}}.item_price_per_unit, {{%vendor_item}}.item_name,{{%vendor_item}}.slug, {{%vendor_item}}.child_category, {{%vendor_item}}.item_id, wv.vendor_name')
                    ->leftJoin('{{%image}} as wi', '{{%vendor_item}}.item_id = wi.item_id')
                    ->leftJoin('{{%vendor}} as wv', '{{%vendor_item}}.vendor_id = wv.vendor_id')
                    ->leftJoin('{{%category}} as wc', 'wc.category_id = {{%vendor_item}}.child_category')
                    ->where(['{{%vendor_item}}.trash' => "Default"])
                    ->andWhere(['{{%vendor_item}}.item_approved' => "Yes"])
                    ->andWhere(['{{%vendor_item}}.item_status' => "Active"])
                    ->andWhere(['{{%vendor_item}}.type_id' => "2"])
                    ->andWhere(['{{%vendor_item}}.item_for_sale' => "Yes"])
                    ->andWhere(['wi.module_type' => "vendor_item"])
                    ->andWhere(['{{%vendor_item}}.vendor_id' =>[$active_vendors]])
                    ->andWhere(['{{%vendor_item}}.category_id' => $model1['category_id']])
                    ->groupBy('{{%vendor_item}}.item_id')
                    ->asArray()
                    ->all();
            }
        }

        /* END CATEGORY */

          foreach ($imageData as $data) {
            $k[] = $data['item_id'];
        }
        $p = array();
        if (!empty($k)) {
            $result = Themes::loadthemename_item($k);
            $out1[] = array();
            $out2[] = array();
            foreach ($result as $r) {
                if (is_numeric($r['theme_id'])) {
                    $out1[] = $r['theme_id'];
                //$out2[]=0;
                }
                if (!is_numeric($r['theme_id'])) {
                    $out2[] = explode(',', $r['theme_id']);
                }
            }
            foreach ($out2 as $id) {
                foreach ($id as $key) {
                    $p[] = $key;
                }
            }
            if (count($out1)) {
                foreach ($out1 as $o) {
                    if (!empty($o)) {
                        $p[] = $o;
                    }
                }
            }
            $p = array_unique($p);
        }
        $themes = Themes::load_all_themename($p);

        /* VENDOR HAVIG ATLEAST ONE PRODUCT */
        $vendor = Vendoritem::find()
            ->select('wv.vendor_id,wv.vendor_name,wv.slug')
            ->join('INNER JOIN', '{{%vendor}} as wv', '{{%vendor_item}}.vendor_id = wv.vendor_id')
            ->leftJoin('{{%category}} as wc', 'wc.category_id = {{%vendor_item}}.child_category')
            ->where(['{{%vendor_item}}.vendor_id' => [$active_vendors]])
            ->andWhere(['wv.vendor_status' => "Active"])
            ->andWhere(['wv.approve_status' => "Yes"])
            ->andWhere(['{{%vendor_item}}.item_status' => "Active"])
            ->andWhere(['{{%vendor_item}}.item_approved' => "Yes"])
            ->andWhere(['{{%vendor_item}}.trash' => "Default"])
            ->andWhere(['{{%vendor_item}}.item_for_sale' =>'Yes'])
            ->groupBy('{{%vendor_item}}.vendor_id')
            ->asArray()
            ->all();
            //print_r($vendor);die;
        /* END get current category to load sub category */

        /* END GET VENDORS */
        if (Yii::$app->user->isGuest) {
            return $this->render('planvenues', ['model' => $model, 'imageData' => $imageData,
            'themes' => $themes, 'vendor' => $vendor, 'slug' => $slug]);
        } else {
                $usermodel = new Users();
                $customer_events_list = $usermodel->get_customer_wishlist_details(Yii::$app->user->identity->id);
                return $this->render('planvenues', ['model' => $model, 'imageData' => $imageData,
                'themes' => $themes, 'vendor' => $vendor, 'slug' => $slug, 'customer_events_list' => $customer_events_list]);
            } 
        }


    public function actionLoaditems()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $condition = '';
            $join = '';
            if ($data['slug'] != '') {
                /* CATEGORY FILTER */
            if ($data['item_ids'] != '') {
                $values = $this->qstring($data['item_ids']);
               // $condition .= "AND wc.slug IN('.$values.')";
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
                //print_r($all_valid_themes);die;
                //$condition .= ' AND wvi.item_id IN('.$all_valid_themes.') ';
                }

                if ($data['vendor'] != '') {
                    $vendor = explode('+', $data['vendor']);
                    $v = implode('","', $vendor);
                    //$condition .= 'AND wv.slug IN("'.$v.'") AND wv.vendor_id IS NOT NULL';
                }
                
                $model1 = Category::find()->select(['category_id', 'category_name'])->where(['slug' => $data['slug']])->asArray()->one();
                $active_vendors = Vendor::loadvalidvendorids($model1['category_id']);

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

            $model1 = Category::find()->select('category_id')->where(['slug' => $data['slug']])->asArray()->one();
                if (!is_null($model1)) {
                $imageData = Vendoritem::find()
                    ->select(['{{%vendor_item}}.category_id','{{%image}}.image_path','{{%vendor_item}}.item_price_per_unit',
                        '{{%vendor_item}}.item_name','{{%vendor_item}}.slug','{{%vendor_item}}.child_category','{{%vendor_item}}.item_id','{{%vendor}}.vendor_name'])
                    ->leftJoin('{{%image}} as wi', '{{%vendor_item}}.item_id = wi.item_id')
                    ->leftJoin('{{%vendor}} as wv', '{{%vendor_item}}.vendor_id = wv.vendor_id')
                    ->leftJoin('{{%category}} as wc', 'wc.category_id = {{%vendor_item}}.child_category')
                    ->where(['{{%vendor_item}}.trash' => "Default"])
                    ->andWhere(['{{%vendor_item}}.item_approved' => "Yes"])
                    ->andWhere(['{{%vendor_item}}.item_status' => "Active"])
                    ->andWhere(['{{%vendor_item}}.type_id' => "2"])
                    ->andWhere(['{{%vendor_item}}.item_for_sale' => "Yes"])
                    ->andWhere(['wi.module_type' => "vendor_item"])
                    ->andWhere(['{{%vendor_item}}.vendor_id' =>[$active_vendors]])
                    ->andWhere(['{{%vendor_item}}.category_id' => $model1['category_id']])
                    //->andWhere([$condition])
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

        return $this->renderPartial('loaditems', ['imageData' => $imageData, 'customer_events_list' => $customer_events_list]);
    }

    public function actionLoadsearchitems()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $condition = '';
            $join = '';
            if (!empty($data['slug'])) {
            if ($data['search'] != '') {
                $values = $this->qstring($data['search']);
                $search = $data['search'];
                $condition .= 'AND wvi.item_name LIKE "%'.$search.'%"';
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
              $condition .= ' AND wvi.item_id IN("'.$all_valid_themes.'") ';
            }
                if (!empty($data['vendor'])) {
                    $vendor = explode('+', $data['vendor']);
                    $v = implode('","', $vendor);

                //SELECT * FROM `whitebook_vendor` WHERE slug IN('whitebook-vendor','thomas')

            /* BEGIN GET VENDORS */
                $active_vendors = Vendor::loadvalidvendors();
                    $condition .= 'AND wvi.vendor_id IN("'.$active_vendors.'") AND wv.slug IN("'.$v.'") AND wv.vendor_id IS NOT NULL';
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
            $cat_item_details = $model->category_search_details($search);

            $category = new Category();
                if (!empty($data['search'])) {
                    $cat_item_details = $model->category_search_details($data['search']);
                    $cat_id = $cat_item_details[0]['category_id'];
                } else {
                    $cat_id = '';
                }

            $imageData = Vendoritem::find()
                    ->select('{{%vendor_item}}.category_id, wi.image_path, {{%vendor_item}}.item_price_per_unit, {{%vendor_item}}.item_name,{{%vendor_item}}.slug, {{%vendor_item}}.child_category, wvi.item_id, wv.vendor_name')
                    ->leftJoin('{{%image}} as wi', '{{%vendor_item}}.item_id = wi.item_id')
                    ->leftJoin('{{%vendor}} as wv', '{{%vendor_item}}.vendor_id = wv.vendor_id')
                    ->leftJoin('{{%category}} as wc', 'wc.category_id = {{%vendor_item}}.child_category')
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

        return $this->renderPartial('loaditems', ['imageData' => $imageData, 'customer_events_list' => $customer_events_list]);
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
            return $this->renderPartial('loaditems', ['imageData' => $imageData]);
        }
    }

    /* BEGIN VENDOR PROFILE PAGE LOAD ITEMS BASED ON VENDOR */
    public function actionLoadvendoritems()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $condition = '';
            $join = '';
        /* CATEGORY */
        if ($data['category_name'] != '') {
            $category_val = explode('+', $data['category_name']);
            $cat = implode('","', $category_val);
            $condition .= 'AND wc.slug IN("'.$cat.'")';
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

           $condition .= ' AND wvi.item_id IN("'.$all_valid_themes.'") ';
        }
        /* END THEME FILTER */

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

           $vendorData = Vendoritem::find()
                ->select('{{%vendor_item}}.category_id, wi.image_path, {{%vendor_item}}.item_price_per_unit, {{%vendor_item}}.item_name,{{%vendor_item}}.slug, {{%vendor_item}}.child_category, wvi.item_id, wv.vendor_name')
                ->leftJoin('{{%image}} as wi', '{{%vendor_item}}.item_id = wi.item_id')
                ->leftJoin('{{%vendor}} as wv', '{{%vendor_item}}.vendor_id = wv.vendor_id')
                ->leftJoin('{{%category}} as wc', 'wc.category_id = {{%vendor_item}}.child_category')
                ->where(['{{%vendor_item}}.trash' => "Default"])
                ->andWhere(['{{%vendor_item}}.item_approved' => "Yes"])
                ->andWhere(['{{%vendor_item}}.item_status' => "Active"])
                ->andWhere(['{{%vendor_item}}.type_id' => "2"])
                ->andWhere(['{{%vendor_item}}.item_for_sale' => "Yes"])
                ->andWhere(['wi.module_type' => "vendor_item"])
                ->andWhere([$condition])
                ->andWhere(['wv.slug' => $data['slug']])
                ->groupBy('{{%vendor_item}}.item_id')
                ->limit(12)
                ->asArray()
                ->all();
            $customer_id = Yii::$app->user->identity->customer_id;
            if (!empty($customer_id)) {
                $usermodel = new Users();
                $customer_events_list = $usermodel->get_customer_wishlist_details($customer_id);

                return $this->renderPartial('loaditems', ['imageData' => $vendorData]);
            } else {
                return $this->renderPartial('loaditems', ['imageData' => $vendorData, 'customer_events_list' => $customer_events_list]);
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
                    return $this->renderPartial('loaditems', ['imageData' => $imageData]);
                } else {
                    return $this->renderPartial('loaditems', ['imageData' => $imageData, 'customer_events_list' => $customer_events_list]);
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
        $categories = implode('","', $cat);

        return $ids = '"'.$categories.'"';
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
