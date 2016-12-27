<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="category-form">
  	<div class="col-md-8 col-sm-8 col-xs-8">    
    
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

	<?= $form->field($model, 'parent_category_id')->dropDownList(\yii\helpers\ArrayHelper::map($categories,'category_id', 'category_name'),['encode' => false])?>

	<?= $form->field($model, 'category_name')->textInput(['maxlength' => 128])?>

	<?= $form->field($model, 'category_name_ar')->textInput(['maxlength' => 128])?>
	
	<?= $form->field($model, 'icon')->textInput(['maxlength' => 128])?>
	
	<?= $form->field($model, 'category_meta_title')->textArea(['maxlength' => 250])?>

	<?= $form->field($model, 'category_meta_keywords')->textArea(['maxlength' => 250])?>

	<?= $form->field($model, 'category_meta_description')->textArea(['maxlength' => 250])?>

	<?= $form->field($model, 'top_ad')->textArea(['maxlength' => 250])?>

	<?= $form->field($model, 'bottom_ad')->textArea(['maxlength' => 250])?>

    <div class="form-group">
        
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>

        <?= Html::a('Back', ['index'], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

