<?php 	
use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\View;
use common\models\Image;
use common\models\VendorItemQuestion;
use common\models\VendorItemQuestionGuide;

	  $count_q=(count($question)); // for initial count questions used in javascript

	 $t=0;	 
	 ?>
	 <?php
	 foreach($question as $question_records)
	 {			
	 	
	?>
	  <div class="question-section" id="question-section_<?= $question_records['question_id'];?>"> 
	  <input type="hidden" name="VendorItemQuestion[<?= $t;?>][update][]" value="<?= $question_records['question_id']; ?>">
		Question <input type="text" id="question_text_<?= $t; ?>" class="form-control" name="VendorItemQuestion[<?= $t;?>][question_text][]" style="margin:10px 0px;" value="<?= $question_records['question_text']; ?>">
		
		 Question Type
		
		  <div class="append_address">
	    	<select id="vendoritemquestion-question_answer_type<?= $t; ?>" class="form-control vendoritemquestion-question_answer_type" name="VendorItemQuestion[<?= $t;?>][question_answer_type][]" style="margin: 10px 0px;">
			<option value="">Choose type</option>
			<option value="text" value="<?= $question_records['question_answer_type'] == 'text' ? '"selected=selected"' : '';?>">Text</option>
			<option value="image" value="<?= $question_records['question_answer_type'] == 'image' ? '"selected=selected"' : '';?>">Image</option>
			<option value="selection" value="<?= $question_records['question_answer_type'] == 'selection' ? '"selected=selected"' : '';?>">Selection</option></select>
						
			<!-- Begin Append selected option values -->
			
			<?php			   				

			 	if(!empty($answers))
			 	{				

					if($question_records['question_answer_type']=='selection') {
					$j=0;
					foreach($answers as $values){ 
					?>	 			
				<div class="selection">			
				<input type="text" class="form-control" name="VendorItemQuestion[<?= $t;?>][text][]" placeholder="Question" id="question" value="<?= $values['answer_text']; ?>"  style="width:40%;float:left;" >

				
				<input type="text" class="form-control" name="VendorItemQuestion[<?= $t;?>][price][]" placeholder="Price(Optional)" id="price" value="<?= $values['answer_price_added'] ?>" style="width:40%;float:left;">
				<!-- hidden field -->
				<?php if($j!=0) { ?>
				<img src="<?php echo Yii::$app->params['appImageUrl'].'remove.png'; ?>" id="<?= $values['answer_id']; ?>" class="selection_delete" onclick="deletequestionselection(this)">
				<?php }		?>
				<input type="hidden" value="<?= $values['answer_id']; ?>" name="VendorItemQuestion[<?= $t;?>][hidden][]" style="width:5%;float:left;" class="form-control answer">
				
				<!-- BEGIN if answer exists displaying view button else add button -->
				</div>		
				<?php $j++; } ?>	
				<input type="button" class="add_question" id="add_question" data-name="VendorItemQuestion[0][text][0][]" data-parent value="Add Selection">
				<input type="button" class="save" name="save" value="Save" onclick="savequestion('selection','<?= $question_records['question_id']; ?>',this)">
				<input type="button" id="<?= $question_records['question_id'];?>" class="saves" data-toggle="modal" data-target="#myModal" value="Guide image"  onclick="checkupload(this)">
				<div class="question_success">Successfully added</div>
				<!-- If it's text -->

				<?php  } else if($question_records['question_answer_type']=='text') {
						foreach($answers as $values){
				 ?>

				<div class="price_val">
				<!-- hidden field -->
				<input type="hidden" name="VendorItemQuestion[0][hidden][]" value="<?= $values['answer_id']; ?>" style="width:5%;float:left;" class="form-control answer">
				<input type="text" style="width:40%;" id="price" placeholder="Price (Optional)" name="VendorItemQuestion[0][price][]" value="<?= $values['answer_price_added'] ?>" class="form-control">
				<input type="button" onclick="savequestion('text',0,this)" value="Save" name="save" class="savebutton">				
				<input type="button" id="<?= $question_records['question_id'];?>" class="saves" data-toggle="modal" data-target="#myModal" value="Guide image"  onclick="checkupload(this)">
				<div class="question_success">Successfully added</div>
				</div>
				
			<?php } }  else if($question_records['question_answer_type']=='image') { 	?>
				<div class="admin" style="text-align: center;  float: left;  width: 100%;">				
					<?php
						foreach($answers as $values){
							
				 		$exist_image = image::find()->where( [ 'image_id' => $values['guide_image_id'], 'module_type'=>'guides'] )->one();		
				 		if(!empty($exist_image))
				 		{
				 		echo Html::img(Yii::getAlias('@web/uploads/vendor_images/').$exist_image['image_path'], ['class'=>'','width'=>'125px','height'=>'125px','alt'=>'Logo']);
				 		}
				 			 				
					 ?>					 
				
				<?php } ?>
				</div>
				<input type="button" id="<?= $question_records['question_id'];?>" class="saves" data-toggle="modal" data-target="#myModal" value="Guide image"  onclick="checkupload(this)">
				<?php  }  } ?>
				

			<!-- End Append selected option values -->						
 		</div>
 			<input type="button" style="float:right; margin:0px 5px 5px 0px;" class="delete_<?= $t;?>" onclick=deleteAddress('<?= $question_records['question_id']; ?>',this) value="Delete">
			<input type="hidden" style="float:right; margin:0px 5px 5px 0px;" class="hide_<?= $t;?>" onclick=hideQuestion("hide_<?= $t;?>",this) value="Hide">
 	</div>
 	

<?php $t++; } ?>

<?php 

$this->registerJs("

	function viewQuestion(q_id,tis)
	{		
		var question_id_append = q_id - 1; 
		var path = '".Url::to(['/vendor-item/renderanswer'])."';

		$.ajax({
			type : 'POST',
			url :  path,
			data: { q_id : q_id }, 
	        success: function( data ) {         
	        	$(tis).closest('.question-section').after(data);   	
	        }
		});	
	}
	
", View::POS_HEAD);

