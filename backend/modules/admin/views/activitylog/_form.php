<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Activitylog */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="activitylog-form">
	<div class="col-md-8 col-sm-8 col-xs-8">	
    <?php $form = ActiveForm::begin(); ?>

<div class="form-group">    <?= $form->field($model, 'log_user_id',[
					  'template' => "{label}<div class='controls'>{input}</div>
{hint}
{error}"
					])->textInput(['maxlength' => 11]) ?>

</div><div class="form-group">    <?= $form->field($model, 'log_user_type',[
					  'template' => "{label}<div class='controls'>{input}</div>
{hint}
{error}"
					])->dropDownList([ 'admin' => 'Admin', 'vendor' => 'Vendor', 'customer' => 'Customer', ], ['prompt' => '']) ?>

</div><div class="form-group">    <?= $form->field($model, 'log_action',[
			  'template' => "{label}<div class='controls'>{input}</div>
{hint}
{error}"
			])->textarea(['rows' => 6,'placeholder' => 'Enter log_action','class'=> 'form-control']) ?>

</div><div class="form-group">    <?= $form->field($model, 'log_datetime',[
					  'template' => "{label}<div class='controls'>{input}</div>
{hint}
{error}"
					])->textInput() ?>

</div>	
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?=  Html::a('Back', ['index', ], ['class' => 'btn btn-default']) ?>
    </div>
	</div>
    <?php ActiveForm::end(); ?>

</div>
