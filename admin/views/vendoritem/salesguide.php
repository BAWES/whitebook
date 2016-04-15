  <!-- Modal -->
<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use dosamigos\fileupload\FileUploadUI;
?>
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button> 
      	 <button type="button" class="kv-file-upload btn btn-xs btn-default" title="Upload file">   <i class="glyphicon glyphicon-upload text-info"></i>
	     </button>    Button to upload each images.   
        </div>
        <div class="modal-body">  		
		<?php $form1 = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

    	<?= $form1->field($model, 'guide_image')->fileInput(['multiple' => true, 'accept' => 'image/*','id'=>'sales_guide_image']) ?>
   		
		<?php ActiveForm::end() ?>	
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
        <?php
         if(!empty($guideimagedata)) {
         	$img= $action = '';
         	foreach ($guideimagedata as $value) { 
			$img .= '"<img src='.Yii::getAlias('@web/uploads/guide_images/').$value->image_path.' width=\'175\'>"'.',';
			$action .='{   	 					        
			        url: "'.Url::to(['/vendoritem/deletesalesimage']).'",
			        key: '.$value->image_id.',       
			    }'.',';
				}
			
			$img = rtrim($img,',');
			$action = rtrim($action,',');
			}
		?>
<script>

$(function() {   
	var qid = <?php echo $question_id; ?>;
	$("#sales_guide_image").fileinput({    	
	showUpload:false,
	showRemove:false,
	<?php if(!empty($guideimagedata)) { ?>
	initialPreview: [
		<?php echo $img; ?>,
		],	

	initialPreviewConfig: [   
	   <?php echo $action; ?>,    
	],  
	<?php } ?>
	overwriteInitial: false, 	
	uploadUrl : "<?php echo Url::to(['/vendoritem/salesguideimage?id="+qid+"']); ?>",	
	});
});
</script>
<style>
.field-vendoritem-guide_image .kv-file-upload { display: inline-block !important;}
</style>