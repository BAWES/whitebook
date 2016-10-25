<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model admin\models\FaqGroup */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="faq-group-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'group_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'group_name_ar')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sort_order')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('Back', ['index', ], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
