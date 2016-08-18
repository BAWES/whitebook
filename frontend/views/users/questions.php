<?php foreach($questions as $question) { ?>

	<div class="form-group">
		<label class="control-label" for="question[<?= $question['ques_id'] ?>]">
			<?php 

			if(Yii::$app->language == 'en') {
				echo $question['question'];
			} else {
				echo $question['question_ar'];
			} ?>
				
		</label>
		<div class="controls">
			<textarea id="question[<?= $question['ques_id'] ?>]" class="form-control" name="question[<?= $question['ques_id'] ?>]"></textarea>
		</div>  
	</div>

<?php } ?>