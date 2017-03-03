<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\OrderRequestStatusSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-request-status-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'request_id') ?>

    <?= $form->field($model, 'order_id') ?>

    <?= $form->field($model, 'request_status') ?>

    <?= $form->field($model, 'request_note') ?>

    <?= $form->field($model, 'created_datetime') ?>

    <?php // echo $form->field($model, 'modified_datetime') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>