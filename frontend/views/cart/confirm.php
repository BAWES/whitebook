<?php 

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\view;
use common\models\Image;
use common\models\CustomerCart;
use common\components\CFormatter;

$this->title = Yii::t('frontend', 'Shopping Cart | Whitebook'); 

?>

<section id="inner_pages_white_back">
    <div class="container paddng0">
       
        <div class="title_main">
			<h1><?= Yii::t('frontend', 'Shopping Cart'); ?></h1>
		</div>

		<br />
		<br />
		<br />

        <?php 

        if($items) { 

        	foreach ($items as $item) {
        		$errors = CustomerCart::validate_item([
        			'item_id' => $item['item_id'],
        			'delivery_date' => $item['cart_delivery_date'],
        			'timeslot_end_time' => $item['timeslot_end_time'],
        			'area_id' => $item['area_id'],
        			'quantity' => $item['cart_quantity']
        		], true);

        		if($errors) { ?>

        			<div class="alert alert-danger">
        				<button class="close" data-dismiss="alert">x</button>
        				<strong><?= $item['item_name'].' #'.$item['cart_id'] ?></strong>
        				<br />
        				<?php foreach ($errors as $key => $attrib_errors) {
        					foreach ($attrib_errors as $error) {
        						echo '<p>' . $error . '</p>';
        					}
        				} ?>
        			</div>
        		<?php
        		}
        	}
       	?>

        <form method="post" action="<?= Url::to(['cart/update']) ?>" id="cart-form">	

        <table class="table table-bordered cart-table">
	        <thead>
	        	<tr>
	        		<td align="center"><?= Yii::t('frontend', 'Image') ?></th>
	        		<td align="left"><?= Yii::t('frontend', 'Item Name') ?></th>
	        		<td align="left"><?= Yii::t('frontend', 'Delivery') ?></th>
	        		<td aligh="left" class="text-center"><?= Yii::t('frontend', 'Quantity') ?></th>
	        		<td align="right"><?= Yii::t('frontend', 'Unit Price') ?></th>
	        		<td align="right"><?= Yii::t('frontend', 'Total') ?></th>
	        	</tr>
	        </thead>
	        <tbody>
	        	<?php 

	        	$sub_total = $delivery_charge = 0;

	        	foreach ($items as $item) {
	
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
	        			<a href="<?= Url::to(["browse/detail", 'slug' => $item['slug']]) ?>">
	        				<?php if(Yii::$app->language == 'en') {
	        					echo $item['item_name'];
	        				} else {
	        					echo $item['item_name_ar']; 
	        				} ?>
	        			</a>
	        		</td>
	        		<td>
	        			<?php 

	        			if(isset($delivery_area->location)) { 

							$delivery_charge += $delivery_area->delivery_price;

	        				?>
	        				
	        				<?php if(Yii::$app->language == 'en') { ?>
	            				<?= $delivery_area->location->location; ?> <br />
	            				<?= $delivery_area->location->city->city_name; ?> <br />
	                        <?php } else { ?>
	                            <?= $delivery_area->location->location_ar; ?> <br />
	                            <?= $delivery_area->location->city->city_name_ar; ?> <br />
	                        <?php } ?>

	        				<?= $item['cart_delivery_date'] ?> <br />
	        			
	        				<?= $item['timeslot_start_time'].' - '.$item['timeslot_end_time'] ?>

	        			<?php } else { ?>
	        				<span class="error">
	        					<?= Yii::t('frontend', 'We cannot delivery this item!'); ?>
	        				</span>
	        			<?php } ?>		        			
	        		</td>
	        		<td align="center">
		        		<div class="input-group btn-block" style="max-width: 140px;">
		                    <input type="text" name="quantity[<?= $item['cart_id'] ?>]" value="<?= $item['cart_quantity'] ?>" size="1" class="form-control">
		                    <button type="submit" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="Update"><i class="glyphicon glyphicon-refresh"></i></button>
		                    <button type="button" data-toggle="tooltip" title="" class="btn btn-danger" data-original-title="Remove"><i class="glyphicon glyphicon-trash"></i></button>
	                    </div>
                    </td>
	        		<td align="right">
	        			<?= CFormatter::format($item['item_price_per_unit'])  ?>
	        		</td>
	        		<td align="right">
	        			<?= CFormatter::format($row_total)  ?>
	        			<?= Yii::$app->params['Currency']; ?>
	        		</td>
	        	</tr>
	        	<?php } ?>
	        </tbody>        	
        </table>

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
        
        <button name="btn_checkout" value="1" class="btn btn-primary pull-right btn-checkout">
        	<?= Yii::t('frontend', 'Proceed to Checkout') ?>
        </button>

        <a href="<?= Url::to(['cart/index']) ?>" class="btn btn-primary pull-right btn-checkout">
        	<?= Yii::t('frontend', 'Continue Shopping') ?>
        </a>

        </form>

        <br />
        <br />
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

    </div>
</section>

<?php

$this->registerJs("
	jQuery('.btn-danger').click(function() {
		jQuery(this).parent().find('input').val(0);
		jQuery('#cart-form').submit();
	});
", View::POS_READY);	 