<?php

use yii\web\View;
use common\models\CategoryPath;
use frontend\models\Themes;
use frontend\models\Vendor;
use common\models\Vendoritemthemes;

$get = Yii::$app->request->get();

$items_query = CategoryPath::find()
    ->select('MAX({{%vendor_item}}.item_price_per_unit) as max, min({{%vendor_item}}.item_price_per_unit) as min')
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
    ]);


//from child categories 
if (isset($get['category']) && $get['category'] !="") {
    
    $items_query->andWhere('{{%category_path}}.path_id IN (select category_id from {{%category}} where slug IN ("'.str_replace(' ', '", "', $get['category']).'") and trash = "Default")');

//from main category
} elseif (isset($get['slug']) && $get['slug'] !="") {
    
    $items_query->andWhere('{{%category_path}}.path_id IN (select category_id from {{%category}} where slug = "' . $get['slug'] .'" and trash = "Default")');
}

//vendor filter
if (isset($get['vendor'])) {
    $items_query->andWhere(['in', '{{%vendor}}.slug', explode('+', $get['vendor'])]);
}

//theme filter 
if (isset($get['themes'])) {

    $theme = explode('+', $get['themes']);

    foreach ($theme as $key => $value) {
        $themes[] = Themes::find()
            ->select('theme_id')
            ->where(['slug' => [$value]])
            ->asArray()
            ->one();
    }

    $all_valid_themes = array();

    foreach ($themes as $key => $value) {

        $get_themes = Vendoritemthemes::find()
            ->select('theme_id, item_id')
            ->where(['trash' => "Default"])
            ->andWhere(['theme_id' => $value['theme_id']])
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

$result = $items_query->asArray()->one();

if($result && $result['max'] != $result['min']) { ?>
<div class="panel panel-default" >
    <div class="panel-heading">
        <div class="clear_left">
            <p>
                <?= Yii::t('frontend', 'Price'); ?>
            </p>
        </div>
        <div class="clear_right">
            <a href="#price" data-parent="#accordion" data-toggle="collapse" class="collapsed" id="sub_category_price">
                <h4 class="panel-title">
                    <span class="plus_acc"></span>
                </h4>
            </a>
        </div>
    </div>
    <div class="panel-collapse collapse" style="height: 0px;" id="price" area-expanded="true">
        <div class="panel-body">
            <input type="hidden" class="price_slider" value="<?= $result['min'].','.$result['max'] ?>" />
        </div>
    </div>
</div>

<?php 

$this->registerCssFile('@web/css/jquery.range.css');
$this->registerJsFile('@web/js/jquery.range.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerJs("
    jQuery('.price_slider').jRange({
        from: ".$result['min'].",
        to: ".$result['max'].",
        //step: 0.5,
        //scale: [-2.0,-1.0,0.0,1.0,2.0],
        //format: '%s KWD',
        width: 200,
        showLabels: true,
        snap: true,
        onbarclicked: function(e){
            filter();
        },
        ondragend: function(e){
            filter();
        }
    });
", View::POS_READY);

}//END if 

