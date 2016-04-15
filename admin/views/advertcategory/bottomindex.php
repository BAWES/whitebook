<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel common\models\AdvertcategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = 'Bottom categories ads';
$this->params['breadcrumbs'][] = $this->title;

            
?>
<div class="row-fluid">
   <div class="span12">
       <div class="grid simple">
		   <div class="tools"> 
			<?= Html::a('Create bottom category ads', ['bottomcreate'], ['class' => 'btn btn-success']) ?>
			</div>
		<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,        
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
           [
			  'header'=>'Category name',			
			  'format' => 'raw',
			  'value'=>function($data) {
				return $data->get_category_name($data->category_id);
				},
			 ],
			 			 
			  [
				'attribute'=>'sort',
				'label'=>'Sort Order',	
				'format' => 'raw',		
				'value'=>function($data){
					return '<b><input type="hidden" id="hidden_'.$data->advert_id.'" value="'.$data->sort.'"><input class="col-md-12" type="text" value="'.$data->sort.'" onblur="change_sort_order(this.value,'.$data->advert_id.')"></b>';
					}	,	'contentOptions'=>['class'=>'sort','style'=>'max-width: 100px;'],					
			],
			[
			  'header'=>'status',			
			  'format' => 'raw',
			  'value'=>function($data) {
				return HTML::a('<img src='.Yii::$app->newcomponent->statusImageurl($data->status).' id="image-'.$data->advert_id.'" title='.Yii::$app->newcomponent->statusTitle($data->status).'>','javascript:void(0)',['id'=>'status', 
				'onclick'=>'change("'.$data->status.'","'.$data->advert_id.'")']);
				},
			 ],
    ['class' => 'yii\grid\ActionColumn',
            'header'=>'Action',
            'template' => '{update} {delete}{link}',
			'buttons' => [
				'update' => function ($url,$data) {
				$url = URL::to(['/advertcategory/bottomupdate/?id='.$data['advert_id']]);
				return Html::a(
					'<span class="glyphicon glyphicon-pencil"></span>',$url);
					},
				'delete' => function ($url,$data) {
				$url = URL::to(['/advertcategory/bottomdelete/?id='.$data['advert_id']]);
				return Html::a(
					'<span class="glyphicon glyphicon-trash"></span>',$url,['title'=>'Delete','data-confirm'=>'Are you sure you want to delete this item?']);
					},
				],
			], 
    ],
    ]); ?>
		</div>
	</div>
</div>
<script type="text/javascript">
	function change(status, aid)
	{		
		var csrfToken = $('meta[name="csrf-token"]').attr("content");		
        var path = "<?php echo Url::to(['/advertcategory/block']); ?> ";
        $.ajax({  
        type: 'POST',      
        url: path, //url to be called
        data: { status: status, aid: aid, _csrf : csrfToken}, //data to be send
        success: function(data) {
			var status1 = (status == 'Active') ? 'Deactive' : 'Active'; 
			$('#image-'+aid).attr('src',data);
			$('#image-'+aid).parent('a').attr('onclick', 
			"change('"+status1+"', '"+aid+"')");
         }
        });
     }
	 
	 
	 
	 function change_sort_order(sort_val,advert_id)
     {
		 
		 var exist_sort=$('#hidden_'+advert_id).val();
		 if(sort_val!=exist_sort || exist_sort==0)
		 {
			if(sort_val<=0 && sort_val!='')
			{
				$('#hidden_'+advert_id).next(':input').val(exist_sort);
				alert("Please enter greater than 0!");
				return false;
			}
			
			if(isNumeric(sort_val))
			{
				var csrfToken = $('meta[name="csrf-token"]').attr("content");		
				var path = "<?php echo Url::to(['/advertcategory/sort_banner']); ?> ";
				$.ajax({  
				type: 'POST',      
				url: path, //url to be called
				data: { sort_val: sort_val,advert_id: advert_id,_csrf : csrfToken}, //data to be send
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
