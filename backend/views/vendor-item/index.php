<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use yii\web\View;

/* @var $searchModel common\models\VendorItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = 'Manage items';
$this->params['breadcrumbs'][] = $this->title;
?>
<?= Html::csrfMetaTags() ?>

<div class="vendoritem-index">
<p>
	<?= Html::a('Create item', ['create'], ['class' => 'btn btn-success']) ?>
	<?= Html::a('Delete', [''], ['class' => 'btn btn-info pull-right','id'=>'Delete','onclick'=>'return Status("Delete")']) ?>
	<?= Html::a('Deactivate', [''], ['class' => 'btn btn-info pull-right','id'=>'Deactive','onclick'=>'return Status("Deactivate")']) ?>
	<?= Html::a('Activate', [''], ['class' => 'btn btn-info pull-right','id'=>'Reject','onclick'=>'return Status("Activate")']) ?>

</p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'id'=>'items',
        'columns' => [
			[ 'class' => 'yii\grid\CheckboxColumn'],
			['class' => 'yii\grid\SerialColumn'],
			[
				'attribute'=>'item_name',
				'value' => function($data) {

					if($data->draftItem) {
					 	$item_name = $data->draftItem->item_name;
					}else{
						$item_name = $data->item_name;
					}

					return (strlen($item_name)>30) ? substr($item_name, 0, 30).'...' : $item_name;
				},
			],
			[
				'attribute'=>'type_id',
				'contentOptions' => ['class' => 'text-center'],
				'headerOptions' => ['class' => 'text-center'],
				'label'=>'Item Type',
				'value'=>function($data){
					if($data->draftItem) {
						return $data->getItemType($data->draftItem->type_id);
					} else {
						return $data->getItemType($data->type_id);
					}
				},
				'filter' => Html::activeDropDownList($searchModel, 'type_id', ArrayHelper::map(common\models\ItemType::find()->where(['!=','trash','Deleted'])->asArray()->all(), 'type_id','type_name'),['class'=>'form-control','prompt' => 'All']),
			],
			[
				'attribute'=>'item_status',
				'contentOptions' => ['class' => 'text-center'],
				'headerOptions' => ['class' => 'text-center'],
				'label'=>'Status',
				'format'=>'raw',
				'value'=>function($data) {
					return HTML::a('<img src='.$data->statusImageurl($data->item_status).' id="image-'.$data->item_id.'" alt="Status Image" title='.$data->statusTitle($data->item_status).'>','javascript:void(0)',['id'=>'status',
					'onclick'=>'change("'.$data->item_status.'","'.$data->item_id.'")']);
				}, 
				'filter' =>  \admin\models\VendorItem::Activestatus(),
			],
			[
				'attribute'=>'sort',
				'label'=>'Sort Order',
				'format' => 'raw',
				'value'=>function($data){
					return '<b><input type="hidden" id="hidden_'.$data->item_id.'" value="'.$data->sort.'"><input class="text-center" type="text" value="'.$data->sort.'" onblur="change_sort_order(this.value,'.$data->item_id.')"></b>';
				},
				'contentOptions'=>['class'=>'sort','style'=>'max-width: 100px;'] // <-- right here
			],
			[
				'attribute'=>'item_approved',
				'contentOptions' => ['class' => 'text-center'],
				'headerOptions' => ['class' => 'text-center'],
				'label'=>'Item approved',
				'filter'=>'',
				'value' => function($data) {

					if($data->draftItem && $data->draftItem->is_ready) {
						return 'Pending Admin Approval';
					}elseif($data->draftItem && !$data->draftItem->is_ready){
						return 'Incomplete Draft';
					} elseif($data->item_approved = 'Yes') {
						return 'Approved';
					} elseif($data->item_approved = 'Pending') {
						return 'Pending Admin Approval';
					}else{
						return 'Rejected';
					}
				},
			],
			[
				'attribute'=>'created_datetime',
				'format' => ['date', Yii::$app->params['dateFormat']],
				'label'=>'created date',
				'contentOptions' => ['class' => 'text-center'],
				'headerOptions' => ['class' => 'text-center'],
			],
			[
				'class' => 'yii\grid\ActionColumn',
				'header'=>'Action',
				'template' => ' {view} {update} {delete}',
			],
    	],
    ]); ?>
   
</div>
</div>

<p>
	<?= Html::a('Delete', [''], ['class' => 'btn btn-info','id'=>'Delete','onclick'=>'return Status("Delete")', 'style'=>'float:right;']) ?>
	<?= Html::a('Deactivate', [''], ['class' => 'btn btn-info','id'=>'Deactive','onclick'=>'return Status("Deactivate")', 'style'=>'float:right;']) ?>
	<?= Html::a('Activate', [''], ['class' => 'btn btn-info','id'=>'Reject','onclick'=>'return Status("Activate")', 'style'=>'float:right;']) ?>
</p>

<?php $this->registerJs("

	var csrfToken = $('meta[name=\"csrf-token\"]').attr('content');
	var txt;

	/* Change status for respective vendor items */

	function Status(status){

		var keys = $('#items').yiiGridView('getSelectedRows');
		var pathUrl = '".Url::to(['vendor-item/status'])."';
		if(keys.length == 0) { alert ('Select atleast one item'); return false;}
		var r = confirm(\"Are you sure want to \" +status+ \"?\");
			status = (status=='Activate')?'Active':((status=='Deactivate'))?'Deactive':status;
		if (r == true) {
			$.ajax({
			   url: pathUrl,
			   type : 'POST',
			   data: {keylist: keys, status:status},
			   success : function(data)
			   {
					location.reload(true);
			   }

			});
			return false;
        }
		return false;
    }

    function change(status, id)
	{			
        var path = '".Url::to(['/vendor-item/block'])."';
        
        $.ajax({  
	        type: 'POST',      
	        url: path, //url to be called
	        data: { status: status, id: id, _csrf : csrfToken}, //data to be send
	        success: function(data) {
				var status1 = (status == 'Active') ? 'Deactive' : 'Active'; 
				$('#image-'+id).attr('src',data);
				$('#image-'+id).parent('a').attr('onclick', 
				\"change('\"+status1+\"', '\"+id+\"')\");
	        }
        });
    }
    
	function change_sort_order(sort_val,item_id)
	{
		 var exist_sort = $('#hidden_'+item_id).val();

		 if(sort_val != exist_sort || exist_sort == 0)
		 {
			if(sort_val <= 0 && sort_val != '')
			{
				alert('Please enter greater than 0!');
				return false;
			}

			if(isNumeric(sort_val))
			{
				var csrfToken = $('meta[name=\"csrf-token\"]').attr('content');
				var path = '".Url::to(['vendor-item/sort_vendor_item'])."';
				$.ajax({
					type: 'POST',
					url: path, //url to be called
					data: { sort_val: sort_val,item_id: item_id,_csrf : csrfToken}, //data to be send
					success: function(data) {
						if(data)
						{
							location.reload();
						}
					}
				});
			}
			else
			{
				if(sort_val!='')
				{
					alert('Enter only integer values!');
					return false;
				}
			}
		}
	}

	function isNumeric(n)
	{
		return !isNaN(parseFloat(n)) && isFinite(n);
	}
", View::POS_HEAD);
