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

    <p>
        <?= Html::a('Approve', ['approve', 'id' => $model->vendor_draft_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->vendor_draft_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="tabbable">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab_1" data-toggle="tab">Vendor Info </a></li>
            <li><a href="#tab_2" data-toggle="tab">Phone no</a></li>
            <li><a href="#tab_3" data-toggle="tab">Category</a></li>
            <li><a href="#tab_4" data-toggle="tab">Order Alert Emails</a></li>
        </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="tab_1">

            <div class="admin" style="text-align: center;padding:0px 0px 25px 0px;">
                <?php if(isset($model->vendor_logo_path)) {
                        echo Html::img(Yii::getAlias('@s3/vendor_logo/').$model->vendor_logo_path, ['class'=>'','width'=>'125px','height'=>'125px','alt'=>'Logo']);
                } ?>
            </div>

            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'vendor_draft_id',
                    'vendor_id',
                    'vendor_name',
                    'vendor_name_ar',
                    'vendor_return_policy:html',
                    'vendor_return_policy_ar:html',
                    'vendor_public_email:email',
                    'vendor_contact_name',
                    'vendor_contact_email:email',
                    'vendor_contact_number',
                    'vendor_contact_address:html',
                    'vendor_contact_address_ar:html',
                    'vendor_emergency_contact_name',
                    'vendor_emergency_contact_email:email',
                    'vendor_emergency_contact_number',
                    'vendor_fax',
                    'short_description:html',
                    'short_description_ar:html',
                    'vendor_website',
                    'vendor_facebook',
                    'vendor_facebook_text',
                    'vendor_twitter',
                    'vendor_twitter_text',
                    'vendor_instagram',
                    'vendor_instagram_text',
                    'vendor_youtube',
                    'vendor_youtube_text',
                    'created_datetime',
                    'modified_datetime',
                    'vendor_bank_name',
                    'vendor_bank_branch',
                    'vendor_account_no',
                    'slug',
                ],
            ]) ?>
        </div>
        <div class="tab-pane" id="tab_2">
            
            <table class="table table-bordered table-email-list">
                <tbody>
                    <?php foreach ($phone_nos as $key => $value) { ?>
                    <tr>
                        <td>
                            <?= $value->phone_no ?>           
                        </td>
                        <td>
                            <?= $value->type ?>           
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>

        </div>
        <div class="tab-pane" id="tab_3">

            <table class="table table-bordered table-email-list">
                <tbody>
                    <?php foreach ($categories as $key => $value) { ?>
                    <tr>
                        <td>
                            <?= $value->category->category_name ?>           
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>

        </div>
        <div class="tab-pane" id="tab_4">

            Email address list to get order notification 

            <br />
            <br />

            <table class="table table-bordered table-email-list">
                <tbody>
                    <?php foreach ($emails as $key => $value) { ?>
                    <tr>
                        <td>
                            <?= $value->email_address ?>           
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>

        </div>
    </div>
</div>
