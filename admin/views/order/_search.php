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

    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'order_id') ?>

            <?= $form->field($model, 'customerName') ?>

            <?= $form->field($model, 'order_total_delivery_charge') ?>

        </div>
        <div class="col-md-3">

            <?= $form->field($model, 'order_total_without_delivery') ?>
            
            <?= $form->field($model, 'order_total_with_delivery') ?>

            <?= $form->field($model, 'order_ip_address') ?>
            
        </div>
        <div class="col-md-3">
            
            <?= $form->field($model, 'order_payment_method') ?>

            <?= $form->field($model, 'order_transaction_id') ?>

            <?= $form->field($model, 'order_gateway_percentage') ?>

        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'order_gateway_total') ?>

            <?= $form
                ->field($model, 'created_datetime')
                ->textInput(['class' => 'form-control datepicker'])
                ->label('Created Date'); ?>

            <?= $form
                ->field($model, 'modified_datetime')
                ->textInput(['class' => 'form-control datepicker'])
                ->label('Modified Date'); ?>

        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
