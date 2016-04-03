<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\EmailtemplateSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="emailtemplate-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'email_template_id') ?>

    <?= $form->field($model, 'email_title') ?>

    <?= $form->field($model, 'email_subject') ?>

    <?= $form->field($model, 'email_content') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
