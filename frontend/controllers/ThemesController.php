<?php
namespace frontend\controllers;

use Yii;
use common\models\Vendoritem;
use common\models\Vendoritemthemes;
use frontend\models\Vendor;
use frontend\models\Category;
use frontend\models\Themes;
use frontend\models\Website;
use frontend\models\Users;
use common\models\Smtp;
use common\models\CategoryPath;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

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

        if(Yii::$app->language == "en") {
            $directory = Themes::loadthemenames();
        }else{
            $directory = Themes::loadthemenames('theme_name_ar');
        }

        $prevLetter = '';
        $result = array();
        foreach ($directory as $d) {

            if(Yii::$app->language == "en") {
                $firstLetter = mb_substr($d['theme_name'], 0, 1, 'utf8');
            }else{
                $firstLetter = mb_substr($d['theme_name_ar'], 0, 1, 'utf8');
                //for arabic last letter will be first letter
            }

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

        $explode = ' ';
        if (Yii::$app->request->isAjax) {
            $explode = '+';
        }

        $theme_result  = Vendoritemthemes::find()->select('item_id')
            ->where(['trash' => "Default"])
            ->andWhere(['theme_id' => $theme->theme_id])
            ->asArray()
            ->all();
        $theme_items = ArrayHelper::map($theme_result,'item_id','item_id');

        Yii::$app->view->title = Yii::$app->params['SITE_NAME'].' | '.ucfirst($theme->theme_name);


        $category = Category::find()->select('category_id')->where(['slug' => $slug])->asArray()->one();

        $active_vendors = Vendor::loadvalidvendorids($category['category_id']);

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
                '{{%vendor_item}}.item_id' => $theme_items,
            ]);
        $cats = $slug;
        $categories = [];
        if (isset($data['category']) && $data['category'] != '') {
            $categories = array_merge($categories,explode($explode, $data['category']));
            $cats = implode("','",$categories);
        }
        $q = "{{%category_path}}.path_id IN (select category_id from {{%category}} where slug IN ('$cats') and trash = 'Default')";
        $items_query->andWhere($q);


        if (isset($data['vendor']) && $data['vendor'] != '') {
            $items_query->andWhere(['in', '{{%vendor}}.slug', explode($explode, $data['vendor'])]);
        }

        //price filter
        if (isset($data['price']) && $data['price'] != '') {

            $price_condition = [];

            foreach (explode($explode, $data['price']) as $key => $value) {
                $arr_min_max = explode('-', $value);
                $price_condition[] = '{{%vendor_item}}.item_price_per_unit between '.$arr_min_max[0].' and '.$arr_min_max[1];
            }

            $items_query->andWhere(implode(' OR ', $price_condition));
        }


        $items_query
            ->groupBy('{{%vendor_item}}.item_id')
            ->orderBy('{{%image}}.vendorimage_sort_order', SORT_ASC);
        
        $items = $items_query->asArray()->all();

       
        $vendor = Vendor::find()
            ->select('{{%vendor}}.vendor_id,{{%vendor}}.vendor_name,{{%vendor}}.slug')
            ->where(['in', '{{%vendor}}.vendor_id', $active_vendors])
            ->asArray()
            ->all();

        if (Yii::$app->request->isAjax) {

            return $this->renderPartial('@frontend/views/common/items', [
                'items' => $items,
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
            'vendor' => $vendor,
            'slug' => $slug,
            'customer_events_list' => $customer_events_list,
        ]);
    }
}



