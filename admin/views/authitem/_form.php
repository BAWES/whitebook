<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="authitem-form">
	<div class="col-md-8 col-sm-8 col-xs-8">
		
    <?php $form = ActiveForm::begin(); ?>

		<?= $form->field($model, 'name')->textInput(['maxlength' => 64]) ?>
		
		<?= $form->field($model, 'type')->textInput() ?>
		
		<?= $form->field($model, 'description')->textarea([
			'rows' => 6,
			'placeholder' => 'Enter description',
			'class'=> 'form-control'
		]) ?>
		
		<?= $form->field($model, 'rule_name')->textInput(['maxlength' => 64]) ?>
		
		<?= $form->field($model, 'data')->textarea([
			'rows' => 6,
			'placeholder' => 'Enter data',
			'class'=> 'form-control'
		]) ?>
		
		<div class="form-group">

		    <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
		
		    <?=  Html::a('Back', ['index', ], ['class' => 'btn btn-default']) ?>
		</div>

    <?php ActiveForm::end(); ?>
    
	</div>
</div>
