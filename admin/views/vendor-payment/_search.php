<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\VendorPaymentSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="vendor-payment-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'payment_id') ?>

    <?= $form->field($model, 'vendor_id') ?>

    <?= $form->field($model, 'booking_id') ?>

    <?= $form->field($model, 'type') ?>

    <?= $form->field($model, 'amount') ?>

    <?php // echo $form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'created_datetime') ?>

    <?php // echo $form->field($model, 'modified_datetime') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
