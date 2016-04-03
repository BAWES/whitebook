<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\SocialinfoSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="socialinfo-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'store_social_id') ?>

    <?= $form->field($model, 'store_id') ?>

    <?= $form->field($model, 'store_facebook_share') ?>

    <?= $form->field($model, 'store_twitter_share') ?>

    <?= $form->field($model, 'store_google_share') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
