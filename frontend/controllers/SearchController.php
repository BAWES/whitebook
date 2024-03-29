<?php

namespace frontend\controllers;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\db\Expression;
use yii\data\ArrayDataProvider;
use frontend\models\Vendor;
use frontend\models\Themes;
use frontend\models\Users;
use frontend\models\CategoryModel;
use common\models\VendorItem;
use common\models\Smtp;
use common\models\CategoryPath;

class SearchController extends BaseController
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

    /**
     * @param $search
     * @return string
     */
    public function actionIndex($search)
    {
        if($search == 'all') {
            $search = '';
        }

        $search = str_replace('and', '&', $search);
        $search = str_replace('-', ' ', $search);
        $k = '';
        $slug = '';
        $explode = ' ';

        if (Yii::$app->request->isAjax) {
            $explode = '+';
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

        //vendor filter
        if (isset($data['vendor'])  && $data['vendor']) {
            $item_query->vendorSlug($data['vendor']);
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


            $item_query->itemThemeJoin();
            $item_query->themeJoin();
            $item_query->themeSlug($data['themes']);
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
        
        //if search query given
        if ($search != 'All') {
            $item_query->likeItemName($search);
        }

        $item_query_result = $item_query
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

        $count = sizeof($items);

        foreach ($items as $data) {
            $k[] = $data['item_id'];
        }

        $themes1 = array();
        $vendor = array();
        if (!empty($k)) {
            $result = Themes::loadthemename_item($k);
            $out1[] = array();
            $out2[] = array();
            foreach ($result as $r) {
                if (is_numeric($r['theme_id'])) {
                    $out1[] = $r['theme_id'];
                }
                if (!is_numeric($r['theme_id'])) {
                    $out2[] = explode(',', $r['theme_id']);
                }
            }
            $p = array();
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

            $themes1 = Themes::load_all_themename($p);
            $vendor = Vendor::loadvendor_item($k);
        }

        $vendorSearchData = Vendor::find()
            ->select(['vendor_name','vendor_name_ar','vendor_logo_path','slug'])
            ->vendorName($search)
            ->defaultVendor()
            ->approved()
            ->active()
            ->orderBy('vendor_name')
            ->limit(10)
            ->all();

        $usermodel = new Users();

        if (Yii::$app->user->isGuest) {
            $customer_events_list = [];
        } else {
            $customer_id = Yii::$app->user->identity->customer_id;
            $customer_events_list = $usermodel->get_customer_wishlist_details($customer_id);
        }

        $provider = new ArrayDataProvider([
            'allModels' => $items,
            'pagination' => [
                'pageSize' => 48,
            ],
        ]);

        if (Yii::$app->request->isAjax) {
            return $this->renderPartial('@frontend/views/common/items', [
                'items' => $provider,
                'customer_events_list' => $customer_events_list
            ]);
        }

        return $this->render('search', [
            'count' => $count,
            'items' => $provider,
            'themes' => $themes1,
            'vendor' => $vendor,
            'slug' => $slug,
            'customer_events_list' => $customer_events_list,
            'search' => $search,
            'vendorSearch' => $vendorSearchData
        ]);
    }

    public function actionSearch()
    {
        $request = Yii::$app->request;

        if ($request->post('search') && $request->post('_csrf')) {

            $item_details = $item_query = CategoryPath::find()
                ->selectedFields()
                ->categoryJoin()
                ->itemJoin()
                ->priorityItemJoin()
                ->vendorJoin()
                ->defaultItems()
                ->likeItemName($request->post('search'))
                ->approved()
                ->active()
                ->groupBy('{{%vendor_item}}.item_id')
                ->orderByExpression()
                ->asArray()
                ->all();

            $vendorSearchData = Vendor::find()
                ->select(['vendor_name','slug'])
                ->where(['like', 'vendor_name', $request->post('search')])
                ->approved()
                ->active()
                ->defaultVendor()
                ->limit(10)
                ->asArray()
                ->all();

            $k = '';

            if (!empty($item_details)) {
                foreach ($item_details as $i) {
                    if (!empty($i['item_name'])) {
                        $item_name = (strlen($i['item_name'])>20) ? substr($i['item_name'],0,20).'...' : $i['item_name'];
                        $k = $k.'<li><a href='.Url::to(['search/index', 'search' => $request->post('search')]).'>'.$item_name.'</a></li>';
                    }
                }
            }

            if (!empty($vendorSearchData)) {
                foreach ($vendorSearchData as $result) {
                    $k .= '<li>'.Html::a($result['vendor_name'],['community/profile','vendor'=>$result['slug']]).'</li>';
                }
            }

            if ($k) {
                return '<ul>' . $k . '</ul>';
            } else {
                echo '0';
                die;
            }
        }
    }
}



