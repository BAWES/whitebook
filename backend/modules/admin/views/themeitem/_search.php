<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\FeaturegroupitemSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="featuregroupitem-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'featured_id') ?>

    <?= $form->field($model, 'group_id') ?>

    <?= $form->field($model, 'item_id') ?>

    <?= $form->field($model, 'featured_start_date') ?>

    <?= $form->field($model, 'featured_end_date') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
