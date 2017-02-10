<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\web\view;

$this->title = 'Update vendor item';
$this->params['breadcrumbs'][] = ['label' => 'Vendor items', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';

?>

<div class="vendoritem-update">

<div class="col-md-12 col-sm-12 col-xs-12">

<?php $form = ActiveForm::begin(['options' => ['enctype'=>'multipart/form-data']]); ?>

<div class="loadingmessage" style="display: none;">
	<p>
    	<?= Html::img(Yii::getAlias('@web/themes/default/img/loading.gif'), ['class'=>'','width'=>'64px','height'=>'64px','id'=>'loading','alt'=>'loading']);?>
	</p>
</div>

<div class="tabbable">
	<ul class="nav nav-tabs">
		<li>
	    	<a href="<?= Url::to(['vendor-item/update', 'id' => $model->item_id]) ?>">
	    		Item Info 
	    	</a>
	    </li>
	    <li>
	    	<a href="<?= Url::to(['vendor-item/item-description', 'id' => $model->item_id]) ?>">
	    		Item description
	    	</a>
	    </li>
	    <li class="active">
	    	<a href="<?= Url::to(['vendor-item/item-price', 'id' => $model->item_id]) ?>">
	    		Item price 
	    	</a>
	    </li>
	    <li>
	    	<a href="<?= Url::to(['vendor-item/menu-items', 'id' => $model->item_id]) ?>">
	    		Menu items
	    	</a>
	    </li>
	    <li>
	    	<a href="<?= Url::to(['vendor-item/addon-menu-items', 'id' => $model->item_id]) ?>">
	    		Addons
	    	</a>
	    </li>
	    <li>
	    	<a href="<?= Url::to(['vendor-item/item-images', 'id' => $model->item_id]) ?>">
	    		Images
	    	</a>
	    </li>
	</ul>

	<div class="tab-content">
		<div class="tab-pane clearfix active">

			<?= $form->field($model, 'item_for_sale')->dropDownList(['Yes'=>'Yes', 'No'=>'No'], 
						['id' => 'vendoritem-item_for_sale']) ?>
			
			<?= $form->field($model, 'item_amount_in_stock')
					->label('Item Number of Stock '.Html::tag('span', '*',['class'=>'required mandatory']))
					->textInput(['maxlength' => 128, 'id' => 'vendoritem-item_amount_in_stock']); ?>

			<?= $form->field($model, 'item_default_capacity')
					->label('Item Default Capacity '.Html::tag('span', '*',['class'=>'required mandatory']))
					->textInput(['maxlength' => 128, 'id' => 'vendoritem-item_default_capacity']); ?>

			<?= $form->field($model, 'item_how_long_to_make')
					->label('No of days delivery '.Html::tag('span', '*',['class'=>'required mandatory']))
					->textInput(['maxlength' => 128, 'id' => 'vendoritem-item_how_long_to_make']); ?>

			<?= $form->field($model, 'item_minimum_quantity_to_order')
					->label('Item Minimum Quantity to Order '.Html::tag('span', '*',['class'=>'required mandatory']))
					->textInput(['maxlength' => 128, 'id' => 'vendoritem-item_minimum_quantity_to_order']); ?>

			<?= $form->field($model, 'item_price_per_unit')->textInput(['maxlength' => 128]); ?>

			<?php if($model->isNewRecord) { ?>
				<div class="form-group multiple_price" style="padding: 5px;  font-size: 14px;">
					<div class="multi_pricing">Price Chart </div>
					<div class="controls1">
						<input type="text" id="vendoritem-item_from" class="form-control from_range_1" name="vendoritem-item_price[from][]" multiple="multiple" placeholder="From Quantity" />

						<input type="text" id="vendoritem-item_to" class="form-control to_range_1" name="vendoritem-item_price[to][]" multiple="multiple" placeholder="To Quantity" />

						<input type="text" id="item_price_per_unit" class="form-control price_kd_1" name="vendoritem-item_price[price][]" multiple="multiple" placeholder="Price">KD

						<input type="button" name="remove" id="remove" value="Remove" class="remove_price" onclick="removePrice(this)" />
					</div>
					<input type="button" class="add_price" name="addprice" id="addprice" value="Add more" onClick="addPrice(this);" />
				</div>
			<?php } else { ?>
				<div class="form-group multiple_price" style="padding: 5px;  font-size: 14px;">
				<div class="multi_pricing">Price Chart</div>
				<?php $t=0;
				foreach ($pricing as $value) { ?>
				<div class="controls<?= $t; ?>">
					<input type="text" id="vendoritem-item_from" class="form-control from_range_<?= $t; ?>" name="vendoritem-item_price[from][]" multiple = "multiple" Placeholder="From Quantity" value="<?= $value['range_from'];?>" />

					<input type="text" id="vendoritem-item_to" class="form-control to_range_<?= $t; ?>" name="vendoritem-item_price[to][]" multiple = "multiple" Placeholder="To Quantity" value="<?= $value['range_to'];?>" />

					<input type="text" id="item_price_per_unit" class="form-control price_kd_<?= $t; ?>" name="vendoritem-item_price[price][]" multiple = "multiple" Placeholder="Price" value="<?= $value['pricing_price_per_unit'];?>">KD

					<input type="button" name="remove" id="remove" value="Remove" class="remove_price" onClick="removePrice(this)" />
				</div>
				<?php $t++; } ?>
				<input type="button" class="add_price" name="addprice" id="addprice" value="Add more" onClick="addPrice(this);" />
			</div>
			<?php } ?>

			<?= $form->field($model, 'item_price_description')->textarea([
					'id' => 'vendoritem-item_price_description'
				]) ?>
			
			<?= $form->field($model, 'item_price_description_ar')->textarea([
					'id' => 'vendoritem-item_price_description_ar'
				]) ?>

			<?= $form->field($model, 'item_customization_description')
					->textarea([
						'class' => 'form-group custom_description',
						'maxlength' => 128,
						'id' => 'vendoritem-item_customization_description'
					]); ?>

			<?= $form->field($model, 'item_customization_description_ar')
					->textarea([
						'class' => 'form-group custom_description_ar',
						'maxlength' => 128,
						'id' => 'vendoritem-item_customization_description_ar'
					]); ?>

			<hr />
			
			<div class="row">				
				<div class="col-md-4">
					<a href="<?= Url::to(['vendor-item/item-description', 'id' => $model->item_id]) ?>" class="btn btn-info pull-left">Prev</a>
				</div>
				<div class="col-md-4 text-center">
					<input type="submit" name="complete" class="btn btn-info" value="Complete" />
				</div>
				<div class="col-md-4">
					<input type="submit" name="btnNext" class="btn btn-info pull-right" value="Next" />
				</div>
			</div>
			
		</div><!-- END .tab-pane -->
	</div><!-- END .tab-content -->
</div><!-- END .tabbable -->

<?php 

ActiveForm::end(); 

echo Html::hiddenInput('appImageUrl',Yii::getAlias('appImageUrl'),['id'=>'appImageUrl']);
echo Html::hiddenInput('image_order_url',Url::to(['/image/imageorder']),['id'=>'image_order_url']);
echo Html::hiddenInput('isNewRecord', 0, ['id'=>'isNewRecord']);
echo Html::hiddenInput('item_id', $model->item_id, ['id'=>'item_id']);

$this->registerJsFile('@web/themes/default/plugins/ckeditor/ckeditor.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerJsFile("@web/themes/default/js/vendor_item_validation.js?v=1.21", ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerJsFile("@web/themes/default/js/vendor_item_steps/price.js", ['depends' => [\yii\web\JqueryAsset::className()]]);