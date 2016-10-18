<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="category-form">
  	<div class="col-md-8 col-sm-8 col-xs-8">    
    
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

	<?= $form->field($model, 'category_name')->textInput(['maxlength' => 128])?>
	
	<?= $form->field($model, 'category_name_ar')->textInput(['maxlength' => 128])?>
	
	<?= $form->field($model, 'icon')->textInput(['maxlength' => 128])?>
	
	<?= $form->field($model, 'category_meta_title')->textArea(['maxlength' => 250])?>

	<?= $form->field($model, 'category_meta_keywords')->textArea(['maxlength' => 250])?>

	<?= $form->field($model, 'category_meta_description')->textArea(['maxlength' => 250])?>

	<?= $form->field($model, 'category_allow_sale')->checkbox(['yes' => 'yes']) ?>

	<?= $form->field($model, 'top_ad')->textArea(['maxlength' => 250])?>

	<?= $form->field($model, 'bottom_ad')->textArea(['maxlength' => 250])?>

    <div class="form-group">
        
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>

        <?= Html::a('Back', ['index'], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<?php 

if($model->isNewRecord || $model->category_allow_sale=='yes'){
	
	$this->registerJs("
		$('#category-category_allow_sale').prop('checked', true);
	");

} else { 

	$this->registerJs("
		$('#category-category_allow_sale').prop('checked', false);
	");
}
