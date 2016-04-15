<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\grid\Column;
use yii\grid\DataColumn;
use yii\base;
/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchCustomer */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Customers';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="customer-index">
   <?= Html::a('Create customer', ['create'], ['class' => 'btn btn-success']) ?>
    <?php $a=1; if($count>0){?>
    <?= Html::a('Export customer list', ['/customer/export'], ['class' => 'btn btn-info','id'=>'export', 'style'=>'float:right;']) ?>
    <?php }?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'rowOptions' => function ($data) {
                            if ($data->status($data->customer_id)) {
                                return ['class' => 'danger'];
                            } else {return [];
                            }
                         },
        'filterModel' => $searchModel,
         'showFooter'=>true,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'customer_name',
            'customer_email:email',
             'customer_mobile',
             'created_datetime',
            [
             'label'=>'Status',
             'format'=>'raw',
			  'value'=>function($model) {
				return HTML::a('<img src='.Yii::$app->newcomponent->statusImageurl($model->customer_status).' id="image-'.$model->customer_id.'" alt="Status Image" 
				title='.Yii::$app->newcomponent->statusTitle($model->customer_status).'>','javascript:void(0)',['id'=>'status', 
				'onclick'=>'change("'.$model->customer_status.'","'.$model->customer_id.'")']);
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




<script type="text/javascript">
	function change(status, id)
	{		
		var csrfToken = $('meta[name="csrf-token"]').attr("content");		
        var path = "<?php echo Url::to(['/customer/block']); ?> ";
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
