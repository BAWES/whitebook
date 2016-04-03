<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Vendoritemquestionansweroption */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="vendoritemquestionansweroption-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="form-group"> 
    <?= $form->field($model, 'question_id',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->dropDownList($questions, ['prompt'=>'Select...']); ?>
	</div>
	
	<div class="form-group">
	<?= $form->field($model, 'answer_background_image_id',['template' => "{label}<div class='controls append_address'>{input}</div> {hint} {error}" 
	])->fileInput() ?>  
	</div>
	
	<div class="form-group">
	<?= $form->field($model, 'answer_text',['template' => "{label}<div class='controls append_address'>{input}</div> {hint} {error}" 
	])->textInput() ?>  
	</div>
	
	<div class="form-group">
	<?= $form->field($model, 'answer_background_color',['template' => "{label}<div class='controls append_address'>{input}</div> {hint} {error}" 
	])->textInput() ?>  
	</div>
	
	<div class="form-group">
	<?= $form->field($model, 'answer_price_added',['template' => "{label}<div class='controls append_address'>{input}</div> {hint} {error}" 
	])->textInput() ?>  
	</div>  
	
	<div class="form-group"> 
   <?= $form->field($model, 'answer_archived',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"
					])->dropDownList([ 'yes' => 'Yes', 'no' => 'No', ], ['prompt' => '']) ?>
	</div>    

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script>
$(function(){
$('#vendoritemquestionansweroption-answer_background_color').colorpicker();
});
</script>
<script src="<?php echo Yii::$app->themeURL->createAbsoluteUrl(''); ?>plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js" type="text/javascript"></script>
<link href="<?php echo Yii::$app->themeURL->createAbsoluteUrl(''); ?>/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.css" rel="stylesheet" type="text/css" />
<link href="<?php echo Yii::$app->themeURL->createAbsoluteUrl(''); ?>/plugins/boostrap-clockpicker/bootstrap-clockpicker.min.css" rel="stylesheet" type="text/css" />

