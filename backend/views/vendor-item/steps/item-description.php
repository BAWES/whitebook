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
	    <li class="active">
	    	<a href="<?= Url::to(['vendor-item/item-description', 'id' => $model->item_id]) ?>">
	    		Description
	    	</a>
	    </li>
	    <li>
	    	<a href="<?= Url::to(['vendor-item/item-price', 'id' => $model->item_id]) ?>">
	    		Price and Inventory
	    	</a>
	    </li>
	    <li>
	    	<a href="<?= Url::to(['vendor-item/menu-items', 'id' => $model->item_id]) ?>">
	    		Menu
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

			<div class="alert alert-info">
				Inline css will be removed from editor for item description, item additional info, price description and customization description.
				<button class="close" data-dismiss="alert">x</button>
			</div>

			<?= $form->field($model, 'item_description')
					->label('Item description'.Html::tag('span', '*',['class'=>'required']))
					->textarea(['maxlength' => 128, 'id' => 'vendoritem-item_description']); ?>

			<?= $form->field($model, 'item_description_ar')
					->label('Item description - Arabic'.Html::tag('span', '*',['class'=>'required']))
					->textarea(['maxlength' => 128, 'id' => 'vendoritem-item_description_ar']); ?>

			<?= $form->field($model, 'item_additional_info')
					->textarea(['maxlength' => 128, 'id' => 'vendoritem-item_additional_info']); ?>

			<?= $form->field($model, 'item_additional_info_ar')
					->textarea(['maxlength' => 128, 'id' => 'vendoritem-item_additional_info_ar']); ?>

			<div class="form-group">
				<label>Notice Period <span>*</span></label>		       	
				<div class="input-group">
					<span class="input-group-btn">
			        	<select name="notice_period_type" style="width: auto; min-height: 37px;">
			        		<option>Hour</option>
			        		<option>Day</option>
			        	</select>
			        </span>		 
			        <input type="text" class="form-control" value="<?= $model->item_how_long_to_make ?>" name="VendorDraftItem[item_how_long_to_make]" />		       	    	 
			    </div>
		    </div>

			<?= $form->field($model, 'max_time'); ?>

			<?= $form->field($model, 'max_time_ar'); ?>

			<?= $form->field($model, 'set_up_time'); ?>

			<?= $form->field($model, 'set_up_time_ar'); ?>

			<?= $form->field($model, 'requirements'); ?>

			<?= $form->field($model, 'requirements_ar'); ?>

			<hr />
			
			<div class="row">				
				<div class="col-md-4">
					<a href="<?= Url::to(['vendor-item/update', 'id' => $model->item_id]) ?>" class="btn btn-info pull-left">Prev</a>
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

$this->registerJsFile("@web/themes/default/js/vendor_item_validation.js?v=1.22", ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerJsFile("@web/themes/default/js/vendor_item_steps/description.js", ['depends' => [\yii\web\JqueryAsset::className()]]);