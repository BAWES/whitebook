<?php 

use yii\helpers\Html;
use yii\helpers\Url;
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
        		<td align="left"><?= Yii::t('frontend', 'Item Name') ?></th>
        		<td align="left"><?= Yii::t('frontend', 'Delivery') ?></th>
        		<td aligh="left">
                    <span class="visible-md visible-lg">
                        <?= Yii::t('frontend', 'Quantity') ?>
                    </span>
                    <span class="visible-xs visible-sm">
                        <?= Yii::t('frontend', 'Qty') ?>
                    </span>
                </td>
        		<td align="right" class="visible-md visible-lg"><?= Yii::t('frontend', 'Unit Price') ?></th>
        		<td align="right" class="visible-md visible-lg"><?= Yii::t('frontend', 'Total') ?></th>
        	</tr>
        </thead>
        <tbody>
        	<?php 

        	$sub_total = $delivery_charge = 0;

        	foreach ($items as $item) {

            //check quantity fall in price chart 
            $price_chart = VendorItemPricing::find()
                ->where(['item_id' => $item['item_id'], 'trash' => 'Default'])
                ->andWhere(['<=', 'range_from', $item['cart_quantity']])
                ->andWhere(['>=', 'range_to', $item['cart_quantity']])
                ->orderBy('pricing_price_per_unit DESC')
                ->one();

            if($price_chart) {
                $unit_price = $price_chart->pricing_price_per_unit;
            }else{
                $unit_price = $item['item_price_per_unit'];
            }

            $row_total = $unit_price * $item['cart_quantity'];

            $menu_items = CustomerCartMenuItem::find()
                ->select('{{%vendor_item_menu_item}}.price, {{%vendor_item_menu_item}}.menu_item_name, {{%vendor_item_menu_item}}.menu_item_name_ar, {{%customer_cart_menu_item}}.quantity')
                ->innerJoin('{{%vendor_item_menu_item}}', '{{%vendor_item_menu_item}}.menu_item_id = {{%customer_cart_menu_item}}.menu_item_id')
                ->where(['cart_id' => $item['cart_id']])
                ->asArray()
                ->all();

            foreach ($menu_items as $key => $value) {
                $row_total += $value['quantity'] * $value['price'];
            }
			
			$sub_total += $row_total;

            $address_data = CustomerCart::getAddressData($address[$item['cart_id']]);

            $delivery_area = CustomerCart::geLocation($item['area_id'], $item['vendor_id']);

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
        				<?=LangFormat::format($item['item_name'],$item['item_name_ar']) ?>
        			</a>

                    <?php 

                        foreach ($menu_items as $key => $menu_item) { 
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

                        <?php if($menu_items) { ?>
                            <div class="visible-xs visible-sm">                         
                                 = <?= CFormatter::format($row_total); ?>
                            </div>
                        <?php } else { ?>
                            <div class="visible-xs visible-sm">                         
                                x <?= $item['cart_quantity'] ?> = <?= CFormatter::format($row_total); ?>
                            </div>
                        <?php } ?>
        		</td>
        		<td>
        			<?php 

        			if(isset($delivery_area->location)) { 

						$delivery_charge += $delivery_area->delivery_price;

        				?>
        				
        				<?= nl2br($address_data); ?> <br />
                        <?=LangFormat::format($delivery_area->location->location,$delivery_area->location->location_ar);?><br/>
                        <?=LangFormat::format($delivery_area->location->city->city_name,$delivery_area->location->city->city_name_ar);?><br/>

                        <?= $item['cart_delivery_date'] ?> <br />
        			
                        <?= $item['time_slot']; ?>

        			<?php } else { ?>
        				<span class="error">
        					<?= Yii::t('frontend', 'We cannot delivery this item!'); ?>
        				</span>
        			<?php } ?>		        			
        		</td>
        		<td align="left">
	        		<?= $item['cart_quantity'] ?>
                    </div>
                </td>
        		<td align="right" class="visible-md visible-lg">
                    <?= CFormatter::format($unit_price); ?>
                </td>
        		<td align="right" class="visible-md visible-lg">
                    <?= CFormatter::format($row_total) ?>
                </td>
        	</tr>
        	<?php } ?>
        </tbody>        	
    </table>

</form>

<?php /* ?>
<div class="row">
    <div class="col-sm-4 pull-right">
      <table class="table table-bordered">
        <tbody>
        <tr>
          <td class="text-right"><strong><?= Yii::t('frontend', 'Sub-Total') ?></strong></td>
          <td class="text-right"><?= CFormatter::format($sub_total) ?></td>
        </tr>
        <tr>
          <td class="text-right"><strong><?= Yii::t('frontend', 'Delivery Charge') ?></strong></td>
          <td class="text-right"><?= CFormatter::format($delivery_charge) ?></td>
        </tr>
        <tr>
          <td class="text-right"><strong><?= Yii::t('frontend', 'Total') ?></strong></td>
          <td class="text-right"><?= CFormatter::format($sub_total + $delivery_charge) ?></td>
        </tr>
        </tbody>
      </table>
    </div>
</div>
<?php */ ?>
<div class="btn-set">
        <button onclick="address();" class="btn btn-primary btn-checkout pull-left margin-left-0">
                <?= Yii::t('frontend', 'Back') ?>
        </button>
        <a href="<?= $pg_link ?>" class="btn btn-primary btn-checkout pull-right">
            <?= Yii::t('frontend', 'Confirm Request(s)') ?>
        </a>
</div>
<br />
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