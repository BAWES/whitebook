<?php
/**
 * Created by PhpStorm.
 * User: anilkumar
 * Date: 9/13/16
 * Time: 11:54 AM
 */

namespace frontend\controllers;

use Yii;
use yii\filters\VerbFilter;
use frontend\models\Users;
use frontend\models\Vendoritem;
use common\models\CategoryPath;


class ProductFilterResultController extends BaseController
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => ['post'],
            ],
        ];
    }

    public function actionSearchingPageFilter() {

        if (!Yii::$app->request->isAjax) {
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        }

        $data = Yii::$app->request->post();

        //items only from active vendors 
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

        if($data["slug"] && $data["slug"]!='all') {
            $items_query->andWhere(['like', '{{%vendor_item}}.item_name', $data['slug']]);
        }

        //vendor filter
        if ($data['vendor'] != '') {
            $items_query->andWhere(['in', '{{%vendor}}.slug', explode('+', $data['vendor'])]);
        }

        //price filter 
        if ($data['price'] != '') {

            $price_condition = [];

            foreach (explode('+', $data['price']) as $key => $value) {
                $arr_min_max = explode('-', $value);
                $price_condition[] = '{{%vendor_item}}.item_price_per_unit between '.$arr_min_max[0].' and '.$arr_min_max[1];
            }

            $items_query->andWhere(implode(' OR ', $price_condition));
        }

        //theme filter 
        if ($data['themes'] != '') {

            $theme = explode('+', $data['themes']);

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

        $items_query
            ->groupBy('{{%vendor_item}}.item_id')
            ->orderBy('{{%image}}.vendorimage_sort_order', SORT_ASC);
        
        $items = $items_query->asArray()->all();

        $customer_events_list = array();

        if (!Yii::$app->user->isGuest) {
            $usermodel = new Users();
            $customer_events_list = $usermodel->get_customer_wishlist_details(
                Yii::$app->user->identity->customer_id
            );
        }

        return $this->renderPartial('@frontend/views/plan/product_list_ajax', [
            'items' => $items, 
            'customer_events_list' => $customer_events_list
        ]);
    }
}