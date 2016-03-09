<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use frontend\models\Users;
use frontend\models\Website;
use backend\models\SubCategory;
use backend\models\Category;
use backend\models\Vendoritem;
use backend\models\Themes;
use backend\models\Vendor;

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
        \Yii::$app->view->title = Yii::$app->params['Sitename'].' | Plan';
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
        $customer_id = Yii::$app->session->get('customer_id');
        //Yii::$app->params['header1'] = "1"; // uncomment call new header
        if ($slug != '') {
            /* BEGIN CATEGORY*/
        $model1 = Category::find()->select(['category_id', 'category_name'])->where(['slug' => $slug])->asArray()->one();
            if (empty($model1)) {
                throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
            }

            $seo_content = \Yii::$app->common->SEOdata('category', 'category_id', $model1['category_id'], array('category_name', 'category_meta_title', 'category_meta_keywords', 'category_meta_description'));

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
                $imageData = Yii::$app->db->createCommand('select wi.image_path, wvi.item_price_per_unit, wvi.item_name,wvi.slug, wvi.child_category, wvi.item_id, wv.vendor_name FROM whitebook_vendor_item as wvi
            LEFT JOIN whitebook_image as wi ON wvi.item_id = wi.item_id
            LEFT JOIN whitebook_vendor as wv ON wv.vendor_id = wvi.vendor_id
            LEFT JOIN whitebook_category as wc ON wc.category_id = wvi.child_category
            WHERE wvi.trash="Default" and wvi.item_approved="Yes" and wvi.item_status="Active" and wvi.type_id="2"
            and wvi.item_for_sale="Yes" AND wi.module_type = "vendor_item" AND wvi.vendor_id IN("'.$active_vendors.'") AND wvi.category_id='.$model1['category_id'].' Group By wvi.item_id limit 4')->queryAll();
            }
        }

        /* END CATEGORY */

      //  $themes = Themes::loadthemenames();
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

            //$out1[]=0;
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
        $vendor = Yii::$app->db->createCommand('SELECT wv.vendor_id,wv.vendor_name,wv.slug FROM `whitebook_vendor_item` as wvi inner join `whitebook_vendor` as wv ON wvi.vendor_id = wv.vendor_id and wvi.vendor_id IN("'.$active_vendors.'") and wv.vendor_status ="Active" and wv.approve_status = "Yes" and wvi.item_status="Active" and wvi.item_approved = "Yes" and wvi.trash="Default" and wvi.item_for_sale group by wvi.vendor_id')->queryAll();

        /* END GET VENDORS */
        if ($customer_id == '') {
            return $this->render('planvenues', ['model' => $model, 'imageData' => $imageData,
            'themes' => $themes, 'vendor' => $vendor, 'slug' => $slug, ]);
        } else {
            $usermodel = new Users();
            if (!empty($customer_id)) {
                $customer_events_list = $usermodel->get_customer_wishlist_details($customer_id);

                return $this->render('planvenues', ['model' => $model, 'imageData' => $imageData,
            'themes' => $themes, 'vendor' => $vendor, 'slug' => $slug, 'customer_events_list' => $customer_events_list, ]);
            } else {
                return $this->render('planvenues', ['model' => $model, 'imageData' => $imageData,
            'themes' => $themes, 'vendor' => $vendor, 'slug' => $slug, ]);
            }
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
                $condition .= 'AND wc.slug IN('.$values.')';
            }
            /* THEMES FILTER */
            if ($data['themes'] != '') {
                $theme = explode('+', $data['themes']);
                foreach ($theme as $key => $value) {
                    $themes[] = Yii::$app->db->createCommand('SELECT theme_id FROM whitebook_theme WHERE slug IN("'.$value.'")')->queryAll();
                }

                $all_valid_themes = array();
                foreach ($themes as $key => $value) {
                    $get_themes = Yii::$app->db->createCommand('SELECT  theme_id, item_id FROM `whitebook_vendor_item_theme` WHERE trash="Default" AND FIND_IN_SET('.$value[0]['theme_id'].', theme_id)')->queryAll();
                //$all_valid_themes[] = implode(',', $get_themes[0]);

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
                if ($data['vendor'] != '') {
                    $vendor = explode('+', $data['vendor']);
                    $v = implode('","', $vendor);

                    $condition .= 'AND wv.slug IN("'.$v.'") AND wv.vendor_id IS NOT NULL';
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
                    /*echo 'select wvi.category_id, wi.image_path, wvi.item_price_per_unit, wvi.item_name,wvi.slug, wvi.child_category, wvi.item_id, wv.vendor_name FROM whitebook_vendor_item as wvi
            LEFT JOIN whitebook_image as wi ON wvi.item_id = wi.item_id
            LEFT JOIN whitebook_vendor as wv ON wv.vendor_id = wvi.vendor_id
            LEFT JOIN whitebook_category as wc ON wc.category_id = wvi.child_category '.$join.'
            WHERE wvi.trash="Default" and wvi.item_approved="Yes" and wvi.item_status="Active" and wvi.type_id="2"
            and wvi.item_for_sale="Yes" and wi.module_type = "vendor_item"  '.$condition.' Group By wvi.item_id
            HAVING wvi.category_id='.$model1['category_id'].' limit 12';      die;
           */
            $imageData = Yii::$app->db->createCommand('select wvi.category_id, wi.image_path, wvi.item_price_per_unit, wvi.item_name,wvi.slug, wvi.child_category, wvi.item_id, wv.vendor_name FROM whitebook_vendor_item as wvi
            LEFT JOIN whitebook_image as wi ON wvi.item_id = wi.item_id
            LEFT JOIN whitebook_vendor as wv ON wv.vendor_id = wvi.vendor_id
            LEFT JOIN whitebook_category as wc ON wc.category_id = wvi.child_category '.$join.'
            WHERE wvi.trash="Default" and wvi.item_approved="Yes" and wvi.item_status="Active" and wvi.type_id="2"
            and wvi.item_for_sale="Yes" and wi.module_type = "vendor_item"  AND wvi.vendor_id IN("'.$active_vendors.'") '.$condition.' Group By wvi.item_id HAVING wvi.category_id='.$model1['category_id'].' limit 12')->queryAll();
                }
            }

          /*   $imageData = Yii::$app->db->createCommand('select wi.image_path, wvi.item_price_per_unit, wvi.item_name, wvi.child_category, wvi.item_id, wv.vendor_name FROM whitebook_vendor_item as wvi
            LEFT JOIN whitebook_image as wi ON wvi.item_id = wi.item_id
            LEFT JOIN whitebook_vendor as wv ON wv.vendor_id = wvi.vendor_id
            LEFT JOIN whitebook_category as wc ON wc.category_id = wvi.child_category '.$join.'
            WHERE wvi.trash="Default" and wvi.item_approved="Yes" and wvi.item_status="Active"
            and wvi.item_for_sale="Yes" '.$condition.' Group By wvi.item_id limit 5')->queryAll();   */
        }
        $customer_events_list = array();
        if (Yii::$app->params['CUSTOMER_ID'] != '') {
            $usermodel = new Users();
            $customer_events_list = $usermodel->get_customer_wishlist_details(Yii::$app->params['CUSTOMER_ID']);
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
                /* CATEGORY FILTER
            if($data['item_ids'] !='')
            {
            $values = $this->qstring($data['item_ids']);
            $condition .= 'AND wc.slug IN('.$values.')';
            }*/

            if ($data['search'] != '') {
                $values = $this->qstring($data['search']);
                $search = $data['search'];
                $condition .= 'AND wvi.item_name LIKE "%'.$search.'%"';
            }

            /* THEMES FILTER */
            if ($data['themes'] != '') {
                $theme = explode('+', $data['themes']);
                foreach ($theme as $key => $value) {
                    $themes[] = Yii::$app->db->createCommand('SELECT  theme_id FROM whitebook_theme WHERE slug IN("'.$value.'")')->queryAll();
                }

                $all_valid_themes = array();
                foreach ($themes as $key => $value) {
                    $get_themes = Yii::$app->db->createCommand('SELECT  theme_id, item_id FROM `whitebook_vendor_item_theme` WHERE trash="Default" AND FIND_IN_SET('.$value[0]['theme_id'].', theme_id)')->queryAll();
                //$all_valid_themes[] = implode(',', $get_themes[0]);

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

            //$model1 = Category::find()->select('category_id')->where(['slug'=>$data['slug']])->asArray()->one();

            /* echo 'select wvi.category_id, wi.image_path, wvi.item_price_per_unit, wvi.item_name,wvi.slug, wvi.child_category, wvi.item_id, wv.vendor_name FROM whitebook_vendor_item as wvi
            LEFT JOIN whitebook_image as wi ON wvi.item_id = wi.item_id
            LEFT JOIN whitebook_vendor as wv ON wv.vendor_id = wvi.vendor_id
            LEFT JOIN whitebook_category as wc ON wc.category_id = wvi.child_category '.$join.'
            WHERE wvi.trash="Default" and wvi.item_approved="Yes" and wvi.item_status="Active" and wvi.type_id="2"
            and wvi.item_for_sale="Yes" '.$condition.' Group By wvi.item_id HAVING wvi.category_id='.$model1['category_id'].' limit 5';
            die; */

            $cat_item_details = $model->category_search_details($search);
            //print_r ($cat_item_details);die;

            $category = new Category();
                if (!empty($data['search'])) {
                    $cat_item_details = $model->category_search_details($data['search']);
                    $cat_id = $cat_item_details[0]['category_id'];
                } else {
                    $cat_id = '';
                }

                $imageData = Yii::$app->db->createCommand('select wvi.category_id, wi.image_path, wvi.item_price_per_unit, wvi.item_name,wvi.slug, wvi.child_category, wvi.item_id, wv.vendor_name FROM whitebook_vendor_item as wvi
            LEFT JOIN whitebook_image as wi ON wvi.item_id = wi.item_id
            LEFT JOIN whitebook_vendor as wv ON wv.vendor_id = wvi.vendor_id
            LEFT JOIN whitebook_category as wc ON wc.category_id = wvi.child_category '.$join.'
            WHERE wvi.trash="Default" and wvi.item_approved="Yes" and wvi.item_status="Active" and wvi.type_id="2"
            and wvi.item_for_sale="Yes" and wi.module_type = "vendor_item"  '.$condition.' Group By wvi.item_id HAVING wvi.category_id='.$model1['category_id'].' limit 12')->queryAll();
            }
        }

          /*   $imageData = Yii::$app->db->createCommand('select wi.image_path, wvi.item_price_per_unit, wvi.item_name, wvi.child_category, wvi.item_id, wv.vendor_name FROM whitebook_vendor_item as wvi
            LEFT JOIN whitebook_image as wi ON wvi.item_id = wi.item_id
            LEFT JOIN whitebook_vendor as wv ON wv.vendor_id = wvi.vendor_id
            LEFT JOIN whitebook_category as wc ON wc.category_id = wvi.child_category '.$join.'
            WHERE wvi.trash="Default" and wvi.item_approved="Yes" and wvi.item_status="Active"
            and wvi.item_for_sale="Yes" '.$condition.' Group By wvi.item_id limit 5')->queryAll();   */
            //}
            $customer_events_list = array();
        if (Yii::$app->params['CUSTOMER_ID'] != '') {
            $usermodel = new Users();
            $customer_events_list = $usermodel->get_customer_wishlist_details(Yii::$app->params['CUSTOMER_ID']);
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

            $imageData = Yii::$app->db->createCommand('select wvi.slug, wi.image_path, wvi.item_price_per_unit, wvi.item_name, wvi.child_category, wvi.item_id, wv.vendor_name FROM whitebook_vendor_item as wvi
        LEFT JOIN whitebook_image as wi ON wvi.item_id = wi.item_id
        LEFT JOIN whitebook_vendor as wv ON wv.vendor_id = wvi.vendor_id
        LEFT JOIN whitebook_category as wc ON wc.category_id = wvi.child_category
        WHERE wvi.trash="Default" and wvi.item_approved="Yes" and wvi.item_status="Active"
        and wvi.item_for_sale="Yes" AND wvi.category_id='.$model1['category_id'].' Group By wvi.item_id limit 4 offset '.$limit.'')->queryAll();

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
                $themes[] = Yii::$app->db->createCommand('SELECT  theme_id FROM whitebook_theme WHERE slug IN("'.$value.'")')->queryAll();
            }

            $all_valid_themes = array();
            foreach ($themes as $key => $value) {
                $get_themes = Yii::$app->db->createCommand('SELECT  theme_id, item_id FROM `whitebook_vendor_item_theme` WHERE trash="Default" AND FIND_IN_SET('.$value[0]['theme_id'].', theme_id)')->queryAll();
            //$all_valid_themes[] = implode(',', $get_themes[0]);

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

        $vendorData = Yii::$app->db->createCommand('select wi.image_path, wvi.item_price_per_unit, wvi.item_name,
            wvi.slug, wvi.child_category, wvi.item_id, wv.vendor_name FROM whitebook_vendor_item as wvi
            LEFT JOIN whitebook_image as wi ON wvi.item_id = wi.item_id
            LEFT JOIN whitebook_vendor as wv ON wv.vendor_id = wvi.vendor_id
            LEFT JOIN whitebook_category as wc ON wc.category_id = wvi.category_id
            WHERE wvi.trash="Default" and wvi.item_approved="Yes" and wvi.item_status="Active" and wvi.type_id="2"
            and wvi.item_for_sale="Yes" AND wi.module_type = "vendor_item"
            '.$condition.' AND wv.slug = "'.$data['slug'].'"
            Group By wvi.item_id limit 12')->queryAll();
            $customer_id = Yii::$app->params['CUSTOMER_ID'];
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
                    $themes[] = Yii::$app->db->createCommand('SELECT theme_id FROM whitebook_theme WHERE slug IN("'.$value.'")')->queryAll();
                }

                $all_valid_themes = array();
                foreach ($themes as $key => $value) {
                    $get_themes = Yii::$app->db->createCommand('SELECT  theme_id, item_id FROM `whitebook_vendor_item_theme` WHERE trash="Default" AND FIND_IN_SET('.$value[0]['theme_id'].', theme_id)')->queryAll();
                //$all_valid_themes[] = implode(',', $get_themes[0]);

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

            $sql = 'select wi.image_path, wvi.item_price_per_unit,wvi.item_id,
            wvi.item_name,wvi.slug,wvi.category_id, wv.vendor_name ,count(*) as total FROM whitebook_vendor_item as wvi
			LEFT JOIN whitebook_image as wi ON wvi.item_id = wi.item_id
			LEFT JOIN whitebook_vendor as wv ON wv.vendor_id = wvi.vendor_id
			WHERE wvi.trash="Default" and wvi.item_approved="Yes" and wvi.item_status="Active" and wvi.type_id="2"
			and wvi.item_for_sale="Yes"  AND wi.module_type = "vendor_item" AND '.$join.$condition.' Group By wi.item_id';
                $imageData = Yii::$app->db->createCommand($sql)->queryAll();
                $customer_id = Yii::$app->params['CUSTOMER_ID'];
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
