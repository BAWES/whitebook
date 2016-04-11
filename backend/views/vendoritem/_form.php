<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use dosamigos\fileupload\FileUploadUI;
use common\models\Vendoritemquestion;
/* @var $this yii\web\View */
/* @var $model common\models\Vendoritem */
/* @var $form yii\widgets\ActiveForm */
if($model->isNewRecord){
$childcategory = array();
$exist_themes =array();
$exist_groups = array();
}
?>

<?= Html::csrfMetaTags() ?>
<div class="col-md-12 col-sm-12 col-xs-12">
<?php $form = ActiveForm::begin(['options'=>['enctype'=>'multipart/form-data']]); ?>
<div class="loadingmessage" style="display: none;">
<p>
<?= Html::img(Yii::getAlias('@web/themes/default/img/loading.gif'), ['class'=>'','width'=>'64px','height'=>'64px','id'=>'loading','alt'=>'loading']);?>
</p>
</div>
 <div class="tabbable">
  <ul class="nav nav-tabs">
    <li class="active">
      <a href="#1" data-toggle="tab">Item Info </a>
    </li>
    <li>
      <a href="#2" data-toggle="tab" id="validone1">Item description</a>
    </li>
    <li>
      <a href="#3" data-toggle="tab" id="validtwo2"> Item price </a>
    </li>
    <li>
      <a href="#5" data-toggle="tab" id="validthree3">Images</a>
    </li>
  </ul>
 <div class="tab-content">
<!-- Begin First Tab -->
<div class="tab-pane" id="1" >

<div class="form-group">
	<?= $form->field($model, 'item_name',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"])->textInput(['maxlength' => 128])?>
</div>

<div class="form-group"><?= $form->field($model, 'category_id',['template' => "{label}<div class='controls'>{input}</div>{hint}
{error}"])->dropDownList($categoryname, ['prompt'=>'Select...']) ?></div>

<div class="form-group"><?= $form->field($model, 'subcategory_id',['template' => "{label}<div class='controls'>{input}</div>{hint}
{error}"])->dropDownList($subcategory, ['prompt'=>'Select...']) ?></div>

<div class="form-group"><?= $form->field($model, 'child_category',['template' => "{label}<div class='controls'>{input}</div>{hint}
{error}"])->dropDownList($childcategory, ['prompt'=>'Select...']) ?></div>

<div class="form-group" style="height: 10px;">
<input type="button" name="btnPrevious" class="btnNext btn btn-info" value="Next">
</div>

</div>
<!--End First Tab -->

<!--BEGIN second Tab -->
<div class="tab-pane" id="2">

<div class="form-group"><?= $form->field($model, 'type_id',['template' => "{label}<div class='controls'>{input}</div>{hint}
{error}"])->dropDownList($itemtype, ['prompt'=>'Select...']) ?></div>

<div class="form-group">
	<?= $form->field($model, 'item_description',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"])
	->label('Item description'.Html::tag('span', '*',['class'=>'required']))->textarea(['maxlength' => 128])?>
</div>

<div class="form-group">
	<?= $form->field($model, 'item_additional_info',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"])->textarea(['maxlength' => 128])?>
</div>

<input type="button" name="btnPrevious" class="btnPrevious btn btn-info" value="Prev">
<input type="button" name="btnNext" class="btnNext btn btn-info" value="Next">
</div>

<!--End Second Tab -->

<!--BEGIN Third Tab -->
<div class="tab-pane" id="3">
<input type="hidden" id="test" value="0" name="tests">
<div class="form-group">
<?= $form->field($model, 'item_for_sale',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}"
])->checkbox(['Yes' => 'Yes'])?>
</div>

<div class="form-group">
	<?= $form->field($model, 'item_amount_in_stock',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"])
	->label('Item Number of Stock '.Html::tag('span', '*',['class'=>'required mandatory']))->textInput(['maxlength' => 128])?>
</div>

<div class="form-group">
	<?= $form->field($model, 'item_default_capacity',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"])
	->label('Item Default Capacity '.Html::tag('span', '*',['class'=>'required mandatory']))->textInput(['maxlength' => 128])?>
</div>

<div class="form-group">
	<?= $form->field($model, 'item_how_long_to_make',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"])
	->label('No of days delivery '.Html::tag('span', '*',['class'=>'required mandatory']))->textInput(['maxlength' => 128])?>
</div>

<div class="form-group">
	<?= $form->field($model, 'item_minimum_quantity_to_order',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"])
	->label('Item Minimum Quantity to Order '.Html::tag('span', '*',['class'=>'required mandatory']))->textInput(['maxlength' => 128])?>
</div>

<div class="form-group">
	<?= $form->field($model, 'item_price_per_unit',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"])->textInput(['maxlength' => 128])?>
</div>
<?php if($model->isNewRecord) { ?>

<div class="form-group multiple_price" style="padding: 5px;  font-size: 14px;">
	<div class="multi_pricing">Price range From - To </div>
	<div class="controls1"><input type="text" id="vendoritem-item_from" class="form-control from_range_1" name="vendoritem-item_price[from][]" multiple="multiple" placeholder="From range"><input type="text" id="vendoritem-item_to" class="form-control to_range_1" name="vendoritem-item_price[to][]" multiple="multiple" placeholder="To range"><input type="text" id="item_price_per_unit" class="form-control price_kd_1" name="vendoritem-item_price[price][]" multiple="multiple" placeholder="Price">KD<input type="button" name="remove" id="remove" value="Remove" class="remove_price" onclick="removePrice(this)"></div>
	<input type="button" class="add_price" name="addprice" id="addprice" value="Add more" onClick="addPrice(this);" />
</div>
<?php } else { ?>
	<div class="form-group multiple_price" style="padding: 5px;  font-size: 14px;">
	<div class="multi_pricing">Price  From - To </div>

	<?php $t=0;
	foreach ($loadpricevalues as $value) {  ?>

	<div class="controls<?= $t; ?>"><input type="text" id="vendoritem-item_from" class="form-control from_range_<?= $t; ?>" name="vendoritem-item_price[from][]" multiple = "multiple" Placeholder="From range" value="<?= $value['range_from'];?>"><input type="text" id="vendoritem-item_to" class="form-control to_range_<?= $t; ?>" name="vendoritem-item_price[to][]" multiple = "multiple" Placeholder="To range" value="<?= $value['range_to'];?>"><input type="text" id="item_price_per_unit" class="form-control price_kd_<?= $t; ?>" name="vendoritem-item_price[price][]" multiple = "multiple" Placeholder="Price" value="<?= $value['pricing_price_per_unit'];?>">KD<input type="button" name="remove" id="remove" value="Remove" class="remove_price" onClick="removePrice(this)" /></div>

	<?php $t++; }?>
	<input type="button" class="add_price" name="addprice" id="addprice" value="Add more" onClick="addPrice(this);" />
</div>
<?php } ?>


<div class="form-group">
	<?= $form->field($model, 'item_price_description',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"])->textarea([])?>
</div>

<div class="form-group custom_description">
	<?= $form->field($model, 'item_customization_description',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"])->textarea(['maxlength' => 128])?>
</div>

<!-- guide image -->
<div class="form-group guide_image">
<?= $form->field($model, 'guide_image[]',['template' => "{label}<div class='controls append_address'>{input}</div> {hint} {error}"
		])->fileInput(['multiple' => true]) ?>

</div>
<!-- BEGIN display exist images -->
<?php
 if(!empty($guideimagedata)) {
         	$img1 = $action1 = '';
         	foreach ($guideimagedata as $value) {
			$img1 .= '"<img src='.Yii::getAlias('@web/uploads/guide_images/').$value->image_path.' width=\'175\' height=\'125\' data-key='.$value->image_id.'>"'.',';
			$action1 .='{
			        url: "'.Url::to(['/admin/vendoritem/deleteserviceguideimage']).'",
			        key: '.$value->image_id.',
			    }'.',';
				}

			$img1 = rtrim($img1,',');
			$action1 = rtrim($action1,',');
			}
 ?>
<!-- END display exist images -->

<input type="button" name="btnPrevious" class="btnPrevious btn btn-info" value="Prev">
<input type="button" name="btnNext" class="btnNext btn btn-info" value="Next">
</div>
<!--End Third Tab -->

<div class="tab-pane" id="4">
<div class="file-block" style="color:red"> Please upload aleast one file</div>
<?= $form->field($model, 'item_status',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}"
])->checkbox(['Value' => true,'disabled'=>'disabled'])?>

<input type="button" name="btnPrevious" class="btnPrevious btn btn-info" value="Prev">
<input type="button" name="btnNext" class="btnNext btn btn-info" value="Next">
</div>
<!--End Third Tab -->

<div class="tab-pane" id="5">
<div class="form-group">
<div class="file-block" style="color:red"> Please upload aleast one file</div>
<?= $form->field($model, 'image_path[]')->fileInput(['multiple' => true]) ?>

<?php    if(!$model->isNewRecord){
//print_r($imagedata);die;
 if(!empty($imagedata)) {

         	$img= $action = '';
         	foreach ($imagedata as $value) {
			$img .= '"<img src='.Yii::getAlias('@web/uploads/vendor_images/').$value->image_path.' width=\'175\' height=\'125\' data-key='.$value->image_id.'>"'.',';
			$action .='{
			        url: "'.Url::to(['/admin/vendoritem/deleteitemimage']).'",
			        key: '.$value->image_id.',
			    }'.',';
				}

			$img = rtrim($img,',');
			$action = rtrim($action,',');
			}
 }?>
</div>

 <div class="form-group">

<?= Html::submitButton($model->isNewRecord ? 'Complete' : 'Complete', ['class' => $model->isNewRecord ? 'btn btn-success complete' : 'btn btn-primary complete','style'=>'float:right;']) ?>
</div>
<input type="button" name="btnPrevious" class="btnPrevious btn btn-info" value="Prev">
</div>
</div>
</div>
</div>
<?php ActiveForm::end(); ?>

<script type="text/javascript">

	/* Begin Tabs NEXT & PREV buttons */
	$('.btnNext').click(function(){
	  $('.nav-tabs > .active').next('li').find('a').trigger('click');
	});

	  $('.btnPrevious').click(function(){
	  $('.nav-tabs > .active').prev('li').find('a').trigger('click');
	});

	/* End Tabs NEXT & PREV buttons */

    $(function (){
 	/* Begin when loading page first tab opened */
 	$('.nav-tabs li:first').addClass("active");
 	$(".tab-content div:first").addClass("active");
 	});

	var csrfToken = $('meta[name="csrf-token"]').attr("content");

	 $(function (){
	$('#option').hide();
    $(".vendoritemquestion-question_answer_type").live('change',function (){
		var type = $(this).val();

		if(type =='selection')
		{
			$(this).next('.price_val').remove();
			var j = $(this).attr('id').replace(/vendoritemquestion-question_answer_type/, '');
			$('#option').show();
			$(this).after('<div class="selection"><input type="text" class="form-control" name="Vendoritemquestion['+j+'][text][0][]" placeholder="Question" id="question" style="width:50%;float:left;"><input type="text" class="form-control" name="Vendoritemquestion['+j+'][price][0][]" placeholder="Price (Optional)" id="price" style="width:40%;float:left;"><input type="button" class="add_question" id="add_question'+j+'" data-option-count="1" name="Addss" value="Add Selection"></div>');
		}
		else if(type =='image' ||  type =='text')
		{
			$(this).next('.selection').remove();
			$(this).next('.price_val').remove();
			var j = $(this).attr('id').replace(/vendoritemquestion-question_answer_type/, '');
			$('#option').show();
			$(this).after('<div class="price_val"><input type="text" class="form-control" name="Vendoritemquestion['+j+'][price][]" placeholder="Price (Optional)" id="price" style="width:40%;float:left;"></div>');
		}


		// Add selection for questions //
		});
		var p = 1;

		$('.add_question').live('click',function(){
			var j = $(this).attr('id').replace(/add_question/, '');
			var p = $(this).attr('data-option-count');
			$(this).before('<div class="selection"><input type="text" class="form-control" name="Vendoritemquestion['+j+'][text]['+p+'][]" placeholder="Question" id="question" style="width:50%;float:left;"><input type="text" class="form-control" name="Vendoritemquestion['+j+'][price]['+p+'][]" placeholder="Price (Optional)" id="price" style="width:45%;float:left;"></div>');p++;
			$(this).attr('data-option-count',p);
		})
});


$(function (){
    $("#vendoritem-category_id").change(function (){
        var id = $('#vendoritem-category_id').val();
        var path = "<?php echo Url::to(['/admin/priorityitem/loadsubcategory']); ?> ";
        $('.loadingmessage').show();
        $.ajax({
        type: 'POST',
        url: path,
        data: { id: id ,_csrf : csrfToken},
        success: function( data ) {
        	$('.loadingmessage').hide();
             $('#vendoritem-subcategory_id').html(data);
         }
        })
     });
 });


//* Load Child Category *//
$(function (){
    $("#vendoritem-subcategory_id").change(function (){
		var id = $('#vendoritem-subcategory_id').val();
        var path = "<?php echo Url::to(['/admin/priorityitem/loadchildcategory']); ?> ";
        $('.loadingmessage').show();
        $.ajax({
        type: 'POST',
        url: path,
        data: { id: id ,_csrf : csrfToken},
        success: function( data ) {
												$('.loadingmessage').hide();
            $('#vendoritem-child_category').html(data);
         }
        })
     });
 });

function deletePhoto(image_id, loc){

	var path = "<?php echo Url::to(['vendoritem/imagedelete']); ?> ";
        $.ajax({
        type: 'POST',
        url: path,
        data: { id: image_id ,_csrf : csrfToken, loc : loc},
        success: function( data ) {
			if(data == 'Deleted')
			{
				$('img#'+image_id).parent().remove();
			}
			return false;
         }
        })
}

function deleteAddress(d,question_id) {
	if(question_id != '')
	{
		var r = confirm("Are you sure want to delete?");
		if (r == true) {
		$("#"+d).remove();
		var path = "<?php echo Url::to(['vendoritem/removequestion']); ?> ";
        $.ajax({
        type: 'POST',
        url: path,
        data: { question_id: question_id ,_csrf : csrfToken},
        success: function( data ) {
             alert(data);
         }
        })
        return false;
	 }
	 return false;
	}
  }

$(function()
{
	CKEDITOR.replace('vendoritem-item_description');
});
$(function()
{
	CKEDITOR.replace('vendoritem-item_additional_info');
});
$(function()
{
	CKEDITOR.replace('vendoritem-item_price_description');
});
$(function()
{
	CKEDITOR.replace('vendoritem-item_customization_description');
});

// Question and answer begin
/* 	BEGIN Themes and groups multiselect widget */
$(function(){
 $('#vendoritem-themes').multiselect({
		'enableFiltering': true,
        'filterPlaceholder': 'Search for something...'
        });
  $('#vendoritem-groups').multiselect({
		'enableFiltering': true,
        'filterPlaceholder': 'Search for something...'
        });

});
/* Price chart for item */
var j= 2;
function addPrice(tis)
{
$(tis).before('<div class="controls'+j+'"><input type="text" id="vendoritem-item_from" class="form-control from_range_'+j+'" name="vendoritem-item_price[from][]" multiple = "multiple" Placeholder="From range"><input type="text" id="vendoritem-item_to" class="form-control to_range_'+j+'" name="vendoritem-item_price[to][]" multiple = "multiple" Placeholder="To range"><input type="text" id="item_price_per_unit" class="form-control price_kd'+j+'" name="vendoritem-item_price[price][]" multiple = "multiple" Placeholder="Price">KD<input type="button" name="remove" id="remove" value="Remove" class="remove_price" onClick="removePrice(this)" /></div>');
j++;
}
function removePrice(tis)
{
	var r = confirm("Are you sure want to delete?");
		if (r == true) {
		$(tis).parent().remove();
        return false;
	 	}
}
/* Price chart for item */

/* END Themes and groups multiselect widget */
/* BEGIN bootstrap file input widget for image preview */
$(document).on('ready', function() {
	$('.file-block').hide();
	/* Sort item image */
    $("#vendoritem-image_path").fileinput({
    	showUpload:false,
		showRemove:false,
		<?php if(!empty($imagedata)) { ?>
		initialPreview: [
			<?php echo $img; ?>,
			],

		initialPreviewConfig: [
		   <?php echo $action; ?>,
		],
		<?php } ?>
		overwriteInitial: false,
    	uploadUrl : '/dummy/dummy',
	});

	/* Sort guide image */
	$("#vendoritem-guide_image").fileinput({
    	showUpload:false,
		showRemove:false,
		<?php if(!empty($guideimagedata)) { ?>
		initialPreview: [
			<?php echo $img1; ?>,
			],

		initialPreviewConfig: [
		   <?php echo $action1; ?>,
		],
		<?php } ?>
		overwriteInitial: false,
    	uploadUrl : '/dummy/dummy',
   		});

		/* BEGIN SORT code for item and guide images */
		var path = "<?php echo Url::to(['/admin/image/imageorder']); ?> ";
		$(".file-preview-thumbnails").sortable({
			items:'> div.file-preview-initial',
        stop : function(event, ui){
		var newArray = $(this).sortable("toArray",{key:'s'});
		sort = [];
		var id = newArray.filter(function(v){return v!==''});
			for(var p=0;p<id.length;p++){
				sort.push($('div#'+id[p]+'').attr('data-key'));
			}
		$.ajax({
        type: 'POST',
        url: path,
        data: { id: id,sort:sort,_csrf : csrfToken},
        success: function( data ) {
            // fine
         }
		})
	  }
	});
	/* END SORT code for item and guide images */

	$(".file-preview-initial > img").each(function(){
		$(this).parent().attr('data-key',$(this).attr('data-key'));
	});
});

<?php if($model->isNewRecord)	{	?>
       $('#vendoritem-item_for_sale').prop('checked', true);
    <?php }  else
    			{
	      		 if($model->item_for_sale=='Yes')	{	?>
	       		 $('#vendoritem-item_for_sale').prop('checked', true);
	        	<?php } else { ?>
	       		$('#vendoritem-item_for_sale').prop('checked', false);
	     		<?php }
	     		 if($model->item_status=='Active')	{	?>
	       		 $('#vendoritem-item_status').prop('checked', true);
	        	<?php } else { ?>
	       		$('#vendoritem-item_status').prop('checked', false);
	     		<?php }
  		} ?>
/* END bootstrap file input widget for image preview */

/*BEGIN  VALIDATION */

$("#validone1").click(function() {

	if($('#test').val()==1)
	{
		return false;
	}


  if($("#vendoritem-item_name").val()=='')
	{
			$(".field-vendoritem-item_name").addClass('has-error');
			$(".field-vendoritem-item_name").find('.help-block').html('Item name cannot be blank.');
			return false;
  }
  if($("#vendoritem-category_id").val()=='')
	{
			$(".field-vendoritem-category_id").addClass('has-error');
			$(".field-vendoritem-category_id").find('.help-block').html('Category cannot be blank.');
			return false;
  }
  if($("#vendoritem-subcategory_id").val()=='')
	{
			$(".field-vendoritem-subcategory_id").addClass('has-error');
			$(".field-vendoritem-subcategory_id").find('.help-block').html('Subcategory cannot be blank.');
			return false;
  }
  if($("#vendoritem-child_category").val()=='')
	{
			$(".field-vendoritem-child_category").addClass('has-error');
			$(".field-vendoritem-child_category").find('.help-block').html('Child category cannot be blank.');
			return false;
  }
   //validate email already exist or not
 	var item_len = $("#vendoritem-item_name").val().length;
     if($("#vendoritem-item_name").val()=='')
	 {
	 	$(".field-vendoritem-item_name").addClass('has-error');
			$(".field-vendoritem-item_name").find('.help-block').html('Item name cannot be blank.');
			return false;
	 }
	 else if(item_len < 4){

	 			$(".field-vendoritem-item_name").addClass('has-error');
	 			$(".field-vendoritem-item_name").find('.help-block').html('Item name minimum 4 letters.');
				return false;
	 } else if(item_len > 3)
	{

		var mail=$("#vendoritem-item_name").val();
        var path = "<?php echo Url::to(['/admin/vendoritem/itemnamecheck']); ?> ";
        $('.loadingmessage').show();
        var item_id = <?php echo isset($_GET['id']) ? $_GET['id'] :  '0'; ?>;
        $.ajax({
        type: 'POST',
        url: path, //url to be called
        data: { item: mail , item_id : item_id, _csrf : csrfToken}, //data to be send
        success: function( data ) {
			$("#test").val(mail);
            if(data>0)
            {
			$('.loadingmessage').hide();
			$(".field-vendoritem-item_name").removeClass('has-success');
			$(".field-vendoritem-item_name").addClass('has-error');
			$(".field-vendoritem-item_name").find('.help-block').html('Item name already exists.');
			$(".field-vendoritem-item_name" ).focus();
			$('#test').val(1);
			}
			else
			{
			$(".field-vendoritem-item_name").find('.help-block').html('');
			$('.loadingmessage').hide();
			$('#test').val(0);
			}
         }
        });
  	}
	else
	  {
	  	return true;
	  }
	});

/* BEGIN TAB 2 */
$("#validtwo2").click(function() {

  if($("#vendoritem-item_name").val()=='')
	{
			$(".field-vendoritem-item_name").addClass('has-error');
			$(".field-vendoritem-item_name").find('.help-block').html('Item name cannot be blank.');
			return false;
  }
  if($("#vendoritem-category_id").val()=='')
	{
			$(".field-vendoritem-category_id").addClass('has-error');
			$(".field-vendoritem-category_id").find('.help-block').html('Category cannot be blank.');
			return false;
  }
  if($("#vendoritem-subcategory_id").val()=='')
	{
			$(".field-vendoritem-subcategory_id").addClass('has-error');
			$(".field-vendoritem-subcategory_id").find('.help-block').html('Subcategory cannot be blank.');
			return false;
  }
  if($("#vendoritem-child_category").val()=='')
	{
			$(".field-vendoritem-child_category").addClass('has-error');
			$(".field-vendoritem-child_category").find('.help-block').html('Child category cannot be blank.');
			return false;
  }

   if($("#vendoritem-type_id").val()=='')
	{
			$(".field-vendoritem-type_id").addClass('has-error');
			$(".field-vendoritem-type_id").find('.help-block').html('Item type cannot be blank.');
			return false;
  	}

  	var messageLength = CKEDITOR.instances['vendoritem-item_description'].getData().replace(/<[^>]*>/gi, '').length;
        if(!messageLength ) {
        $(".field-vendoritem-item_description").addClass('has-error');
		$(".field-vendoritem-item_description").find('.help-block').html('Item description cannot be blank.');
		return false;
     }
  else
  {return true;}
});


/* BEGIN TAB 3 */
$("#validthree3").click(function() {

  if($("#vendoritem-item_name").val()=='')
	{
			$(".field-vendoritem-item_name").addClass('has-error');
			$(".field-vendoritem-item_name").find('.help-block').html('Item name cannot be blank.');
			return false;
  }
  if($("#vendoritem-category_id").val()=='')
	{
			$(".field-vendoritem-category_id").addClass('has-error');
			$(".field-vendoritem-category_id").find('.help-block').html('Category cannot be blank.');
			return false;
  }
  if($("#vendoritem-subcategory_id").val()=='')
	{
			$(".field-vendoritem-subcategory_id").addClass('has-error');
			$(".field-vendoritem-subcategory_id").find('.help-block').html('Subcategory cannot be blank.');
			return false;
  }
  if($("#vendoritem-child_category").val()=='')
	{
			$(".field-vendoritem-child_category").addClass('has-error');
			$(".field-vendoritem-child_category").find('.help-block').html('Child category cannot be blank.');
			return false;
  }
	/* BEGIN Validate item for sale yes or no */
  if($("#vendoritem-item_for_sale").prop('checked') == true)
  {
	if($("#vendoritem-item_amount_in_stock").val()=='')
	{
			$(".field-vendoritem-item_amount_in_stock").addClass('has-error');
			$(".field-vendoritem-item_amount_in_stock").find('.help-block').html('Item number of stock cannot be blank.');
			return false;
 	 }
    if($("#vendoritem-item_default_capacity").val()=='')
	{
			$(".field-vendoritem-item_default_capacity").addClass('has-error');
			$(".field-vendoritem-item_default_capacity").find('.help-block').html('Item default capacity cannot be blank.');
			return false;
  	}
 	 if($("#vendoritem-item_how_long_to_make").val()=='')
	{
			$(".field-vendoritem-item_how_long_to_make").addClass('has-error');
			$(".field-vendoritem-item_how_long_to_make").find('.help-block').html('No of days delivery cannot be blank.');
			return false;
 	 }
 	 if($("#vendoritem-item_minimum_quantity_to_order").val()=='')
	{
			$(".field-vendoritem-item_minimum_quantity_to_order").addClass('has-error');
			$(".field-vendoritem-item_minimum_quantity_to_order").find('.help-block').html('Item minimum quantity to order cannot be blank.');
			return false;
  	}
   }
  if($("#vendoritem-type_id").val()=='')
	{
			$(".field-vendoritem-type_id").addClass('has-error');
			$(".field-vendoritem-type_id").find('.help-block').html('Item type cannot be blank.');
			return false;
  }

  else
  {return true;}
});


$('.complete').click(function()
{
	if($(".file-preview-thumbnails img").length <= 0)
	{
		$(".field-vendoritem-image_path").addClass('has-error');
			$(".field-vendoritem-image_path").find('.help-block').html('Upload atleast one image.');
			return false;
	}
 });

/* Guide images and descrition show / hide */

$(function(){
	$('.custom_description').hide();
	$('.guide_image').hide();
	$('.mandatory').show();

	$('#vendoritem-item_for_sale').click(function()
	{
		if($(this).is(':checked'))
		{
		$('.custom_description').hide();
		$('.guide_image').hide();
		$('.mandatory').show();
		}
		else
		{
			$('.mandatory').hide();
			$('.custom_description').show();
			$('.guide_image').show();
		}
	});

	<?php if(!$model->isNewRecord) { ?>
		if($("#vendoritem-item_for_sale").prop('checked') == true){
				$('.custom_description').hide();
				$('.guide_image').hide();
				$('.mandatory').show();
			}
			else
			{
				$('.custom_description').show();
				$('.guide_image').show();
				$('.mandatory').hide();
			}
	<?php } ?>
});

/* Guide images and descrition show / hide */

/* END VALIDATION */
</script>
<!-- multi select begin -->
<script>

/* BEGIN Vendor item check exist or not */
 $(function () {

 $("#vendoritem-item_name").on('keyup keypress focusout',function () {
	if($("#vendoritem-item_name").val().length > 3)
	{
		var mail=$("#vendoritem-item_name").val();
        var path = "<?php echo Url::to(['/admin/vendoritem/itemnamecheck']); ?> ";
        $('.loadingmessage').show();
        var item_id = <?php echo isset($_GET['id']) ? $_GET['id'] : '0'; ?>;
        $.ajax({
        type: 'POST',
        url: path,
        data: { item: mail ,item_id : item_id, _csrf : csrfToken},
        success: function( data ) {
			$("#test").val(mail);
            if(data>0)
            {
			$('.loadingmessage').hide();
			$(".field-vendoritem-item_name").removeClass('has-success');
			$(".field-vendoritem-item_name").addClass('has-error');
			$(".field-vendoritem-item_name").find('.help-block').html('Item name already exists.');
			$(".field-vendoritem-item_name" ).focus();
			$('#test').val(1);
			}
			else
			{
			$(".field-vendoritem-item_name").find('.help-block').html('');
			$('.loadingmessage').hide();
			$('#test').val(0);
			}
         }
        });
  }
});
});
/* END Vendor item check exist or not */

// single question view
function questionView(q_id,tis){
	var check = $('.show_ques'+q_id).html();
	if(check==''){
	var path = "<?php echo Url::to(['vendoritem/renderquestion']); ?> ";
	$.ajax({
		type : 'POST',
		url :  path,
		data: { q_id: q_id ,_csrf : csrfToken},
        success: function( data ) {
        $('.show_ques'+q_id).html(data);
        $(tis).toggleClass("expanded");
        return false;
        }
	})
	}else{
			$('.show_ques'+q_id).toggle();
			$(tis).toggleClass("expanded");
	}
}
</script>
<script type="text/javascript" src="<?= Url::to("@web/themes/default/plugins/bootstrap-multiselect/dist/js/bootstrap-multiselect.js") ?>"></script>
<link href="<?= Url::to("@web/themes/default/plugins/bootstrap-multiselect/dist/css/bootstrap-multiselect.css") ?>" rel="stylesheet" type="text/css" />
<!-- multi select end -->

<!-- Bootatrap file input widget -->
<link rel="stylesheet" href="<?= Url::to("@web/themes/default/plugins/bootstrap-fileinput/fileinput.min.css") ?>" rel="stylesheet" type="text/css" media="screen">
<script src="<?= Url::to("@web/themes/default/plugins/bootstrap-fileinput/fileinput.min.js") ?>" type="text/javascript"></script>
<!-- Bootatrap file input widget -->

<script src="<?= Url::to("@web/themes/default/plugins/ckeditor/ckeditor.js") ?>" type="text/javascript"></script>


<style>
input#question{  margin: 10px 5px 10px 0px;  float: left;  width: 45%;}
input#price{	margin: 10px 5px 10px 0px;  float: left;  width: 45%;}
.price_val{  width: 100%;  float: left;}
.question-section input[type="text"] { margin:10px 0px;}
</style>
