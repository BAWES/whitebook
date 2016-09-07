<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="sub-order-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'order_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'vendor_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'suborder_delivery_charge')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'suborder_total_without_delivery')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'suborder_total_with_delivery')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'suborder_commission_percentage')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'suborder_commission_total')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'suborder_vendor_total')->textInput(['maxlength' => true]) ?>

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
