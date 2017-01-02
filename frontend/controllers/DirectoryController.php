<?php

namespace frontend\controllers;

use Yii;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\data\ArrayDataProvider;
use frontend\models\Vendor;
use frontend\models\Category;
use frontend\models\Themes;
use frontend\models\Website;
use frontend\models\Users;
use common\models\Smtp;
use common\models\CategoryPath;
use common\models\VendorPhoneNo;
use common\models\VendorItemThemes;

class DirectoryController extends BaseController
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
        \Yii::$app->view->title = Yii::$app->params['SITE_NAME'].' | Vendor';
        \Yii::$app->view->registerMetaTag(['name' => 'description', 'content' => Yii::$app->params['META_DESCRIPTION']]);
        \Yii::$app->view->registerMetaTag(['name' => 'keywords', 'content' => Yii::$app->params['META_KEYWORD']]);

        if(Yii::$app->language == "en") {
            $sort = 'vendor_name';
        } else {
            $sort = 'vendor_name_ar';
        }

        $today = date('Y-m-d H:i:s');

        $query = Vendor::find()
            ->andWhere(['{{%vendor}}.trash'=>'Default'])
            ->andWhere(['{{%vendor}}.approve_status'=>'Yes'])
            ->andWhere(['{{%vendor}}.vendor_status'=>'Active']);

            # for filteration
            if (Yii::$app->request->isAjax) {
                $category_id = Yii::$app->request->post('slug');
                if ($category_id != 'All') {
                    $query->leftJoin('{{%vendor_item}}', '{{%vendor_item}}.vendor_id = {{%vendor}}.vendor_id');
                    $query->leftJoin('{{%vendor_item_to_category}}', '{{%vendor_item}}.item_id = {{%vendor_item_to_category}}.item_id');
                    $query->leftJoin('{{%category_path}}', '{{%category_path}}.category_id = {{%vendor_item_to_category}}.category_id');
                    $query->andWhere(['{{%category_path}}.path_id' => $category_id]);
                }
            }

            $directory = $query->orderby(['{{%vendor}}.'.$sort => SORT_ASC])
            ->groupby(['{{%vendor}}.vendor_id'])
            ->asArray()
            ->all();

        $prevLetter = '';

        $result = array();

        foreach ($directory as $d) {

            if(Yii::$app->language == "en") {
                $firstLetter = mb_substr($d['vendor_name'], 0, 1, 'utf8');
            }else{
                $firstLetter = mb_substr($d['vendor_name_ar'], 0, 1, 'utf8');
            }

            if ($firstLetter != $prevLetter) {
                $result[] = strtoupper($firstLetter);
            }

            $prevLetter = $firstLetter;
        }

        $result = array_unique($result);


        if (Yii::$app->request->isAjax) {
            $request = Yii::$app->request;
            if ($request->post('ajaxdata') == 0) {

                return $this->renderPartial('_listing', [
                    'directory' => $directory,
                    'first_letter' => $result,
                ]);

            } else {

                return $this->renderPartial('_m_listing', [
                    'directory' => $directory,
                    'first_letter' => $result
                ]);
            }
        }

        return $this->render('index', [
            'directory' => $directory,
            'first_letter' => $result,
        ]);
    }

    public function actionProfile($vendor,$slug='all'){
        $website_model = new Website();
        $vendor_details = Vendor::findOne(['slug'=>$vendor]);

        if (empty($vendor_details)) {
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        }

        $data = Yii::$app->request->get();

        $item_query = CategoryPath::find()
            ->select('{{%vendor_item}}.item_for_sale, {{%vendor_item}}.slug, {{%vendor_item}}.item_id, {{%vendor_item}}.item_id, {{%vendor_item}}.item_name, {{%vendor_item}}.item_name_ar, {{%vendor_item}}.item_price_per_unit, {{%vendor}}.vendor_name, {{%vendor}}.vendor_name_ar')
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
                '{{%vendor_item}}.vendor_id' => $vendor_details->vendor_id,
            ]);

        //price filter
        if (isset($data['price']) && $data['price'] != '') {

            $price_condition = [];

            $arr_min_max = explode('-', $data['price']);

            $price_condition[] = '{{%vendor_item}}.item_price_per_unit IS NULL';
            $price_condition[] = '{{%vendor_item}}.item_price_per_unit between '.$arr_min_max[0].' and '.$arr_min_max[1];

            $item_query->andWhere(implode(' OR ', $price_condition));
        }

        //theme filter
        if (isset($data['themes']) && count($data['themes'])>0) {
            
            $item_query->leftJoin('{{%vendor_item_theme}}', '{{%vendor_item}}.item_id = {{%vendor_item_theme}}.item_id');
            $item_query->leftJoin('{{%theme}}', '{{%theme}}.theme_id = {{%vendor_item_theme}}.theme_id');
            $item_query->andWhere(['IN', '{{%theme}}.slug', $data['themes']]);
        }

        $cats = '';

        if ($slug != 'all') 
        {
            $category = Category::findOne(['slug' => $slug]);

            if (empty($Category)) {
                throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
            }

            $cats = $category->category_id;
        }

        if (isset($data['category']) && count($data['category']) > 0) 
        {
            $cats = implode("','", $data['category']);
        }

        if($cats)
        {   
            $item_query->andWhere("{{%category_path}}.path_id IN ('".$cats."')");
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

        $vendor_items = $item_query
            ->groupBy('{{%vendor_item}}.item_id')
            ->orderBy($expression)
            ->asArray()
            ->all();

        $provider = new ArrayDataProvider([
            'allModels' => $vendor_items,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        $item_ids = ArrayHelper::map($vendor_items, 'item_id', 'item_id');

        $themes = \common\models\VendorItemThemes::find()
            ->select(['wt.theme_id','wt.slug','wt.theme_name','wt.theme_name_ar'])
            ->leftJoin('{{%theme}} AS wt', 'FIND_IN_SET({{%vendor_item_theme}}.theme_id,wt.theme_id)')
            ->Where([
                'wt.theme_status' => 'Active',
                'wt.trash' => 'Default',
                '{{%vendor_item_theme}}.trash' => 'Default'
            ])
            ->andWhere(['IN', '{{%vendor_item_theme}}.item_id', $item_ids])
            ->groupby(['wt.theme_id'])
            ->asArray()
            ->all();

        if (!isset(Yii::$app->user->identity->customer_id)) {

            $customer_events_list = [];
            $customer_events = [];

        } else {

            $event_limit = 8;
            $wish_limit = 6;
            $offset = 0;
            $type = '';
            $customer_id = Yii::$app->user->identity->customer_id;

            $model = new Users();
            $customer_events_list = $model->get_customer_wishlist_details($customer_id);
            $customer_events = $model->getCustomerEvents($customer_id, $event_limit, $offset, $type);
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderPartial('@frontend/views/common/items',['items' => $provider,'customer_events_list' => $customer_events_list]);
        }

        if ($vendor_details['vendor_website'] && strpos($vendor_details['vendor_website'], 'http') === false) 
        {
            $vendor_details['vendor_website'] = 'http://'.$vendor_details['vendor_website'];
        }

        $phone_icons = [
            'Whatsapp' => 'fa fa-whatsapp',
            'Mobile' => 'fa fa-mobile',
            'Fax' => 'fa fa-fax',
            'Office' => 'fa fa-building'
        ];

        //day off 
        $search = array(0, 1, 2, 3, 4, 5, 6, ',');

        $replace = array(
            Yii::t('frontend', 'Sunday'),
            Yii::t('frontend', 'Monday'),
            Yii::t('frontend', 'Tuesday'),
            Yii::t('frontend', 'Wednesday'),
            Yii::t('frontend', 'Thursday'),
            Yii::t('frontend', 'Friday'),
            Yii::t('frontend', 'Saturday'),
            ', '
        );

        $day_off = explode(',', $vendor_details['day_off']);

        $txt_day_off = str_replace($search, $replace, $vendor_details['day_off']);

        $TopCategories = Category::find()
            ->where('(parent_category_id IS NULL or parent_category_id = 0) AND trash = "Default"')
            ->orderBy('sort')
            ->asArray()
            ->all();

        return $this->render('profile', [
            'vendor_detail' => $vendor_details,
            'vendor_items' => $vendor_items,
            'themes' => $themes,
            'TopCategories' => $TopCategories,
            'provider' => $provider,
            'slug' => $slug,
            'customer_events' => $customer_events,
            'customer_events_list' => $customer_events_list,
            'phones' => VendorPhoneNo::findAll(['vendor_id' => $vendor_details->vendor_id]),
            'phone_icons' => $phone_icons,
            'txt_day_off' => $txt_day_off
        ]);
    }
}



