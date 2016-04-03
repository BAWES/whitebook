<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchCategory */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Category';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-index">
    <p>
        <?= Html::a('Create category', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'category_name',
		   [
				'attribute'=>'sort',
				'label'=>'Sort Order',	
				'format' => 'raw',		
				'value'=>function($data){
					return '<b><input type="hidden" id="hidden_'.$data->category_id.'" value="'.$data->sort.'"><input type="text" value="'.$data->sort.'" onblur="change_sort_order(this.value,'.$data->category_id.')"></b>';
					},
				'contentOptions'=>['class'=>'sort','style'=>'max-width: 100px;'],			
			],	
			[
				'attribute'=>'created_datetime',
				'format' => ['date', Yii::$app->params['dateFormat']],
				'label'=>'created date',			
			],
            [
			  'header'=>'Status',
			 'format' => 'raw',
			  'value'=>function($data) {
				return HTML::a('<img src='.$data->statusImageurl($data->category_allow_sale).' id="image-'.$data->category_id.'" alt="my_image" title='.$data->statusTitle($data->category_allow_sale).'>','javascript:void(0)',['id'=>'status', 
				'onclick'=>'change("'.$data->category_allow_sale.'","'.$data->category_id.'")']);
				},
			 ],
           ['class' => 'yii\grid\ActionColumn',
            'header'=>'Action',
            'template' => ' {update} {delete}{link}',
			],
        ],
    ]); ?>

</div>

<script type="text/javascript">
	function change(status, cid)
	{		
		var csrfToken = $('meta[name="csrf-token"]').attr("content");		
        var path = "<?php echo Url::to(['/admin/category/block']); ?> ";
        $.ajax({  
        type: 'POST',      
        url: path, //url to be called
        data: { status: status, cid: cid, _csrf : csrfToken}, //data to be send
        success: function(data) {
			var status1 = (status == 'yes') ? 'no' : 'yes'; 
			$('#image-'+cid).attr('src',data);
			$('#image-'+cid).parent('a').attr('onclick', 
			"change('"+status1+"', '"+cid+"')");
         }
        });
     }
	 
	 function change_sort_order(sort_val,cat_id)
     {
		 var exist_sort=$('#hidden_'+cat_id).val();
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
				var path = "<?php echo Url::to(['/admin/category/sort_category']); ?> ";
				$.ajax({  
				type: 'POST',      
				url: path, //url to be called
				data: { sort_val: sort_val,cat_id: cat_id,_csrf : csrfToken}, //data to be send
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
