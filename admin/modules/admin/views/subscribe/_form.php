<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Subscribe */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="subscribe-form">
	<div class="col-md-8 col-sm-8 col-xs-8">	
    <?php $form = ActiveForm::begin(); ?>

<div class="form-group">    <?= $form->field($model, 'name',[
					  'template' => "{label}<div class='controls'>{input}</div>
{hint}
{error}"
					])->textInput(['maxlength' => 230]) ?>

</div><div class="form-group">    <?= $form->field($model, 'email',[
					  'template' => "{label}<div class='controls'>{input}</div>
{hint}
{error}"
					])->textInput(['maxlength' => 230]) ?>

</div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?=  Html::a('Back', ['index', ], ['class' => 'btn btn-default']) ?>
    </div>
	</div>
    <?php ActiveForm::end(); ?>

</div>
