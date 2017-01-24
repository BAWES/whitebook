<?php

use yii\helpers\Url;
use yii\web\view;
use common\models\VendorItemMenuItem;
use common\models\CustomerCartMenuItem;
use common\components\CFormatter;

$deliver_date = $item->cart_delivery_date;

?>
<form class="form col-md-12 center-block" id="form-update-cart" name="form-update-cart" method="POST">

	<input type="hidden" id="_csrf" name="_csrf" value="<?= Yii::$app->request->csrfToken; ?>" />
	<input type="hidden" id="vendor_id" name="vendor_id" value="<?= $item->item->vendor_id; ?>" />
	<input type="hidden" id="item_id" name="item_id" value="<?= $item->item_id; ?>" />
	<input type="hidden" id="area_id" name="area_id" value="<?= $item->area_id; ?>" />
	<input type="hidden" id="cart_id" name="cart_id" value="<?= $item->cart_id; ?>" />

	<div class="col-md-12 padding-left-0">
		<div class="form-group">
			<label><?=Yii::t('frontend', 'Delivery Date'); ?></label>
			<div data-date-format="dd-mm-yyyy" data-date="12-02-2012" class="input-append date position-relative" id="delivery_date_wrapper">
				<input value="" readonly="true" name="delivery_date" id="delivery_date3" class="date-picker-box form-control required"  placeholder="<?php echo Yii::t('frontend', 'Date'); ?>" >
				<i class="fa fa-calendar" aria-hidden="true"></i>
			</div>
			<span class="error cart_delivery_date"></span>
		</div>
	</div>
	<div class="col-md-12 padding-left-0 timeslot_id_div">
		<div class="form-group">
			<label><?=Yii::t('frontend', 'Delivery Time Slot'); ?></label>
			<div class="text padding-top-12"><?=Yii::t('frontend','Please Select Delivery Date');?></div>
		</div>
	</div>
	<div class="col-md-12 padding-left-0 timeslot_id_select" style="display:none">
		<div class="form-group">
			<label><?=Yii::t('frontend', 'Delivery Time Slot'); ?></label>
			<select name="timeslot_id" id="timeslot_id" class="selectpicker" data-size="10" data-style=""></select>
			<span class="error timeslot_id"></span>
		</div>
	</div>
	<div class="col-md-12 padding-left-0 timeslot_id_select" style="display: none">
		<div class="form-group">
			<label><?=Yii::t('frontend', 'Quantity'); ?></label>
			<input value="<?=$item->cart_quantity?>" name="quantity" id="quantity" class="date-picker-box form-control required"  placeholder="<?php echo Yii::t('frontend', 'Date'); ?>" >
			<span class="error cart_quantity"></span>
		</div>
	</div>

	<div class="menu-item-detail col-md-12 padding-left-0">
                            
        <?php foreach ($menu as $key => $value) { ?>
            <div class="menu-detail">    
                <h3 class="menu-title" style="margin-top: 5px;">

                    <span class="title">
                        <?php if(Yii::$app->language == 'en') { 
                                echo $value->menu_name;
                          } else { 
                                echo $value->menu_name_ar;
                          } ?>
                    </span>

                    <?php if($value->min_quantity || $value->max_quantity) { ?>

                    <span class="menu-hint">
                        
                        <?php 

                        echo Yii::t('frontend', 'Quantity range : ');

                        if($value->min_quantity) { 
                            echo Yii::t('frontend', 'Minimum {qty}', [
                                'qty' => $value->min_quantity
                            ]); 
                        } 

                        if($value->min_quantity && $value->max_quantity) { 
                            echo ' , ';
                        }

                        if($value->max_quantity) { 
                            echo Yii::t('frontend', 'Maximum {qty}', [
                                'qty' => $value->max_quantity
                            ]); 
                        } 
                       
                        ?>
                    </span>                                        
                    <?php } ?>
                </h3>

                <span class="error menu_<?= $value->menu_id ?>"></span>

                <ul class="menu-items"  data-max-quantity="<?= $value->max_quantity ?>">
                <?php 

                $menu_item = VendorItemMenuItem::findAll(['menu_id' => $value->menu_id]);

                foreach ($menu_item as $menu_item) { 

                	//get selected quantity from cart 

                	$cart_menu_item = CustomerCartMenuItem::findOne([
                		'cart_id' => $item->cart_id,
                		'menu_item_id' => $menu_item->menu_item_id
                	]);

                	if($cart_menu_item) {
                		$menu_item_qty = $cart_menu_item->quantity;
                	}else{
                		$menu_item_qty = 0;
                	}

                	?>

                    <li> 
                        <!-- qty box -->

                        <span class="menu-item-qty-box">
                            <i class="fa fa-minus"></i>
                            <input name="menu_item[<?= $menu_item->menu_item_id ?>]" class="menu-item-qty" value="<?= $menu_item_qty ?>" readonly />
                            <i class="fa fa-plus"></i>
                        </span>

                        <!-- item name -->

                        <span class="menu-item-name">
                            <?php if(Yii::$app->language == 'en') { 
                                    echo $menu_item->menu_item_name;
                              } else { 
                                    echo $menu_item->menu_item_name_ar;
                              } ?> 
                        </span>

                        <!-- price -->

                        <?php if($menu_item->price > 0) { ?>
                        <span class="menu_item_price">
                            (+<?= CFormatter::format($menu_item->price) ?>)
                        </span>
                        <?php  } ?>

                        <!-- hint -->

                        <?php 

                        $hint =  Yii::$app->language == 'en' ? $menu_item->hint : $menu_item->hint_ar;

                        if($hint) { ?>
                        <span class="menu-item-hint" data-toggle="tooltip" title="<?= $hint ?>"><i class="fa fa-info-circle"></i></span>
                        <?php } ?>
                        
                        <span class="error menu_item_<?= $menu_item->menu_item_id ?>"></span>
                    </li>
                <?php } ?>
                </ul>
            </div><!-- END .menu-detail -->
        <?php } ?>

        <hr />

        <?php if($model->allow_special_request) { ?>
        <div class="form-group">
            <label><?= Yii::t('frontend', 'Special request') ?></label>
            <textarea name="special_request" class="form-control"><?= $item->special_request ?></textarea>
        </div>
        <?php } ?>

        <?php if($model->have_female_service) { ?>
        <div class="form-group checkbox">
            <input type="checkbox" name="female_service" value="1" id="chk_female_service" <?= $item->female_service ? 'checked' : ''; ?> />
            <label for="chk_female_service">
                <?= Yii::t('frontend', 'Female Service') ?>                                    
            </label>
        </div>
        <?php } ?>

    </div><!-- END .menu-item-detail -->

	<div class="col-md-12 padding-left-0">
		<input type="submit" name="change" value="Change" class="btn btn-primary pull-right btn-checkout btn-cart-change">
	</div>
</form>