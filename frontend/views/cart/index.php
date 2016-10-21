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
	        		<td aligh="center" class="text-center">
	        			<span class="visible-md visible-lg">
	        				<?= Yii::t('frontend', 'Quantity') ?>
	        			</span>
	        			<span class="visible-xs visible-sm">
	        				<?= Yii::t('frontend', 'Qty') ?>
	        			</span>
	        		</th>
	        		<td align="right" class="visible-md visible-lg"><?= Yii::t('frontend', 'Unit Price') ?></th>
	        		<td align="right" class="visible-md visible-lg"><?= Yii::t('frontend', 'Total') ?></th>
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
	        			<a href="<?= Url::to(["shop/product", 'slug' => $item['slug']]) ?>">
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
					<?php
					$color = '';
					$msg = '';
					if (strtotime($item['cart_delivery_date']) < strtotime(date('Y-m-d'))) {
						$color = '#f2dede';
						$msg = '<small>'.Yii::t('frontend','Past delivery date').'</small>';
					} else if (
						(strtotime($item['cart_delivery_date']) == strtotime(date('Y-m-d'))) &&
						(strtotime($item['timeslot_end_time']) < strtotime(date('H:i:s')))
					) {
						$color = '#f2dede';
						$msg = '<small>'.Yii::t('frontend','Past delivery time slot date').'</small>';
					}

					?>
	        		<td class="position-relative " style="background-color:<?=$color?> ">
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

	        					<?= $item['cart_delivery_date'] ?><br />
								
								<?= date('h:m A', strtotime($item['timeslot_start_time'])) ?> - 
		    					<?=	date('h:m A', strtotime($item['timeslot_end_time'])); ?>

								<i title="Change Date and time" class="fa fa-edit" data-cart-id="<?=$item['cart_id']?>"></i>
							<br/>

							<?= $msg; ?>

	        			<?php } else { ?>
	        				<span class="error">
	        					<?= Yii::t('frontend', 'We cannot delivery this item!'); ?>
	        				</span>
	        			<?php } ?>		        			
	        		</td>
	        		<td align="center">
		        		<div class="input-group btn-block" style="max-width: 150px;">
		                    <input type="text" name="quantity[<?= $item['cart_id'] ?>]" value="<?= $item['cart_quantity'] ?>" size="1" class="form-control">
		                    <button type="submit" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="Update"><i class="glyphicon glyphicon-refresh"></i></button>
		                    <button type="button" data-toggle="tooltip" title="" class="btn btn-danger" data-original-title="Remove"><i class="glyphicon glyphicon-trash"></i></button>
	                    </div>
                    </td>
	        		<td align="right" class="visible-md visible-lg">
	        			<?= CFormatter::format($item['item_price_per_unit'])  ?>
	        		</td>
	        		<td align="right" class="visible-md visible-lg">
	        			<?= CFormatter::format($row_total)  ?>
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

        <a href="<?= Url::to(['shop/index']) ?>" class="btn btn-primary pull-right btn-checkout">
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
    var vendor_id = '';
    var isGuest = ".(int)Yii::$app->user->isGuest.";
    var customer_id = '".Yii::$app->user->id."';
    var addtobasket_url = '".Yii::$app->urlManager->createAbsoluteUrl('cart/add')."';
    var getdeliverytimeslot_url = '".Url::toRoute('cart/getdeliverytimeslot')."';
    var area_option_url = '".Url::toRoute('site/area')."';
    var availablity = '".Url::toRoute('shop/product-available')."';
    var product_availability = '".Url::toRoute('cart/validation-product-available')."';
    var update_cart_url = '".Url::toRoute('cart/update-cart-item')."';
", View::POS_HEAD);

$this->registerJs("
	jQuery('.btn-danger').click(function() {
		jQuery(this).parent().find('input').val(0);
		jQuery('#cart-form').submit();
	});

	jQuery('.fa-edit').click(function(){
		jQuery.ajax({
                url: '".Url::to(['cart/update-cart-item-popup'])."',
                type:'post',
                data:{id:$(this).data('cart-id')},
                success:function(data)
                {
                	jQuery('#update-cart-modal').modal('show');
                	jQuery('#update-cart-modal .modal-body').html(data);
                }
            });
	});
", View::POS_READY);

?>

<div class="modal fade" id="update-cart-modal" tabindex="-1" data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content update-cart row">
			<div class="modal-header">

				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<div class="text-center">
					<span class="yellow_top"></span>
				</div>
				<h4 class="modal-title text-center" id="myModalLabel">
					<span><?= Yii::t('frontend', 'Update Cart Item') ?></span>
				</h4>
			</div>
			<div class="modal-body">
				<form class="form col-md-12 center-block" id="loginForm" name="update-cart" method="POST">

					<input type="hidden" id="_csrf" name="_csrf" value="QVc1WjZPZ1d0Gw0xeDoBODRjd2lUNwIxBBNvCF97LWENFgQ8fCseYQ==" />

					<div class="col-md-2 padding-left-0">
						<div class="form-group">
							<label>Delivery Date</label>
							<div data-date-format="dd-mm-yyyy" data-date="12-02-2012" class="input-append date" id="delivery_date_wrapper">
								<input value="" readonly="true" name="delivery_date" id="delivery_date" class="date-picker-box form-control required"  placeholder="Date" >
								<i class="fa fa-calendar" aria-hidden="true"></i>
							</div>
							<span class="error cart_delivery_date"></span>
						</div>
					</div>
					<div class="col-md-5 padding-left-0 timeslot_id_div">
						<div class="form-group">
							<label>Delivery Time Slot</label>
							<div class="text padding-top-12">Please Select Delivery Date</div>
						</div>
					</div>
					<div class="col-md-2 padding-left-0 timeslot_id_select" style="display: none;">
						<div class="form-group">
							<label>Delivery Time Slot</label>
							<select name="timeslot_id" id="timeslot_id" class="selectpicker" data-size="10" data-style="btn-primary"></select>
							<span class="error timeslot_id"></span>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
