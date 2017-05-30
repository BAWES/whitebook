<?php 

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\view;
use common\models\Image;
use common\models\CustomerCart;
use common\components\CFormatter;
use common\components\LangFormat;
use common\models\VendorItem;
use common\models\VendorItemPricing;
use common\models\CustomerCartMenuItem;
use yii\helpers\ArrayHelper;

$this->title = Yii::t('frontend', 'Shopping Cart | Whitebook'); 

$session = $session = Yii::$app->session;
$delivery_location   = ($session->has('delivery-location')) ? $session->get('delivery-location') : null;
$delivery_date  = ($session->has('delivery-date')) ? $session->get('delivery-date') : '';
$event_time  = ($session->has('event_time')) ? $session->get('event_time') : '';

$arr_time = ['12:00', '12:30', '01:00', '01:30', '02:00', '02:30', '03:00', '03:30', '04:00', '04:30', '05:00',
          '05:30', '06:00', '06:30', '07:00', '07:30', '08:00', '08:30', '09:00', '09:30', '10:00', '10:30',
          '11:00', '11:30'];

?>

<section id="inner_pages_white_back">
    <div class="container paddng0">
        <div class="title_main">
			<h1><?= Yii::t('frontend', 'Shopping Cart'); ?></h1>
		</div><br /><br /><br />
        
        <?php if ($items) { ?>

        <form method="post" action="<?= Url::to(['cart/update']) ?>" id="cart-form">

        <div class="row delivery-info-wrapper">
            <div class="col-md-4">
                <div class="form-group margin-left-0">
                    <label><?=Yii::t('frontend', 'Event Area'); ?></label>
                    <div class="select_boxes">
                        <?php
                            echo Html::dropDownList('area_id', $delivery_location,
                            $vendor_area,
                            ['data-height'=>"100px",'data-live-search'=>"true",'id'=>"area_id", 'class'=>"selectpicker", 'data-size'=>"10", 'data-style'=>"btn-primary"]);
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label><?=Yii::t('frontend', 'Event Date'); ?></label>
                    <div data-date-format="dd-mm-yyyy" data-date="12-02-2012" class="input-append date">
                        <input value="<?= $delivery_date ?>" readonly="true" name="delivery_date" class="date-picker-box form-control required"  placeholder="<?php echo Yii::t('frontend', 'Date'); ?>" >
                        <i class="fa fa-calendar" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4" id="event-time">
                <div class="form-group">
                    <label><?=Yii::t('frontend', 'Event Time'); ?></label>
                    <select id="event_time" name="event_time" class="selectpicker" data-live-search="false" data-size="10" data-placeholder="">
                        <option value="" class="label"><?= Yii::t('frontend', 'Event Time') ?></option>
                        <optgroup label="am">                        
                            <?php foreach ($arr_time as $key => $value) {
                                if($value.' AM' == $event_time) 
                                    $selected = 'selected'; 
                                else
                                    $selected = ''; ?>
                                <option value="<?= $value ?> AM" data-content="<?= $value ?> <span>am</span>" <?= $selected ?>> 
                                    <?= $value ?>
                                </option>
                            <?php } ?>
                        </optgroup>
                        <optgroup label="pm">                        
                            <?php foreach ($arr_time as $key => $value) { 
                                if($value.' PM' == $event_time) 
                                    $selected = 'selected'; 
                                else
                                    $selected = ''; ?>
                                <option value="<?= $value ?> PM" <?= $selected ?> data-content="<?= $value ?> <span>pm</span>">
                                    <?= $value ?>
                                </option>
                            <?php } ?>
                        </optgroup>
                    </select>
                </div>
            </div>
        </div>

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
	        	foreach ($items as $item) {
                    $vendor_name = CustomerCart::getVendorDetail($item['vendor_id'])->vendor_name;
                    
                    $vendors[$item['vendor_id']] = [
                            'vendor' => $vendor_name,
                            'area_id' => $delivery_location
                        ];

                    $row_total = ($item['item']['item_base_price']) ? $item['item']['item_base_price'] : 0;
	        		
	        		$menu_option_items = CustomerCartMenuItem::find()
	    				->select('{{%vendor_item_menu_item}}.price, {{%vendor_item_menu_item}}.menu_item_id, {{%vendor_item_menu_item}}.menu_id, {{%vendor_item_menu_item}}.menu_item_name, {{%vendor_item_menu_item}}.menu_item_name_ar, {{%customer_cart_menu_item}}.quantity')
                        ->joinVendorItemMenuItem()
                        ->joinVendorItemMenu()
                        ->cartID($item['cart_id'])
                        ->andWhere(['menu_type' => 'options'])
	    				->asArray()
	    				->all();

	    			$menu_addon_items = CustomerCartMenuItem::find()
	    				->select('{{%vendor_item_menu_item}}.price, {{%vendor_item_menu_item}}.menu_item_id, {{%vendor_item_menu_item}}.menu_id, {{%vendor_item_menu_item}}.menu_item_name, {{%vendor_item_menu_item}}.menu_item_name_ar, {{%customer_cart_menu_item}}.quantity')
                        ->joinVendorItemMenuItem()
                        ->joinVendorItemMenu()
                        ->cartID($item['cart_id'])
	    				->andWhere(['menu_type' => 'addons'])
	    				->asArray()
	    				->all();

	        		$errors = CustomerCart::validate_item([
	        			'item_id' => $item['item_id'],
			        	'time_slot' => Yii::$app->session->get('event_time'),
		    			'delivery_date' => Yii::$app->session->get('delivery-date'),
		                'area_id' => Yii::$app->session->get('delivery-location'),
	        			'quantity' => $item['cart_quantity'],
	        			'menu_item' => ArrayHelper::map(
	        					array_merge($menu_option_items, $menu_addon_items), 'menu_item_id', 'quantity'
	        				)
	        		], true);

					$delivery_area = CustomerCart::geLocation($delivery_location, $item['vendor_id']);

					$row_total = VendorItem::itemFinalPrice(
			            $item['item_id'], 
			            $item['cart_quantity'], 
			            array_merge($menu_option_items, $menu_addon_items)
			        );

	    			$sub_total += $row_total; // not in use

		        	?>
		        	<tr>
		        		<td align="center">
		        			<?php

		        			$image_row = Image::find()->select(['image_path'])
	                                ->item($item['item_id'])
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
		        			<input type="hidden" name="quantity[<?= $item['cart_id'] ?>]" value="<?= $item['cart_quantity'] ?>" />

		        			<a href="<?= Url::to(["browse/detail", 'slug' => $item['slug']]) ?>">
		        				<?= LangFormat::format($item['item_name'], $item['item_name_ar']); ?>
		        			</a>

		        			<a href="<?= Url::to(["browse/detail", 'slug' => $item['slug'], 'cart_id' => $item['cart_id']]) ?>" style="cursor: pointer;">[<?= Yii::t('frontend', 'Edit') ?>]
	        				</a>

		        			<br />
                            <!-- Quantity -->
                            <i><small><?=Yii::t('frontend','Quantity').': '. $item['cart_quantity']?></small></i>
                            <!-- Quantity -->
                            <br/>


                            <i><small><strong><?=Yii::t('frontend','Vendor Name').':</strong> '. CustomerCart::getVendorDetail($item['vendor_id'])->vendor_name?></small></i>
                            <!-- Quantity -->
                            <br/>
		        			<?php

		        			$arr_menu_id = [];

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

		        				//get distinct menu_id 

		        				$arr_menu_id[$menu_item['menu_id']] = $menu_item['menu_id'];
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

		        				//get distinct menu_id 

		        				$arr_menu_id[$menu_item['menu_id']] = $menu_item['menu_id'];
		        			}
                            $questionAnswers = \common\models\CustomerCartItemQuestionAnswer::getCartQuestionAnswer($item['cart_id']);
                            if($questionAnswers)
                            {
                                echo '<b>'.Yii::t('frontend', 'Customs').'</b><br />';
                                $q =1;
                                foreach($questionAnswers as $answer) {
                                    echo "<b>Question $q: </b>".$answer->question->question;
                                    echo "<br/><b>answer $q: </b>".$answer->answer.'<br/>';
                                    $q++;
                                }
                            }

	                        if($item['female_service']) {
	                            echo '<i class="cart_menu_item">'.Yii::t('frontend', 'Female service').'</i>';
	                        }

	                        if($item['special_request']) {
	                            echo '<i class="cart_menu_item">'.$item['special_request'].'</i>';
	                        }

		        			?>

		        			<?php if($menu_addon_items || $menu_option_items) { ?>
			        			<div class="visible-xs visible-sm">	        				
			        				 = <?= CFormatter::format($row_total); ?>
			        			</div>
		        			<?php } else { ?>
			        			<div class="visible-xs visible-sm">	        				
			        				x <?= $item['cart_quantity'] ?> = <?= CFormatter::format($row_total); ?>
			        			</div>
		        			<?php } ?>

		        			<!-- display errors --> 
		        			
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
                                unset($errors['area_id']);
                            }


                            if(isset($errors['cart_quantity'])) { 
                                foreach($errors['cart_quantity'] as $key => $error) { 

                                    if(is_array($error)) {
                                        foreach ($error as $value) {
                                            echo '<span class="label label-danger">' . $value . '</span>';
                                        }   
                                    } else {
                                        echo '<span class="label label-danger">' . $error . '</span>';
                                    }     
                                } 
                                unset($errors['cart_quantity']);
                            }

                            if(isset($errors['cart_delivery_date'])) 
                            {                            
                                foreach($errors['cart_delivery_date'] as $key => $error) {
                                    if(is_array($error)) {
                                        foreach ($error as $value) {
                                            echo '<span class="label label-danger">' . $value . '</span>';
                                        }   
                                    } else {
                                        echo '<span class="label label-danger">' . $error . '</span>';
                                    }     
                                } 
                                unset($errors['cart_delivery_date']);
                            } 

                            if(isset($errors['time_slot'])) {                             
                                foreach($errors['time_slot'] as $key => $error) {
                                    if(is_array($error)) {
                                        foreach ($error as $value) {
                                            echo '<span class="label label-danger">' . $value . '</span>';
                                        }   
                                    } else {
                                        echo '<span class="label label-danger">' . $error . '</span>';
                                    }     
                                } 
                                unset($errors['time_slot']);
                            } 

                            // display remaining errors 

                            unset($errors['cart_quantity_remain']);

	        				foreach($errors as $error) { 
		        				if(is_array($error)) {
	    							foreach ($error as $value) {
	    								echo '<span class="label label-danger">' . $value . '</span>';
	    							}	
	    						} else {
	    							echo '<span class="label label-danger">' . $error . '</span>';
	    						}     
	        				} //foreach errors 

                            ?>

                            <div class="clearfix"></div>
                            
                            <a href="<?= Url::to(['cart/remove', 'cart_id' => $item['cart_id']]) ?>" title="<?= Yii::t('frontend', 'Remove') ?>" class="btn btn-primary pull-left">
                            	<i class="fa fa-trash"></i>
                            </a>
		        		</td>

		        		<td align="right" class="visible-md visible-lg">
		        			<?= CFormatter::format($row_total)  ?>
		        		</td>
		        	</tr>
	        	<?php } ?>
                <tr>
                    <td colspan="2" class="text-right"><strong><?= Yii::t('frontend', 'Sub-Total') ?></strong></td>
                    <td class="text-right"><?= CFormatter::format($sub_total) ?></td>
                </tr>
                <?php
                foreach ($vendors as $key => $vendor) {
                    $charge = \common\models\Booking::getDeliveryCharges('',$key,$vendor['area_id']);

                    $delivery_charge += (int) $charge;
                    
                    if($charge < 1)
                    	continue;

                    ?>
                    <tr>
                        <td colspan="2" class="text-right"><strong><?= Yii::t('frontend', 'Delivery Charge') ?></strong> <small>( <?=$vendor['vendor']?> )</small></td>
                        <td class="text-right"><?= CFormatter::format($charge) ?></td>
                    </tr>
                <?php } ?>
                <tr>
                    <td colspan="2"  class="text-right"><strong><?= Yii::t('frontend', 'Total') ?></strong></td>
                    <td class="text-right"><?= CFormatter::format($sub_total + $delivery_charge) ?></td>
                </tr>
	        </tbody>        	
        </table>

        <a href="<?= Url::to(['browse/list', 'slug' => 'all']) ?>" class="btn btn-primary pull-left btn-checkout">
        	<?= Yii::t('frontend', 'Continue Shopping') ?>
        </a>

        <button name="btn_checkout" value="1" class="btn btn-primary btn-checkout pull-right">
            <?= Yii::t('frontend', 'Proceed to Checkout') ?>
        </button>

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

$this->registerJsFile('@web/js/cart.js?v=1.7', ['depends' => [\yii\web\JqueryAsset::className()]]);

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