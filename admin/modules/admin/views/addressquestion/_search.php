<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\AddressQuestionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="address-question-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'ques_id') ?>

    <?= $form->field($model, 'address_type_id') ?>

    <?= $form->field($model, 'question') ?>

    <?= $form->field($model, 'status') ?>

    <?= $form->field($model, 'created_by') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
