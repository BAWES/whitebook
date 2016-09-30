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

    // END wish list manage page load vendorss based on category

    public function actionDetail($slug = '', $category = '',$subcategory = '', $vendor='', $price='')
    {
        if ($slug) {
            $url = \yii\helpers\Url::to(['themes/detail','slug'=>$slug,'subcategory'=>$subcategory,'vendor'=>$vendor,'price'=>$price]);
            $themeName = Themes::findOne(['slug' => $slug, 'trash' => 'Default']);

            if ($themeName) {
                \Yii::$app->view->title = Yii::$app->params['SITE_NAME'].' | '.ucfirst($themeName->theme_name);

                $itemThemeList = Vendoritemthemes::findAll(['theme_id' => $themeName['theme_id']]);
                $item = \yii\helpers\ArrayHelper::map($itemThemeList, 'item_id', 'item_id');

                $category_id = '';
                $category_slug = '';
                $condition = '( {{%vendor_item}}.trash = "Default") ';
                /* BEGIN GET VENDORS */
                if (!empty($category) && $category!='All') {
                    $category_val = Category::find()->select('category_id')
                        ->where(['slug' => $category])
                        ->asArray()
                        ->one();
                    $category_id = $category_val['category_id'];
                    $category_slug = $category; /* category name Very important */
                    $condition .= ' AND ({{%vendor_item}}.category_id IN("' . $category_id . '")) ';
                }
                if (Yii::$app->request->isAjax) {

                    if ($subcategory != '') {
                        $subcat = str_replace(' ', '","', $subcategory);
                        $condition .= ' AND ({{%category}}.slug IN("' . $subcat . '")) ';
                    }

                    if ($vendor != '') {

                        $vendor = str_replace(' ', '","', $vendor);
                        $condition .= ' AND ({{%vendor}}.slug IN("' . $vendor . '")) ';
                    }


                    /* BEGIN PRICE FILTER */
                    if ($price != '') {
                        $price = explode(' ', $price);
                        foreach ($price as $key => $value) {
                            $prices[] = $value;
                            $price_val = explode('-', $value);
                            $price_val1[] = 'AND ({{%vendor_item}}.item_price_per_unit between ' . $price_val[0] . ' and ' . $price_val[1] . ')';
                        }
                        $condition1 = implode(' OR ', $price_val1);
                        $condition .= str_replace('OR AND', 'OR', $condition1);
                    }
                    /* END PRICE FILTER */
                }

                $active_vendors = Vendor::loadvalidvendorids($category_id);
                if (!is_null($itemThemeList)) {

                    $imageData = Vendoritem::find()
                        ->leftJoin('{{%vendor}}', '{{%vendor_item}}.vendor_id = {{%vendor}}.vendor_id')
                        ->leftJoin('{{%category}}', '{{%category}}.category_id = {{%vendor_item}}.child_category')
                        ->where($condition)
                        ->andWhere(['{{%vendor_item}}.item_id' => $item])
                        ->andWhere(['{{%vendor_item}}.item_approved' => "Yes"])
                        ->andWhere(['{{%vendor_item}}.item_status' => "Active"])
                        ->andWhere(['{{%vendor}}.vendor_id' => $active_vendors])
                        ->andWhere(['{{%vendor}}.trash' => 'Default'])
                        ->andWhere(['{{%vendor_item}}.trash' => "Default"])
                        ->groupBy('{{%vendor_item}}.item_id')
                        ->all();
                }
                // print_r($active_vendors);die;
                /* VENDOR HAVIG ATLEAST ONE PRODUCT */
                $vendor = Vendoritem::find()
                    ->select('{{%vendor}}.vendor_id,{{%vendor}}.vendor_name,{{%vendor}}.slug')
                    ->join('INNER JOIN', '{{%vendor}}', '{{%vendor_item}}.vendor_id = {{%vendor}}.vendor_id')
                    ->leftJoin('{{%category}}', '{{%category}}.category_id = {{%vendor_item}}.child_category')
                    ->where(['{{%vendor_item}}.vendor_id' => $active_vendors])
                    ->andWhere(['{{%vendor}}.vendor_status' => "Active"])
                    ->andWhere(['{{%vendor}}.approve_status' => "Yes"])
                    ->andWhere(['{{%vendor_item}}.item_status' => "Active"])
                    ->andWhere(['{{%vendor_item}}.item_approved' => "Yes"])
                    ->andWhere(['{{%vendor_item}}.trash' => "Default"])
                    ->groupBy('{{%vendor_item}}.vendor_id')
                    ->asArray()
                    ->all();

                /* END get current category to load sub category */

                if (Yii::$app->request->isAjax) {
                    return $this->renderPartial('search_ajax', [
                        'imageData' => $imageData,
                        'customer_events_list'=>[]
                    ]);
                }

                if (Yii::$app->user->isGuest) {

                    return $this->render('search', [
                        'url' => $url,
                        'themeName' => $themeName,
                        'imageData' => $imageData,
                        'vendor' => $vendor,
                        'slug' => $slug,
                        'customer_events_list' => [],
                        'category_slug' => $category_slug,
                        'category_id' => $category_id
                    ]);

                } else {

                    $usermodel = new Users();
                    $customer_events_list = $usermodel->get_customer_wishlist_details(Yii::$app->user->identity->id);

                    return $this->render('search', [
                        'url' => $url,
                        'themeName' => $themeName,
                        'imageData' => $imageData,
                        'vendor' => $vendor,
                        'slug' => $slug,
                        'category_slug' => $category_slug,
                        'customer_events_list' => $customer_events_list,
                        'category_id' => $category_id
                    ]);
                }
            }
        } else {
            return $this->redirect(['site/index']);
        }
    }
}



