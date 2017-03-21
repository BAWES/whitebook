<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model admin\models\VendorDraftSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="vendor-draft-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'vendor_draft_id') ?>

    <?= $form->field($model, 'vendor_id') ?>

    <?= $form->field($model, 'vendor_name') ?>

    <?= $form->field($model, 'vendor_name_ar') ?>

    <?= $form->field($model, 'vendor_return_policy') ?>

    <?php // echo $form->field($model, 'vendor_return_policy_ar') ?>

    <?php // echo $form->field($model, 'vendor_public_email') ?>

    <?php // echo $form->field($model, 'vendor_contact_name') ?>

    <?php // echo $form->field($model, 'vendor_contact_email') ?>

    <?php // echo $form->field($model, 'vendor_contact_number') ?>

    <?php // echo $form->field($model, 'vendor_contact_address') ?>

    <?php // echo $form->field($model, 'vendor_contact_address_ar') ?>

    <?php // echo $form->field($model, 'vendor_emergency_contact_name') ?>

    <?php // echo $form->field($model, 'vendor_emergency_contact_email') ?>

    <?php // echo $form->field($model, 'vendor_emergency_contact_number') ?>

    <?php // echo $form->field($model, 'vendor_fax') ?>

    <?php // echo $form->field($model, 'vendor_logo_path') ?>

    <?php // echo $form->field($model, 'short_description') ?>

    <?php // echo $form->field($model, 'short_description_ar') ?>

    <?php // echo $form->field($model, 'vendor_website') ?>

    <?php // echo $form->field($model, 'vendor_facebook') ?>

    <?php // echo $form->field($model, 'vendor_facebook_text') ?>

    <?php // echo $form->field($model, 'vendor_twitter') ?>

    <?php // echo $form->field($model, 'vendor_twitter_text') ?>

    <?php // echo $form->field($model, 'vendor_instagram') ?>

    <?php // echo $form->field($model, 'vendor_instagram_text') ?>

    <?php // echo $form->field($model, 'vendor_youtube') ?>

    <?php // echo $form->field($model, 'vendor_youtube_text') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'modified_by') ?>

    <?php // echo $form->field($model, 'created_datetime') ?>

    <?php // echo $form->field($model, 'modified_datetime') ?>

    <?php // echo $form->field($model, 'vendor_bank_name') ?>

    <?php // echo $form->field($model, 'vendor_bank_branch') ?>

    <?php // echo $form->field($model, 'vendor_account_no') ?>

    <?php // echo $form->field($model, 'slug') ?>

    <?php // echo $form->field($model, 'is_ready') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
