<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use common\models\Vendor;

$this->title = 'Vendor pending items';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="vendoritem-index">
    <p>
        <?= Html::a('Reject', [''], ['class' => 'btn btn-info','id'=>'Reject','onclick'=>'return approve("Rejected")', 'style'=>'float:right;']) ?>

		<?= Html::a('Approve', [''], ['class' => 'btn btn-info','id'=>'Approve','onclick'=>'return approve("Yes")', 'style'=>'float:right;']) ?>
		
		<?= Html::a('Delete', [''], ['class' => 'btn btn-info','id'=>'Delete','onclick'=>'return Status("Delete")', 'style'=>'float:right;']) ?>

		<?= Html::a('Deactivate', [''], ['class' => 'btn btn-info','id'=>'Deactive','onclick'=>'return Status("Deactivate")', 'style'=>'float:right;']) ?>

		<?= Html::a('Activate', [''], ['class' => 'btn btn-info','id'=>'Reject','onclick'=>'return Status("Activate")', 'style'=>'float:right;']) ?>			
	</p>

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
				'attribute'=>'type_id',
				'label'=>'Item Type',			
				'value'=>function($data){
					return $data->getItemType($data->type_id);
				},
				'filter' => Html::activeDropDownList($searchModel, 'type_id', ArrayHelper::map(common\models\ItemType::find()->where(['!=','trash','Deleted'])->asArray()->all(), 'type_id','type_name'),['class'=>'form-control','prompt' => 'All']),
			],
			[
				'attribute'=>'item_status',
             	'label'=>'Visible On Website',
             	'format'=>'raw',
				'contentOptions' => ['class' => 'text-center'],
				'headerOptions' => ['class' => 'text-center'],
			  	'value' => function($data) {
					return HTML::a('<img src='.$data->statusImageurl($data->item_status).' id="image-'.$data->item_id.'" alt="Status Image" title='.$data->statusTitle($data->item_status).'>','javascript:void(0)',['id'=>'status',
					'onclick'=>'change("'.$data->item_status.'","'.$data->item_id.'")']);
				}, 
				'filter' => \admin\models\VendorItem::Activestatus(),
			
			],
			[
				'class' => 'yii\grid\ActionColumn',
            	'header'=>'Action',
            	'template' => ' {view}',
            	'buttons' => [
            		'view' => function($url, $data) {
            			return HTML::a(
            				'<i class="glyphicon glyphicon-eye-open"></i>', 
            				Url::to(['vendoritem/view', 'id' => $data->item_id]),
            				[
            					'target' => '_blank'
            				]
            			);
            		}
            	]
			],
        ],
    ]); ?>

</div>
<p>

	<?= Html::a('Reject', [''], ['class' => 'btn btn-info','id'=>'Reject','onclick'=>'return approve("Rejected")', 'style'=>'float:right;']) ?>

	<?= Html::a('Approve', [''], ['class' => 'btn btn-info','id'=>'Approve','onclick'=>'return approve("Yes")', 'style'=>'float:right;']) ?>

	<?= Html::a('Delete', [''], ['class' => 'btn btn-info','id'=>'Delete','onclick'=>'return Status("Delete")', 'style'=>'float:right;']) ?>

	<?= Html::a('Deactivate', [''], ['class' => 'btn btn-info','id'=>'Deactive','onclick'=>'return Status("Deactivate")', 'style'=>'float:right;']) ?>

	<?= Html::a('Activate', [''], ['class' => 'btn btn-info','id'=>'Reject','onclick'=>'return Status("Activate")', 'style'=>'float:right;']) ?>			

</p>
<script type="text/javascript">

	var csrfToken = $('meta[name="csrf-token"]').attr("content");		
	var txt;
	
	function approve(status){
						
		var keys = $('#items').yiiGridView('getSelectedRows');		
		var pathUrl = "<?php echo Url::to(['/vendoritem/approve']); ?>";		
		
		if(keys.length == 0) { 
			alert ('Select atleast one item'); 
			return false;
		}

		var r = confirm("Are you sure? Item approve status will be '" +status+ "'.");	
		
		if (r == true) {			
			$.ajax({
			    url: pathUrl, 
			    type : 'POST',			 
			    data: {
			   		keylist : keys, item_approved : status
			    },
			    success : function(data)
			    {
			   		window.location.reload(true); 
			    }
			
			});
			return false;
        }         
		return false;
    }

    function Status(status){
						
		var keys = $('#items').yiiGridView('getSelectedRows');		
		var pathUrl = "<?php echo Url::to(['/vendoritem/status']); ?>";		
		if(keys.length == 0) { alert ('Select atleast one item'); return false;}
		var r = confirm("Are you sure want to " +status+ "?");	
		status = (status=='Activate')?'Active':((status=='Deactivate'))?'Deactive':status;			
		if (r == true) {			
			$.ajax({
			   url: pathUrl, 
			   type : 'POST',			 
			   data: {keylist: keys, status:status},
			   success : function(data)
			   {
			   	window.location.reload(true); 
			   }
			
			});
			return false;
        }         
		return false;
    }
    
    function change(status, id)
	{			
        var path = "<?php echo Url::to(['/vendoritem/block']); ?> ";
        $.ajax({  
        type: 'POST',      
        url: path, //url to be called
        data: { status: status, id: id, _csrf : csrfToken}, //data to be send
        success: function(data) {
			var status1 = (status == 'Active') ? 'Deactive' : 'Active'; 
			$('#image-'+id).attr('src',data);
			$('#image-'+id).parent('a').attr('onclick', 
			"change('"+status1+"', '"+id+"')");
         }
        });
    }
    
</script>

