<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Smtp */
/* @var $form yii\widgets\ActiveForm */
?>

 <div class="col-md-8 col-sm-8 col-xs-8">

    <?php $form = ActiveForm::begin(); ?>
   <div class="form-group">   
	<?= $form->field($model, 'smtp_host',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->textInput(['maxlength' => 100])->label('SMTP Host',['class'=> 'form-label-cap']) ?>
  </div>
  
  <div class="form-group">   
	<?= $form->field($model, 'smtp_username',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->textInput(['maxlength' => 100])->label('SMTP username',['class'=> 'form-label-cap']) ?>
  </div>
  
  <div class="form-group">   
	<?= $form->field($model, 'smtp_password',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->passwordInput(['maxlength' => 100])->label('SMTP password',['class'=> 'form-label-cap']) ?>
  </div>
  
  <div class="form-group">   
	<?= $form->field($model, 'smtp_port',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->textInput(['maxlength' => 100])->label('SMTP port',['class'=> 'form-label-cap']) ?>
  </div>
  
  <div class="form-group">   
	<?= $form->field($model, 'transport_layer_security',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->textInput() ?>
  </div>  
 
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('Back', ['site/index', ], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
