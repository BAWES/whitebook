<?php 	
use yii\helpers\Url;
use yii\helpers\Html;
use common\models\Image;
use common\models\VendorItemQuestion;
use common\models\VendorItemQuestionGuide;

$count_q = (count($question)); 
	
$t=0;	 
	 
?>

<table class="table table-striped table-bordered detail-view">
	<tbody>
	<?php foreach($question as $question_records) {	?>
	  	<tr><th> Question :<?= ucfirst($question_records['question_text']);?></th><th></th></tr>
		<tr><th> Question Type :<?= ucfirst($question_records['question_answer_type']);?></th><th></th></tr>
		  
		<!-- Begin Append selected option values -->
		<?php 

		if(!empty($answers)) {

			if($question_records['question_answer_type']=='selection') { ?>
			
				<tr><th>Selection</th><th>Price</th></tr>
			
				<?php foreach($answers as $values){ ?>	 			
				<tr><td><?= ucfirst($values['answer_text']); ?></td><td><?= ucfirst($values['answer_price_added']); ?></td></tr>
				<?php 
				}//foreach 
				
			} else if($question_records['question_answer_type']=='text') { ?>
			
		<tr><th>Text </th><th></th></tr>
		
		<?php foreach($answers as $values){ ?>
		<tr><td colspan="2"><?= ucfirst($values['answer_price_added']) ?></td></tr>
		<?php } ?>

		<?php }  else if($question_records['question_answer_type']=='image') { 	?>
			<tr><th>Image </th><th></th></tr>
			<tr>
				<td colspan="2">
					<div class="admin" style="text-align: center;  float: left;  width: 15%;padding:3%;">	
					<?php					
						foreach($answers as $values){
						
				 		$exist_image = image::find()->where( [ 'image_id' => $values['guide_image_id'],'module_type'=>'guides' ] )->one();		
				 		if(!empty($exist_image))
				 		{
				 		echo Html::img(Yii::getAlias('@web/uploads/vendor_images/').$exist_image['image_path'], ['class'=>'','width'=>'125px','height'=>'125px','alt'=>'Logo1'.$exist_image['image_path']]);
				 		}
					?>
					</div>
				</td>
			</tr>
		<?php  }  } } ?>
		<!-- End Append selected option values -->

<?php $t++; } ?>

</tbody>
</table>

<?php $this->registerJs("
	function viewQuestion(q_id,tis)
	{	
		
		var question_id_append = q_id - 1; 
		var path = '".Url::to(['/admin/vendoritem/renderanswer'])."';
		
		$.ajax({
			type : 'POST',
			url :  path,
			data: {q_id :q_id }, 
	        success: function( data ) {         
	        	$(this).closest('.question-section').after(data);   	
	        }
		});		
	}
");
