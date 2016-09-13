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

            if (Yii::$app->request->isAjax) {

                $data = Yii::$app->request->post();
                $condition = '';
                $join = '';
                if (!empty($data['slug'])) {
                    if (!empty($data['vendor'])) {
                        $vendor = explode('+', $data['vendor']);
                        foreach ($vendor as $key => $val) {
                            $vendor_ids[] = \frontend\models\Vendor::Vendorid_item($val)['vendor_id'];
                        }
                        $v = implode('","', $vendor_ids);

                        $condition .= ' AND wvi.vendor_id IN("'.$v.'")';
                    }

                    /* THEMES FILTER */
                    if ($data['themes'] != '') {
                        $theme = explode('+', $data['themes']);
                        foreach ($theme as $key => $value) {
                            $themes[] = \common\models\Themes::find()->select('theme_id')->where(['slug'=>[$value]])->asArray()->all();
                        }

                        $all_valid_themes = array();
                        foreach ($themes as $key => $value) {
                            $get_themes = \common\models\Vendoritemthemes::find()->select('theme_id, item_id')
                                ->where(['trash'=>"Default"])
                                ->andWhere(['theme_id'=>[$value[0]['theme_id']]])
                                ->asArray()
                                ->all();
                            foreach ($get_themes as $key => $value) {
                                $all_valid_themes[] = $value['item_id'];
                            }
                        }

                        if (count($all_valid_themes) <= 1) {
                            $all_valid_themes = $all_valid_themes[0];
                        } else {
                            //$all_valid_themes = implode('","', $all_valid_themes);
                            $all_valid_themes = implode(',', $all_valid_themes);
                        }
                        /* END Multiple themes match comma seperate values in table*/

                        //  $join .= ' inner join whitebook_theme as wt ON wt.slug REGEXP "'.$theme_ids.'" ';
                        $condition .= ' AND wvi.item_id IN('.$all_valid_themes.') ';
                    }

                    /* BEGIN PRICE FILTER */
                    if ($data['price'] != '') {
                        $price = explode('+', $data['price']);
                        foreach ($price as $key => $value) {
                            $prices[] = $value;
                            $price_val = explode('-', $value);
                            $price_val1[] = 'AND (wvi.item_price_per_unit between '.$price_val[0].' and '.$price_val[1].')';
                        }
                        $condition1 = implode(' OR ', $price_val1);
                        $condition .= str_replace('OR AND', 'OR', $condition1);
                    }

                    /* END PRICE FILTER */

                    $q = 'select *, count(*) as total from whitebook_vendor_item as wvi ';
                    $q .= 'left join whitebook_image as wi on wvi.item_id = wi.item_id left join whitebook_vendor as wv on wvi.vendor_id = wv.vendor_id ';
                    $q .= 'where wvi.trash = "Default" AND wvi. item_approved = "Yes" AND wvi.item_status = "Active" AND (wvi.item_name Like "%'.$data["slug"].'%" OR wvi.item_name_ar Like "%'.$data["slug"].'%")';
                    $q .= $condition;
                    $q .= 'group by wvi.item_id LIMIT 12';
                    $result = Vendoritem::findBySql($q)->all();
                    if (Yii::$app->user->isGuest) {
                        $customer_events_list = [];
                    } else {
                        $customer_id = Yii::$app->user->identity->customer_id;
                        $usermodel = new Users();
                        $customer_events_list = $usermodel->get_customer_wishlist_details($customer_id);
                    }
                    return $this->renderPartial('_item', ['imageData' => $result, 'customer_events_list' => $customer_events_list]);
                }
            } else {
                return $this->redirect(['site/index']);
            }
    }
}