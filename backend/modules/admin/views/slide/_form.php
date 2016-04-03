<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Slide */
/* @var $form yii\widgets\ActiveForm */
//'onsubmit'=>'return check_validation();'
?>

<div class="slide-form">
	<div class="col-md-8 col-sm-8 col-xs-8">	
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data',]]);?>
    
	<div class="form-group"><?= $form->field($model, 'slide_title',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"])->textInput(['maxlength' => 255]) ?></div>

	<div class="form-group">
	<?= $form->field($model, 'slide_type')->dropDownList(['image'=>'image','video'=>'video script'], ['prompt'=>'Select...']); ?>
	</div>
    
    <div class="form-group" id="image">  
    <?= $form->field($model, 'slide_image',['template' => "{label}<div class='controls1'>{input}</div> {hint} {error}" 
	])->fileInput()->hint('Image slide size 1600 * 600')->label('Slide Image'. Html::tag('span', '*',['class'=>'required1']),['class'=> 'form-label-cap']) ?>
	</div>

<div class="form-group" id="video_url"><?= $form->field($model, 'slide_video_url',['template' => "{label}<div class='controls1'>{input}</div> {hint} {error}" 
	])->fileInput()->hint('Video size 1601 * 600')->label('Slide video'. Html::tag('span', '*',['class'=>'required1']),['class'=> 'form-label-cap']) ?></div>

<div class="form-group"><?= $form->field($model, 'slide_url',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"])->textInput(['maxlength' => 255]) ?></div>

<div class="form-group">
	<?= $form->field($model, 'slide_status',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->checkbox(['Active' => 'Active']) ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?=  Html::a('Back', ['index', ], ['class' => 'btn btn-default']) ?>
    </div>
	</div>
    <?php ActiveForm::end(); ?>

</div>

<script>
	<?php if(@$model->slide_video_url=='') { ?>
	$('#image').show();
	$('#video_url').hide();
	<?php }
	else
	{ ?>
	$('#image').hide();
	$('#video_url').show();
	<?php } ?>
	
		
$(function (){
     $("#slide-slide_type").change(function (){
		var csrfToken = $('meta[name="csrf-token"]').attr("content");
        var id = $('#slide-slide_type').val();
        if(id=="image")
        {
			$('#image').show();
			$(".field-slide-slide_image").addClass('has-error');
			$("#video_url").find('.has-error').removeClass('has-error');
			$("#video_url").find('.help-block').html('');
			$('#video_url').find('textarea').val('');
			$('#video_url').hide();
		}
		else if(id=="video")
        {
			$('#image').hide();
			$(".field-slide-slide_video_url").addClass('has-error');
			$("#image").find('.has-error').removeClass('has-error');
			$("#image").find('.help-block').html('');
			$('#image').find('input:file').val('');
			$('#video_url').show();
		}


     }); 
 });
</script>

<script>
	<?php if($model->isNewRecord){ ?>
	$('#slide-slide_status').prop('checked', true);
	<?php }
	else
	{ if($model->slide_status=='Active'){?>
	$('#slide-slide_status').prop('checked', true);	
		<?php }	else { ?>
	$('#slide-slide_status').prop('checked', false);		
			<?php } ?>
	<?php } ?>


	<?php if(!$model->isNewRecord)
	{ if($model->slide_type=='image'){?>
			$('#image').show();
			$(".field-slide-slide_image").addClass('has-error');
			$("#video_url").find('.has-error').removeClass('has-error');
			$("#video_url").find('.help-block').html('');
			$('#video_url').find('textarea').val('');
			$('#video_url').hide();

		<?php }	else { ?>

			$('#image').hide();
			$(".field-slide-slide_video_url").addClass('has-error');
			$("#image").find('.has-error').removeClass('has-error');
			$("#image").find('.help-block').html('');
			$('#image').find('input:file').val('');
			$('#video_url').show();

			<?php } ?>
	<?php } ?>	
</script>
