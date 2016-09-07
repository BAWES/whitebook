<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="sub-order-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'suborder_id') ?>

    <?= $form->field($model, 'order_id') ?>

    <?= $form->field($model, 'vendor_id') ?>

    <?= $form->field($model, 'status_id') ?>

    <?= $form->field($model, 'suborder_delivery_charge') ?>

    <?php // echo $form->field($model, 'suborder_total_without_delivery') ?>

    <?php // echo $form->field($model, 'suborder_total_with_delivery') ?>

    <?php // echo $form->field($model, 'suborder_commission_percentage') ?>

    <?php // echo $form->field($model, 'suborder_commission_total') ?>

    <?php // echo $form->field($model, 'suborder_vendor_total') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'modified_by') ?>

    <?php // echo $form->field($model, 'created_datetime') ?>

    <?php // echo $form->field($model, 'modified_datetime') ?>

    <?php // echo $form->field($model, 'trash') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
