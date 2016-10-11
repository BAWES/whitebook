<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\PaymentGatewaySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="payment-gateway-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'gateway_id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'name_ar') ?>

    <?= $form->field($model, 'code') ?>

    <?= $form->field($model, 'percentage') ?>

    <?php // echo $form->field($model, 'order_status_id') ?>

    <?php // echo $form->field($model, 'under_testing') ?>

    <?php // echo $form->field($model, 'status') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
