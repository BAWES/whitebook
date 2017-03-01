<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\VendorAccountPayableSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="vendor-account-payable-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'payable_id') ?>

    <?= $form->field($model, 'vendor_id') ?>

    <?= $form->field($model, 'amount') ?>

    <?= $form->field($model, 'description') ?>

    <?= $form->field($model, 'created_datetime') ?>

    <?php // echo $form->field($model, 'modified_datetime') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
