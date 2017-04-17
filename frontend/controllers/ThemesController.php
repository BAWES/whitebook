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
            ->defaultItemThemes()
            ->theme($theme->theme_id)
            ->asArray()
            ->all();

        $theme_items = ArrayHelper::map($theme_result,'item_id','item_id');

        if ($slug != 'all') {
            $category = Category::find()->select('category_id')->slug($slug)->asArray()->one();
            $active_vendors = Vendor::loadvalidvendorids($category['category_id']);
        } else {
            $active_vendors = Vendor::loadvalidvendorids();
        }

        $items_query = CategoryPath::find()
            ->selectedFields()
            ->categoryJoin()
            ->itemJoin()
            ->priorityItemJoin()
            ->vendorJoin()
            ->defaultItems()
            ->approved()
            ->active()
            ->activeVendor()
            ->approvedVendor()
            ->defaultVendor()
            ->vendorItems($theme_items);
            
        $cats = '';

        if ($slug != 'all') 
        {
            $category = Category::find()->slug($slug)->one();

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
            $items_query->categoryIDs($cats);
        }

        if (isset($data['vendor']) && $data['vendor'] != '') 
        {
            $items_query->vendorSlug($data['vendor']);
        }

        //price filter
        if (isset($data['price']) && $data['price'] != '') {

            $price_condition = [];

            $arr_min_max = explode('-', $data['price']);

            $items_query->price($arr_min_max[0],$arr_min_max[1]);
        }

        $item_query_result = $items_query
            ->groupBy('{{%vendor_item}}.item_id')
            ->orderByExpression()
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
            ->vendorIDs($active_vendors)
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



