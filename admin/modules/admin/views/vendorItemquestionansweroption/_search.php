<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\VendoritemquestionansweroptionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="vendoritemquestionansweroption-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'answer_id') ?>

    <?= $form->field($model, 'question_id') ?>

    <?= $form->field($model, 'answer_background_image_id') ?>

    <?= $form->field($model, 'answer_text') ?>

    <?= $form->field($model, 'answer_background_color') ?>

   <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
