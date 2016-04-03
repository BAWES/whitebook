<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Vendor */
/* @var $form yii\widgets\ActiveForm */
?>
 <div class="col-md-12 col-sm-12 col-xs-12">
<?php $form = ActiveForm::begin(['options'=>['enctype'=>'multipart/form-data']]);?>		
<div class="loadingmessage" style="display: none;">
<p>
<?= Html::img(Yii::getAlias('@web/themes/default/img/loading.gif'), ['class'=>'','width'=>'64px','height'=>'64px','id'=>'loading','alt'=>'loading']);?> 
</p>
</div>
<!-- Begin Twitter Tabs-->
<div class="tabbable">
  <ul class="nav nav-tabs">
    <li class="active">
      <a href="#1" data-toggle="tab">Basic Info </a>
    </li>
    <li>
      <a href="#2" data-toggle="tab" class="onevalid1">Main Info</a>
    </li>
    <li>
      <a href="#3" data-toggle="tab" class="twovalid2">Additional Info</a>
    </li>
    <li>
      <a href="#4" data-toggle="tab" class="twovalid2">Social Info</a>
    </li>          
  </ul>
  <div class="tab-content">
<!-- Begin First Tab -->
    <div class="tab-pane" id="1" > 
    <div class="form-group vendor_logo">
	<?= $form->field($model, 'vendor_logo_path',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->label('Vendor logo'.Html::tag('span', '*',['class'=>'required']))->fileInput()->hint('Logo Size 150 * 250') ?>
	
	<?php  if(!$model->isNewRecord) { ?>
	<!-- Venodr logo begin -->
	<?php if(isset($model->vendor_logo_path)) { 		
		echo Html::img(Yii::getAlias('@web/uploads/vendor_logo/').$model->vendor_logo_path, ['class'=>'','width'=>'125px','height'=>'125px','alt'=>'Logo']);
		} ?>
	<!-- Venodr logo end -->
	
	<?php } ?>
	</div>

    <div class="form-group">   
	<?= $form->field($model, 'vendor_name',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->textInput(['maxlength' => 100,'autocomplete' => 'off']) ?>
	</div> 		

	<?php if(!$model->isNewRecord) { ?>

	<div class="form-group">
	<?= $form->field($model, 'vendor_contact_email',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->textInput(['maxlength' => 100,'readonly'=>true,]) ?>
	</div>

	<?php } else {?>

	<div class="form-group">
	<?= $form->field($model, 'vendor_contact_email',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->textInput(['maxlength' => 100,'autocomplete' => 'off']) ?>
	</div>	
	
	<div class="form-group">
	<?= $form->field($model, 'vendor_password',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->passwordInput() ?>
	</div>
	
	<div class="form-group">
	<?= $form->field($model, 'confirm_password',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->passwordInput() ?>
	</div>
	
	 <?php } ?>	
	<input type="hidden" name="email_valid" value="" />
	 <div class="form-group">
	<?= $form->field($model, 'vendor_contact_name',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->textInput(['maxlength' => 100,'autocomplete' => 'off']) ?>
	</div>		
	
	<?php if($model->isNewRecord) { $count_vendor = 1;?>
	<div class="form-group" style="border: 1px solid #ccc;  padding: 5px;  font-size: 14px;">
	<?= $form->field($model, 'vendor_contact_number[]',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->textInput(['multiple' => 'multiple','autocomplete' => 'off']) ?>		
	<input type="button" name="add_item" id="addnumber" value="Add phone numbers" onClick="addPhone('current');" /> 
	</div>
	<?php } else { ?>		
	<div class="form-group" style="border: 1px solid #ccc;  padding: 5px;  font-size: 14px;">
		<label class="control-label" for="vendor-vendor_contact_number">Contact Phone Number</label>
	<?php 
	$i =1;
	$count_vendor =  count($vendor_contact_number);
	foreach($vendor_contact_number as $contact_numbers)
	{ ?>
	<?= $form->field($model, 'vendor_contact_number[]',[  'template' => "<div class='controls".$i."'>{input}<input type='button' name='remove' id='remove' value='Remove' onClick='removePhone(".$i.")' style='margin:5px;' /></div> {hint} {error}" 
	])->textInput(['multiple' => 'multiple','autocomplete' => 'off','value'=>$contact_numbers]) ?>
	
	<?php $i++; } ?>
	<input type="button" name="add_item" id="addnumber" value="Add phone numbers" onClick="addPhone('current');" style="margin:5px;" /> 	
	
	</div>
	<?php } ?>
	
	<div class="form-group">
	<?= $form->field($model, 'vendor_contact_address',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->textArea() ?>
	</div>

	<div class="form-group" style="height: 10px;">
	<input type="button" name="btnPrevious" class="btnNext btn btn-info" value="Next">
	</div>

	</div>

	<!--End First Tab -->

	<div class="tab-pane" id="2">
	<input type="hidden" id="test1" value="0" name="tests">
	 <div class="form-group"><?= $form->field($model, 'category_id',['template' => "{label}<div class='controls'>{input}</div>{hint}
{error}"])->dropDownList(common\models\Category::loadcategory() , ['multiple'=>'multiple']) ?>
</div>
		
	<input type='hidden' id='test' value='0' name='tests1'>

	<div class="form-group">   
	<?= $form->field($model, 'vendor_status',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->checkbox(['Active' => 'Active'])?>
	</div>	
	
	
	<input type="button" name="btnPrevious" class="btnPrevious btn btn-info" value="Prev">
	<input type="button" name="btnNext" class="btnNext btn btn-info" value="Next">
	</div>

	<!--End Second Tab -->	
     
    <div class="tab-pane" id="3">	

    <div class="form-group">
	<?= $form->field($model, 'vendor_return_policy',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->textArea(['id'=>'text-editor']) ?>
	</div>

	<div class="form-group">
	<?= $form->field($model, 'vendor_fax',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->textInput() ?>
	</div>
	
	<div class="form-group">
	<?= $form->field($model, 'short_description',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->textArea() ?>
	</div>	

	<div class="form-group">
	<?= $form->field($model, 'vendor_bank_name',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->textInput() ?>
	</div>
	
	<div class="form-group">
	<?= $form->field($model, 'vendor_bank_branch',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->textInput() ?>
	</div>
	
	<div class="form-group">
	<?= $form->field($model, 'vendor_account_no',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->textInput() ?>
	</div>	

	<input type="button" name="btnPrevious" class="btnPrevious btn btn-info" value="Prev">
	<input type="button" name="btnNext" class="btnNext btn btn-info" value="Next">
</div>
		<!--End Third Tab -->	
     
<div class="tab-pane" id="4">	

	<div class="form-group">
		<?= $form->field($model, 'vendor_website',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->textInput()->label('Vendor Website URL',['class'=> 'form-label-cap']) ?>
	</div>
	
	<div class="form-group">
	<?= $form->field($model, 'vendor_facebook',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->textInput()->label('Vendor Facebook URL',['class'=> 'form-label-cap']) ?>
	</div>

	<div class="form-group">
	<?= $form->field($model, 'vendor_twitter',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->textInput()->label('Vendor Twitter URL',['class'=> 'form-label-cap']) ?>
	</div>
	
	<div class="form-group">
	<?= $form->field($model, 'vendor_instagram',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->textInput()->label('Vendor Instagram URL',['class'=> 'form-label-cap']) ?>
	</div>
	
	<div class="form-group">
	<?= $form->field($model, 'vendor_googleplus',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->textInput()->label('Vendor Google Plus URL',['class'=> 'form-label-cap']) ?>
	</div>
	
	<div class="form-group">
	<?= $form->field($model, 'vendor_skype',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->textInput()->label('Skype ID',['class'=> 'form-label-cap']) ?>
	</div>

	<div class="form-groups">
	<input type="button" name="btnPrevious" class="btnPrevious btn btn-info" value="Prev">

    <?= Html::submitButton($model->isNewRecord ? 'Complete' : 'Complete', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary','style'=>'float:right;']) ?>        
    </div>
    <?php ActiveForm::end(); ?>
 </div>
</div>
</div>
<!--End Third Tab -->
</div>
<!-- BEGIN PLUGIN CSS -->
<link href="<?php echo Yii::$app->themeURL->createAbsoluteUrl(''); ?>plugins/bootstrap-datepicker/css/datepicker.css" rel="stylesheet" type="text/css" />

<!-- END PLUGIN CSS -->
<!-- multi select begin -->
<script type="text/javascript" src="<?php echo Yii::$app->themeURL->createAbsoluteUrl(''); ?>plugins/bootstrap-multiselect/dist/js/bootstrap-multiselect.js"></script>
<link href="<?php echo Yii::$app->themeURL->createAbsoluteUrl(''); ?>plugins/bootstrap-multiselect/dist/css/bootstrap-multiselect.css" rel="stylesheet" type="text/css" />
<!-- multi select end -->

<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="<?php echo Yii::$app->themeURL->createAbsoluteUrl(''); ?>plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>

<!-- END PAGE LEVEL PLUGINS -->
<script>
$('.controls1').find('#remove').remove();
$('.datepicker').datepicker();
$('select#package').hide();

$("#change_pack").live('click',function(){
   $('select#package').show();
});

$(function()
{	/* Begin when loading page first tab opened */
 	$('.nav-tabs li:first').addClass("active");
 	$(".tab-content div:first").addClass("active");
 	/* End when loading page first tab opened */

	CKEDITOR.replace('text-editor');
});

var j= <?= $count_vendor+1; ?>;
function addPhone(current)
{		
$('#addnumber').before('<div class="controls'+j+'"><input type="text" id="vendor-vendor_contact_number'+j+'" class="form-control" name="Vendor[vendor_contact_number][]" multiple = "multiple" maxlength="15" Placeholder="Phone Number" style="margin:5px 0px;"><input type="button" name="remove" id="remove" value="Remove" onClick="removePhone('+j+')" style="margin:5px;" /></div>');

   j++;	

  $("#vendor-vendor_contact_number2").keypress(function (e) {
     //if the letter is not digit then display error and don't type anything
     if (  e.which  != 43   && e.which  != 45 && e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57 )) {
        //display error message
          $(".field-vendor-vendor_contact_number").find('.help-block').html('Contact number digits only+.').animate({ color: "#a94442" }).show().fadeOut(2000);
               return false;
    }
   });
  $("#vendor-vendor_contact_number3").keypress(function (e) {
     //if the letter is not digit then display error and don't type anything
     if ( e.which  != 43   && e.which  != 45  && e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        //display error message
          $(".field-vendor-vendor_contact_number").find('.help-block').html('Contact number digits only.').animate({ color: "#a94442" }).show().fadeOut(2000);
               return false;
    }
   });
  $("#vendor-vendor_contact_number4").keypress(function (e) {
     //if the letter is not digit then display error and don't type anything
     if ( e.which  != 43   && e.which  != 45 && e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        //display error message
          $(".field-vendor-vendor_contact_number").find('.help-block').html('Contact number digits only.').animate({ color: "#a94442" }).show().fadeOut(2000);
               return false;
    }
   });
   

  $("#vendor-vendor_contact_number5").keypress(function (e) {
     //if the letter is not digit then display error and don't type anything
     if ( e.which  != 43   && e.which  != 45 && e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        //display error message
          $(".field-vendor-vendor_contact_number").find('.help-block').html('Contact number digits only.').animate({ color: "#a94442" }).show().fadeOut(2000);
               return false;
    }
   });
}

function removePhone(phone) {	
	$(".controls"+phone).remove();
}

	/* Begin Tabs NEXT & PREV buttons */
	$('.btnNext').click(function(){
	  $('.nav-tabs > .active').next('li').find('a').trigger('click');
	});
	
	//category add drop downlist
	$(".vendor-category_id:last-child").css({"clear" : "both","float" :"inherit"});
	$('#option').hide();
		
	  $('.btnPrevious').click(function(){
	  $('.nav-tabs > .active').prev('li').find('a').trigger('click');
	});  
	/* End Tabs NEXT & PREV buttons */
	
	$(function(){
	 $('#vendor-category_id').multiselect({
			'enableFiltering': true,
			'filterPlaceholder': 'Search for something...'
			});       
	});
	


</script>
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="<?php echo Yii::$app->themeURL->createAbsoluteUrl(''); ?>plugins/ckeditor/ckeditor.js" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->

<script>
$('#vendor-package_start_date').datepicker({  format: 'dd-mm-yyyy', startDate: 'today',});
$('#vendor-package_end_date').datepicker({  format: 'dd-mm-yyyy', });
</script>



<script>
	<?php if($model->isNewRecord){ ?>
	$('#vendor-vendor_status').prop('checked', true);
	<?php }
	else
	{ if($model->vendor_status=='Active'){?>
	$('#vendor-vendor_status').prop('checked', true);	
		<?php }	else { ?>
	$('#vendor-vendor_status').prop('checked', false);		
			<?php } ?>
	<?php } ?>
</script>

<script>
	<?php if($model->isNewRecord){ ?>
	$('#vendor-approve_status').prop('checked', true);
	<?php }
	else
	{
		if($model->approve_status=='Yes'){?>
	$("#vendor-vendor_logo_path").val()='image';
	$('#vendor-approve_status').prop('checked', true);	
		<?php }	else { ?>
	$('#vendor-approve_status').prop('checked', false);		
			<?php } ?>
	<?php } ?>
</script>

<script>
$(document).ready(function () {
	//called when key is pressed in textbox
  $("#vendor-vendor_contact_number").keypress(function (e) {
     //if the letter is not digit then display error and don't type anything
     if ( e.which  != 43  && e.which  != 45 && e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        //display error message
        
      
          $(".field-vendor-vendor_contact_number").find('.help-block').html('Contact number digits only.').animate({ color: "#a94442" }).show().fadeOut(2000);
               return false;
    }
   });
});

$(document).ready(function () {
  //called when key is pressed in textbox
  $("#vendor-vendor_contact_number2").keypress(function (e) {alert(1);
   });
});

</script>
<?php // Validation only on update scenario ?> 

<script>
  var csrfToken = $('meta[name="csrf-token"]').attr("content");
  var c1 = true; 
$( ".onevalid1" ).click(function() {
	if($('#test').val()==1)
	{
		return false;
	}
	if($('#test1').val()==1)
	{
		return false;
	}
	
	<?php if($model->isNewRecord) { ?>

	if($("#vendor-vendor_logo_path").val()=='')
	{
			$(".field-vendor-vendor_logo_path").addClass('has-error');
			$(".field-vendor-vendor_logo_path").find('.help-block').html('Please upload a file.');
			c1=false;
  }
  <?php } ?>
  if($("#vendor-vendor_name").val()=='')
	{
			$(".field-vendor-vendor_name").addClass('has-error');
			$(".field-vendor-vendor_name").find('.help-block').html('Vendor name cannot be blank.');
			c1=false;
  	}
  	if($("#vendor-vendor_contact_email").val()=='')
	{
			$(".field-vendor-vendor_contact_email").addClass('has-error');
			$(".field-vendor-vendor_contact_email").find('.help-block').html('Email cannot be blank.');
			c1=false;
  	}
  	<?php if($model->isNewRecord) {?>
   	if($("#vendor-vendor_contact_email").val()!='')
	{			
		if(validateEmail($("#vendor-vendor_contact_email").val()) == true){
			var mail=$("#vendor-vendor_contact_email").val();			
			var path = "<?php echo Url::to(['/admin/vendor/emailcheck']); ?> ";
			$('.loadingmessage').show();
			$.ajax({  
			type: 'POST',      
			url: path, //url to be called
			async:true,
			data: { id: mail ,_csrf : csrfToken}, //data to be send
			success: function( data ) {	
				$(".loadingmessage").ajaxComplete(function(event, request, settings){            	
				$("input[name=email_valid]").val(data);
				if(data>0)
				{
				$(".field-vendor-vendor_contact_email").removeClass('has-success');
				$(".field-vendor-vendor_contact_email").addClass('has-error');
				$(".field-vendor-vendor_contact_email").find('.help-block').html('Email already exists.');
				c1=false;
				$('.loadingmessage').hide();
				}
				else
				{		
				$(".field-vendor-vendor_contact_email").removeClass('has-error');		
				$(".field-vendor-vendor_contact_email").addClass('has-success');			
				$(".field-vendor-vendor_contact_email").find('.help-block').html('');
					 $('.loadingmessage').hide();
					$('#test').val(0);	
					c1=true;
				}
				});
			 }
			});
		}else c1=false;
  }  
  <?php } ?>
  // check only if its new record

 // check only if its new record
  
  if($("#vendor-vendor_password").val()=='')
	{
			$(".field-vendor-vendor_password").addClass('has-error');
			$(".field-vendor-vendor_password").find('.help-block').html('Password cannot be blank');
			c1=false;
  }
  <?php if($model->isNewRecord) { ?>
  if($("#vendor-vendor_password").val()!='')
	{
			var pass=$("#vendor-vendor_password").val();
			if(pass.length<6)
			{$(".field-vendor-vendor_password").addClass('has-error');
			$(".field-vendor-vendor_password").find('.help-block').html('Password should contain minimum 6 Letter.');
			c1=false;}
  } 
  if($("#vendor-confirm_password").val()=='')
	{
			$(".field-vendor-confirm_password").addClass('has-error');
			$(".field-vendor-confirm_password").find('.help-block').html('Confirm password cannot be blank.');
			c1=false;
  }else  if($("#vendor-confirm_password").val()!=$("#vendor-vendor_password").val())
	{
			$(".field-vendor-confirm_password").addClass('has-error');
			$(".field-vendor-confirm_password").find('.help-block').html('Password and confirm password not same.');
			c1=false;
  } 
  <?php } ?>
    if($("#vendor-vendor_contact_name").val()=='')
	{
			$(".field-vendor-vendor_contact_name").addClass('has-error');
			$(".field-vendor-vendor_contact_name").find('.help-block').html('Contact name  cannot be blank.');
			c1=false;
  }
    if($("#vendor-vendor_contact_number").val()=='')
	{
			$(".field-vendor-vendor_contact_number").addClass('has-error');
			$(".field-vendor-vendor_contact_number").find('.help-block').html('Contact number cannot be blank.');
			c1=false;
  } 
    if($("#vendor-vendor_contact_address").val()=='')
	{
			$(".field-vendor-vendor_contact_address").addClass('has-error');
			$(".field-vendor-vendor_contact_address").find('.help-block').html('Contact address cannot be blank.');
			c1=false;
  }    
	  if(c1==false)
	  {
		  c1='';
		  return false;
		}  

	var item_len = $("#vendor-vendor_name").val().length; 
     if($("#vendor-vendor_name").val()=='')
	 {		
	 	$(".field-vendor-vendor_name").addClass('has-error');
			$(".field-vendor-vendor_name").find('.help-block').html('Item name cannot be blank.');			
			c1=false;
	 }
	 else if(item_len < 3){	
	 			
	 			$(".field-vendor-vendor_name").addClass('has-error');
	 			$(".field-vendor-vendor_name").find('.help-block').html('Item name minimum 4 letters.');
				c1=false;
	 } 
return c1;           
});
function validateEmail(email) { 
    var re = /^[a-zA-Z0-9!#$%&'*+\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&'*+\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?$/;
    return re.test(email);
}

</script>

<script>
$( ".twovalid2" ).click(function() {
	var y=$('#vendor-category_id').val();
	
	if($("#vendoritem-item_amount_in_stock").val()=='')
	{
			$(".field-vendor-vendor_logo_path").addClass('has-error');
			$(".field-vendor-vendor_logo_path").find('.help-block').html('Please upload a file.');
			return false;
  }
  if($("#vendor-vendor_name").val()=='')
	{
			$(".field-vendor-vendor_name").addClass('has-error');
			$(".field-vendor-vendor_name").find('.help-block').html('Vendor name cannot be blank.');
			return false;
  }
  if($("#vendor-vendor_contact_email").val()=='')
	{
			$(".field-vendor-vendor_contact_email").addClass('has-error');
			$(".field-vendor-vendor_contact_email").find('.help-block').html('Email cannot be blank.');
			return false;
  }
  <?php if($model->isNewRecord) { ?>
  if($("#vendor-vendor_password").val()=='')
	{
			$(".field-vendor-vendor_password").addClass('has-error');
			$(".field-vendor-vendor_password").find('.help-block').html('Password cannot be blank.');
			return false;
  }
  if($("#vendor-confirm_password").val()=='')
	{
			$(".field-vendor-confirm_password").addClass('has-error');
			$(".field-vendor-confirm_password").find('.help-block').html('Confirm password cannot be blank.');
			return false;
  }
 if($("#vendor-confirm_password").val()!=$("#vendor-vendor_password").val())
 {
			$(".field-vendor-confirm_password").addClass('has-error');
			$(".field-vendor-confirm_password").find('.help-block').html('Password and confirm password not same.');
			return false;
  }
  <?php } ?>
    if($("#vendor-vendor_contact_name").val()=='')
	{
			$(".field-vendor-vendor_contact_name").addClass('has-error');
			$(".field-vendor-vendor_contact_name").find('.help-block').html('Contact name  cannot be blank.');
			return false;
  }
    if($("#vendor-vendor_contact_number").val()=='')
	{
			$(".field-vendor-vendor_contact_number").addClass('has-error');
			$(".field-vendor-vendor_contact_number").find('.help-block').html('Contact number cannot be blank.');
			return false;
  }
    if($("#vendor-vendor_contact_address").val()=='')
	{
			$(".field-vendor-vendor_contact_address").addClass('has-error');
			$(".field-vendor-vendor_contact_address").find('.help-block').html('Contact address cannot be blank.');
			return false;
  }

   // check only if its new record
  <?php if($model->isNewRecord) {?>
  else if($("#vendor-vendor_contact_email").val()!='')
	{		
		if(validateEmail($("#vendor-vendor_contact_email").val()) == true){			
			var mail=$("#vendor-vendor_contact_email").val();			
			var path = "<?php echo Url::to(['/admin/vendor/emailcheck']); ?> ";
			//$('.loadingmessage').show();
			$.ajax({  
			type: 'POST',      
			url: path, //url to be called
			async:true,
			data: { id: mail ,_csrf : csrfToken}, //data to be send
			success: function( data ) {	
				$(".loadingmessage").ajaxComplete(function(event, request, settings){            	
				$("input[name=email_valid]").val(data);
				 if(data>0)
				 {
				$(".field-vendor-vendor_contact_email").removeClass('has-success');
				$(".field-vendor-vendor_contact_email").addClass('has-error');
				$(".field-vendor-vendor_contact_email").find('.help-block').html('Email already exists.');
				c1=false;
				$('.loadingmessage').hide();
				}
				else
				{	
				$(".field-vendor-vendor_contact_email").removeClass('has-error');		
				$(".field-vendor-vendor_contact_email").addClass('has-success');						
				$(".field-vendor-vendor_contact_email").find('.help-block').html('');
					 $('.loadingmessage').hide();
					$('#test').val(0);
					c1=true;	
				}
				});
			 }
			});
		}else c1=false;
  }  
  <?php } ?>

	if((y=='')||(y==null))
	{
			$(".field-vendor-category_id").addClass('has-error');
			$(".field-vendor-category_id").find('.help-block').html('category name  cannot be blank.');
			return false;
  }
  else
  {return true;}
});


$(document).ready(function(){
  $('#vendor-vendor_contact_number').bind("paste",function(e) {
      e.preventDefault();
  });
  $('#vendor-vendor_contact_email').bind("paste",function(e) {
      e.preventDefault();
  });
});

<?php if($model->isNewRecord) {?>
$(function () {
 $("#vendor-vendor_contact_email").on('keyup keypress focusout',function () {
	 if(validateEmail($(this).val()) == true){	
	if($("#vendor-vendor_contact_email").val()!='')
	{			
		$(".field-vendor-vendor_contact_email").removeClass('has-success');
		$(".field-vendor-vendor_contact_email").addClass('has-error');
		$('.loadingmessage').show();		
		var mail=$("#vendor-vendor_contact_email").val();
		var csrfToken = $('meta[name="csrf-token"]').attr("content");
        var path = "<?php echo Url::to(['/admin/vendor/emailcheck']); ?> ";
        $('.loadingmessage').show();
        $.ajax({  
        type: 'POST',      
        url: path, //url to be called
        async:true,
        data: { id: mail ,_csrf : csrfToken}, //data to be send
        success: function( data ) {	
			$(".loadingmessage").ajaxComplete(function(event, request, settings){            	
				$("input[name=email_valid]").val(data);
				 if(data>0)
				 {
				$(".field-vendor-vendor_contact_email").removeClass('has-success');
				$(".field-vendor-vendor_contact_email").addClass('has-error');
				$(".field-vendor-vendor_contact_email").find('.help-block').html('Email already exists.');
				c1=false;
				$('.loadingmessage').hide();
				}
				else
				{	
				$(".field-vendor-vendor_contact_email").removeClass('has-error');		
				$(".field-vendor-vendor_contact_email").addClass('has-success');				
				$(".field-vendor-vendor_contact_email").find('.help-block').html('');
					 $('.loadingmessage').hide();
					$('#test').val(0);
					c1=true;	
				}
				});
			}
});
 }
}
	     });
	 
});  
<?php } ?>
</script>
<script>
$(document).ready(function(){
 $('#vendor-vendor_name').bind("paste",function(e) {
     e.preventDefault();
 });
});
 $(function () {
 $("#vendor-vendor_name").on('keyup keypress focusout',function () {
	if($("#vendor-vendor_name").val().length > 3)	
	{
		var mail=$("#vendor-vendor_name").val();		
        var path = "<?php echo Url::to(['/admin/vendor/vendornamecheck']); ?> ";
        $('.loadingmessage').show();
        $.ajax({  
        type: 'POST',      
        url: path, //url to be called
        data: { vendor_name: mail ,_csrf : csrfToken}, //data to be send
        success: function( data ) {	
			$("#test1").val(mail);	
            if(data>0)
            {
			$('.loadingmessage').hide();
			$(".field-vendor-vendor_name").removeClass('has-success');
			$(".field-vendor-vendor_name").addClass('has-error');
			$(".field-vendor-vendor_name").find('.help-block').html('Vendor name already exists.');
			$(".field-vendor-vendor_name" ).focus();
			$('#test1').val(1);
			return false			
			}
			else
			{				
			$(".field-vendoritem-item_name").find('.help-block').html('');
			$('.loadingmessage').hide();
			$('#test1').val(0);
			return false;	
			}
         }
        });
	//}
  }
});
});

</script>

