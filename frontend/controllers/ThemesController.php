<?php
namespace frontend\controllers;

use Yii;
use yii\db\Expression;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\data\ArrayDataProvider;
use common\models\VendorItem;
use common\models\VendorItemThemes;
use frontend\models\Vendor;
use frontend\models\Category;
use frontend\models\Themes;
use frontend\models\Website;
use frontend\models\Users;
use common\models\Smtp;
use common\models\CategoryPath;
use common\components\LangFormat;

class ThemesController extends BaseController
{
    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        $website_model = new Website();
        $category_url = Yii::$app->request->get('name');
        $main_category = $website_model->get_main_category();

        if ($category_url != '') {
            $category_id = $website_model->get_category_id($category_url);
        } else {
            $category_id = '';
        }

        \Yii::$app->view->title = Yii::$app->params['SITE_NAME'].' | Themes';
        \Yii::$app->view->registerMetaTag(['name' => 'description', 'content' => Yii::$app->params['META_DESCRIPTION']]);
        \Yii::$app->view->registerMetaTag(['name' => 'keywords', 'content' => Yii::$app->params['META_KEYWORD']]);

        $directory = Themes::loadthemenames();

        $prevLetter = '';
        $result = array();
        foreach ($directory as $d) {

            $firstLetter  = LangFormat::format(mb_substr($d['theme_name'], 0, 1, 'utf8'),mb_substr($d['theme_name_ar'], 0, 1, 'utf8'));

            if ($firstLetter != $prevLetter) {
                $result[] = strtoupper($firstLetter);
            }

            $prevLetter = $firstLetter;
        }

        $result = array_unique($result);

        return $this->render('index', [
            'category' => $main_category,
            'directory' => $directory,
            'first_letter' => $result,
        ]);
    }


    public function actionDetail($themes, $slug)
    {
        $data = Yii::$app->request->get();

        $theme = Themes::findOne(['slug' => $themes, 'trash' => 'Default']);

        if (!$theme) {
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        }
        \Yii::$app->view->title = Yii::$app->params['SITE_NAME'].' | '.$theme->theme_name;
        \Yii::$app->view->registerMetaTag(['name' => 'description', 'content' => Yii::$app->params['META_DESCRIPTION']]);
        \Yii::$app->view->registerMetaTag(['name' => 'keywords', 'content' => Yii::$app->params['META_KEYWORD']]);

        $theme_result  = VendorItemThemes::find()->select('item_id')
            ->where(['trash' => "Default"])
            ->andWhere(['theme_id' => $theme->theme_id])
            ->asArray()
            ->all();

        $theme_items = ArrayHelper::map($theme_result,'item_id','item_id');

        if ($slug != 'all') {
            $category = Category::find()->select('category_id')->where(['slug' => $slug])->asArray()->one();
            $active_vendors = Vendor::loadvalidvendorids($category['category_id']);
        } else {
            $active_vendors = Vendor::loadvalidvendorids();
        }

        $items_query = CategoryPath::find()
            ->select('{{%vendor_item}}.item_base_price, {{%vendor_item}}.min_order_amount, {{%vendor_item}}.item_status,{{%vendor_item}}.trash,{{%vendor_item}}.item_approved,{{%vendor_item}}.item_how_long_to_make, {{%vendor_item}}.slug, {{%vendor_item}}.item_id, {{%vendor_item}}.item_id, {{%vendor_item}}.item_name, {{%vendor_item}}.item_name_ar, {{%vendor_item}}.item_price_per_unit, {{%vendor}}.vendor_id, {{%vendor}}.vendor_name, {{%vendor}}.vendor_name_ar')
            ->leftJoin(
                '{{%vendor_item_to_category}}', 
                '{{%vendor_item_to_category}}.category_id = {{%category_path}}.category_id'
            )
            ->leftJoin(
                '{{%vendor_item}}',
                '{{%vendor_item}}.item_id = {{%vendor_item_to_category}}.item_id'
            )
            ->leftJoin(
                '{{%priority_item}}',
                '{{%priority_item}}.item_id = {{%vendor_item}}.item_id'
            )
            ->leftJoin('{{%vendor}}', '{{%vendor_item}}.vendor_id = {{%vendor}}.vendor_id')
            ->where([
                '{{%vendor_item}}.trash' => 'Default',
                '{{%vendor_item}}.item_approved' => 'Yes',
                '{{%vendor_item}}.item_status' => 'Active',
                '{{%vendor}}.vendor_status' => 'Active',
                '{{%vendor}}.approve_status' => 'Yes',
                '{{%vendor}}.trash' => 'Default',
                '{{%vendor_item}}.item_id' => $theme_items,
            ]);

        $cats = '';

        if ($slug != 'all') 
        {
            $category = Category::findOne(['slug' => $slug]);

            if (empty($category)) {
                throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
            }

            $cats = $category->category_id;
        }
        if (isset($data['category']) && count($data['category'])>0) 
        {
            $cats = implode("','", $data['category']);
        }

        if ($cats) 
        {
            $q = "{{%category_path}}.path_id IN ('".$cats."')";
            $items_query->andWhere($q);
        }

        if (isset($data['vendor']) && $data['vendor'] != '') 
        {
            $items_query->andWhere(['in', '{{%vendor}}.slug', $data['vendor']]);
        }

        //price filter
        if (isset($data['price']) && $data['price'] != '') {

            $price_condition = [];

            $arr_min_max = explode('-', $data['price']);
            
            $price_condition[] = '{{%vendor_item}}.item_price_per_unit IS NULL';
            $price_condition[] = '{{%vendor_item}}.item_price_per_unit between '.$arr_min_max[0].' and '.$arr_min_max[1];


            $items_query->andWhere(implode(' OR ', $price_condition));
        }

        $expression = new Expression(
            "CASE 
                WHEN
                    `whitebook_priority_item`.priority_level IS NULL 
                    OR whitebook_priority_item.status = 'Inactive' 
                    OR whitebook_priority_item.trash = 'Deleted' 
                    OR DATE(whitebook_priority_item.priority_start_date) > DATE(NOW()) 
                    OR DATE(whitebook_priority_item.priority_end_date) < DATE(NOW()) 
                THEN 2 
                WHEN `whitebook_priority_item`.priority_level = 'Normal' THEN 1 
                WHEN `whitebook_priority_item`.priority_level = 'Super' THEN 0 
                ELSE 2 
            END, {{%vendor_item}}.sort");

        $item_query_result = $items_query
            ->groupBy('{{%vendor_item}}.item_id')
            ->orderBy($expression)
            ->asArray()
            ->all();

        /*
        Whenever results within browse belong to multiple vendors, alternate items to show 1 from each vendor.

        # Example:
        5 from candy vendor, 2 from chocolate, one from juice vendor.

        ## Will show in following order:
        candy, chocolate, juice, candy chocolate, candy, candy, candy
        */

        $vendor_chunks = [];

        foreach ($item_query_result as $key => $value)
        {
            $vendor_chunks[$value['vendor_id']][] = $value;
        }

        //get size of biggest chunk 
        
        $max_size = 0;

        foreach ($vendor_chunks as $key => $value) 
        {
            if(sizeof($value) > $max_size)
            {
                $max_size = sizeof($value);
            }
        }

        //get items from every chunk one by one 

        $items = [];

        for($i = 0; $i < $max_size; $i++)
        {
            foreach ($vendor_chunks as $key => $value) 
            {
                if(isset($value[$i]))
                {
                    $items[] = $value[$i];    
                }            
            }
        }

        $provider = new ArrayDataProvider([
            'allModels' => $items,
            'pagination' => [
                'pageSize' => 50,
            ],
        ]);
       
        $vendor = Vendor::find()
            ->select('{{%vendor}}.vendor_id,{{%vendor}}.vendor_name,{{%vendor}}.vendor_name_ar,{{%vendor}}.slug')
            ->where(['in', '{{%vendor}}.vendor_id', $active_vendors])
            ->asArray()
            ->all();

        if (Yii::$app->request->isAjax) {

            return $this->renderPartial('@frontend/views/common/items', [
                'items' => $provider,
                'customer_events_list'=>[]
            ]);
        }

        if (!Yii::$app->user->isGuest) {

            $usermodel = new Users();
            
            $customer_events_list = $usermodel->get_customer_wishlist_details(
                Yii::$app->user->identity->id
            );
        } else {

            $customer_events_list = [];
        }

        return $this->render('listing', [
            'theme' => $theme,
            'items' => $items,
            'provider' => $provider,
            'vendor' => $vendor,
            'slug' => $slug,
            'customer_events_list' => $customer_events_list,
        ]);
    }
}



