<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel common\models\FeaturegroupitemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Feature group items';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="featuregroupitem-index">
<p>
        <?= Html::a('Create feature group item', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
    [
				'attribute'=>'item_id',
				'label'=>'Group Name',			
				'value'=>function($data){
					return $data->getGroupName($data->group_id);
					}				
			],
            [
				'attribute'=>'featured_start_date',
				'format' => ['date', 'php:d/m/Y'],
				'label'=>'Feature start date',			
			],			
            [
				'attribute'=>'featured_end_date',
				'format' => ['date', 'php:d/m/Y'],
				'label'=>'Feature start date',			
			],
			 [
				'attribute'=>'featured_sort',
				'label'=>'Sort Order',	
				'format' => 'raw',		
				'value'=>function($data){
					return '<b><input type="hidden" id="hidden_'.$data->featured_id.'" value="'.$data->featured_sort.'"><input type="text" value="'.$data->featured_sort.'" onblur="change_sort_order(this.value,'.$data->featured_id.')"></b>';
					},
				'contentOptions'=>['class'=>'sort','style'=>'max-width: 100px;']					
			],
			[
             'label'=>'Status',    
             'format'=>'raw',                 
			  'value'=>function($data) {
				return HTML::a('<img src='.$data->statusImageurl($data->group_item_status).' id="image-'.$data->featured_id.'" alt="Status Image" title='.$data->statusTitle($data->group_item_status).'>','javascript:void(0)',['id'=>'status', 
				'onclick'=>'change("'.$data->group_item_status.'","'.$data->featured_id.'")']);
				}
				
			
			],
			[
				'attribute'=>'created_datetime',
				'format' => ['date', 'php:d/m/Y'],
				'label'=>'created date',			
			],
  ['class' => 'yii\grid\ActionColumn',
            'header'=>'Action',
            'template' => '{update} {delete}{link}',
            ],
        ],
    ]); ?>

</div>
<script type="text/javascript">
	
	function change(status, id)
	{		
		var csrfToken = $('meta[name="csrf-token"]').attr("content");		
        var path = "<?php echo Url::to(['/featuregroupitem/block']); ?> ";
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
     
function change_sort_order(sort_val,featured_id)
     {
		 var exist_sort=$('#hidden_'+featured_id).val();
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
				var path = "<?php echo Url::to(['/featuregroupitem/sort_feature_group']); ?> ";
				$.ajax({  
				type: 'POST',      
				url: path, //url to be called
				data: { sort_val: sort_val,featured_id: featured_id,_csrf : csrfToken}, //data to be send
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
