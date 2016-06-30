<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use frontend\models\Category_model;

/**
 * Category controller.
 */
class CategoryController extends BaseController
{
    public $category_model;

    public function init()
    {
        parent::init();
        $this->category_model = new Category_model();
    }

    public function actionCategory_products()
    {
        $category_url = Yii::$app->request->get('name');
        $category_details = $this->category_model->get_category_id($category_url);
        $cat_id = $category_details[0]['category_id'];
        $cat_name = $category_details[0]['category_name'];

        $category_product_list = $this->category_model->get_products_based_category($cat_id, 9, 0);

        $category_list = $this->category_model->get_main_category();
        $top_ad = $this->category_model->get_category_top_ads();
        $bottom_ad = $this->category_model->get_category_bottom_ads();
        $vendors = $this->category_model->vendor_list();
        $themes = $this->category_model->get_themes();

        $customer_id = Yii::$app->user->identity->customer_id;
        $customer_events = array();
        if ($customer_id != '') {
            $customer_events = $this->category_model->getCustomerEvents($customer_id);
        }
        $event_type = $this->category_model->get_event_types();

        $seo_content = Website::SEOdata('category', 'category_id', 8, array('category_meta_title', 'category_meta_keywords', 'category_meta_description'));

        \Yii::$app->view->title = ($seo_content[0]['category_meta_title']) ? $seo_content[0]['category_meta_title'] : SITE_NAME.'|'.$cat_name;

        \Yii::$app->view->registerMetaTag(['name' => 'keywords', 'content' => ($seo_content[0]['category_meta_keywords']) ? $seo_content[0]['category_meta_keywords'] : META_KEYWORD]);

        \Yii::$app->view->registerMetaTag(['name' => 'description', 'content' => ($seo_content[0]['category_meta_description']) ? $seo_content[0]['category_meta_description'] : META_DESCRIPTION]);

        return $this->render('/product/category', [
            'category_products' => $category_product_list, 'main_category' => $category_list, 'category_name' => $cat_name, 'top_ad' => $top_ad, 'bottom_ad' => $bottom_ad, 'vendors' => $vendors, 'themes' => $themes, 'customer_events' => $customer_events, 'event_type' => $event_type,
        ]);
    }
}
