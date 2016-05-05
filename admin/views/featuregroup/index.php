<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel common\models\FeaturegroupSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Feature groups';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="featuregroup-index">
    <p>
        <?= Html::a('Create feature group', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'group_name',
            [
             'label'=>'Status',
             'format'=>'raw',
			  'value'=>function($data) {
				return HTML::a('<img src='.$data->statusImageurl($data->group_status).' id="image-'.$data->group_id.'" alt="Status Image" title='.Yii::$app->newcomponent->statusTitle($data->group_status).'>','javascript:void(0)',['id'=>'status', 
				'onclick'=>'change("'.$data->group_status.'","'.$data->group_id.'")']);
				},
			],
			[
				'attribute'=>'created_datetime',
				'format' => ['date', 'php:d/m/Y'],
				'label'=>'created date',			
			],
             ['class' => 'yii\grid\ActionColumn',
            'header'=>'Action',
            'template' => '{update} {delete}{link}',],
        ],
    ]); ?>

</div>

<script type="text/javascript">
	function change(status, id)
	{		
		var csrfToken = $('meta[name="csrf-token"]').attr("content");		
        var path = "<?php echo Url::to(['/featuregroup/block']); ?> ";
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
