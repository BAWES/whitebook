<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Order */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'customer_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'order_total_delivery_charge')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'order_total_without_delivery')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'order_total_with_delivery')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'order_payment_method')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'order_transaction_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'order_gateway_percentage')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'order_gateway_total')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'order_ip_address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_by')->textInput() ?>

    <?= $form->field($model, 'modified_by')->textInput() ?>

    <?= $form->field($model, 'created_datetime')->textInput() ?>

    <?= $form->field($model, 'modified_datetime')->textInput() ?>

    <?= $form->field($model, 'trash')->dropDownList([ 'Default' => 'Default', 'Deleted' => 'Deleted', ], ['prompt' => '']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
