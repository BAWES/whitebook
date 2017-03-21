<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel admin\models\VendorDraftSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Vendor Drafts';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vendor-draft-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'vendor_draft_id',
            'vendor_id',
            'vendor_name',
            'vendor_name_ar',
            //'vendor_contact_name',
            'vendor_contact_email:email',
            'vendor_contact_number',
            //'vendor_return_policy:ntext',
            // 'vendor_return_policy_ar:ntext',
            // 'vendor_public_email:email',
            // 'vendor_contact_address:ntext',
            // 'vendor_contact_address_ar:ntext',
            // 'vendor_emergency_contact_name',
            // 'vendor_emergency_contact_email:email',
            // 'vendor_emergency_contact_number',
            // 'vendor_fax',
            // 'vendor_logo_path',
            // 'short_description:ntext',
            // 'short_description_ar:ntext',
            // 'vendor_website',
            // 'vendor_facebook',
            // 'vendor_facebook_text',
            // 'vendor_twitter',
            // 'vendor_twitter_text',
            // 'vendor_instagram',
            // 'vendor_instagram_text',
            // 'vendor_youtube',
            // 'vendor_youtube_text',
            // 'created_by',
            // 'modified_by',
            'created_datetime',
            // 'modified_datetime',
            // 'vendor_bank_name',
            // 'vendor_bank_branch',
            // 'vendor_account_no',
            // 'slug',
            // 'is_ready',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
