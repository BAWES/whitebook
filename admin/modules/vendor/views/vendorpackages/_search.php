<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\VendorpackagesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="vendorpackages-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'vendor_id') ?>

    <?= $form->field($model, 'package_id') ?>

    <?= $form->field($model, 'package_price') ?>

    <?= $form->field($model, 'created_datetime') ?>

   <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
