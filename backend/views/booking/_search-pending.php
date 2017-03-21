<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="order-search">

    <?php $form = ActiveForm::begin([
        'action' => ['pending'],
        'method' => 'get',
    ]); ?>

    <div class="row">
        <div class="col-md-3">

            <?= $form->field($model, 'booking_id') ?>

            <?= $form->field($model, 'customer_name') ?>

        </div>
        <div class="col-md-3">

            <?= $form->field($model, 'total_without_delivery') ?>
            
            <?= $form->field($model, 'total_with_delivery') ?>

        </div>
        <div class="col-md-3">
            
            <?= $form->field($model, 'total_delivery_charge') ?>

            <?= $form->field($model, 'ip_address') ?>
            
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
        <?= Html::a('Reset', ['booking/pending'],['class' => 'btn btn-default']) ?>
        <?php //Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
