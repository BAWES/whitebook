<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

?>

<div class="sub-order-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'suborder_id') ?>

            <?= $form->field($model, 'order_id') ?>

            <?= $form->field($model, 'vendor_id') ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'status_id')
                ->dropDownList(
                    ArrayHelper::map($status, 'order_status_id', 'name'),           
                    ['prompt'=>'']    
                )->label('Status') ?>

            <?= $form->field($model, 'suborder_delivery_charge') ?>

            <?= $form->field($model, 'suborder_total_without_delivery') ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'suborder_total_with_delivery') ?>

            <?= $form->field($model, 'suborder_commission_percentage') ?>

            <?= $form->field($model, 'suborder_commission_total') ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'suborder_vendor_total') ?>

            <?= $form->field($model, 'created_datetime')->label('Created date') ?>

            <?= $form->field($model, 'modified_datetime')->label('Modified date') ?>
        </div>
    </div>
    
    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
