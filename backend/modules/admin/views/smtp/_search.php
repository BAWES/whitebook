<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SmtpSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="smtp-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'smtp_host') ?>

    <?= $form->field($model, 'smtp_username') ?>

    <?= $form->field($model, 'smtp_password') ?>

    <?= $form->field($model, 'smtp_port') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
