<?php

namespace frontend\controllers;

use Yii;
use common\models\Vendoritem;
use frontend\models\Vendor;
use frontend\models\Themes;
use frontend\models\Users;
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
                '{{%vendor_item}}.item_status' => 'Active'
            ]);

        //vendor filter
        if (isset($data['vendor'])  && $data['vendor'] != '') {
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
        //theme filter
        if (isset($data['themes']) && $data['themes'] != '') {
            $theme = explode($explode, $data['themes']);
            foreach ($theme as $key => $value) {
                $themes[] = \common\models\Themes::find()
                    ->select('theme_id')
                    ->where(['slug' => [$value]])
                    ->asArray()
                    ->all();
            }

            $all_valid_themes = array();
            foreach ($themes as $key => $value) {
                $get_themes = \common\models\Vendoritemthemes::find()
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


        //if search query given
        if ($search != 'All') {
            $items_query->andWhere(['like','{{%vendor_item}}.item_name', $search]);
        }

        $items = $items_query->groupBy('{{%vendor_item}}.item_id')
            ->orderBy('{{%image}}.vendorimage_sort_order', SORT_ASC)
            ->asArray()
            ->all();


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

        $usermodel = new Users();

        if (Yii::$app->user->isGuest) {
            $customer_events_list = [];
        } else {
            $customer_id = Yii::$app->user->identity->customer_id;
            $customer_events_list = $usermodel->get_customer_wishlist_details($customer_id);
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderPartial('@frontend/views/plan/product_list_ajax', [
                'items' => $items,
                'customer_events_list' => $customer_events_list
            ]);
        }

        return $this->render('search', [
            'items' => $items,
            'themes' => $themes1,
            'vendor' => $vendor,
            'slug' => $slug,
            'customer_events_list' => $customer_events_list,
            'search' => $search
        ]);
    }

    public function actionSearch()
    {
        $request = Yii::$app->request;

        if ($request->post('search') && $request->post('_csrf')) {

            $item_details = Vendoritem::find()
                ->select(['item_name','slug'])
                ->where(['like', 'item_name',$request->post('search')])
                ->andwhere(['whitebook_vendor_item.trash' =>'Default','item_for_sale' =>'Yes','item_status'=>'Active'])
                ->distinct()
                ->asArray()
                ->all();

            $k = '';
            if (!empty($item_details)) {
                foreach ($item_details as $i) {
                    if (!empty($i['item_name'])) {
                        $k = $k.'<li><a href='.\yii\helpers\Url::to(['/search/','slug'=>$i['slug']]).'>'.$i['item_name'].'</a></li>';
                    }
                }
                return '<ul>'.$k.'</ul>';
            } else {
                echo '0';
                die;
            }
        }
    }
}


