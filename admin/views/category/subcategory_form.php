<?php

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\filters\VerbFilter;
use yii\widgets\ActiveForm;
use common\models\Category;
use common\models\SubCategory;
use common\models\CategorySearch;

?>

<div class="category-form">
	<div class="col-md-8 col-sm-8 col-xs-8">    
    
	<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
	
    <?= $form->field($model, 'parent_category_id')->dropDownList($subcategory, ['prompt'=>'Select...']) ?></div>

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
        <?= Html::a('Back', ['manage_subcategory', ], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php 

if($model->isNewRecord || $model->category_allow_sale=='yes') { 
	
	$this->registerJs("
		$('#subcategory-category_allow_sale').prop('checked', true);
	");	

} else { 
	
	$this->registerJs("
		$('#subcategory-category_allow_sale').prop('checked', false);		
	");	
}