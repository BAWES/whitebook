<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Prioritylog */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="prioritylog-form">
	<div class="col-md-8 col-sm-8 col-xs-8">	
    <?php $form = ActiveForm::begin(); ?>

<div class="form-group">    <?= $form->field($model, 'vendor_id',[
					  'template' => "{label}<div class='controls'>{input}</div>
{hint}
{error}"
					])->textInput(['maxlength' => 10]) ?>

</div><div class="form-group">    <?= $form->field($model, 'item_id',[
					  'template' => "{label}<div class='controls'>{input}</div>
{hint}
{error}"
					])->textInput(['maxlength' => 10]) ?>

</div><div class="form-group">    <?= $form->field($model, 'priority_level',[
					  'template' => "{label}<div class='controls'>{input}</div>
{hint}
{error}"
					])->dropDownList([ 'normal' => 'Normal', 'super' => 'Super', ], ['prompt' => '']) ?>

</div><div class="form-group">    <?= $form->field($model, 'priority_start_date',[
					  'template' => "{label}<div class='controls'>{input}</div>
{hint}
{error}"
					])->textInput() ?>

</div><div class="form-group">    <?= $form->field($model, 'priority_end_date',[
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
					])->dropDownList([ 'default' => 'Default', 'deleted' => 'Deleted', '' => '', ], ['prompt' => '']) ?>

</div>	
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?=  Html::a('Back', ['index', ], ['class' => 'btn btn-default']) ?>
    </div>
	</div>
    <?php ActiveForm::end(); ?>

</div>
