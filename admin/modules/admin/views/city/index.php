
<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $searchModel backend\models\CitySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Governorate';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row-fluid">
   <div class="span12">
       <div class="grid simple">
		   <div class="tools"> 
			<?= Html::a('Create Governorate', ['create'], ['class' => 'btn btn-success']) ?>
			</div>	
		<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
         
            [
				'attribute'=>'country_id',
				'label'=>'Country Name',			
				'value'=>function($data){
					return $data->getCountryName($data->country_id);
					}				
			],
            'city_name',
            [
			  'header'=>'status',
			  'format' => 'raw',
			  'value'=>function($data) {
				return HTML::a('<img src='.Yii::$app->newcomponent->statusImageurl($data->status).' id="image-'.$data->city_id.'" title='.Yii::$app->newcomponent->statusTitle($data->status).'>','javascript:void(0)',['id'=>'status', 
				'onclick'=>'change("'.$data->status.'","'.$data->city_id.'")']);
				},
			 ],                
            ['class' => 'yii\grid\ActionColumn',
            'header'=>'Action',],
        ],
    ]); ?>   
		</div>
	</div>
</div>

<script type="text/javascript">
	function change(status, cid)
	{				
		var csrfToken = $('meta[name="csrf-token"]').attr("content");		
        var path = "<?php echo Url::to(['/admin/city/block']); ?> ";
        $.ajax({  
        type: 'POST',      
        url: path, //url to be called
        data: { status: status, cid: cid, _csrf : csrfToken}, //data to be send
        success: function(data) {
			var status1 = (status == 'Active') ? 'Deactive' : 'Active'; 
			$('#image-'+cid).attr('src',data);
			$('#image-'+cid).parent('a').attr('onclick', 
			"change('"+status1+"', '"+cid+"')");
         }
        });
     }
	 
</script>
