<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\AccesscontrolSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="accesscontrol-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'access_id') ?>

    <?= $form->field($model, 'role_id') ?>

    <?= $form->field($model, 'admin_id') ?>

    <?= $form->field($model, 'created_by') ?>

    <?= $form->field($model, 'modified_by') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
