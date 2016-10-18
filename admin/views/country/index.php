<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

$this->title = 'Countries';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="row-fluid">
   <div class="span12">
       <div class="grid simple">
		   <div class="tools"> 
			<?= Html::a('Create country', ['create'], ['class' => 'btn btn-success']) ?>
		   </div>

		<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'country_name',
            'iso_country_code',       
             [
			  'header'=>'country status',
			  'format' => 'raw',
			  'value'=>function($data) {
				return HTML::a('<img 
				src='.$data->statusImageurl($data->country_status).' id="image-'.$data->country_id.'" title='.$data->statusTitle($data->country_status).'>','javascript:void(0)',['id'=>'status',
				'onclick'=>'change("'.$data->country_status.'","'.$data->country_id.'")']);
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
        var path = "<?php echo Url::to(['/country/block']); ?> ";
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
