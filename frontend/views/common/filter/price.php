<?php

use yii\web\View;
use common\models\CategoryPath;

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
    ->where([
        '{{%vendor_item}}.trash' => 'Default',
        '{{%vendor_item}}.item_approved' => 'Yes',
        '{{%vendor_item}}.item_status' => 'Active',
    ]);

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
    <div class="panel-collapse collapse height-0"  id="price" area-expanded="true">
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
        format: '%s',
        width: 200,
        showLabels: true,
        snap: true,
        onbarclicked: function(e){filter();},
        ondragend: function(e){filter();}
    });

", View::POS_READY);

if (isset($get['price'])) {
        $price = explode('-',$get['price']);
    $this->registerJs("jQuery('.price_slider').jRange('updateRange', '".$result['min'].",".$result['max']."', '".$price[0].",".$price[1]."');", View::POS_READY);
}
}//END if
//
$this->registerCss("
.height-0{height: 0px!important;}
");

