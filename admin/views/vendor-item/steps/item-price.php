<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\web\view;

$this->title = 'Update vendor item';
$this->params['breadcrumbs'][] = ['label' => 'Vendor items', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';

$model->item_status = ($model->item_status == 'Active') ? 1 : 0;

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
	    	<a href="javascript::void()">
	    		Item Info 
	    	</a>
	    </li>
	    <li>
	    	<a href="javascript::void()">
	    		Item description
	    	</a>
	    </li>
	    <li class="active">
	    	<a href="javascript::void()">
	    		Item price 
	    	</a>
	    </li>
	    <li>
	    	<a href="javascript::void()">
	    		Menu items
	    	</a>
	    </li>
	    <li>
	    	<a href="javascript::void()">
	    		Addons
	    	</a>
	    </li>
	    <li>
	    	<a href="javascript::void()">
	    		Approval 
	    	</a>
	    </li>
	    <li>
	    	<a href="javascript::void()">
	    		Images
	    	</a>
	    </li>
	    <li>
	    	<a href="javascript::void()">
	    		Other
	    	</a>
	    </li>
	</ul>

	<div class="tab-content">
		<div class="tab-pane clearfix active">

			<?php
                $model->item_for_sale = ($model->item_for_sale == 'Yes') ? 1:0;
                echo $form->field($model, 'item_for_sale')->checkbox(['Yes' => 'Yes']); ?>

			<?= $form->field($model, 'item_amount_in_stock')
				->label('Item Number of Stock '.Html::tag('span', '*',['class'=>'required mandatory']))
				->textInput(['maxlength' => 128]); ?>

			<?= $form->field($model, 'item_default_capacity')
				->label('Item Default Capacity '.Html::tag('span', '*',['class'=>'required mandatory']))
				->textInput(['maxlength' => 128]); ?>

			<?= $form->field($model, 'item_how_long_to_make')
				->label('No of days delivery '.Html::tag('span', '*',['class'=>'required mandatory']))
				->textInput(['maxlength' => 128]); ?>

			<?= $form->field($model, 'item_minimum_quantity_to_order')
				->label('Item Minimum Quantity to Order '.Html::tag('span', '*',['class'=>'required mandatory']))
				->textInput(['maxlength' => 128]); ?>

			<?= $form->field($model, 'item_price_per_unit', 
				['options' => [
					'class' => 'single_price'
				]])
				->textInput([
					'maxlength' => 128
				]); ?>

			<div class="form-group multiple_price" style="padding: 5px;  font-size: 14px;"><div class="multi_pricing">Price Chart </div>

				<?php $t=0;
				foreach ($itemPricing as $value) {  ?>

				<div class="controls<?= $t; ?>">
					<input type="text" id="vendoritem-item_from" class="form-control from_range_<?= $t; ?>" name="vendoritem-item_price[from][]" multiple = "multiple" Placeholder="From Quantit" value="<?= $value['range_from'];?>" />

					<input type="text" id="vendoritem-item_to" class="form-control to_range_<?= $t; ?>" name="vendoritem-item_price[to][]" multiple = "multiple" Placeholder="To Quantity" value="<?= $value['range_to'];?>" />

					<input type="text" id="item_price_per_unit" class="form-control price_kd_<?= $t; ?>" name="vendoritem-item_price[price][]" multiple = "multiple" Placeholder="Price" value="<?= $value['pricing_price_per_unit'];?>">KD

					<input type="button" name="remove" id="remove" value="Remove" class="remove_price" onClick="removePrice(this)" />
				</div>

				<?php $t++; }?>
				<input type="button" class="add_price" name="addprice" id="addprice" value="Add more" onClick="addPrice(this);" />
			</div>

			<?= $form->field($model, 'item_price_description')->textarea(['maxlength' => 128]); ?>

			<?= $form->field($model, 'item_price_description_ar')->textarea(['maxlength' => 128]); ?>

			<?= $form->field($model, 'item_customization_description',
					['options' => ['class' => 'form-group custom_description']]
				)->textarea(
					['maxlength' => 128]
				); ?>

			<?= $form->field($model, 'item_customization_description_ar',
					['options' => ['class' => 'form-group custom_description_ar']]
				)->textarea(
					['maxlength' => 128]
				); ?>

			<hr />

			<a href="<?= Url::to(['vendor-item/item-description', 'id' => $model->item_id]) ?>" class="btn btn-info pull-left">Prev</a>
		
			<input type="submit" name="btnNext" class="btn btn-info pull-right" value="Next" />

		</div>
	</div>
</div>

<?php 

ActiveForm::end(); 

echo Html::hiddenInput('isNewRecord', 0, ['id'=>'isNewRecord']);
echo Html::hiddenInput('item_id', $model->item_id, ['id'=>'item_id']);

$this->registerJsFile('@web/themes/default/plugins/ckeditor/ckeditor.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerJsFile("@web/themes/default/js/vendor_item_validation.js?v=1.21", ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerCss("
	input#question{  margin: 10px 5px 10px 0px;  float: left;  width: 45%;}
	input#price, input#image,{	margin: 10px 5px 10px 0px;    width: 45%;}
	.selection_delete{	margin:15px 5px 10px 5px; }
	.price_val{  width: 100%;  float: left;}
	.image_val{  width: 100%;  float: left;}
	.question-section input[type=\"text\"] { margin:10px 0px;}
	.superbox{ min-height:250px;}
	.questionanswer li.parent_question{padding: 5px;  list-style: none;  border: 1px solid #000;}
	.questionanswer li.level1_question{  padding: 5px;  list-style: none;  border: 1px solid #000;  margin: 5px 0px 0px 10px;}
	.question_toggle{padding: 5px;  list-style: none;   margin: 5px 0px 0px 10px;}
	.viewbutton,.savebutton{margin-right: 10px;float: left; }
	.form-group li { list-style: none;}
	.form-groups  { margin-top: 10px;}
	.add_question, .save, .saves { float: left;  margin-right: 10px;}
	.question_success{  color: green;  line-height: 5px;  font-weight: bold; display:none; float: left;  margin: 10px 5px 10px 0;}
	.superbox-s > li > b { margin:10px 0px 5px 0px;}
	.question_title{font-weight: bold;  margin-top: 15px;  line-height: 31px;  font-size: 15px;}
	.upimage {margin: 5px 0px 10px 0px;}
	#vendoritem-groups label,#vendoritem-themes label, #vendoritem-packages label {
		float: left;min-width: 15%;margin-right: 43px;
	}
	.border-top{border-top: 1px solid;}
	.padding-top-bottom{padding: 36px 0;}
	.btn-xs, .btn-group-xs>.btn {
	    padding: 1px 5px !important;
	    margin-top: 5px;
	}

	.table-category-list .checkbox {
	    margin-left: 20px;
	}
	
	.main-category-list .chk_wrapper,
	.sub-category-list .chk_wrapper,
	.child-category-list .chk_wrapper{
		max-height: 200px;
    	overflow-y: scroll;
	}
");


?>

</div>

</div><!-- END .vendoritem-update -->
