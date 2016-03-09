<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Country */
/* @var $form yii\widgets\ActiveForm */
$model->banner_type = 1;
?>

<div class="col-md-8 col-sm-8 col-xs-8">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data','onsubmit'=>'return check_validation();']]);?>
    <div class="form-group">   
	<?= $form->field($model, 'banner_title',['template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->textInput(['maxlength' => 100]) ?>
    </div>
    <div class="bannertitle" style="color:#a94442; margin-top:8px;">Kindly enter the banner title</div>
    <?php if(@$model->banner_video_url=='') { ?>
     <div class="form-group">   
	<?= $form->field($model, 'banner_type',['template' => "{label}<div class='controls1'>{input}</div> {hint} {error}" 
	])->radioList(['1'=>'Image','2'=>'Video'],['itemOptions' => ['id' =>'banner_type','onchange'=>"show_image_video(this.value)"]]) ?>
    </div>
    <?php }
    else { 
		$model->banner_type = 2;
     ?>
     <div class="form-group">   
	<?= $form->field($model, 'banner_type',['template' => "{label}<div class='controls1'>{input}</div> {hint} {error}" 
	])->radioList(['1'=>'Image','2'=>'Video'],['itemOptions' => ['id' =>'banner_type','onchange'=>"show_image_video(this.value)"]]) ?>
    </div>
    <?php } 
    ?>
    <div class="form-group" id="image">  
    <?= $form->field($model, 'banner_image',['template' => "{label}<div class='controls1'>{input}</div> {hint} {error}" 
	])->fileInput()->hint('Banner Size 1300 * 500')->label('Banner Image'. Html::tag('span', '*',['class'=>'required']),['class'=> 'form-label-cap']) ?>
	</div>
	<div class="imgerror" style="color:#a94442; margin-top:8px;">Kindly add the Banner image</div>
	
    <div class="form-group"  id="video_url">   
	<?= $form->field($model, 'banner_video_url',['template' => "{label}<div class='controls1'>{input}</div> {hint} {error}" 
	])->textArea()->label('Banner Video URL'. Html::tag('span', '*',['class'=>'required']),['class'=> 'form-label-cap']) ?>
    </div>
    <div class="ctrlnew" style="color:#a94442; margin-top:8px;">Kindly enter the Banner video URL</div>
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>  
    <div class="form-group">   
	<?= $form->field($model, 'banner_url',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->textInput(['maxlength' => 250])->label('Banner URL',['class'=> 'form-label-cap']) ?>	
	<div class="form-group">
	<?= $form->field($model, 'banner_status',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->checkbox(['Active' => 'Active']) ?>
    </div>      
<div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary-ads']) ?>
        <?= Html::a('Back', ['index', ], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script>
	<?php if(@$model->banner_video_url=='') { ?>
	$('#image').show();
	$('#video_url').hide();
	<?php }
	else
	{ ?>
	$('#image').hide();
	$('#video_url').show();
	<?php } ?>
function show_image_video(val)
{
	if(val==1)
	{
		$('#video_url').hide();
		$('#image').show();
	}
	else
	{
		$('#video_url').show();
		$('#image').hide();
		$(".imgerror").hide();
	}
}
</script>
<script>
	<?php if($model->isNewRecord){ ?>
	$('#banner-banner_status').prop('checked', true);
	<?php }
	else
	{ if($model->banner_status=='Active'){?>
	$('#banner-banner_status').prop('checked', true);	
		<?php }	else { ?>
	$('#banner-banner_status').prop('checked', false);		
			<?php } ?>
	<?php } ?>
	
</script>
<script type="text/javascript">
$(function() {	
	$(".bannertitle").hide();	
	$(".ctrlnew").hide();	
	$(".imgerror").hide();	
		});
 
</script>
<script type="text/javascript"> 

 function check_validation()
{
	var banner_title=$('input[name="Banner[banner_title]"]').val();
	var message = $('textarea#banner-banner_video_url').val();
		if(banner_title=='' || banner_title==null)
		{
			$(".bannertitle").show();
		}
	var x=$('input[name="Banner[banner_type]"]:checked').val();
	if(x=='2')
	{
		if(banner_title=='' || banner_title==null)
		{
			$(".bannertitle").show();
		}else
		{$(".bannertitle").hide();}
		if(message=='' || message==null)
		{
			$(".ctrlnew").show();
			return false;
		}else{$(".ctrlnew").hide();
			}
		if(banner_title=='' || banner_title==null)
		{
			$(".bannertitle").show();
			return false;
		}
		else
		{
		$('#fade').remove();
		$('body').append('<div id="fade"></div>');
		$('.processing_image').show();
		return true;
		}
	}else if(x=='1')
	{
      var file = $('input[type="file"]').val();
      var exts = ['jpg','jpeg','png'];
      // first check if file field has any value
      if ( file ) {
        // split file name at dot
        var get_ext = file.split('.');
        // reverse name to check extension
        get_ext = get_ext.reverse();
        // check file type is valid as given in 'exts' array
        if ( $.inArray ( get_ext[0].toLowerCase(), exts ) > -1 ){
			$('#video_url').hide();
			$('#image').show();
			$(".has-error").hide();
			$(".imgerror").hide();
			$('#fade').remove();
			if(banner_title=='' || banner_title==null)
		{
			$(".bannertitle").show();
			return false;
		}
		$('body').append('<div id="fade"></div>');
		$('.processing_image').show();
			return true;
          //alert( 'Allowed extension!' );
        } else {
          alert( 'Invalid file format!' );
          return false;
        }
      }else
      {
		$(".imgerror").show();
		//alert('no file');
	  }
return false;
 }
}
</script>
