<?php 

use yii\helpers\Html;
use yii\helpers\Url;
use common\models\Image;
use common\models\CustomerCart;
use common\components\CFormatter;
?>
<h3>
	<?= Yii::t('frontend', 'Payment method selected : <strong>{payment_method}</strong>', [
		'payment_method' => $payment_method
	]) ?>	
</h3>
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

			$address_data = CustomerCart::getAddressData($address[$item['cart_id']]);

			$delivery_area = CustomerCart::geLocation($item['area_id'], $item['vendor_id']);

			$row_total = $item['item_price_per_unit'] * $item['cart_quantity'];

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
                        $imglink = Yii::getAlias("@web/images/no_image.jpg");
                    }

                    echo Html::img($imglink, ['style'=>'width:50px; height:50px;']);

                    ?>
        		</td>
        		<td>
        			<a target="_blank" href="<?= Url::to(["browse/detail", 'slug' => $item['slug']]) ?>">
        				<?php if(Yii::$app->language == 'en') {
        					echo $item['item_name'];
        				} else {
        					echo $item['item_name_ar']; 
        				} ?>
        			</a>

                    <div class="visible-xs visible-sm">                         
                        x <?= $item['cart_quantity'] ?> = <?= CFormatter::format($row_total); ?>
                    </div>
        		</td>
        		<td>
        			<?php 

        			if(isset($delivery_area->location)) { 

						$delivery_charge += $delivery_area->delivery_price;

        				?>
        				
        				<?= nl2br($address_data); ?> <br />

                        <?php if(Yii::$app->language == 'en') { ?>
            				<?= $delivery_area->location->location; ?> <br />
            				<?= $delivery_area->location->city->city_name; ?> <br />
                        <?php } else { ?>
                            <?= $delivery_area->location->location_ar; ?> <br />
                            <?= $delivery_area->location->city->city_name_ar; ?> <br />
                        <?php } ?>

        				<?= $item['cart_delivery_date'] ?> <br />
        			
                        <?= date('h:m A', strtotime($item['timeslot_start_time'])) ?> - 
                        <?= date('h:m A', strtotime($item['timeslot_end_time'])); ?>

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
                    <?= CFormatter::format($item['item_price_per_unit']); ?>
                </td>
        		<td align="right" class="visible-md visible-lg">
                    <?= CFormatter::format($row_total) ?>
                </td>
        	</tr>
        	<?php } ?>
        </tbody>        	
    </table>

</form>

<div class="row">
    <div class="col-sm-4 col-sm-offset-8">
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
        
<div class="btn-set">
        <button onclick="payment();" class="btn btn-primary btn-checkout pull-left margin-left-0">
                <?= Yii::t('frontend', 'Back') ?>
        </button>
        <a href="<?= $pg_link ?>" class="btn btn-primary btn-checkout pull-right">
            <?= Yii::t('frontend', 'Confirm Order') ?>
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