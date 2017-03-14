<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\PaymentGateway */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="payment-gateway-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name_ar')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => true, 'readonly' => 'true']) ?>
    
    <?= $form->field($model, 'percentage')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fees')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'under_testing')->dropDownList([0 => 'No', 1 => 'Yes']) ?>

    <?= $form->field($model, 'status')->dropDownList([0 => 'Inactive', 1 => 'Active']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
