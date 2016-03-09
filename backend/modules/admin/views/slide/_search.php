<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\SlideSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="slide-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'slide_id') ?>

    <?= $form->field($model, 'slide_title') ?>

    <?= $form->field($model, 'slide_image') ?>

    <?= $form->field($model, 'slide_video_url') ?>

    <?= $form->field($model, 'slide_url') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
