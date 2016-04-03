<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Admin */
/* @var $form yii\widgets\ActiveForm */
?>

 <div class="col-md-8 col-sm-8 col-xs-8">	

    <?php $form = ActiveForm::begin(); ?>
    
    <div class="form-group">   
	<?= $form->field($model, 'role_id',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->dropDownList($role,['prompt'=>'Select...']) ?>
	</div>
	
	<div class="form-group">   
	<?= $form->field($model, 'admin_name',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->textInput(['maxlength' => 100]) ?>
	</div>
	
	<div class="form-group">   
	<?= $form->field($model, 'admin_email',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->textInput(['maxlength' => 100]) ?>
	</div>
	
	 <?php if($model->isNewRecord) {?>
		 
	<div class="form-group">   
	<?= $form->field($model, 'admin_password',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->PasswordInput(['maxlength' => 100]) ?>
	</div>
	
	<?php } ?>
	
	<div class="form-group">   
	<?= $form->field($model, 'address',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->textArea(['maxlength' => 100]) ?>
	</div>
	
	<div class="form-group">   
	<?= $form->field($model, 'phone',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->textInput(['maxlength' => 100]) ?>
	</div>
	
	<div class="form-group">   
	<?= $form->field($model, 'admin_status',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->dropDownList(['Active' => 'Active', 'Deactive' => 'Deactive']) ?>
	</div>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('Back', ['index', ], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
