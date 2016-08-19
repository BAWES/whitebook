<?php

/* Get max price_per_unit in item table */
$min_price = Yii::$app->db->createCommand('SELECT MIN(item_price_per_unit) as price FROM `whitebook_vendor_item` WHERE trash="Default" and item_approved="Yes"  and item_status="Active" and item_for_sale="Yes"')->queryAll();

$max_price = Yii::$app->db->createCommand('SELECT MAX(item_price_per_unit) as price FROM `whitebook_vendor_item` WHERE trash="Default" and item_approved="Yes"  and item_status="Active" and item_for_sale="Yes"')->queryAll();

$max = $max_price[0]['price'];

if($max > 0) { ?>
<div class="panel panel-default" >
    <div class="panel-heading">
        <div class="clear_left">
            <p>
                <?= Yii::t('frontend', 'Price'); ?>
                <a href="javascript:void(0)" class="filter-clear" id="filter-clear" title="Clear">- <?= Yii::t('frontend', 'Clear') ?>
                </a>
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
            <div class="table">
                <ul class="test_scroll">
                    <?php

                    $divide = round($max / 5);

                    $i = 0;

                    for ($x = $min_price[0]['price'] ; $x <= 1000 ; $x+=$divide) {

                        $min_kd = round($x-$divide);

                        if ($min_kd > 0 ) {

                            foreach ($imageData as $key => $value) {
                                /* Check checkbox based on URL */
                                if (isset($get['price']) && $get['price'] !="") {

                                    $val = explode(' ',$get['price']);

                                    $checked3 = (in_array($value['slug'],$val)) ? 'checked=checked' : '';
                                }
                                /* Check checkbox based on URL */
                                # code...
                                $item_price = $value['item_price_per_unit'];

                                $check_range = ($item_price >= $min_kd && $item_price <= $x) ? 1 : 0;

                                if($check_range ==1) { ?>
                                    <li>
                                        <label class="label_check" for="checkbox-<?php echo $x;?>">
                                        <input name="price" id="checkbox-<?php echo $x;?>" value="<?php echo $min_kd = floor($min_kd / 100) * 100;  $min_kd; ?>-<?php echo $x = ceil($x / 100) * 100;?>" type="checkbox">
                                        <?php echo $min_kd = floor($min_kd / 100) * 100;  $min_kd; ?> KD  -  <?php echo $x = ceil($x / 100) * 100;?> KD</label>
                                    </li>
                                    <?php
                                    break;
                                }
                                $i++; 
                            }
                        }
                    } ?>
                </ul>
            </div>
        </div>
    </div>
</div>
<?php } ?>