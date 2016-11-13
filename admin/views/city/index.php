<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\web\View;

$this->title = 'Governorate';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="row-fluid">
   <div class="span12">
       <div class="grid simple">
		   <div class="tools"><?= Html::a('Create Governorate', ['create'], ['class' => 'btn btn-success']) ?></div>
				<?= GridView::widget(
					[
						'dataProvider' => $dataProvider,
						'filterModel' => $searchModel,
						'columns' => [
							['class' => 'yii\grid\SerialColumn'],
							'city_name',
							'city_name_ar',
							[
								'header'=>'status',
								'format' => 'raw',
								'value'=>function($data) {
									return HTML::a('<img src='.$data->statusImageurl($data->status).' id="image-'.$data->city_id.'" title='.$data->statusTitle($data->status).'>','javascript:void(0)',['id'=>'status', 'onclick'=>'change("'.$data->status.'","'.$data->city_id.'")']);
								},
							],
							[
								'class' => 'yii\grid\ActionColumn',
								'header'=>'Action',
								'template' => ' {update} {delete}'
							],
						],
					]
				); ?>
		</div>
	</div>
</div>

<?php 

$this->registerJs("

function change(status, cid)
{
	var csrfToken = $('meta[name=\"csrf-token\"]').attr('content');
	var path = '".Url::to(['/city/block'])."';
	$.ajax({
		type: 'POST',
		url: path, //url to be called
		data: { status: status, cid: cid, _csrf : csrfToken}, //data to be send
		success: function(data) {
			var status1 = (status == 'Active') ? 'Deactive' : 'Active';
			$('#image-'+cid).attr('src',data);
			$('#image-'+cid).parent('a').attr('onclick', \"change('\"+status1+\"', '\"+cid+\"')\");
		}
	});
}

", View::POS_HEAD);