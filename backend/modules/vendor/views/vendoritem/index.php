<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\VendoritemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = 'Manage items';
$this->params['breadcrumbs'][] = $this->title;
?>
<?= Html::csrfMetaTags() ?>
<div class="vendoritem-index">
<p>
        <?= Html::a('Create item', ['create'], ['class' => 'btn btn-success']) ?>
        
       <?= Html::a('Delete', [''], ['class' => 'btn btn-info','id'=>'Delete','onclick'=>'return Status("Delete")', 'style'=>'float:right;']) ?>			
        
        <?= Html::a('Deactivate', [''], ['class' => 'btn btn-info','id'=>'Deactive','onclick'=>'return Status("Deactivate")', 'style'=>'float:right;']) ?>			
        
		<?= Html::a('Activate', [''], ['class' => 'btn btn-info','id'=>'Reject','onclick'=>'return Status("Activate")', 'style'=>'float:right;']) ?>			

    </p>
    
	<?php Pjax::begin(['enablePushState' => false]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'id'=>'items',
        'columns' => [
			[ 'class' => 'yii\grid\CheckboxColumn'],
            ['class' => 'yii\grid\SerialColumn'],
            'item_name',
            [
				'attribute'=>'category_id',
				'label'=>'Category Name',			
				'value'=>function($data){
					return $data->getCategoryName($data->category_id);
					},	
					
				'filter' => $vendor_category,						
			],
           
            
			[
				'attribute'=>'type_id',
				'label'=>'Item Type',			
				'value'=>function($data){
					return $data->getItemType($data->type_id);
					},
				'filter' => Html::activeDropDownList($searchModel, 'type_id', ArrayHelper::map(backend\models\Itemtype::find()->where(['!=','trash','Deleted'])->asArray()->all(), 'type_id','type_name'),['class'=>'form-control','prompt' => 'All']),																
			],
     [
             'label'=>'Status',
             'format'=>'raw',
			  'value'=>function($data) {
				return HTML::a('<img src='.$data->statusImageurl($data->item_status).' id="image" alt="Status Image" title='.Yii::$app->newcomponent->statusTitle($data->item_status).'>','#',['id'=>'status']);
				},
			 'filter' => Yii::$app->newcomponent->Activestatus(),
			],
			[
				'attribute'=>'sort',
				'label'=>'Sort Order',	
				'format' => 'raw',		
				'value'=>function($data){
					return '<b><input type="hidden" id="hidden_'.$data->item_id.'" value="'.$data->sort.'"><input type="text" value="'.$data->sort.'" onblur="change_sort_order(this.value,'.$data->item_id.')"></b>';
					},
				 'contentOptions'=>['class'=>'sort','style'=>'max-width: 100px;'] // <-- right here
			],	
   [
				'attribute'=>'item_approved',				
				'label'=>'Item approved',
				'filter'=>'',			
			],	
		
			[
				'attribute'=>'created_datetime',
				'format' => ['date', DATE],
				'label'=>'created date',			
			],	
			['class' => 'yii\grid\ActionColumn',
            'header'=>'Action',
            'template' => ' {view} {update} {delete}',
 	],
        ],
    ]); ?>
     <?php Pjax::end(); ?>

</div>
</div>

<p>       
        <?= Html::a('Delete', [''], ['class' => 'btn btn-info','id'=>'Delete','onclick'=>'return Status("Delete")', 'style'=>'float:right;']) ?>			
        
        <?= Html::a('Deactivate', [''], ['class' => 'btn btn-info','id'=>'Deactive','onclick'=>'return Status("Deactivate")', 'style'=>'float:right;']) ?>			
        
		<?= Html::a('Activate', [''], ['class' => 'btn btn-info','id'=>'Reject','onclick'=>'return Status("Activate")', 'style'=>'float:right;']) ?>			
</p>
<script type="text/javascript">
	var csrfToken = $('meta[name="csrf-token"]').attr("content");		
	var txt;
	
	/* Change status for respective vendor items */
	
		function Status(status){
						
		var keys = $('#items').yiiGridView('getSelectedRows');		
		var pathUrl = "<?php echo Url::to(['/vendor/vendoritem/status']); ?>";		
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
					location.reload(true); 
			   }
			
			});
			return false;
        }         
		return false;
    }
    
	function change(status, id)
	{				
        var path = "<?php echo Url::to(['/vendor/vendoritem/block']); ?> ";
        $.ajax({  
        type: 'POST',      
        url: path, //url to be called
        data: { status: status, id: id,_csrf : csrfToken}, //data to be send
        success: function(data) {	
         }
        });
     }
     
     function change_sort_order(sort_val,item_id)
     {
		 var exist_sort=$('#hidden_'+item_id).val();
		 if(sort_val!=exist_sort || exist_sort==0)
		 {
			if(sort_val<=0 && sort_val!='')
			{
				alert("Please enter greater than 0!");
				return false;
			}
			
			if(isNumeric(sort_val))
			{
				var csrfToken = $('meta[name="csrf-token"]').attr("content");		
				var path = "<?php echo Url::to(['/vendor/vendoritem/sort_vendor_item']); ?> ";
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
					alert("Enter only integer values!");
					return false;
				}
			}
		}
	 }
	function isNumeric(n)
	{
		return !isNaN(parseFloat(n)) && isFinite(n);
	}
</script>
