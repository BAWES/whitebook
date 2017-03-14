<?php 

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\view;
use common\models\Image;
use common\models\CustomerCart;
use common\components\CFormatter;
use common\components\LangFormat;
use common\models\VendorItemPricing;
use common\models\CustomerCartMenuItem;
use yii\helpers\ArrayHelper;

$this->title = Yii::t('frontend', 'Shopping Cart | Whitebook'); 

?>

<section id="inner_pages_white_back">
    <div class="container paddng0">
        <div class="title_main">
			<h1><?= Yii::t('frontend', 'Shopping Cart'); ?></h1>
		</div><br /><br /><br />
        
        <?php if ($items) { ?>

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

	        		//$menu_items = CustomerCartMenuItem::findAll(['cart_id' => $item['cart_id']]);
	        		
	        		$menu_items = CustomerCartMenuItem::find()
	    				->select('{{%vendor_item_menu_item}}.price, {{%vendor_item_menu_item}}.menu_item_id, {{%vendor_item_menu_item}}.menu_id, {{%vendor_item_menu_item}}.menu_item_name, {{%vendor_item_menu_item}}.menu_item_name_ar, {{%customer_cart_menu_item}}.quantity')
	    				->innerJoin('{{%vendor_item_menu_item}}', '{{%vendor_item_menu_item}}.menu_item_id = {{%customer_cart_menu_item}}.menu_item_id')
	    				->where(['cart_id' => $item['cart_id']])
	    				->asArray()
	    				->all();

	        		$errors = CustomerCart::validate_item([
	        			'item_id' => $item['item_id'],
	        			'time_slot' => $item['time_slot'],
	        			'delivery_date' => $item['cart_delivery_date'],
	        			'area_id' => $item['area_id'],
	        			'quantity' => $item['cart_quantity'],
	        			'menu_item' => ArrayHelper::map($menu_items, 'menu_item_id', 'quantity')
	        		], true);

					$delivery_area = CustomerCart::geLocation($item['area_id'], $item['vendor_id']);

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

	    			foreach ($menu_items as $key => $value) {
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

	                        echo Html::img($imglink, ['style'=>'width:50px; height:50px;']);
	                        ?>
		        		</td>
		        		<td>
		        			<a href="<?= Url::to(["browse/detail", 'slug' => $item['slug']]) ?>">
		        				<?= LangFormat::format($item['item_name'], $item['item_name_ar']); ?>
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

		        			<?php foreach ($menu_items as $key => $menu_item) { 
		        				if(isset($errors['menu_'.$menu_item['menu_id']])) { 
			        				foreach($errors['menu_'.$menu_item['menu_id']] as $error) { 

				        				if(is_array($error)) {
		        							foreach ($error as $value) {
		        								echo '<span class="label label-danger">' . $value . '</span>';
		        							}	
		        						} else {
		        							echo '<span class="label label-danger">' . $error . '</span>';
		        						}     
			        				} //foreach errors 
		        				}//if menu have error   
		        			} ?>
		        		</td>
		        		<td class="position-relative">
		        			<?php

		        			if(isset($delivery_area->location)) {

								$delivery_charge += $delivery_area->delivery_price;
								
								echo LangFormat::format($delivery_area->location->location,$delivery_area->location->location_ar).' <br />';

								echo LangFormat::format($delivery_area->location->city->city_name,$delivery_area->location->city->city_name_ar).' <br />';
		        				?>
	        				
	        					<?= $item['cart_delivery_date'] ?><br />
								
								<?= $item['time_slot']; ?>

								<i title="Change Date and time" class="fa fa-edit" data-cart-id="<?=$item['cart_id']?>"></i>

		        			<?php } else { ?>
		        				<span class="label label-danger">
		        					<?= Yii::t('frontend', 'We cannot delivery this item!'); ?>
		        				</span>
		        			<?php } ?>	

		        			<div class="clearfix"></div>
		        			<?php 

		        			if(isset($errors['area_id'])) { 
		        				foreach($errors['area_id'] as $key => $error) { 

			        				if(is_array($error)) {
	        							foreach ($error as $value) {
	        								echo '<span class="label label-danger">' . $value . '</span>';
	        							}	
	        						} else {
	        							echo '<span class="label label-danger">' . $error . '</span>';
	        						}     
		        				} 
		        			} ?>

		        			<?php if(isset($errors['cart_delivery_date'])) { 	        				
		        				foreach($errors['cart_delivery_date'] as $key => $error) {
			        				if(is_array($error)) {
	        							foreach ($error as $value) {
	        								echo '<span class="label label-danger">' . $value . '</span>';
	        							}	
	        						} else {
	        							echo '<span class="label label-danger">' . $error . '</span>';
	        						}     
		        				} 
		        			} ?>

		        			<?php if(isset($errors['time_slot'])) { 	        				
		        				foreach($errors['time_slot'] as $key => $error) {
			        				if(is_array($error)) {
	        							foreach ($error as $value) {
	        								echo '<span class="label label-danger">' . $value . '</span>';
	        							}	
	        						} else {
	        							echo '<span class="label label-danger">' . $error . '</span>';
	        						}     
		        				} 
		        			} ?>
		        		</td>
		        		<td align="center">
			        		<div class="input-group btn-block max-width-150-px">
			                    <input type="text" name="quantity[<?= $item['cart_id'] ?>]" value="<?= $item['cart_quantity'] ?>" size="1" class="form-control">
			                    <button type="submit" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="Update"><i class="glyphicon glyphicon-refresh"></i></button>
			                    <button type="button" data-toggle="tooltip" title="" class="btn btn-danger" data-original-title="Remove"><i class="glyphicon glyphicon-trash"></i></button>
		                    </div>

		                    <?php if(isset($errors['cart_quantity'])) { 	        				
		        				foreach($errors['cart_quantity'] as $key => $error) {
			        				if(is_array($error)) {
	        							foreach ($error as $value) {
	        								echo '<span class="label label-danger">' . $value . '</span>';
	        							}	
	        						} else {
	        							echo '<span class="label label-danger">' . $error . '</span>';
	        						}     
		        				} 
		        			} ?>
	                    </td>
		        		<td align="right" class="visible-md visible-lg">
		        			<?= CFormatter::format($unit_price)  ?>
		        		</td>
		        		<td align="right" class="visible-md visible-lg">
		        			<?= CFormatter::format($row_total)  ?>
		        		</td>
		        	</tr>
	        	<?php } ?>
	        </tbody>        	
        </table>

        <button name="btn_checkout" value="1" class="btn btn-primary pull-right btn-checkout">
        	<?= Yii::t('frontend', 'Proceed to Checkout') ?>
        </button>

        <a href="<?= Url::to(['browse/list', 'slug' => 'all']) ?>" class="btn btn-primary pull-right btn-checkout">
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
    var getdeliverytimeslot_url = '".Url::toRoute('cart/get-delivery-timeslot')."';
    var area_option_url = '".Url::toRoute('site/area')."';
    var availablity = '".Url::toRoute('browse/product-available')."';
    var product_availability = '".Url::toRoute('cart/validation-product-available')."';
    var update_cart_url = '".Url::toRoute('cart/update-cart-item')."';
    var update_cart_popup_url = '".Url::to(['cart/update-cart-item-popup'])."';

", View::POS_HEAD);

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
				<form class="form col-md-12 center-block" id="update-cart" name="update-cart" method="POST">

					<input type="hidden" id="_csrf" name="_csrf" value="<?=Yii::$app->request->csrfToken?>" />

					<div class="col-md-2 padding-left-0">
						<div class="form-group">
							<label><?=Yii::t('frontend','Delivery Date')?></label>
							<div data-date-format="dd-mm-yyyy" data-date="12-02-2012" class="input-append date" id="delivery_date_wrapper">
								<input value="" readonly="true" name="delivery_date" id="delivery_date" class="date-picker-box form-control required"  placeholder="Date" >
								<i class="fa fa-calendar" aria-hidden="true"></i>
							</div>
							<span class="error cart_delivery_date"></span>
						</div>
					</div>
					<div class="col-md-5 padding-left-0 timeslot_id_div">
						<div class="form-group">
							<label><?=Yii::t('frontend','Delivery Time')?></label>
							<div class="text padding-top-12"><?=Yii::t('frontend','Please Select Delivery Date')?></div>
						</div>
					</div>
					<div class="col-md-2 padding-left-0 timeslot_id_select" style="display: none;">
						<div class="form-group">
							<label>Delivery Time</label>
							<select name="working_id" id="timeslot_id" class="selectpicker" data-size="10" data-style="btn-primary"></select>
							<span class="error timeslot_id"></span>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<?php

$this->registerJsFile('@web/js/cart.js?v=1.4', ['depends' => [\yii\web\JqueryAsset::className()]]);

echo Html::hiddenInput('txt-select', Yii::t('frontend', 'Select '), ['id' => 'txt-select']);
echo Html::hiddenInput('txt-min', Yii::t('frontend', 'atleast {qty} '), ['id' => 'txt-min']);
echo Html::hiddenInput('txt-max', Yii::t('frontend', ' upto {qty}'), ['id' => 'txt-max']);

$this->registerJs("
    var isGuest = ".(int)Yii::$app->user->isGuest.";
", View::POS_HEAD);

$this->registerCss("
	.max-width-150-px{max-width: 150px;}
	.fa-calendar{
		position: absolute;
		right: 9px;
		top: 10px;
	}
	.position-relative {position:relative;}
	.fa-calendar{position: absolute;top: 9px;right: 7px;font-size: 17px;}
	div.datepicker{  top: 157px!important;  border: 1px solid #f2f2f2;}
	.dropdown-toggle{    background: none;  color: #000;  border: 1px solid #ccc;}
");