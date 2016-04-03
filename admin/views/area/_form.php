<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Area */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="area-form">
	<div class="col-md-8 col-sm-8 col-xs-8">	
    <?php $form = ActiveForm::begin(); ?>

<div class="form-group">    <?= $form->field($model, 'area_name',[
					  'template' => "{label}<div class='controls'>{input}</div>
{hint}
{error}"
					])->textInput() ?>

</div><div class="form-group">    <?= $form->field($model, 'created_by',[
					  'template' => "{label}<div class='controls'>{input}</div>
{hint}
{error}"
					])->textInput() ?>

</div><div class="form-group">    <?= $form->field($model, 'modified_by',[
					  'template' => "{label}<div class='controls'>{input}</div>
{hint}
{error}"
					])->textInput() ?>

</div><div class="form-group">    <?= $form->field($model, 'created_datetime',[
					  'template' => "{label}<div class='controls'>{input}</div>
{hint}
{error}"
					])->textInput() ?>

</div><div class="form-group">    <?= $form->field($model, 'modified_datetime',[
					  'template' => "{label}<div class='controls'>{input}</div>
{hint}
{error}"
					])->textInput() ?>

</div><div class="form-group">    <?= $form->field($model, 'trash',[
					  'template' => "{label}<div class='controls'>{input}</div>
{hint}
{error}"
					])->dropDownList([ 'Default' => 'Default', 'Deleted' => 'Deleted', ], ['prompt' => '']) ?>

</div>	
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?=  Html::a('Back', ['index', ], ['class' => 'btn btn-default']) ?>
    </div>
	</div>
    <?php ActiveForm::end(); ?>

</div>
