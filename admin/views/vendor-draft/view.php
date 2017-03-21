<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\VendorDraft */

$this->title = $model->vendor_draft_id;
$this->params['breadcrumbs'][] = ['label' => 'Vendor Drafts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vendor-draft-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->vendor_draft_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->vendor_draft_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'vendor_draft_id',
            'vendor_id',
            'vendor_name',
            'vendor_name_ar',
            'vendor_return_policy:ntext',
            'vendor_return_policy_ar:ntext',
            'vendor_public_email:email',
            'vendor_contact_name',
            'vendor_contact_email:email',
            'vendor_contact_number',
            'vendor_contact_address:ntext',
            'vendor_contact_address_ar:ntext',
            'vendor_emergency_contact_name',
            'vendor_emergency_contact_email:email',
            'vendor_emergency_contact_number',
            'vendor_fax',
            'vendor_logo_path',
            'short_description:ntext',
            'short_description_ar:ntext',
            'vendor_website',
            'vendor_facebook',
            'vendor_facebook_text',
            'vendor_twitter',
            'vendor_twitter_text',
            'vendor_instagram',
            'vendor_instagram_text',
            'vendor_youtube',
            'vendor_youtube_text',
            'created_by',
            'modified_by',
            'created_datetime',
            'modified_datetime',
            'vendor_bank_name',
            'vendor_bank_branch',
            'vendor_account_no',
            'slug',
            'is_ready',
        ],
    ]) ?>

</div>
