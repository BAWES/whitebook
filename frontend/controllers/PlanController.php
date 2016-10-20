<?php

namespace frontend\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use frontend\models\Users;
use frontend\models\Website;
use common\models\Category;
use common\models\VendorItem;
use common\models\Vendoritemthemes;
use common\models\CategoryPath;
use frontend\models\Themes;
use frontend\models\Vendor;
use yii\data\ArrayDataProvider;


/**
 * Category controller.
 */
class PlanController extends BaseController
{
    /**
     *
     */
    public function init()
    {
        parent::init();
    }

    /**
     * @return string
     */
    public function actionIndex()
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

        return $this->render('index', ['category' => $category]);
    }

    /**
     * @param $slug
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionProducts($slug)
    {
        $model = new Website();
        $themes = '';
        $data = Yii::$app->request->get();

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
            $item_query->andWhere(['in', '{{%vendor}}.slug', $data['vendor']]);
        }

        //price filter
        if (isset($data['price']) && $data['price'] != '') {

            $price_condition = [];

            $arr_min_max = explode('-', $data['price']);
            $price_condition[] = '{{%vendor_item}}.item_price_per_unit between '.$arr_min_max[0].' and '.$arr_min_max[1];


            $item_query->andWhere(implode(' OR ', $price_condition));
        }

        //theme filter
        if (isset($data['themes']) && count($data['themes'])>0) {

            $item_query->leftJoin('{{%vendor_item_theme}}', '{{%vendor_item}}.item_id = {{%vendor_item_theme}}.item_id');
            $item_query->leftJoin('{{%theme}}', '{{%theme}}.theme_id = {{%vendor_item_theme}}.theme_id');
            $item_query->andWhere(['IN', '{{%theme}}.slug', $data['themes']]);
        }

        //category filter
        $cats = $slug;
        $categories = [];

        if (isset($data['category']) && count($data['category'])>0) {
            $categories = array_merge($categories,$data['category']);
            $cats = implode("','",$categories);
        }
        $q = "{{%category_path}}.path_id IN (select category_id from {{%category}} where slug IN ('$cats') and trash = 'Default')";
        $item_query->andWhere($q);


        $items = $item_query->groupBy('{{%vendor_item}}.item_id')
            ->orderBy('{{%image}}.vendorimage_sort_order', SORT_ASC)
            ->asArray()
            ->all();

        $provider = new ArrayDataProvider([
            'allModels' => $items,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        if (!empty($items)) {
            $item_ids = ArrayHelper::map($items, 'item_id', 'item_id');
            $themes = Vendoritemthemes::find()
                ->select(['theme_id'])
                ->with('themeDetail')
                ->where("trash='default' and item_id IN(".implode(',', array_keys($item_ids)).")")
                ->groupBy('theme_id')
                ->asArray()
                ->all();
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
                'items' => $provider,
                'customer_events_list' => $customer_events_list
            ]);
        }
        return $this->render('products', [
            'model' => $model,
            'top_categories' => $top_categories,
            'items' => $items,
            'provider' => $provider,
            'themes' => $themes,
            'vendor' => $vendor,
            'category_id' => $category_model['category_id'],
            'slug' => $slug,
            'customer_events_list' => $customer_events_list
        ]);
    }

    /**
     *
     */
    public function actionAddevent()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $model = VendorItem::find()->where('item_id='.$data['item_id'])->asArray()->all();
            $this->renderPartial('addevent', array('model' => $model));
        }
    }
}