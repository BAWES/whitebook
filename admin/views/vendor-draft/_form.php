<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\VendorDraft */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="vendor-draft-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'vendor_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'vendor_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'vendor_name_ar')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'vendor_return_policy')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'vendor_return_policy_ar')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'vendor_public_email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'vendor_contact_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'vendor_contact_email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'vendor_contact_address')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'vendor_contact_address_ar')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'vendor_emergency_contact_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'vendor_emergency_contact_email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'vendor_emergency_contact_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'vendor_fax')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'vendor_logo_path')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'short_description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'short_description_ar')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'vendor_website')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'vendor_facebook')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'vendor_facebook_text')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'vendor_twitter')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'vendor_twitter_text')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'vendor_instagram')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'vendor_instagram_text')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'vendor_youtube')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'vendor_youtube_text')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_by')->textInput() ?>

    <?= $form->field($model, 'modified_by')->textInput() ?>

    <?= $form->field($model, 'created_datetime')->textInput() ?>

    <?= $form->field($model, 'modified_datetime')->textInput() ?>

    <?= $form->field($model, 'vendor_bank_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'vendor_bank_branch')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'vendor_account_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'is_ready')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
