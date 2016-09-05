<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\OrderSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'order_id') ?>

    <?= $form->field($model, 'customer_id') ?>

    <?= $form->field($model, 'order_total_delivery_charge') ?>

    <?= $form->field($model, 'order_total_without_delivery') ?>

    <?= $form->field($model, 'order_total_with_delivery') ?>

    <?php // echo $form->field($model, 'order_payment_method') ?>

    <?php // echo $form->field($model, 'order_transaction_id') ?>

    <?php // echo $form->field($model, 'order_gateway_percentage') ?>

    <?php // echo $form->field($model, 'order_gateway_total') ?>

    <?php // echo $form->field($model, 'order_datetime') ?>

    <?php // echo $form->field($model, 'order_ip_address') ?>

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
