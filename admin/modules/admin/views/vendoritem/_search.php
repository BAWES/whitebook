<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\VendoritemSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="vendoritem-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'item_id') ?>

    <?= $form->field($model, 'type_id') ?>

    <?= $form->field($model, 'vendor_id') ?>

    <?= $form->field($model, 'category_id') ?>

    <?= $form->field($model, 'item_name') ?>

    <?php // echo $form->field($model, 'item_description') ?>

    <?php // echo $form->field($model, 'item_additional_info') ?>

    <?php // echo $form->field($model, 'item_amount_in_stock') ?>

    <?php // echo $form->field($model, 'item_default_capacity') ?>

    <?php // echo $form->field($model, 'item_price_per_unit') ?>

    <?php // echo $form->field($model, 'item_customization_description') ?>

    <?php // echo $form->field($model, 'item_price_description') ?>

    <?php // echo $form->field($model, 'item_for_sale') ?>

    <?php // echo $form->field($model, 'item_how_long_to_make') ?>

    <?php // echo $form->field($model, 'item_minimum_quantity_to_order') ?>

    <?php // echo $form->field($model, 'item_approved') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'modified_by') ?>

    <?php // echo $form->field($model, 'created_datetime') ?>

    <?php // echo $form->field($model, 'modified_datetime') ?>

    <?php // echo $form->field($model, 'trash') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
