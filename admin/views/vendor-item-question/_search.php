<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\VendoritemquestionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="vendoritemquestion-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'question_id') ?>

    <?= $form->field($model, 'item_id') ?>

    <?= $form->field($model, 'answer_id') ?>

    <?= $form->field($model, 'question_text') ?>

    <?= $form->field($model, 'question_answer_type') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
