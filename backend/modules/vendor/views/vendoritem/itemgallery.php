<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use kartik\sortable\Sortable;
$this->title = 'Vendor item gallery';
?>
<div class="col-md-8 col-sm-8 col-xs-8" style="height:750px">
<?php $form = ActiveForm::begin(['options'=>['enctype'=>'multipart/form-data']]); ?>

	 <div class="form-group">
		 <span style="color:red"><br><b>Note: You can select single or multiple images</b><br><br></span>		
		<?= $form->field($model, 'image_path[]',['template' => "{label}<div class='controls append_address'>{input}</div> {hint} {error}" 
		])->fileInput(['multiple' => true]) ?>  
		
	 </div> 
	 
	 <div class="form-group" style="width: 150px;">
	<?= Html::submitButton($model->isNewRecord ? 'Upload' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	<?= Html::a('Back', ['vendoritem/index'], ['class' => 'btn btn-default']) ?>
	</div>
			
	<?php if(count($imagedata) > 0) { ?>
		<input type="button" class="check" value="Check all" onclick='return checkall()'/>
		<?php echo '[ <span color:#f90>No of images available : '.count($imagedata).'</span> ]'; ?>
		<?= Html::a('Delete', [''], ['class' => 'btn btn-info','id'=>'Delete','onclick'=>'return changeStatus("imagedelete")', 'style'=>'float:right;']) ?>	
		<?= " [ Use  this  ".Html::img(Yii::getAlias('@web/uploads/app_img/drag_icon.png'), ['id'=>'drag','alt'=>'drag','data-img'=>Yii::getAlias('@web/uploads/app_img/drag_icon.png')])."  to drag image ] ";?> 
		
	</p>
	<div class="superbox" style="height:150px;">  
			<?php 
			 foreach ($imagedata as $image) {
			 $approval = Yii::$app->db->createCommand('Select image_status FROM whitebook_image WHERE image_path="'.$image->image_path.'"');
			 $approval = $approval->queryOne();                 
			?>
			<div class="superbox-list" id="images_<?= $image->image_id;?>">
			<input type="hidden" id="image_id" value="<?php echo $image->image_id;?>">
			<?= Html::img(Yii::getAlias('@web/uploads/vendor_images/').$image->image_path, ['class'=>'superbox-img','width'=>'125px','height'=>'125px','id'=>$image->image_id,'alt'=>'Gallery','data-img'=>Yii::getAlias('@web/uploads/vendor_images/').$image->image_path]);?> 
			<input type="checkbox" name="photo" id="<?= $image->image_path; ?>" value="<?php echo $image->image_id;?>" class="photo_<?php echo $image->image_id; ?>" style="margin:1px !important">
			<?= Html::img(Yii::getAlias('@web/uploads/app_img/drag_icon.png'), ['id'=>'drag_icon','alt'=>'drag','data-img'=>Yii::getAlias('@web/uploads/app_img/drag_icon.png')]);?> 
			</div> 
			<?php } ?>
	</div>
<?php } ?>
<?php ActiveForm::end(); ?>
</div>


<link rel="stylesheet" href="<?php echo Yii::$app->themeURL->createAbsoluteUrl(''); ?>plugins/jquery-superbox/css/style.css" rel="stylesheet" type="text/css" media="screen">
<script src="<?php echo Yii::$app->themeURL->createAbsoluteUrl(''); ?>plugins/jquery-superbox/js/superbox.js" type="text/javascript"></script>

<script type="text/javascript">
$(function() {		
					$('.superbox').SuperBox();		
		});
		
	var csrfToken = $('meta[name="csrf-token"]').attr("content");
	
	$(document).ready(function(){			
		var path = "<?php echo Url::to(['/vendor/vendoritem/imageorder']); ?> ";
	<!-- Begin Sortable images -->		
    $(".superbox").sortable({
        stop : function(event, ui){    
		var newArray = $(this).sortable("toArray",{key:'s'});		
		var id = newArray.filter(function(v){return v!==''});	
		$.ajax({  
        type: 'POST',      
        url: path,
        data: { id: id ,_csrf : csrfToken}, 
        success: function( data ) {
         }          	
		})  
	  }
	})
	$(".superbox").disableSelection();
	<!-- End Sortable images -->
	<!-- Begin Select all checkbox images -->	
	$('.check:button').toggle(function(){ 
        $('input:checkbox').attr('checked','checked');
			$(this).val('Uncheck all');
		},function(){
			$('input:checkbox').removeAttr('checked');
			$(this).val('Check all');        
		});
		return false;
	<!-- End Select all checkbox images -->	
 });
 
 
 
function changeStatus(action){	
	var ids = $("input[name=photo]:checked").map(function() {
    return this.value;
	}).get().join(",");	
	if(ids.length == 0) { alert ('Select atleast one image'); return false;}	
	var loc = $("input[name=photo]:checked").map(function() {
    return $(this).attr('id');
	}).get().join(",");			
	var path = "<?php echo Url::to(['/vendor/vendoritem/']); ?>/"+action;
        $.ajax({  
        type: 'POST',      
        url: path, 
        data: { id: ids ,loc : loc,_csrf : csrfToken},
         success: function(data) {				 		
			if(data == 'Deleted')
			{					
				window.location.reload();		
			}	
			return false;				
         }              
     })  
      return false; 
}
</script>
