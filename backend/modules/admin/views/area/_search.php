<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\AreaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="area-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'area_id') ?>

    <?= $form->field($model, 'area_name') ?>

    <?= $form->field($model, 'created_by') ?>

    <?= $form->field($model, 'modified_by') ?>

    <?= $form->field($model, 'created_datetime') ?>
    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
