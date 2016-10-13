<?php

use yii\widgets\ActiveForm;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use common\models\Category;
use common\models\SubCategory;
use common\models\CategorySearch;

?>

<div class="category-form">
	<div class="col-md-8 col-sm-8 col-xs-8">    

	<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

	<?php if($model->isNewRecord){?>

		<?= $form->field($model, 'parent_category_id')->dropDownList($category, ['prompt'=>'Select...']) ?>
		<?= $form->field($model, 'subcategory_id')->dropDownList(['prompt'=>'Select...']) ?>

	<?php } else  { 

		$model->parent_category_id = $parentid;
		$model->subcategory_id = $subcategory_id; ?>

		<?= $form->field($model, 'parent_category_id')->dropDownList($parentcategory, ['prompt'=>'Select...']) ?>
		<?= $form->field($model, 'subcategory_id')->dropDownList($subcategory,['prompt'=>'Select...']) ?>

	<?php } ?>

	<?= $form->field($model, 'category_name')->textInput(['maxlength' => 128])?>

	<?= $form->field($model, 'category_name_ar')->textInput(['maxlength' => 128])?>

	<?= $form->field($model, 'category_meta_title')->textArea(['maxlength' => 250])?>

	<?= $form->field($model, 'category_meta_keywords')->textArea(['maxlength' => 250])?>

	<?= $form->field($model, 'category_meta_description')->textArea(['maxlength' => 250])?>
   
	<?= $form->field($model, 'category_allow_sale')->checkbox(['yes' => 'yes']) ?>

	<?= $form->field($model, 'top_ad')->textArea(['maxlength' => 250])?>

	<?= $form->field($model, 'bottom_ad')->textArea(['maxlength' => 250])?>

    <div class="form-group" style="margin-top:10px;">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('Back', ['child_category_index', ], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


<?php 

$this->registerJs("
	
	$(function (){ 

	    $('#childcategory-parent_category_id').change(function (){
			var csrfToken = $('meta[name=\"csrf-token\"]').attr('content');
	        var id = $('#childcategory-parent_category_id').val();
	        var path = '".Url::to(['/category/loadsubcategory'])."';
	        
	        $.ajax({
		        type: 'POST',      
		        url: path, //url to be called
		        data: { id: id ,_csrf : csrfToken}, //data to be send
		        success: function( data ) {			
		             $('#childcategory-subcategory_id').html(data);
		        }
	        });

	     });
	});
");

if($model->isNewRecord || $model->category_allow_sale=='yes'){ 
	
	$this->registerJs("
		$('#childcategory-category_allow_sale').prop('checked', true);
	");

} else { 
	
	$this->registerJs("
		$('#childcategory-category_allow_sale').prop('checked', false);		
	");
} 