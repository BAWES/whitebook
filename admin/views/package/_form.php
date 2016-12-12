<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="package-form">
    <div class="col-md-8 col-sm-8 col-xs-8">    

        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

        <?= $form->field($model, 'package_name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'imageFile')->fileInput() ?>

        <?php if(!$model->isNewRecord && $model->package_background_image) { ?>
            <div class="thumbnail pull-left">
                <img src="<?= Yii::getAlias('@s3').'/'.$model->package_background_image ?>" />
            </div>
            <div class="clearfix"></div>
        <?php } ?>

        <?php /*$form->field($model, 'package_background_image')->textInput(['maxlength' => true]) */ ?>

        <?= $form->field($model, 'package_description')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'package_avg_price')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'package_number_of_guests')->textInput(['maxlength' => true]) ?>

        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
