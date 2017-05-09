<?php 

use common\models\CustomerCart;
use frontend\models\Customer;

//get area from item in cart 

$hide_area = Yii::$app->request->post('hide_area');

?>

<div class="row">

<?php if(!$hide_area) { ?>
<div class="col-md-6">
	<div class="form-group">
		<label><?=Yii::t('frontend', 'Area'); ?></label>
		<div class="controls">
			<input type="text" id="area" class="form-control required" name="area" value="<?= $area_name ?>" disabled />
		</div>  
	</div>
</div>
<?php } ?>

<?php foreach($questions as $question) { 

	if($question['required']) {
		$class = "form-control required";
	} else {
		$class = "form-control";
	}

	?>

	<div class="col-md-6">
		<div class="form-group">
			<label class="control-label" for="question[<?= $question['ques_id'] ?>]">
				<?= \common\components\LangFormat::format($question['question'],$question['question_ar']); ?><?=($question['required']) ? '*' : '';?>
			</label>
			<div class="controls">
				<input type="text" id="question[<?= $question['ques_id'] ?>]" class="<?= $class ?>" name="question[<?= $question['ques_id'] ?>]" value="<?= isset($question['response_text'])?$question['response_text']:'' ?>" />
			</div>  
		</div>
	</div>
<?php } ?>

</div>