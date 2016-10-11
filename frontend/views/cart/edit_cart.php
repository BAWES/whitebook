

<?php
use yii\helpers\Url;
use yii\web\view;

$deliver_date = $items->cart_delivery_date;
?>
<style>
	.position-relative {position:relative;}
	.fa-calendar{position: absolute;top: 9px;right: 7px;font-size: 17px;}
	div.datepicker{  top: 157px!important;  border: 1px solid #f2f2f2;}
	.dropdown-toggle{    background: none;  color: #000;  border: 1px solid #eee;}
</style>
<form class="form col-md-12 center-block" id="form-update-cart" name="form-update-cart" method="POST">

	<input type="hidden" id="_csrf" name="_csrf" value="<?= Yii::$app->request->csrfToken; ?>" />
	<input type="hidden" id="vendor_id" name="vendor_id" value="<?= $items->item->vendor_id; ?>" />
	<input type="hidden" id="item_id" name="item_id" value="<?= $items->item_id; ?>" />
	<input type="hidden" id="area_id" name="area_id" value="<?= $items->area_id; ?>" />
	<input type="hidden" id="cart_id" name="cart_id" value="<?= $items->cart_id; ?>" />

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
	<div class="col-md-12 padding-left-0 timeslot_id_select" style="display: none;">
		<div class="form-group">
			<label><?=Yii::t('frontend', 'Delivery Time Slot'); ?></label>
			<select name="timeslot_id" id="timeslot_id" class="selectpicker" data-size="10" data-style="btn-primary"></select>
			<span class="error timeslot_id"></span>
		</div>
	</div>
	<div class="col-md-12 padding-left-0 timeslot_id_select">
		<div class="form-group">
			<label><?=Yii::t('frontend', 'Quantity'); ?></label>
			<input value="<?=$items->cart_quantity?>" name="quantity" id="quantity" class="date-picker-box form-control required"  placeholder="<?php echo Yii::t('frontend', 'Date'); ?>" >
			<span class="error cart_quantity"></span>
		</div>
	</div>
	<div class="col-md-12 padding-left-0">
		<input type="submit" name="change" value="Change" class="btn btn-primary pull-right btn-checkout btn-cart-change">
	</div>
</form>