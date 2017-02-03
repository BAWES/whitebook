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
	    <li class="active">
	    	<a href="javascript::void()">
	    		Item description
	    	</a>
	    </li>
	    <li>
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

			<div class="alert alert-info">
				Inline css will be removed from editor for item description, item additional info, price description and customization description.
				<button class="close" data-dismiss="alert">x</button>
			</div>

			<?= $form->field($model, 'type_id')->dropDownList($itemType, ['prompt'=>'Select...']) ?>

			<?= $form->field($model, 'item_description')
					->label('Item description'.Html::tag('span', '*',['class'=>'required']))
					->textarea(['maxlength' => 128]); ?>
	
			<?= $form->field($model, 'item_description_ar')
					->label('Item description - Arabic '.Html::tag('span', '*',['class'=>'required']))
					->textarea(['maxlength' => 128]); ?>

			<?= $form->field($model, 'item_additional_info')->textarea(['maxlength' => 128]); ?>

			<?= $form->field($model, 'item_additional_info_ar')->textarea(['maxlength' => 128]); ?>
			
			<hr />

			<a href="<?= Url::to(['vendor-item/update', 'id' => $model->item_id]) ?>" class="btn btn-info pull-left">Prev</a>
		
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
");


?>

</div>

</div><!-- END .vendoritem-update -->

