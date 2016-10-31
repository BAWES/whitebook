<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="vendoritemquestionansweroption-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'question_id')->dropDownList($questions, ['prompt'=>'Select...']); ?>

	<?= $form->field($model, 'answer_background_image_id')->fileInput() ?>
	
	<?= $form->field($model, 'answer_text')->textInput() ?>

	<?= $form->field($model, 'answer_background_color', [
				'options' => ['class' => 'controls append_address']
			])->textInput(); ?>
	
	<?= $form->field($model, 'answer_price_added',[
				'options' => ['class' => 'controls append_address']
			])->textInput() ?>
	
   <?= $form->field($model, 'answer_archived')
   			->dropDownList([ 'yes' => 'Yes', 'no' => 'No', ], ['prompt' => '']) ?>
   			
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php 

$this->registerJs("
	$(function(){
		$('#vendoritemquestionansweroption-answer_background_color').colorpicker();
	});
");

$this->registerJsFile("@web/themes/default/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js", ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerCssFile("@web/themes/default/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.css");
$this->registerCssFile("@web/themes/default/plugins/boostrap-clockpicker/bootstrap-clockpicker.min.css");
