<?php foreach($questions as $question) { ?>

	<div class="form-group">
		<label class="control-label" for="question[<?= $question['ques_id'] ?>]"><?= $question['question'] ?></label>
		<div class="controls">
			<textarea id="question[<?= $question['ques_id'] ?>]" class="form-control" name="question[<?= $question['ques_id'] ?>]"></textarea>
		</div>  
	</div>

<?php } ?>