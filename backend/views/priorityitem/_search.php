<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\PriorityitemSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="priorityitem-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'priority_id') ?>

    <?= $form->field($model, 'item_id') ?>

    <?= $form->field($model, 'priority_level') ?>

    <?= $form->field($model, 'priority_start_date') ?>

    <?= $form->field($model, 'priority_end_date') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
