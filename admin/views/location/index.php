<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\web\View;

$this->title = 'Area';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="row-fluid">
   <div class="span12">
       <div class="grid simple">
		   <div class="tools">   
			<?= Html::a('Create Area', ['create'], ['class' => 'btn btn-success']) ?>
		   </div>

		<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
           /*[
            'label'=>'country name',
            'value'=>'country.country_name',
            ],*/
            'cityName',
            'location',
            'location_ar',
            [
			  'header'=>'country status',
			  'format' => 'raw',
			  'value'=>function($data) {
				return HTML::a('<img src='.$data->statusImageurl($data->status).' id="image-'.$data->id.'" title='.$data->statusTitle($data->status).'>','javascript:void(0)',['id'=>'status', 
				'onclick'=>'change("'.$data->status.'","'.$data->id.'")']);
				},
			 ],  

            ['class' => 'yii\grid\ActionColumn',
            'header'=>'Action',
            'template' => ' {update} {delete}'],
        ],
    ]); ?>
		</div>
	</div>
</div>

<?php 

$this->registerJs("

    function change(status, lid)
    {               
        var csrfToken = $('meta[name=\"csrf-token\"]').attr('content');       
        var path = '".Url::to(['/location/block'])."';
        
        $.ajax({  
            type: 'POST',      
            url: path, //url to be called
            data: { status: status, lid: lid, _csrf : csrfToken}, //data to be send
            success: function(data) {
                var status1 = (status == 'Active') ? 'Deactive' : 'Active'; 
                $('#image-'+lid).attr('src',data);
                $('#image-'+lid).parent('a').attr('onclick', 
                \"change('\" + status1 + \"', '\" + lid + \"')\");
            }
        });
    }

", View::POS_HEAD);

	