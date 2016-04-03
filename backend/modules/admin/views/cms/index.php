<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel common\models\CmsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Static page';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row-fluid">
   <div class="span12">
       <div class="grid simple">
		   <div class="tools"> 
			<?= Html::a('Create static page', ['create'], ['class' => 'btn btn-success']) ?>
			</div>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'page_name',
             [
				'attribute'=>'page_content',
				'label'=>'Page Content',	
				'value' => function ($data) {
					return strip_tags($data->getContent($data->page_id));                      
					},	
			],	
            [
			  'header'=>'page status',			
			  'format' => 'raw',
			  'value'=>function($data) {
				return HTML::a('<img src='.Yii::$app->newcomponent->statusImageurl($data->page_status).' id="image-'.$data->page_id.'" title='.Yii::$app->newcomponent->statusTitle($data->page_status).'>','javascript:void(0)',['id'=>'status', 
				'onclick'=>'change("'.$data->page_status.'","'.$data->page_id.'")']);
				},
			 ],             
			[
				'attribute'=>'created_datetime',
				'format' => ['date', 'php:d/m/Y'],
				'label'=>'created date',			
			],
         ['class' => 'yii\grid\ActionColumn',
            'header'=>'Action',
            'template' => '{view}{update} {delete}{link}',],
        ],
    ]); ?>  
		</div>
	</div>
</div>

<script type="text/javascript">
	function change(status, id)
	{	
		var csrfToken = $('meta[name="csrf-token"]').attr("content");		
        var path = "<?php echo Url::to(['/admin/cms/block']); ?> ";
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
