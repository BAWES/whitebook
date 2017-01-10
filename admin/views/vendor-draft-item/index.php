<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use common\models\Vendor;

$this->title = 'Vendor Draft Items';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="vendoritem-index">
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'id'=>'items',
        'columns' => [
			['class' => 'yii\grid\CheckboxColumn'],
            ['class' => 'yii\grid\SerialColumn'],
			['attribute'=>'vendor_name',
				 'value'=>'vendor.vendor_name',
			],
			[
				'attribute'=>'item_name',
				'value'=>function($data){
					return (strlen($data->item_name)>30) ? substr($data->item_name,0,30) : $data->item_name;
				},
			],  
			[
				'class' => 'yii\grid\ActionColumn',
            	'header'=>'Action',
            	'template' => ' {view} {approve} {reject}',
            	'buttons' => [
            		'reject' => function($url, $data) {
                        return '<a title="Reject" data-id="'.$data->draft_item_id.'" class="btn-reject"><i class="glyphicon glyphicon-remove"></i></a>';
                    },
            		'approve' => function($url, $data) {
            			return HTML::a(
            				'<i class="glyphicon glyphicon-ok"></i>', 
            				Url::to(['vendor-draft-item/approve', 'id' => $data->draft_item_id]),
            				[
            					'title' => 'Approve'
            				]
            			);
            		}
            	]
			],
        ],
    ]); ?>

</div>

<div class="modal fade modal_reject" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Reject item</h4>
      </div>
      <div class="modal-body">
        <form>
            <input type="hidden" name="draft_item_id" />
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

echo Html::hiddenInput('reject_url', Url::to(['vendor-draft-item/reject']), ['id' => 'reject_url']);

$this->registerJsFile("@web/themes/default/js/vendor_draft_item.js", ['depends' => [\yii\web\JqueryAsset::className()]]);
