<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

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

        </div>
        <div class="col-md-3">

            <?= $form->field($model, 'order_total_without_delivery') ?>
            
            <?= $form->field($model, 'order_total_with_delivery') ?>

        </div>
        <div class="col-md-3">
            
            <?= $form->field($model, 'order_total_delivery_charge') ?>

            <?= $form->field($model, 'order_ip_address') ?>
            
        </div>
        <div class="col-md-3">
            
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
