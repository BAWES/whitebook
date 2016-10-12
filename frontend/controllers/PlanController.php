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

        $db = Yii::$app->db;
      
        $category = Category::find()->select('category_id,category_name, slug')
            ->where([
                'category_level' => 0, 
                'category_allow_sale' => 'yes', 
                'trash' => 'Default'
            ])
            ->asArray()
            ->all();

        return $this->render('plans', ['category' => $category]);
    }

    public function actionPlan($slug)
    {
        $model = new Website();

        $data = Yii::$app->request->get();

        $explode = (Yii::$app->request->isAjax) ? '+' : ' ';

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
                '{{%vendor_item}}.item_approved' => 'Yes',
                '{{%vendor_item}}.item_status' => 'Active',
            ]);            
        
        $item_query->andWhere(['in', '{{%vendor_item}}.vendor_id', $active_vendors]);



        if (isset($data['vendor']) && $data['vendor'] != '') {
            $item_query->andWhere(['in', '{{%vendor}}.slug', explode($explode, $data['vendor'])]);
        }

        //price filter
        if (isset($data['price']) && $data['price'] != '') {

            $price_condition = [];

            foreach (explode($explode, $data['price']) as $key => $value) {
                $arr_min_max = explode('-', $value);
                $price_condition[] = '{{%vendor_item}}.item_price_per_unit between '.$arr_min_max[0].' and '.$arr_min_max[1];
            }

            $item_query->andWhere(implode(' OR ', $price_condition));
        }

        //theme filter
        if (isset($data['themes']) && $data['themes'] != '') {

            $theme = explode($explode, $data['themes']);

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

            $item_query->andWhere('{{%vendor_item}}.item_id IN("'.$all_valid_themes.'")');

        }//if themes

        //category filter
        $cats = $slug;
        $categories = [];
        if (isset($data['category']) && $data['category'] != '') {
            $categories = array_merge($categories,explode($explode, $data['category']));
            $cats = implode("','",$categories);
        }
        $q = "{{%category_path}}.path_id IN (select category_id from {{%category}} where slug IN ('$cats') and trash = 'Default')";
        $item_query->andWhere($q);


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

        $vendor = Vendor::find()
            ->select('{{%vendor}}.vendor_id, {{%vendor}}.vendor_name, {{%vendor}}.vendor_name_ar, {{%vendor}}.slug')
            ->where(['IN', '{{%vendor}}.vendor_id', $active_vendors])
            ->asArray()
            ->all();
        
        if (!Yii::$app->user->isGuest) {
            $usermodel = new Users();            
            $customer_events_list = $usermodel->get_customer_wishlist_details(Yii::$app->user->identity->id);
        } else {
            $customer_events_list = [];
        }


        if (Yii::$app->request->isAjax) {
            return $this->renderPartial('@frontend/views/common/items', [
                'items' => $items,
                'customer_events_list' => $customer_events_list
            ]);
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
