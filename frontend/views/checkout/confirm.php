<?php 

use yii\helpers\Html;
use yii\helpers\Url;
use common\models\Booking;
use common\models\Image;
use common\models\CustomerCart;
use common\components\CFormatter;
use common\components\LangFormat;
use common\models\VendorItemPricing;
use common\models\CustomerCartMenuItem;

?>
    <h4 class="panel-title">
        <?= Yii::t('frontend', 'Please Confirms Booking Requests') ?>
    </h4>
<hr />

<?php if($items) { ?>

<form method="post" action="<?= Url::to(['cart/update']) ?>" id="cart-form">	

    <table class="table table-bordered cart-table">
        <thead>
        	<tr>
        		<td align="center"><?= Yii::t('frontend', 'Image') ?></th>
        		<td align="left"><?= Yii::t('frontend', 'Item') ?></th>
        		<td align="right" class="visible-md visible-lg"><?= Yii::t('frontend', 'Total') ?></th>
        	</tr>
        </thead>
        <tbody>
        	<?php
        	$sub_total = $delivery_charge = 0;
            $vendors = [];
        	foreach ($items as $item) {
                $vendor_name = CustomerCart::getVendorDetail($item['vendor_id'])->vendor_name;
                $vendors[$item['vendor_id']] = $vendor_name;
        	    // base price
        	    $row_total = ($item['item']['item_base_price']) ? $item['item']['item_base_price'] : 0;

                //check quantity fall in price chart
                $price_chart = VendorItemPricing::find()
                    ->where(['item_id' => $item['item_id'], 'trash' => 'Default'])
                    ->andWhere(['<=', 'range_from', $item['cart_quantity']])
                    ->andWhere(['>=', 'range_to', $item['cart_quantity']])
                    ->orderBy('pricing_price_per_unit DESC')
                    ->one();

                if ($price_chart) {
                    $unit_price = $price_chart->pricing_price_per_unit;
                } else {
                    $unit_price = $item['item_price_per_unit'];
                }

                if ($item['item']['included_quantity'] > 0) {
                    $min_quantity_to_order = $item['item']['included_quantity'];
                } else {
                    $min_quantity_to_order = 1;
                }

                $actual_item_quantity = $item['cart_quantity'] - $min_quantity_to_order;

                $row_total += $unit_price * $actual_item_quantity;

                $menu_option_items = CustomerCartMenuItem::find()
                        ->select('{{%vendor_item_menu_item}}.price, {{%vendor_item_menu_item}}.menu_item_id, {{%vendor_item_menu_item}}.menu_id, {{%vendor_item_menu_item}}.menu_item_name, {{%vendor_item_menu_item}}.menu_item_name_ar, {{%customer_cart_menu_item}}.quantity')
                        ->innerJoin('{{%vendor_item_menu_item}}', '{{%vendor_item_menu_item}}.menu_item_id = {{%customer_cart_menu_item}}.menu_item_id')                        
                        ->innerJoin('{{%vendor_item_menu}}', '{{%vendor_item_menu}}.menu_id = {{%customer_cart_menu_item}}.menu_id')
                        ->where(['cart_id' => $item['cart_id'], 'menu_type' => 'options'])
                        ->asArray()
                        ->all();

                $menu_addon_items = CustomerCartMenuItem::find()
                    ->select('{{%vendor_item_menu_item}}.price, {{%vendor_item_menu_item}}.menu_item_id, {{%vendor_item_menu_item}}.menu_id, {{%vendor_item_menu_item}}.menu_item_name, {{%vendor_item_menu_item}}.menu_item_name_ar, {{%customer_cart_menu_item}}.quantity')
                    ->innerJoin('{{%vendor_item_menu_item}}', '{{%vendor_item_menu_item}}.menu_item_id = {{%customer_cart_menu_item}}.menu_item_id')
                    ->innerJoin('{{%vendor_item_menu}}', '{{%vendor_item_menu}}.menu_id = {{%customer_cart_menu_item}}.menu_id')
                    ->where(['cart_id' => $item['cart_id'], 'menu_type' => 'addons'])
                    ->asArray()
                    ->all();

                foreach ($menu_addon_items as $key => $value) {
                    $row_total += $value['quantity'] * $value['price'];
                }

                foreach ($menu_option_items as $key => $value) {
                    $row_total += $value['quantity'] * $value['price'];
                }

                $sub_total += $row_total;

                ?>
                <tr>
                    <td align="center">
                        <?php

                        $image_row = Image::find()->select(['image_path'])
                                ->where(['item_id' => $item['item_id']])
                                ->orderby(['vendorimage_sort_order' => SORT_ASC])
                                ->asArray()
                                ->one();

                        if ($image_row) {
                            $imglink = Yii::getAlias("@s3/vendor_item_images_210/")
                                . $image_row['image_path'];
                        } else {
                            $imglink = Url::to("@web/images/item-default.png");
                        }

                        echo Html::img($imglink, ['style' => 'width:50px; height:50px;']);

                        ?>
                    </td>
                    <td>
                        <a target="_blank" href="<?= Url::to(["browse/detail", 'slug' => $item['slug']]) ?>">
                            <?= LangFormat::format($item['item_name'],$item['item_name_ar']) ?>
                        </a>

                        <br />
                        <!-- Quantity -->
                        <i><small><strong><?=Yii::t('frontend','Quantity').':</strong> '. $item['cart_quantity']?></small></i>
                        <!-- Quantity -->
                        <br/>

                        <i><small><strong><?=Yii::t('frontend','Vendor Name').':</strong> '. $vendor_name?></small></i>
                        <!-- Quantity -->
                        <br/>
                        <?php

                            if($menu_option_items)
                            {
                                echo '<b>'.Yii::t('frontend', 'Options').'</b>';
                            }

                            foreach ($menu_option_items as $key => $menu_item) {
                                if(Yii::$app->language == 'en') {
                                    echo '<i class="cart_menu_item">'.$menu_item['menu_item_name'].' x '.$menu_item['quantity'];
                                }else{
                                    echo '<i class="cart_menu_item">'.$menu_item['menu_item_name_ar'].' x '.$menu_item['quantity'];
                                }

                                $menu_item_total = $menu_item['quantity'] * $menu_item['price'];

                                if($menu_item_total) {
                                    echo ' = '.CFormatter::format($menu_item_total);
                                }

                                echo '</i>';
                            }

                            if($menu_addon_items)
                            {
                                echo '<b>'.Yii::t('frontend', 'Add-Ons').'</b><br />';
                            }

                            foreach ($menu_addon_items as $key => $menu_item) {
                                if(Yii::$app->language == 'en') {
                                    echo '<i class="cart_menu_item">'.$menu_item['menu_item_name'].' x '.$menu_item['quantity'];
                                }else{
                                    echo '<i class="cart_menu_item">'.$menu_item['menu_item_name_ar'].' x '.$menu_item['quantity'];
                                }

                                $menu_item_total = $menu_item['quantity'] * $menu_item['price'];

                                if($menu_item_total) {
                                    echo ' = '.CFormatter::format($menu_item_total);
                                }

                                echo '</i>';
                            }

                            if($item['female_service']) {
                                echo '<i class="cart_menu_item">'.Yii::t('frontend', 'Female service').'</i>';
                            }

                            if($item['special_request']) {
                                echo '<i class="cart_menu_item">'.$item['special_request'].'</i>';
                            }

                            ?>

                            <?php if($menu_option_items || $menu_addon_items) { ?>
                                <div class="visible-xs visible-sm">
                                     = <?= CFormatter::format($row_total); ?>
                                </div>
                            <?php } else { ?>
                                <div class="visible-xs visible-sm">
                                    x <?= $item['cart_quantity'] ?> = <?= CFormatter::format($row_total); ?>
                                </div>
                            <?php } ?>
                    </td>
                    <td align="right" class="visible-md visible-lg">
                        <?= CFormatter::format($row_total) ?>
                    </td>
                </tr>
        	<?php
        	}
        	?>
            <tr>
                <td colspan="2" class="text-right"><strong><?= Yii::t('frontend', 'Sub-Total') ?></strong></td>
                <td class="text-right"><?= CFormatter::format($sub_total) ?></td>
            </tr>
            <?php
                foreach ($vendors as $key => $vendor) {
                    $charge = Booking::getDeliveryCharges(Yii::$app->session->get('address_id'),$key);
                    
                    $delivery_charge += (int) $charge;

                    if($charge < 1)
                        continue;
            ?>
            <tr class="delivery-charge-detail">
                <td colspan="2" class="text-right"><strong><?= Yii::t('frontend', 'Delivery Charge') ?></strong> <small>( <?=$vendor?> )</small></td>
                <td class="text-right"><?= CFormatter::format($charge) ?></td>
            </tr>
            <?php } ?>

            <?php if($delivery_charge > 0) { ?>
            <tr class="delivery-charge-total">
                <td colspan="2" class="text-right">
                    <strong><?= Yii::t('frontend', 'Delivery Charge') ?></strong>
                    <button class="btn btn-xs" type="button">
                        <i class="fa fa-plus"></i> <?= Yii::t('frontend', 'Expand') ?>
                    </button>
                </td>
                <td class="text-right"><?= CFormatter::format($delivery_charge) ?></td>
            </tr>
            <?php } ?>
            
            <tr>
                <td colspan="2"  class="text-right"><strong><?= Yii::t('frontend', 'Total') ?></strong></td>
                <td class="text-right"><?= CFormatter::format($sub_total + $delivery_charge) ?></td>
            </tr>

        </tbody>        	
    </table>

    <h3><?= Yii::t('frontend', 'Delivery Address') ?></h3>
    <?= Booking::getPurchaseDeliveryAddress(Yii::$app->session->get('address_id')); ?>

    <?php require '_delivery.php'; ?>
</form>

<div class="checkout-confirm-btn-set">

    <div class="checkbox checkbox-inline">    
        <input type="checkbox" name="agree" value="1" id="chk_agree" />
        <label for="chk_agree">
            <?= Yii::t('frontend', 'I agree on the {a} terms & conditions {/a}', [
                    'a' => '<a target="_blank" href="'.Url::toRoute('/terms-conditions',true).'">', 
                    '/a' => '</a>'
                ]); ?>
        </label>
    </div>

    <hr />

    <a href="<?= $pg_link ?>" class="btn btn-primary btn-checkout btn-confirm btn-lg">
        <?= Yii::t('frontend', 'Confirm Request(s)') ?>
    </a>

</div>

<br />
<br />
<?php } else { ?>
	<p class="text-center">
		<?= Yii::t('frontend', 'Your cart is empty!') ?>
	</p>
	<br />
	<br />
	<br />
	<br />
<?php } ?>

<?php

$this->registerCss("
    .margin-left-0{margin-left: 0;}
");
?>