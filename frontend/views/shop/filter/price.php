<?php

use yii\web\View;

//price for selected category + selected theme + selected vendor 

$sql = 'SELECT MIN(vi.item_price_per_unit) as min, MAX(vi.item_price_per_unit) as max FROM `whitebook_vendor_item` vi ';

$implode = array();

//from child categories 
if (isset($get['category']) && $get['category'] !="") {
    
    $sub_sql = 'select category_id from whitebook_category where slug IN ("' . str_replace(' ', '", "', $get['category']) . '")';
    
    $implode[] = '(vi.category_id IN ('.$sub_sql.') OR vi.subcategory_id IN ('.$sub_sql.') OR child_category IN ('.$sub_sql.'))';        

//from main category
} elseif (isset($get['slug']) && $get['slug'] !="") {
    
    $sub_sql = 'select category_id from whitebook_category where slug = "'.$get['slug'].'"';

    $implode[] = '(vi.category_id IN ('.$sub_sql.') OR vi.subcategory_id IN ('.$sub_sql.') OR child_category IN ('.$sub_sql.'))';        
}

if (isset($get['themes']) && $get['themes'] !="") {
    
    $sql .= 'inner join whitebook_vendor_item_theme it on it.theme_id= vi.item_id ';
    $sql .= 'inner join whitebook_theme t on t.theme_id = it.theme_id ';

    $implode[] = 'it.trash = Default ';
    $implode[] = 't.slug IN ("' . str_replace(' ', '", "', $get['themes']) . '")';
}

if (isset($get['vendor']) && $get['vendor'] !="") {
    
    $sql .= 'inner join whitebook_vendor v on v.vendor_id = vi.vendor_id ';

    $implode[] = 'v.slug IN ("'.str_replace(' ', '", "', $get['vendor']).'")';
}

//stock availability 
$implode[] = 'vi.trash = "Default"';
$implode[] = 'vi.item_approved = "Yes"';
$implode[] = 'vi.item_status = "Active"';
$implode[] = 'vi.type_id = "2"';
$implode[] = 'vi.item_for_sale = "Yes"';

if($implode) {
    $sql .= ' WHERE '.implode(' AND ', $implode);
}

$result = Yii::$app->db->createCommand($sql)->queryOne();

if($result['max'] != $result['min']) { ?>
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