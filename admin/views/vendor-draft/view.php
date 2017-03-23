<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\VendorCategory;
use common\models\VendorPhoneNo;
use common\models\VendorOrderAlertEmails;

/* @var $this yii\web\View */
/* @var $model common\models\VendorDraft */

$this->title = $model->vendor_draft_id;
$this->params['breadcrumbs'][] = ['label' => 'Vendor Drafts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$vendor = $model->vendor;

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
                    [
                        'label' => $vendor->vendor_name != $model->vendor_name ? 'Vendor Name *' : 'Vendor Name ',
                        'value' => $model->vendor_name,
                    ],
                    [
                        'label' => $vendor->vendor_name_ar != $model->vendor_name_ar ? 'Vendor Name - AR *' : 'Vendor Name - AR',
                        'value' => $model->vendor_name_ar,
                    ],
                    [
                        'label' => $vendor->vendor_return_policy != $model->vendor_return_policy ? 'VENDOR RETURN POLICY *' : 'VENDOR RETURN POLICY ',
                        'format' => 'raw',
                        'value' => $model->vendor_return_policy
                    ],
                    [
                        'label' => $vendor->vendor_return_policy_ar != $model->vendor_return_policy_ar ? 'VENDOR RETURN POLICY - AR *' : 'VENDOR RETURN POLICY - AR',
                        'format' => 'raw',
                        'value' => $model->vendor_return_policy_ar
                    ],
                    [
                        'label' => $vendor->vendor_public_email != $model->vendor_public_email ? 'VENDOR PUBLIC EMAIL *' : 'VENDOR PUBLIC EMAIL',
                        'value' => $model->vendor_public_email,
                    ],
                    [
                        'label' => $vendor->vendor_contact_name != $model->vendor_contact_name ? 'VENDOR CONTACT NAME *' : 'VENDOR CONTACT NAME',
                        'value' => $model->vendor_contact_name,
                    ],
                    [
                        'label' => $vendor->vendor_contact_email != $model->vendor_contact_email ? 'VENDOR CONTACT EMAIL *' : 'VENDOR CONTACT EMAIL',
                        'value' => $model->vendor_contact_email,
                    ],
                    [
                        'label' => $vendor->vendor_contact_number != $model->vendor_contact_number ? 'VENDOR CONTACT NUMBER *' : 'VENDOR CONTACT NUMBER',
                        'value' => $model->vendor_contact_number,
                    ],
                    [
                        'label' => $vendor->vendor_contact_address != $model->vendor_contact_address ? 'VENDOR CONTACT ADDRESS *' : 'VENDOR CONTACT ADDRESS',
                        'format' => 'raw',
                        'value' => $model->vendor_contact_address,
                    ],
                    [
                        'label' => $vendor->vendor_contact_address_ar != $model->vendor_contact_address_ar ? 'VENDOR CONTACT ADDRESS - AR *' : 'VENDOR CONTACT ADDRESS - AR',
                        'format' => 'raw',
                        'value' => $model->vendor_contact_address_ar,
                    ],
                    [
                        'label' => $vendor->vendor_emergency_contact_name != $model->vendor_emergency_contact_name ? 'VENDOR EMERGENCY CONTACT NAME *' : 'VENDOR EMERGENCY CONTACT NAME',
                        'value' => $model->vendor_emergency_contact_name,
                    ],
                    [
                        'label' => $vendor->vendor_emergency_contact_email != $model->vendor_emergency_contact_email ? 'VENDOR EMERGENCY CONTACT EMAIL *' : 'VENDOR EMERGENCY CONTACT EMAIL',
                        'value' => $model->vendor_emergency_contact_email,
                    ],
                    [
                        'label' => $vendor->vendor_emergency_contact_number != $model->vendor_emergency_contact_number ? 'VENDOR EMERGENCY CONTACT NUMBER *' : 'VENDOR EMERGENCY CONTACT NUMBER',
                        'value' => $model->vendor_emergency_contact_number,
                    ],
                    [
                        'label' => $vendor->vendor_fax != $model->vendor_fax ? 'VENDOR FAX *' : 'VENDOR FAX',
                        'value' => $model->vendor_fax,
                    ],
                    [
                        'label' => $vendor->short_description != $model->short_description ? 'SHORT DESCRIPTION *' : 'SHORT DESCRIPTION',
                        'format' => 'raw',
                        'value' => $model->short_description,
                    ],
                    [
                        'label' => $vendor->short_description_ar != $model->short_description_ar ? 'SHORT DESCRIPTION - AR *' : 'SHORT DESCRIPTION - AR',
                        'format' => 'raw',
                        'value' => $model->short_description_ar,
                    ],
                    [
                        'label' => $vendor->vendor_website != $model->vendor_website ? 'VENDOR WEBSITE *' : 'VENDOR WEBSITE',
                        'value' => $model->vendor_website,
                    ],
                    [
                        'label' => $vendor->vendor_facebook != $model->vendor_facebook ? 'VENDOR FACEBOOK *' : 'VENDOR FACEBOOK',
                        'value' => $model->vendor_facebook,
                    ],
                    [
                        'label' => $vendor->vendor_facebook_text != $model->vendor_facebook_text ? 'VENDOR FACEBOOK TEXT *' : 'VENDOR FACEBOOK TEXT',
                        'value' => $model->vendor_facebook_text,
                    ],
                    [
                        'label' => $vendor->vendor_twitter != $model->vendor_twitter ? 'VENDOR TWITTER *' : 'VENDOR TWITTER',
                        'value' => $model->vendor_twitter,
                    ],
                    [
                        'label' => $vendor->vendor_twitter_text != $model->vendor_twitter_text ? 'VENDOR TWITTER TEXT *' : 'VENDOR TWITTER TEXT',
                        'value' => $model->vendor_twitter_text,
                    ],
                    [
                        'label' => $vendor->vendor_instagram != $model->vendor_instagram ? 'VENDOR INSTAGRAM *' : 'VENDOR INSTAGRAM',
                        'value' => $model->vendor_instagram,
                    ],
                    [
                        'label' => $vendor->vendor_instagram_text != $model->vendor_instagram_text ? 'VENDOR INSTAGRAM TEXT *' : 'VENDOR INSTAGRAM TEXT',
                        'value' => $model->vendor_instagram_text,
                    ],
                    [
                        'label' => $vendor->vendor_youtube != $model->vendor_youtube ? 'VENDOR YOUTUBE *' : 'VENDOR YOUTUBE',
                        'value' => $model->vendor_youtube,
                    ],
                    [
                        'label' => $vendor->vendor_youtube_text != $model->vendor_youtube_text ? 'VENDOR YOUTUBE TEXT *' : 'VENDOR YOUTUBE TEXT',
                        'value' => $model->vendor_youtube_text,
                    ],
                    [
                        'label' => $vendor->created_datetime != $model->created_datetime ? 'CREATED DATETIME *' : 'CREATED DATETIME',
                        'value' => $model->created_datetime,
                    ],
                    [
                        'label' => $vendor->modified_datetime != $model->modified_datetime ? 'MODIFIED DATETIME *' : 'MODIFIED DATETIME',
                        'value' => $model->modified_datetime,
                    ],
                    [
                        'label' => $vendor->vendor_bank_name != $model->vendor_bank_name ? 'VENDOR BANK NAME *' : 'VENDOR BANK NAME',
                        'value' => $model->vendor_bank_name,
                    ],
                    [
                        'label' => $vendor->vendor_bank_branch != $model->vendor_bank_branch ? 'VENDOR BANK BRANCH *' : 'VENDOR BANK BRANCH',
                        'value' => $model->vendor_bank_branch,
                    ],
                    [
                        'label' => $vendor->vendor_account_no != $model->vendor_account_no ? 'VENDOR ACCOUNT NO *' : 'VENDOR ACCOUNT NO',
                        'value' => $model->vendor_account_no,
                    ],
                    [
                        'label' => $vendor->slug != $model->slug ? 'SLUG *' : 'SLUG',
                        'value' => $model->slug,
                    ]
                ],
            ]) ?>
        </div>
        <div class="tab-pane" id="tab_2">
            
            <table class="table table-bordered table-email-list">
                <tbody>
                    <?php foreach ($phone_nos as $key => $value) { 

                        //check if dirty 

                        $old_value = VendorPhoneNo::findOne([
                                'vendor_id' => $model->vendor_id,
                                'phone_no' => $value->phone_no,
                                'type' => $value->type
                            ]);

                        ?>
                    <tr>
                        <td>
                            <?= $value->phone_no ?>   

                            <?php if(!$old_value) echo '*'; ?>        
                        </td>
                        <td>
                            <?= $value->type ?>          

                            <?php if(!$old_value) echo '*'; ?> 
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>

        </div>
        <div class="tab-pane" id="tab_3">

            <table class="table table-bordered table-email-list">
                <tbody>
                    <?php foreach ($categories as $key => $value) { 

                        //check if dirty 

                        $old_value = VendorCategory::findOne([
                                'vendor_id' => $model->vendor_id,
                                'category_id' => $value->category_id
                            ]);

                        ?>
                    <tr>
                        <td>
                            <?= $value->category->category_name ?>           

                            <?php if(!$old_value) echo '*'; ?>
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
                    <?php foreach ($emails as $key => $value) { 

                        //check if dirty 

                        $old_value = VendorOrderAlertEmails::findOne([
                                'vendor_id' => $model->vendor_id,
                                'email_address' => $value->email_address
                            ]);

                        ?>
                    <tr>
                        <td>
                            <?= $value->email_address ?>   

                            <?php if(!$old_value) echo '*'; ?>        
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>

        </div>
    </div>
</div>
