<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SlideSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Slides';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="slide-index">
<p>
        <?= Html::a('Create slide', ['create'], ['class' => 'btn btn-success']) ?>       
        
        <?= Html::a('Delete', [''], ['class' => 'btn btn-info','id'=>'Delete','onclick'=>'return Status("Delete")', 'style'=>'float:right;']) ?>			
        
        <?= Html::a('Deactivate ', [''], ['class' => 'btn btn-info','id'=>'Deactive','onclick'=>'return Status("Deactive")', 'style'=>'float:right;']) ?>			
        
		<?= Html::a('Activate', [''], ['class' => 'btn btn-info','id'=>'Reject','onclick'=>'return Status("Active")', 'style'=>'float:right;']) ?>			
</p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'id'=>'slides',
        'columns' => [
			[ 'class' => 'yii\grid\CheckboxColumn'],
            ['class' => 'yii\grid\SerialColumn'],
            'slide_title',
            [
			  'header'=>'Slide Image',
			  'format' => 'raw',
			  'value'=>function($data) {
				  if(file_exists($_SERVER['DOCUMENT_ROOT'].'/backend/web/uploads/banner_images/banner_'.$data->slide_id.'.png'))
				  {
					  return '<a href="" data-target="#banner_image_'.$data->slide_id.'" data-toggle="modal"><img src="'.Yii::$app->request->hostInfo.'/backend/web/uploads/banner_images/banner_'.$data->slide_id.'.png" width="100" height="70"></a>
					  <div id="banner_image_'.$data->slide_id.'" class="modal fade" role="dialog" data-keyboard="true">
  <div class="modal-dialog">
    <div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">'.ucfirst($data->slide_title).'</h4>
      </div>
      <div class="modal-body" style="background-color:#fff">
        <img src="'.Yii::$app->request->hostInfo.'/backend/web/uploads/banner_images/banner_'.$data->slide_id.'.png" style="width: 100%;height: 100%;">
      </div>
    </div>
  </div>
</div> ';
				  }
				  else if(file_exists($_SERVER['DOCUMENT_ROOT'].'/backend/web/uploads/banner_images/banner_'.$data->slide_id.'.mp4'))
				  {
					  return '<video width="200" height="150" controls>
  <source src="'.Yii::$app->request->hostInfo.'/backend/web/uploads/banner_images/banner_'.$data->slide_id.'.mp4"'. 'type="video/mp4"> </video>';
				  }
				  else if(file_exists($_SERVER['DOCUMENT_ROOT'].'/backend/web/uploads/banner_images/banner_'.$data->slide_id.'.avi'))
				  {
					  return '<video width="200" height="150" controls>
  <source src="'.Yii::$app->request->hostInfo.'/backend/web/uploads/banner_images/banner_'.$data->slide_id.'.avi"'. 'type="video/avi"> </video>';
					  
				  }
				},
			 ],  
            'slide_video_url:ntext',
            'slide_url:url',
            			 [
				'attribute'=>'sort',
				'label'=>'Sort Order',	
				'format' => 'raw',		
				'value'=>function($data){
					return '<b><input type="hidden" id="hidden_'.$data->slide_id.'" value="'.$data->sort.'"><input class="col-md-12" type="text" value="'.$data->sort.'" onblur="change_sort_order(this.value,'.$data->slide_id.')"></b>';
					}				
			],
			 [
			  'header'=>'status',
			  'format' => 'raw',
			  'value'=>function($data) {
				return HTML::a('<img src='.Yii::$app->newcomponent->statusImageurl($data->slide_status).' id="image-'.$data->slide_id.'" title='.Yii::$app->newcomponent->statusTitle($data->slide_status).'>','javascript:void(0)',['id'=>'status', 
				'onclick'=>'change("'.$data->slide_status.'","'.$data->slide_id.'")']);
				},
			 ], 
			 [
				'attribute'=>'created_datetime',
				'format' => ['date', Yii::$app->params['dateFormat']],
				'label'=>'created date',			
			],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
	   </div>
	 </div>
   </div>

</div>




<script type="text/javascript">
	$(document).on('keyup',function(evt) {
    if (evt.keyCode == 27) {
       $('.close').click();
    }
});
	var csrfToken = $('meta[name="csrf-token"]').attr("content");		
	var txt;
	
	/* Change status for respective Banners */
	
		function Status(status){		
		var keys = $('#slides').yiiGridView('getSelectedRows');		
		var pathUrl = "<?php echo Url::to(['/admin/slide/status']); ?>";		
		if(keys.length == 0) { alert ('Select atleast one item'); return false;}
		var r = confirm("Are you sure want to " +status+ "?");				
		if (r == true) {			
			$.ajax({
			   url: pathUrl, 
			   type : 'POST',			 
			   data: {keylist: keys, status:status},
			   success : function(data)
			   {				  
					window.location.reload(true); 
			   }
			
			});
			return false;
        }         
		return false;
    }
    
	function change(status, cid)
	{			
		var csrfToken = $('meta[name="csrf-token"]').attr("content");		
        var path = "<?php echo Url::to(['/admin/slide/block']); ?> ";
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
	
	function change_sort_order(sort_val,slide_id)
     {
		 var exist_sort=$('#hidden_'+slide_id).val();
		 if(sort_val!=exist_sort || exist_sort==0)
		 {
			if(sort_val<=0 && sort_val!='')
			{
				$('#hidden_'+slide_id).next(':input').val(exist_sort);
				alert("Please enter greater than 0!");
				return false;
			}
			
			if(isNumeric(sort_val))
			{
				var csrfToken = $('meta[name="csrf-token"]').attr("content");		
				var path = "<?php echo Url::to(['/admin/slide/sort_slide']); ?> ";
				$.ajax({  
				type: 'POST',      
				url: path, //url to be called
				data: { sort_val: sort_val,slide_id: slide_id,_csrf : csrfToken}, //data to be send
				success: function(data) {
					if(data)
					{
						location.reload();
					}
				 }
				});
			}
			else
			{
				if(sort_val!='')
				{
					alert("Enter only integer values!");
					return false;
				}
			}
		}
	 }
	 function isNumeric(n)
	{
		return !isNaN(parseFloat(n)) && isFinite(n);
	}

</script>
