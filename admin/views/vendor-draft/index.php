<?php

use yii\helpers\Html;
use yii\helpers\Url;
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
            //'vendor_id',
            'vendor_name',
            //'vendor_name_ar',
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

            [
                'class' => 'yii\grid\ActionColumn',
                'header'=>'Action',
                'template' => '{view} {approve} {reject} {delete}',
                'buttons' => [            
                    'reject' => function($url, $data) {
                        return '<a title="Reject" data-id="'.$data->vendor_draft_id.'" class="btn-reject"><i class="glyphicon glyphicon-remove"></i></a>';
                    },
                    'approve' => function($url, $data) {
                        return HTML::a(
                            '<i class="glyphicon glyphicon-ok"></i>', 
                            Url::to(['vendor-draft/approve', 'id' => $data->vendor_draft_id]),
                            [
                                'title' => 'Approve'
                            ]
                        );
                    }
                ]
            ]
        ],
    ]); ?>
</div>

<div class="modal fade modal_reject" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Reject</h4>
      </div>
      <div class="modal-body">
        <form>
            <input type="hidden" name="vendor_draft_id" />
            <textarea class="form-control" name="reason" placeholder="Reason for rejection"></textarea>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary btn-reject-submit">Submit</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php

echo Html::hiddenInput('reject_url', Url::to(['vendor-draft/reject']), ['id' => 'reject_url']);

$this->registerJsFile("@web/themes/default/js/vendor_draft.js", ['depends' => [\yii\web\JqueryAsset::className()]]);
