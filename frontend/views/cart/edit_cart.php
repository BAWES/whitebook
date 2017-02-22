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
	<input type="hidden" id="vendor_id" name="vendor_id" value="<?= $model->vendor_id; ?>" />
	<input type="hidden" id="item_id" name="item_id" value="<?= $item->item_id; ?>" />
	<input type="hidden" id="area_id" name="area_id" value="<?= $item->area_id; ?>" />
	<input type="hidden" id="cart_id" name="cart_id" value="<?= $item->cart_id; ?>" />

	<div class="col-md-12 padding-left-0">
		<div class="form-group">
			<label><?=Yii::t('frontend', 'Delivery Date'); ?></label>
			<div data-date-format="dd-mm-yyyy" data-date="12-02-2012" class="input-append date position-relative" id="delivery_date_wrapper">
				<input value="<?= $item->cart_delivery_date ?>" readonly="true" name="delivery_date" id="delivery_date3" class="date-picker-box form-control required"  placeholder="<?php echo Yii::t('frontend', 'Date'); ?>" >
				<i class="fa fa-calendar" aria-hidden="true"></i>
			</div>
			<span class="error cart_delivery_date"></span>
		</div>
	</div>

	<div class="col-md-12 padding-left-0 timeslot_id_div <?php if($vendor_timeslot) { echo 'hidden'; }; ?>">
		<div class="form-group">
			<label><?=Yii::t('frontend', 'Delivery Time Slot'); ?></label>
			<div class="text padding-top-12"><?=Yii::t('frontend','Please Select Delivery Date');?></div>
		</div>
	</div>

	<div class="col-md-12 padding-left-0 timeslot_id_select <?php if(!$vendor_timeslot) { echo 'hidden'; }; ?>">
		<div class="form-group">
			<label><?=Yii::t('frontend', 'Delivery Time Slot'); ?></label>
			<select name="working_id" id="timeslot_id" class="selectpicker" data-size="10" data-style="">
               <?php foreach ($vendor_timeslot as $key => $value) {

                        if($item->working_id == $value['working_id']) {
                            $selected = 'selected';
                        } else {
                            $selected = '';
                        }

                        if (strtotime($item->cart_delivery_date) == time()) {
                            
                            if (strtotime(date('H:i:s')) < strtotime($value['working_start_time'])) {
                                $start = date('g:i A', strtotime($value['working_start_time']));
                                $end = date('g:i A', strtotime($value['working_end_time']));
                                echo '<option value="' . $value['working_id'] . '" '.$selected.'>' . $start . ' - ' . $end . '</option>';
                            }

                        } else {
                            $start = date('g:i A', strtotime($value['working_start_time']));
                            $end = date('g:i A', strtotime($value['working_end_time']));
                            echo '<option value="' . $value['working_id'] . '" '.$selected.'>' . $start . ' - ' . $end . '</option>';
                        }
                    } ?>         
            </select>
			<span class="error timeslot_id"></span>
		</div>
	</div>
	<div class="col-md-12 padding-left-0 timeslot_id_select <?php if(!$vendor_timeslot) { echo 'hidden'; }; ?>">
		<div class="form-group">
			<label><?=Yii::t('frontend', 'Quantity'); ?></label>
			<input value="<?= $item->cart_quantity ?>" name="quantity" id="quantity" class="date-picker-box form-control required"  placeholder="<?php echo Yii::t('frontend', 'Date'); ?>" >
			<span class="error cart_quantity"></span>
		</div>
	</div>

    <div class="col-md-12" id="accordion">

        <?php if($menu) { ?>
        <div class="panel panel-default">
            <div class="panel-heading">
              <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse-options" aria-expanded="false">
                    <?= Yii::t('frontend', 'Options') ?>
                </a>
              </h4>
            </div>
            <div id="collapse-options" class="panel-collapse collapse">
              <div class="panel-body">                                            
                 <div class="menu-item-detail">
                    <?php foreach ($menu as $key => $value) { ?>
                        <div class="menu-detail">    
                            <h3 class="menu-title">

                                <span class="title">
                                    <?php if(Yii::$app->language == 'en') { 
                                            echo $value->menu_name;
                                      } else { 
                                            echo $value->menu_name_ar;
                                      } ?>
                                </span>

                                <?php if($value->min_quantity || $value->max_quantity) { ?>
                                <span class="menu-hint" data-max-quantity="<?= $value->max_quantity ?>" data-min-quantity="<?= $value->min_quantity ?>" data-txt-min="<?= Yii::t('frontend', 'atleast {qty} '); ?>" data-txt-max="<?= Yii::t('frontend', 'upto {qty} '); ?>">
                                    
                                    <?php 

                                    echo Yii::t('frontend', 'Select ');

                                    if($value->min_quantity) { 
                                        echo Yii::t('frontend', 'atleast {qty} ', [
                                            'qty' => $value->min_quantity * $item->cart_quantity
                                        ]); 
                                    } 

                                    if($value->min_quantity && $value->max_quantity) { 
                                        echo ' , ';
                                    }

                                    if($value->max_quantity) { 
                                        echo Yii::t('frontend', ' upto {qty}', [
                                            'qty' => $value->max_quantity * $item->cart_quantity
                                        ]); 
                                    } 
                                   
                                    ?>
                                </span>                                        
                                <?php } ?>
                            </h3>

                            <span class="error menu_<?= $value->menu_id ?>"></span>

                            <ul class="menu-items" data-max-quantity="<?= $value->max_quantity ?>">
                            <?php 

                            $menu_items = VendorItemMenuItem::findAll(['menu_id' => $value->menu_id]);

                            foreach ($menu_items as $menu_item) { 

                                $cart_menu_item = CustomerCartMenuItem::findOne([
                                        'menu_item_id' => $menu_item->menu_item_id,
                                        'cart_id' => $item->cart_id
                                    ]); ?>

                                <li> 
                                    
                                    <?php if($value->quantity_type == 'selection') { ?>

                                    <!-- qty box -->

                                    <span class="menu-item-qty-box">
                                        <i class="fa fa-minus"></i>
                                        <input name="menu_item[<?= $menu_item->menu_item_id ?>]" class="menu-item-qty" value="<?= $cart_menu_item?$cart_menu_item->quantity:0 ?>" readonly />
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

                                    <?php } else { ?>

                                    <div class="checkbox checkbox-inline">
                                        <input name="menu_item[<?= $menu_item->menu_item_id ?>]" id="menu_item[<?= $menu_item->menu_item_id ?>]" class="menu-item-qty" value="1" type="checkbox" <?php if($cart_menu_item) echo 'checked'; ?> />

                                        <label for="menu_item[<?= $menu_item->menu_item_id ?>]">
                                            <?php if(Yii::$app->language == 'en') { 
                                                    echo $menu_item->menu_item_name;
                                              } else { 
                                                    echo $menu_item->menu_item_name_ar;
                                              } ?> 
                                        </label>

                                        &nbsp;
                                    </div>

                                    <?php } ?>

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
                 </div><!-- END .menu-item-detail -->

              </div>
            </div>
        </div><!-- END .panel -->
        <?php } ?>

        <?php if($addons) { ?>
        <div class="panel panel-default">
            <div class="panel-heading">
              <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" aria-expanded="false" href="#collapse-addons">
                    <?= Yii::t('frontend', 'Addons') ?>
                </a>
              </h4>
            </div>
            <div id="collapse-addons" class="panel-collapse collapse">
              <div class="panel-body">                                            
                 <div class="menu-item-detail">
                    <?php foreach ($addons as $key => $value) { ?>
                        <div class="menu-detail">    
                            <h3 class="menu-title">

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

                            $menu_items = VendorItemMenuItem::findAll(['menu_id' => $value->menu_id]);

                            foreach ($menu_items as $menu_item) { 

                                $cart_menu_item = CustomerCartMenuItem::findOne([
                                        'menu_item_id' => $menu_item->menu_item_id,
                                        'cart_id' => $item->cart_id
                                    ]); ?>

                                <li> 
                                    <!-- qty box -->

                                    <span class="menu-item-qty-box">
                                        <i class="fa fa-minus"></i>
                                        <input name="menu_item[<?= $menu_item->menu_item_id ?>]" class="menu-item-qty" value="<?= $cart_menu_item?$cart_menu_item->quantity:0 ?>" readonly />
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
                 </div><!-- END .menu-item-detail -->

              </div>
            </div>
        </div><!-- END .panel -->
        <?php } ?>

        <?php if($model->allow_special_request) { ?>
        <div class="panel panel-default">
            <div class="panel-heading">
              <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse-special-request" aria-expanded="false">
                    <?= Yii::t('frontend', 'Special request') ?>
                </a>
              </h4>
            </div>
            <div id="collapse-special-request" class="panel-collapse collapse">
              <div class="panel-body">
                
                <br />

                <textarea name="special_request" class="form-control"></textarea>
              </div>
            </div>
        </div>
        <?php } ?>

        <?php if($model->have_female_service) { ?>
        <div class="panel panel-default">
            <div class="panel-heading">
              <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse-female" aria-expanded="false">
                    <?= Yii::t('frontend', 'Female Service') ?>
                </a>
              </h4>
            </div>
            <div id="collapse-female" class="panel-collapse collapse">
              <div class="panel-body">
                <div class="form-group checkbox" style="margin-left: 0px;">
                    <input type="checkbox" name="female_service" value="1" id="chk_female_service" />
                    <label for="chk_female_service">
                        <?= Yii::t('frontend', 'Include Female Service') ?>
                    </label>
                </div>
              </div>
            </div>
        </div>
        <?php } ?>
    </div>
        
	<div class="col-md-12 padding-left-0" style="padding-top: 15px;">
	    <div class="row">
            <div class="col-sm-10">
                <p class="alert alert-danger cart-update-error-msg"><?= Yii::t('frontend', 'Please check form catefully!') ?></p>
            </div>
            <div class="col-sm-2">
                <input type="submit" name="change" value="Change" class="btn btn-primary pull-right btn-checkout btn-cart-change">
            </div>
        </div>
	</div>
</form>