<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\BlockeddateSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="blockeddate-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'block_id') ?>

    <?= $form->field($model, 'vendor_id') ?>

    <?= $form->field($model, 'block_date') ?>

    <?= $form->field($model, 'created_by') ?>

    <?= $form->field($model, 'modified_by') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
