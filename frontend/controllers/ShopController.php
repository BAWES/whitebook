<?php

namespace frontend\controllers;

use common\models\Vendorlocation;
use Yii;
use frontend\models\Users;
use frontend\models\Website;
use common\models\Category;
use frontend\models\Vendoritem;
use common\models\Vendoritemthemes;
use frontend\models\Themes;
use frontend\models\Vendor;


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
        return $this->render('index');
    }

    public function actionProducts($slug)
    {
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


            $TopCategories = Category::findAll(['category_level' => 0]);
            $ActiveVendors = Vendor::loadvalidvendorids($Category->category_id);
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
                    ->andWhere(['{{%vendor_item}}.type_id' => "2"])
                    ->andWhere(['{{%vendor_item}}.item_for_sale' => "Yes"])
                    ->andWhere(['{{%vendor_item}}.vendor_id' =>$ActiveVendors])
                    ->andWhere(['{{%vendor_item}}.category_id' => $Category->category_id])
                    ->groupBy('{{%vendor_item}}.item_id')
                    ->asArray()
                    ->all();
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

        /* VENDOR HAVIG ATLEAST ONE PRODUCT */
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

                $all_valid_themes = (count($all_valid_themes)==1) ? $all_valid_themes[0] : implode('","', $all_valid_themes);


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
                    ->andWhere(['{{%vendor_item}}.type_id' => "2"])
                    ->andWhere(['{{%vendor_item}}.item_for_sale' => "Yes"])
                    ->andWhere(['{{%vendor_item}}.vendor_id' =>$active_vendors])
                    ->andWhere(['{{%vendor_item}}.category_id' => $model1['category_id']])
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

        return $this->renderPartial('_load', ['imageData' => $imageData, 'customer_events_list' => $customer_events_list]);
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
            return $this->renderPartial('_load', ['imageData' => $imageData]);
        }
    }

    public function actionProduct($slug)
    {
        $Similar = new \common\models\Featuregroupitem();
        $model = Vendoritem::findOne(['slug'=>$slug]);

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

        if (empty($model)) {
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        }

        $output = \common\models\Image::find()->select(['image_path'])
            ->where(['item_id' => $model['item_id']])
            ->orderby(['vendorimage_sort_order'=>SORT_ASC])
            ->asArray()
            ->one();

        $baselink = Yii::$app->homeUrl.Yii::getAlias('@vendor_images/').'no_image.jpg';

        if (file_exists(Yii::getAlias('@vendor_images/').$output['image_path'])) {
            $baselink = Yii::$app->homeUrl . Yii::getAlias('@vendor_images/') . $output['image_path'];
        }

        /* BEGIN DELIVERY AREAS --VENDOR */

        $VendorArea = Vendorlocation::find()
            ->joinWith('location')
            ->where(['{{%location}}.trash'=>'Default'])
            ->asArray()
            ->all();

        \Yii::$app->view->registerMetaTag(['property' => 'og:title', 'content' => 'Whitebook Application '.ucfirst($model->vendor->vendor_name)]);
        \Yii::$app->view->registerMetaTag(['property' => 'og:url', 'content' => urlencode(Yii::$app->homeUrl . $_SERVER['REQUEST_URI'])]);
        \Yii::$app->view->registerMetaTag(['property' => 'og:image', 'content' => $baselink]);
        \Yii::$app->view->registerMetaTag(['property' => 'og:image:width', 'content' => '200']);
        \Yii::$app->view->registerMetaTag(['property' => 'og:image:height', 'content' => '200']);
        \Yii::$app->view->registerMetaTag(['property' => 'og:site_name', 'content' => 'Whitebook Application '.ucfirst($model->vendor->vendor_name).' '.ucfirst($model->item_name)]);
        \Yii::$app->view->registerMetaTag(['property' => 'og:description', 'content' => $baselink]);

        if (Yii::$app->user->isGuest) {
            return $this->render('product_detail', [
                'AvailableStock' => $AvailableStock,
                'model' => $model,
                'similiar_item' => $Similar->similiar_details(),
                'vendor_area' => $VendorArea
            ]);

        } else {
            $user = new Users();
            $customer_events_list = $user->get_customer_wishlist_details(Yii::$app->user->identity->customer_id);
            return $this->render('product_detail', [
                'model' => $model,
                'similiar_item' => $Similar->similiar_details(),
                'AvailableStock' => $AvailableStock,
                'customer_events_list' => $customer_events_list,
                'vendor_area' => $VendorArea
            ]);
        }
    }
}
