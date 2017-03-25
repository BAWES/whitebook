<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\VendorWorkingTimingSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="vendor-working-timing-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'working_id') ?>

    <?= $form->field($model, 'vendor_id') ?>

    <?= $form->field($model, 'working_day') ?>

    <?= $form->field($model, 'working_start_time') ?>

    <?= $form->field($model, 'working_end_time') ?>

    <?php // echo $form->field($model, 'trash') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>