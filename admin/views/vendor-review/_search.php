<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\VendorReviewSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="vendor-review-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'review_id') ?>

    <?= $form->field($model, 'customer_id') ?>

    <?= $form->field($model, 'vendor_id') ?>

    <?= $form->field($model, 'rating') ?>

    <?= $form->field($model, 'review') ?>

    <?php // echo $form->field($model, 'approved') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
