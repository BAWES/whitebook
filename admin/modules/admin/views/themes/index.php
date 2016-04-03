<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\themesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Themes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="themes-index">
<p>
        <?= Html::a('Create themes', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'theme_name',
             [
             'label'=>'Status',
             'format'=>'raw',
			  'value'=>function($data) {
				return HTML::a('<img src='.Yii::$app->newcomponent->statusImageurl($data->theme_status).' id="image-'.$data->theme_id.'" alt="Status Image" title='.Yii::$app->newcomponent->statusTitle($data->theme_status).'>','javascript:void(0)',['id'=>'status', 
				'onclick'=>'change("'.$data->theme_status.'","'.$data->theme_id.'")']);
				},
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
        var path = "<?php echo Url::to(['/admin/themes/block']); ?> ";
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
