<?php foreach($questions as $question) { ?>

	<div class="form-group">
		<label class="control-label" for="question[<?= $question['ques_id'] ?>]">
			<?= \common\components\LangFormat::format($question['question'],$question['question_ar']); ?>
		</label>
		<div class="controls">
			<textarea id="question[<?= $question['ques_id'] ?>]" class="form-control" name="question[<?= $question['ques_id'] ?>]"><?= isset($question['response_text'])?$question['response_text']:'' ?></textarea>
		</div>  
	</div>

<?php } ?>