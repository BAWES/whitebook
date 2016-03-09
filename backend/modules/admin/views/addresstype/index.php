<?php
use yii\widgets\Pjax;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;


use backend\models\Addresstype;
use backend\models\AddresstypeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Query;
use yii\base;
use yii\data\ActiveDataProvider;



/* @var $this yii\web\View */
/* @var $searchModel backend\models\AddresstypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Address type';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="addresstype-index">
	 <p>
    <div class="row-fluid">
       <div class="span12">
         <div class="grid simple ">
        <div class="tools">
        <?= Html::a('Create address type', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

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
				src='.Yii::$app->newcomponent->statusImageurl($data->status).' id="image-'.$data->type_id.'" title='.Yii::$app->newcomponent->statusTitle($data->status).'>','javascript:void(0)',['id'=>'status',
				'onclick'=>'change("'.$data->status.'","'.$data->type_id.'")']);
				},
			 ],
			 
            [
				'attribute'=>'created_datetime',
				'format' => ['date', DATE],
				'label'=>'created date',			
			],
			['class' => 'yii\grid\ActionColumn',
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





<script type="text/javascript">
	function change1(status, id)
	{		
		var csrfToken = $('meta[name="csrf-token"]').attr("content");		
        var path = "<?php echo Url::to(['/addresstype/block']); ?> ";
        $.ajax({  
        type: 'POST',      
        url: path, //url to be called
        data: { status: status, id: id,_csrf : csrfToken}, //data to be send
        success: function(data) {	
			$.pjax.reload({container:'#medicine'});  //Reload GridView
			//$('#grid').yiiGridView('update');
		//$.fn.yiiGridView.update('#medicine');
			//$('a#status').attr("src", data);		
           
         }
        });
     }



	function change(status, cid)
	{				
		var csrfToken = $('meta[name="csrf-token"]').attr("content");		
        var path = "<?php echo Url::to(['/admin/addresstype/block']); ?> ";
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
</script>


