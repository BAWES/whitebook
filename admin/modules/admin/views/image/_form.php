<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Image */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="image-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'image_user_id')->textInput(['maxlength' => 11]) ?>

    <?= $form->field($model, 'image_user_type')->dropDownList([ 'admin' => 'Admin', 'vendor' => 'Vendor', 'customer' => 'Customer', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'image_path')->textInput(['maxlength' => 128]) ?>

    <?= $form->field($model, 'image_file_size')->textInput(['maxlength' => 11]) ?>

    <?= $form->field($model, 'image_width')->textInput(['maxlength' => 11]) ?>

    <?= $form->field($model, 'image_height')->textInput(['maxlength' => 11]) ?>

    <?= $form->field($model, 'image_datetime')->textInput() ?>

    <?= $form->field($model, 'image_ip_address')->textInput(['maxlength' => 128]) ?>

    <?= $form->field($model, 'created_by')->textInput() ?>

    <?= $form->field($model, 'modified_by')->textInput() ?>

    <?= $form->field($model, 'created_datetime')->textInput() ?>

    <?= $form->field($model, 'modified_datetime')->textInput() ?>

    <?= $form->field($model, 'trash')->dropDownList([ 'Default' => 'Default', 'Deleted' => 'Deleted', ], ['prompt' => '']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('Back', ['index', ], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
