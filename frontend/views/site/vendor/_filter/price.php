<?php 

use common\components\CFormatter;

$min_price = Yii::$app->db->createCommand('SELECT MIN(item_price_per_unit) as price FROM `whitebook_vendor_item` WHERE trash="Default" and item_approved="Yes"  and item_status="Active" and item_for_sale="Yes"')->queryAll();

$max_price = Yii::$app->db->createCommand('SELECT MAX(item_price_per_unit) as price FROM `whitebook_vendor_item` WHERE trash="Default" and item_approved="Yes"  and item_status="Active" and item_for_sale="Yes"')->queryAll();

$max = $max_price[0]['price'];

if($max_price[0]['price'] != $min_price[0]['price']) { ?>
<div class="panel panel-default" >
    <div class="panel-heading">
        <div class="clear_left"><p><?= Yii::t('frontend', 'Price'); ?> <a href="javascript:void(0)" class="filter-clear" id="filter-clear" title="Clear">- Clear</a></p></div>
        <div class="clear_right">
            <a href="#price" data-parent="#accordion" data-toggle="collapse" class="collapsed" id="sub_category_price">
                <h4 class="panel-title">
                    <span class="plus_acc">
                    </span>
                </h4></a>
        </div>
    </div>
    <div class="panel-collapse collapse" style="height: 0px;" id="price" area-expanded="true" aria-expanded="true">
        <div class="panel-body">
            <div class="table">
                <ul class="test_scroll">
                    <?php
                   
                    $divide = round($max / 5);

                    $i = 0;
                    for ($x = $min_price[0]['price']; $x <= 1000; $x+=$divide) {

                        $min_kd = round($x - $divide);

                        if ($min_kd > 0) {
                            foreach ($vendorData as $key => $value) {
                                # code...
                                $item_price = $value['item_price_per_unit'];

                                $check_range = ($item_price >= $min_kd && $item_price <= $x) ? 1 : 0;

                                if ($check_range == 1) {
                                    ?>
                                    <li>
                                        <label class="label_check" for="checkbox-<?php echo $x; ?>">
                                            <input name="price" id="checkbox-<?php echo $x; ?>" value=<?php echo $min_kd . '-' . $x; ?> type="checkbox">

                                            <?php 

                                            $min_kd = floor($min_kd / 100) * 100;

                                            echo CFormatter::asCurrency($min_kd) ?>
                                            -  
                                            <?php 

                                            $x = ceil($x / 100) * 100; 

                                            echo CFormatter::asCurrency($x) ?>
                                        </label>
                                    </li>
                    <?php
                                break;
                                }
                                $i++;
                            }
                        }
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>
</div>
<?php 
}