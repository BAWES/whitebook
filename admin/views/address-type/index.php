<?php

use yii\base;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\web\View;
use common\models\AddressTypeSearch;

/* @var $this yii\web\View */
/* @var $searchModel common\models\AddressTypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Address type';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="addresstype-index">
	<div class="row-fluid">
        <div class="span12">
        	<div class="grid simple ">
        		<div class="tools">
					<?= Html::a('Create address type', ['create'], ['class' => 'btn btn-success']) ?>
					<?= GridView::widget([
						'dataProvider' => $dataProvider,
						'filterModel' => $searchModel,
						'columns' => [
							['class' => 'yii\grid\SerialColumn'],
						   'type_name',
							[
							  'header'=>'Status',
							  'format' => 'raw',
							  'value'=>function($data) {
								return HTML::a('<img
								src='.$data->statusImageurl($data->status).' id="image-'.$data->type_id.'" title='.$data->statusTitle($data->status).'>','javascript:void(0)',['id'=>'status',
									'onclick'=>'changeStatus("'.$data->status.'","'.$data->type_id.'")']);
								},
							],
							[
								'attribute'=>'created_datetime',
								'format' => ['date', Yii::$app->params['dateFormat']],
								'label'=>'created date',
							],
							[
								'class' => 'yii\grid\ActionColumn',
								'header'=>'Action',
								'template' => ' {update} {delete}{link}',
							],
						],
					]); ?>
       			</div>
     		</div>
   		</div>
	</div>
</div>

<?php 

$this->registerJs("

	function changeStatus(status, cid)
    {
		var csrfToken = jQuery('meta[name=\"csrf-token\"]').attr('content');
		var path = '".Url::to(['/address-type/block'])."';
	
		jQuery.post(
			path, 
			{ 
				status: status, cid: cid, _csrf : csrfToken 
			}, 
			function(data) {
				
				if(status == 'Active') {
					var status1 =  'Deactive';
				}else{
					var status1 =  'Active';
				}

				jQuery('#image-'+cid).attr('src',data);
				jQuery('#image-'+cid).parent('a').attr('onclick', 'changeStatus(\"' + status1 + '\", ' + cid + ')');
			}
		);
     }

", View::POS_HEAD);
