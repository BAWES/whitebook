<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\CategoryadsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="categoryads-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'advert_id') ?>

    <?= $form->field($model, 'category_id') ?>

    <?= $form->field($model, 'top_ad') ?>

    <?= $form->field($model, 'bottom_ad') ?>

    <?= $form->field($model, 'advert_code') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
