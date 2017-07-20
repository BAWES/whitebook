<?php

namespace frontend\controllers;

use common\models\VendorWorkingTiming;
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
use common\models\VendorReview;
use common\models\Booking;

class CommunityController extends BaseController
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
            ->defaultVendor()
            ->active()
            ->approved();

            # for filteration
            if (Yii::$app->request->isAjax) {
                $category_id = Yii::$app->request->post('slug');
                if ($category_id != 'All') {
                    $query->joinVendorItem();
                    $query->joinVendorToCategory();
                    $query->joinCategoryPath();
                    $query->categoryPathID($category_id);
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
        
            return $this->renderPartial('_listing', [
                'directory' => $directory,
                'first_letter' => $result,
            ]);
        }

        return $this->render('index', [
            'directory' => $directory,
            'first_letter' => $result,
        ]);
    }

    public function actionProfile($vendor, $slug='all'){
        $website_model = new Website();
        $vendor_details = Vendor::findOne(['slug'=>$vendor]);

        if (empty($vendor_details)) {
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        }

        $data = Yii::$app->request->get();

        $item_query = CategoryPath::find()
            ->selectedFields()
            ->categoryJoin()
            ->itemJoin()
            ->priorityItemJoin()
            ->vendorJoin()
            ->defaultItems()
            ->approved()
            ->active();

        $item_query->vendor($vendor_details->vendor_id);

        //price filter
        if (isset($data['price']) && $data['price'] != '') {

            $price_condition = [];

            $arr_min_max = explode('-', $data['price']);
            $item_query->price($arr_min_max[0], $arr_min_max[1]);
        }
        
        //notice_period 
        if (isset($data['notice_period_from']) && ($data['notice_period_from'] >= 0 || $data['notice_period_to'] >= 0)) 
        {
            $item_query->filterByNoticePeriod(
                    $data['notice_period_from'],
                    $data['notice_period_to'],
                    $data['notice_period_type']
                );
        }

        //theme filter
        if (isset($data['themes']) && count($data['themes'])>0) {
            
            $item_query->itemThemeJoin();
            $item_query->themeJoin();
            $item_query->themeSlug($data['themes']);
        }

        $cats = '';

        if ($slug != 'all') 
        {
            $category = Category::findOne(['slug' => $slug]);

            if (empty($category)) {
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
            $item_query->categoryIDs($cats);
        }

        $item_query->orderByExpression();

        $vendor_items = $item_query
            ->groupBy('{{%vendor_item}}.item_id')
            ->asArray()
            ->all();

        $provider = new ArrayDataProvider([
            'allModels' => $vendor_items,
            'pagination' => [
                'pageSize' => 50,
            ],
        ]);

        $item_ids = ArrayHelper::map($vendor_items, 'item_id', 'item_id');

        $q = \common\models\VendorItemThemes::find()
            ->select(['wt.theme_id','wt.slug','wt.theme_name','wt.theme_name_ar'])
            ->leftJoin('{{%theme}} AS wt', 'FIND_IN_SET({{%vendor_item_theme}}.theme_id,wt.theme_id)')
            ->Where([
                'wt.theme_status' => 'Active',
                'wt.trash' => 'Default',
                '{{%vendor_item_theme}}.trash' => 'Default'
            ])
            ->andWhere(['IN', '{{%vendor_item_theme}}.item_id', $item_ids])
            ->groupby(['wt.theme_id']);

        if(Yii::$app->language == 'en')
        {
            $q->orderBy('wt.theme_name');
        }
        else
        {
            $q->orderBy('wt.theme_name_ar');
        }
            
        $themes = $q->asArray()
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
        );
        $working_days = ArrayHelper::map(VendorWorkingTiming::find()->vendor($vendor_details->vendor_id)->all(),'working_day','working_day');
        $txt_day_off = implode(', ',array_diff($replace,$working_days));

        $TopCategories = Category::find()
            ->allParents()
            ->defaultCategories()
            ->orderBy('sort')
            ->asArray()
            ->all();

        $modelReview = new VendorReview;
        $modelReview->vendor_id = $vendor_details->vendor_id;

        //check if customer have place order from this vendor, but not added review for that 

        $booking = Booking::find()
            ->where([
                'vendor_id' => $vendor_details->vendor_id,
                'customer_id' => Yii::$app->user->getId()
            ])
            ->one();

        $review = VendorReview::find()
            ->where([
                    'vendor_id' => $vendor_details->vendor_id,
                    'customer_id' => Yii::$app->user->getId()
            ])
            ->one();

        if($booking && !$review) {
            $canAddReview = true;
        } else {
            $canAddReview = false;
        }

        $reviews = VendorReview::find()
            ->where([
                'vendor_id' => $vendor_details->vendor_id,
                'approved' => 1
            ])
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
            'txt_day_off' => $txt_day_off,
            'canAddReview' => $canAddReview,
            'modelReview' => $modelReview,
            'reviews' => $reviews
        ]);
    }

    public function actionReview() 
    {
        Yii::$app->response->format = \Yii\web\Response::FORMAT_JSON;

        $model = new VendorReview();        
        $model->customer_id = Yii::$app->user->getId();

        if ($model->load(Yii::$app->request->post()) && $model->save()) 
        {
            Yii::$app->getSession()->setFlash('success', Yii::t('frontend', 'We got your review, thank you!'));

            return [
                'operation' => 'success'
            ];    
        } 
        else 
        {
            return [
                'operation' => 'error',
                'message' => $model->getErrors()
            ];   
        }
    }
}



