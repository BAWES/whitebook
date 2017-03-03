<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\BookingRequestSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="booking-request-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'booking_id') ?>

            <?= $form->field($model, 'customer_name') ?>
        </div>

        <div class="col-md-4">
            <?= $form->field($model, 'customer_lastname') ?>

            <?= $form->field($model, 'customer_email') ?>
        </div>

        <div class="col-md-4">
            <?= $form->field($model, 'ip_address') ?>

            <?= $form->field($model, 'vendor_id')->dropDownList($arr_vendor, ['prompt'=>'Select...']) ?>
        </div>
    </div>

    <?php // echo $form->field($model, 'modified_datetime') ?>

    <?php // $form->field($model, 'booking_token') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>