<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use kartik\sortable\Sortable;
use backend\models\Vendor;
use backend\models\Vendoritemquestion;
use dosamigos\fileupload\FileUploadUI;

/* @var $this yii\web\View */
/* @var $model backend\models\Vendoritem */
/* @var $form yii\widgets\ActiveForm */
if($model->isNewRecord){
$categoryname = array();
$subcategory = array();
$childcategory = array();
$exist_themes =array();
$exist_groups = array();
}
?>

<div class="col-md-12 col-sm-12 col-xs-12">
<?php $form = ActiveForm::begin(['options'=>['enctype'=>'multipart/form-data']]); ?>
<div class="loadingmessage" style="display: none;">
<p>
<?= Html::img(Yii::getAlias('@web/themes/default/img/loading.gif'), ['class'=>'','width'=>'64px','height'=>'64px','id'=>'loading','alt'=>'loading']);?> 
</p>
</div>
<!-- Begin Twitter Tabs-->
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
      <a href="#4" data-toggle="tab" id="validthree3"> Approval </a>
    </li>  
    <li>
      <a href="#5" data-toggle="tab" id="validfour4">Images</a>
    </li>    
    <li>
      <a href="#6" data-toggle="tab" id="validfive5">Themes & Groups</a>
    </li>     
    <!-- BEGIN if item type sales question and answer tab will be display -->
    <?php if(!$model->isNewRecord && $model->item_for_sale =='Yes') {?>   
    <li>
      <a href="#7" data-toggle="tab" id="validsix6"> Questions </a>
    </li>    
    <?php } ?>          
    <!-- END if item type sales question and answer tab will be display -->
  </ul>  
  
<div class="tab-content">
<!-- Begin First Tab -->
<div class="tab-pane" id="1" >
<!-- vid - click create item button from item view page for the particular item view page-->
<?php if(isset($_GET['vid'])) {  
$vendor_name = Vendor::getvendorname($_GET['vid']); ?>
<div class="form-group"><?= $form->field($model, 'vendor_id',['template' => "{label}<div class='controls'>{input}</div>{hint}
{error}"])->dropDownList($vendor_name) ?></div>

<!-- if its update form disable particular vendor-->
<?php } else if (!$model->isNewRecord) {?>
<div class="form-group"><?= $form->field($model, 'vendor_id',['template' => "{label}<div class='controls'>{input}</div>{hint}
{error}"])->dropDownList($vendorname, ['prompt'=>'Select...', "disabled"=>"disabled"]) ?></div>
<?php } else { ?>

<!-- if its create form-->
<div class="form-group"><?= $form->field($model, 'vendor_id',['template' => "{label}<div class='controls'>{input}</div>{hint}
{error}"])->dropDownList($vendorname, ['prompt'=>'Select...']) ?></div>
<?php } ?>

<div class="form-group">
	<?= $form->field($model, 'item_name',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"])->textInput(['maxlength' => 128,'autocomplete' => 'off'])?>
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

<!-- BEGIN ITEM TYPE -->

<div class="form-group"><?= $form->field($model, 'type_id',['template' => "{label}<div class='controls'>{input}</div>{hint}
{error}"])->dropDownList($itemtype, ['prompt'=>'Select...']) ?></div>

<!-- END ITEM TYPE -->

<div class="form-group">
	<?= $form->field($model, 'item_description',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"])
	->label('Item_description'.Html::tag('span', '*',['class'=>'required']))->textarea(['maxlength' => 128])?>
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

<?php if($model->isNewRecord) { ?>
<!-- BEGIN if type is sale -->
<div class="form-group single_price">
	<?= $form->field($model, 'item_price_per_unit',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"])->textInput(['maxlength' => 128])?>
</div> 
<!-- END if type is sale -->

<!-- BEGIN if type is rental or service -->
<div class="form-group multiple_price" style="padding: 5px;  font-size: 14px;">
	<div class="multi_pricing">Price range From - To </div> 
	<div class="controls1"><input type="text" id="vendoritem-item_from" class="form-control from_range_1" name="vendoritem-item_price[from][]" multiple="multiple" placeholder="From range"><input type="text" id="vendoritem-item_to" class="form-control to_range_1" name="vendoritem-item_price[to][]" multiple="multiple" placeholder="To range"><input type="text" id="item_price_per_unit" class="form-control price_kd_1" name="vendoritem-item_price[price][]" multiple="multiple" placeholder="Price">KD<input type="button" name="remove" id="remove" value="Remove" class="remove_price" onclick="removePrice(this)"></div>
	<input type="button" class="add_price" name="addprice" id="addprice" value="Add more" onClick="addPrice(this);" /> 
</div>

<div class="form-group">
	<?= $form->field($model, 'item_price_description',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"])->textarea(['maxlength' => 128])?>
</div> 

<div class="form-group custom_description">
	<?= $form->field($model, 'item_customization_description',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"])->textarea(['maxlength' => 128])?>
</div> 
 
<div class="form-group guide_image">
  
<?= $form->field($model, 'guide_image[]',['template' => "{label}<div class='controls append_address'>{input}</div> {hint} {error}" 
		])->fileInput(['multiple' => true]) ?> 

</div>

<?php } else if(!$model->isNewRecord){ ?>

<div class="form-group single_price">
	<?= $form->field($model, 'item_price_per_unit',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"])->textInput(['maxlength' => 128])?>
</div> 

<div class="form-group multiple_price" style="padding: 5px;  font-size: 14px;">
	<div class="multi_pricing">Price  From - To </div> 

	<?php $t=0; 
	foreach ($loadpricevalues as $value) {  ?>
			
	<div class="controls<?= $t; ?>"><input type="text" id="vendoritem-item_from" class="form-control from_range_<?= $t; ?>" name="vendoritem-item_price[from][]" multiple = "multiple" Placeholder="From range" value="<?= $value['range_from'];?>"><input type="text" id="vendoritem-item_to" class="form-control to_range_<?= $t; ?>" name="vendoritem-item_price[to][]" multiple = "multiple" Placeholder="To range" value="<?= $value['range_to'];?>"><input type="text" id="item_price_per_unit" class="form-control price_kd_<?= $t; ?>" name="vendoritem-item_price[price][]" multiple = "multiple" Placeholder="Price" value="<?= $value['pricing_price_per_unit'];?>">KD<input type="button" name="remove" id="remove" value="Remove" class="remove_price" onClick="removePrice(this)" /></div>

	<?php $t++; }?>
	<input type="button" class="add_price" name="addprice" id="addprice" value="Add more" onClick="addPrice(this);" /> 
</div>

<div class="form-group">
	<?= $form->field($model, 'item_price_description',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"])->textarea(['maxlength' => 128])?>
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
 }?>
<!-- END display exist images -->

<input type="button" name="btnPrevious" class="btnPrevious btn btn-info" value="Prev">
<input type="button" name="btnNext" class="btnNext btn btn-info" value="Next">

</div>
<!--End third Tab -->

<div class="tab-pane" id="4">
<div class="form-group">
	<?= $form->field($model, 'item_approved',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"])->dropDownList([ 'Pending' => 'Pending','Yes' => 'Yes', 'Rejected'=>'Rejected']) ?>
</div> 

<div class="form-group">   

<?= $form->field($model, 'item_status',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
])->checkbox(['Value' => true])?>
</div>

<input type="button" name="btnPrevious" class="btnPrevious btn btn-info" value="Prev">
<input type="button" name="btnNext" class="btnNext btn btn-info" value="Next">
</div>
<!--End fourth Tab -->

<div class="tab-pane" id="5">
<div class="file-block" style="color:red"> Please upload aleast one file</div>  
 <div class="form-group">
<?= $form->field($model, 'image_path[]',['template' => "{label}<div class='controls append_address'>{input}</div> {hint} {error}" 
		])->fileInput(['multiple' => true]) ?>
		
</div>

<?php if(!$model->isNewRecord){  
 if(!empty($imagedata)) {
         	$img= $action = '';
         	foreach ($imagedata as $value) { 
       			$img .= '"<img src='.Yii::getAlias('@vendor_item_images_210').$value->image_path.' width=\'175\' height=\'125\' data-key='.$value->image_id.'>"'.',';
       			$action .='{   	 					        
       			        url: "'.Url::to(['/admin/vendoritem/deleteitemimage']).'",
       			        key: '.$value->image_id.',       
       			    }'.',';
       				}
			
			$img = rtrim($img,',');
			$action = rtrim($action,',');
			}
 }?>
<input type="button" name="btnPrevious" class="btnPrevious btn btn-info" value="Prev">
<input type="button" name="btnNext" class="btnNext btn btn-info" value="Next">
</div>
<!--End fifth Tab -->

<div class="tab-pane" id="6">

<!-- BEGIN Exist THEME list displayed on JSON format-->
 <div class="form-group"><?= $form->field($model, 'themes',['template' => "{label}<div class='controls'>{input}</div>{hint}
{error}"])->dropDownList($themelist , ['multiple'=>'multiple',]) ?></div>
 <!-- BEGIN Exist theme list displayed on JSON format-->
 <div class="form-group"><?= $form->field($model, 'groups',['template' => "{label}<div class='controls'>{input}</div>{hint}
{error}"])->dropDownList($grouplist , ['multiple'=>'multiple']) ?></div>

 <!-- BEGIN Exist theme list displayed on JSON format-->

<input type="button" name="btnPrevious" class="btnPrevious btn btn-info" value="Prev">

<?= Html::submitButton($model->isNewRecord ? 'Complete' : 'Complete', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-success', 'style'=>'float:right;']) ?>
</div>

<!--End sixth Tab -->

<!-- Begin Question Answer Part -->
<div class="tab-pane" id="7">

<div class="questionanswer" >
<?php 
	 $exist_question = Vendoritemquestion::find()->where( [ 'item_id' => $model->item_id ] )->count();
	
	if($exist_question >= 1) {	
	$count_q=(count($model_question)); // for initial count questions used in javascript;	
	 $t=0;
	 foreach($model_question as $question_records)
	 {		 
	?>	 
	 	<div class="form-group superbox-s" id="delete_<?= $t;?>"> 
		<li id="question-section_0" class="parent_question_<?= $question_records['question_id']; ?>"> <span class="question_title"> <?= $question_records['question_text']; ?></span> <span class="plus"><a href="#" onclick="questionView('<?= $question_records['question_id']; ?>',this)" ></a></span><div class="show_ques<?= $question_records['question_id']; ?>"></div></li>	
	</div>
	<?php $t++;}	?>		
	<input type="button" name="add" id="add" value="Add Question" onclick="addAddress(this)" style="margin:10px 0px;">
<?php 

} else {  
	$count_q=1;
	$h_id =0;
	?>
<div class="form-group"> 
	<div id="question-section" class="question-section"> 
	<input type="hidden" name="parent_id" id="adds" value="0" class="form-control temp_qa">
	Question <input type="text" id="question_text_0" class="form-control temp_qa" name="Vendoritemquestion[0][question_text][]" style="margin:10px 0px;"> Question Type	
		<div class="append_address">
			<select id="vendoritemquestion-question_answer_type0" class="form-control vendoritemquestion-question_answer_type temp_qa" name="Vendoritemquestion[0][question_answer_type][]" parent_id="0" style="margin:10px 0px;">
			<option value="">Choose type</option>
			<option value="text">Text</option>
			<option value="image">Image</option>
			<option value="selection">Selection</option></select>
		</div>
		</div>
	</div>
		<div class="question">
		</div>	
	<input type="button" name="add" id="add" value="Add Question" onclick="addAddress(this)" style="margin:10px 0px;">
<?php } ?>

<!-- Question Answer Part	End	-->
<div class="form-groups" >

<input type="button" name="btnPrevious" class="btnPrevious btn btn-info" value="Prev">
<?= Html::a('Back to Manage', ['index', ], ['class' => 'btn btn-info', 'style'=>'float:right;']) ?>
</div>
</div>
 </div>
 </div>
</div>
<?php ActiveForm::end(); ?>
<script src="<?php echo Yii::$app->themeURL->createAbsoluteUrl(''); ?>plugins/ckeditor/ckeditor.js" type="text/javascript"></script>
<script type="text/javascript">
/* BEGIN CKeditor  widget */
 $(function()
{
	CKEDITOR.replace('vendoritem-item_description');
});

$(function()
{
	CKEDITOR.replace('vendoritem-item_price_description');
});

$(function()
{
	CKEDITOR.replace('vendoritem-item_customization_description');
});
$(function()
{
	CKEDITOR.replace('vendoritem-item_additional_info');
});
/* END CKeditor  widget */

	var csrfToken = $('meta[name="csrf-token"]').attr("content");	

	/* Begin Tabs NEXT & PREV buttons */
	$('.btnNext').click(function(){
	  $('.nav-tabs > .active').next('li').find('a').trigger('click');
	});

	  $('.btnPrevious').click(function(){
	  $('.nav-tabs > .active').prev('li').find('a').trigger('click');
	});   
	/* End Tabs NEXT & PREV buttons */

		
 $(function (){  	

 	/* For themes and groups list checkbox alignment*/
 	$(".themelists:last-child").css({"clear" : "both","float" :"inherit"});
 	/* For themes and groups list checkbox alignment*/

	$('#option').hide();
	 });

		// if it is new record //
    $(".vendoritemquestion-question_answer_type").live('change',function (){						
		var type = $(this).val();
		var parent_id = $(this).attr("parent_id");
		var parent = $(this).attr("data-parent");
		parent = (parent =='' || parent==undefined)?'':parent;
			
		if(type =='selection')
		{
			$(this).next('.price_val').remove();
			$(this).next('.image_val').remove();
			var j1 = $(this).attr('id').replace(/vendoritemquestion-question_answer_type/, '');			
			
			var level_text = ($(this).attr('name').replace('[question_answer_type][]','')+'[text][0][]');
			var level_price = ($(this).attr('name').replace('[question_answer_type][]','')+'[price][0][]');
			var level_hidden = ($(this).attr('name').replace('[question_answer_type][]','')+'[hidden][0][]');
			
			var que_id = $('input#ans_id').val();					
			$('#option').show();	
			$(this).after('<div class="selection"><input type="text" class="form-control question temp_qa" name="'+level_text+'" placeholder="Answer" id="question" style="width:40%;float:left;"><input type="text" class="form-control temp_qa" name="'+level_price+'" placeholder="Price (Optional)" id="price" style="width:35%;float:left;"><input type="hidden" name="Vendoritemquestion[0][hidden][0][]" class="form-control answer" style="width:5%;float:left;"><input type="hidden" id="subquestion" value="Add" class="add-sub-question temp_qa" data-name="'+level_text+'" onclick="addsubquestions(this)"><input type="button" id="viewquestion" value="View" class="add-sub-question temp_qa" data-name="'+level_text+'" onclick="viewsubquestions(this)"></div><input type="button" class="add_question" id="add_question'+j1+'" data-name="'+level_text+'" data-parent ="'+parent+'" value="Add Selection"> <input type="button" class="save" name="save" value="Save" onclick="savequestion(\''+type+'\','+parent_id+',this)"><input type="button" value="Guide Image" id="" class="saves" data-toggle="modal" data-target="#myModal" onclick="checkupload(this)"><div class="question_success">Successfully added</div>');	
			// remove current div add button
			$(this).parent().parent().find('input#subquestion').hide();	//hide before add
			$(this).parent().parent().find('input#viewquestion').hide(); //hide before add
			$(this).parent().find('input.saves').hide(); //hide before add
		}
		else if(type =='image')
		{			
			$(this).next('.selection').remove();
			$(this).next('.price_val').remove();
			$(this).parent().find('.add_question').remove();
			$(this).parent().find('.save').remove();		
			var j1 = $(this).attr('id').replace(/vendoritemquestion-question_answer_type/, '');
			$('#option').show();
				
			$(this).after('<div class="image_val"><input type="file" class="form-control upimage"  multiple="true" name="Vendoritemquestion['+j1+'][image][]" placeholder="Image (Optional)" id="guide_image" style="width:40%;"><input type="button" class="savebutton" name="save" value="Save" onclick="savequestion(\''+type+'\','+parent_id+',this)"><input type="button" value="Guide Image" id="" class="saves" data-toggle="modal" data-target="#myModal" onclick="checkupload(this)"><div class="question_success">Successfully added</div></div>');	
			$(this).parent().find('input.saves').hide(); //hide before add
		}

		else if(type =='text')
		{			
			$(this).next('.selection').remove();
			$(this).next('.image_val').remove();
			$(this).parent().find('.add_question').remove();
			$(this).parent().find('.save').remove();			
			var j1 = $(this).attr('id').replace(/vendoritemquestion-question_answer_type/, '');
			$('#option').show();

			$(this).after('<div class="price_val"><input type="text" class="form-control" name="Vendoritemquestion['+j1+'][price][]" placeholder="Price (Optional)" id="price" style="width:40%;"><input type="button" class="savebutton" name="save" value="Save" onclick="savequestion(\''+type+'\','+parent_id+',this)"><input type="button" value="Guide Image" id="" class="saves" data-toggle="modal" data-target="#myModal" onclick="checkupload(this)"><div class="question_success">Successfully added</div></div>');	
			$(this).parent().find('input.saves').hide(); //hide before add
		}		
		// Add selection for questions //
		}); 
		$('.add_question').live('click',function(){ 	
			
			var j = $(this).attr('id').replace(/add_question/, '');
			var par = $(this).attr('data-parent');
			var p = ($(this).parent().find('.add-sub-question').length);
			var na = $(this).attr('data-name');
			console.log(na);
			var new_n = na.substring(0,(na.lastIndexOf('[]')-3));
			var new_p = na.substring(0,(na.lastIndexOf('[]')-9));
			var ques_txt =(new_n+'['+p+'][]');
			var ques_ans =(new_p+'[price]['+p+'][]');

			$(this).before('<div class="selection"><input type="text" class="form-control question" name="'+ques_txt+'" placeholder="Answer" id="question" style="width:40%;float:left;"><input type="text" class="form-control"  placeholder="Price (Optional)" name="'+ques_ans+'" id="price" style="width:40%;float:left;"><input type="hidden" class="form-control answer" name="Vendoritemquestion[0][hidden][0][]" style="width:5%;float:left;"><img src="<?php echo Yii::$app->params['appImageUrl']."remove.png"; ?>" class="selection_delete" onclick="deletequestionselection(this)"><input type="hidden" id="subquestion" value="Add" class="add-sub-question temp_qa" data-name="'+ques_txt+'"  onclick="addsubquestions(this)"><input type="button" id="viewquestion" value="View" class="add-sub-question temp_qa" data-name="'+ques_txt+'" onclick="viewsubquestions(this)"></div>');	p++;			 					
			$('input#subquestion').hide(); //hide before add
			$('input#viewquestion').hide();		


		});				
		
function savequestion(typ,q_parent,tis)
{	
	<?php if(isset($model->item_id)) { echo $item_id = $model->item_id; } else { echo $item_id = 0;};?>
	//Hide once question added
	$('input#subquestion').show(); //hide before add	
	if(typ=='selection')
	{
		var parent_div = $(tis).parent().parent().attr('id');			
	}
	else if(typ =='image')
	{
		var parent_div = $(tis).parent().parent().parent().attr('id');		
	}
	else if(typ =='text'){
		var parent_div = $(tis).parent().parent().parent().attr('id');
	}		 
		var serial_div = $("#"+parent_div+" :input").serializeArray();

	var path = "<?php echo Url::to(['/admin/vendoritem/addquestion']); ?> ";
	$.ajax({  
        type: 'POST',      
        dataType: 'json',             
        url: path, //url to be called
        data: {serial_div : serial_div, item_id : <?= $item_id; ?> }, //data to be send
        success: function( data ) {  
        	$(tis).parent().find('.saves').show();
        	$(tis).parent().find('.question_success').show();
        	$(tis).parent().find('.question_success').fadeOut(3000);
          if(typ =='selection')
          {
          $(tis).parent().find('.saves').attr('id',data[0].response.parent_id);
          $.each(data[0].response.answers, function( index, value ) {
		  $(tis).parent().find('.answer').eq(index).attr('value',value);
		  $(tis).parent().find('.selection_delete').eq(index).attr('id',value);
		  $(tis).parent().find('.answer').eq(index).next().addClass("answer_"+value+"");
		  $(tis).parent().find('.answer').eq(index).next().next().addClass("view_"+value+"");
		  });
		  }
		  else if('image')
		  {		  	
		  	$(tis).parent().find('.saves').attr('id',data[0].response.parent_id);
		  	//	BEGIN Upload image and insert images to tables.
				var myfiles = document.getElementById("guide_image");
				var files = myfiles.files;     
			    var form_data = new FormData(); 
			    
			    //form_data.append('file', file_data);
			    for (i = 0; i < files.length; i++) {
		               form_data.append('file' + i, files[i]);
		        }
		        form_data.append('question_id',data[0].response.parent_id); 
		        form_data.append('item_id',<?= $item_id; ?>);
		        
		        var path = "<?php echo Url::to(['/admin/vendoritem/guideimage']); ?> "; 	       
			    $.ajax({  
		        type: 'POST',      
		        dataType: 'json',  // what to expect back from the PHP script, if anything
		        cache: false,
		        contentType: false,
		        processData: false,       
		        url: path, //url to be called
		        data: form_data, //data to be send  
		        success: function( data ) { 
		        	        	
		       }
		       });	     
			//	END Upload image and insert images to tables.	
		  }        
         return false;        	
       }

    })
}

// Add sub questions
var i = <?= $exist_question + 1; ?>;
function addsubquestions(tis) 
{			
	var ans_id = $(tis).parent().find('input.answer').val(); 		
 // var quest_val = q_parent ;
	var ques_txt = ($(tis).attr('data-name').replace('[]','')+'[question_text][]');
	var ques_ans = ($(tis).attr('data-name').replace('[]','')+'[question_answer_type][]');

	$(tis).parent().parent().parent().after('<div id="question-section_'+i+'" class="question-section"> <div style="width:100%; height:25px;float:left;">Level '+i+' </div> <input type="hidden" id="parentid_'+ans_id+'" value="'+ans_id+'"class="form-control" name="parent_id" placeholder="Parent Question ">Question <input type="text" id="question_text_'+j+'" class="form-control" name="'+ques_txt+'" style="margin:10px 0px;"> Question Type	<div class="append_address"><select id="vendoritemquestion-question_answer_type'+j+'" class="form-control vendoritemquestion-question_answer_type" name="'+ques_ans+'" style="margin: 10px 0px;" parent_id="'+ans_id+'" data-parent="'+j+'"><option value="">Choose type</option><option value="text">Text</option><option value="image">Image</option><option value="selection">Selection</option></select></div><input type="button" style="float:right; margin:0px 5px 5px 0px;" class="delete_'+j+'" onclick=deleteAddress('+ans_id+',this) value=Delete><input type="button" style="float:right; margin:0px 5px 5px 0px;" class="hide_'+j+'" onclick=hideQuestion("hide_'+j+'",this) value=Hide></div>');
	
	j++;	
	i++;	
}

function deleteAddress(question_id,tis) {	
	if(question_id != '')
	{		
		var r = confirm("Are you sure want to delete?");				
		if (r == true) {
		$(tis).parent().parent().parent().parent().hide(); 
		var path = "<?php echo Url::to(['/admin/vendoritem/removequestion']); ?> ";
        $.ajax({  
        type: 'POST',      
        url: path, //url to be called
        data: { question_id: question_id ,_csrf : csrfToken}, //data to be send
        success: function( data ) {
             alert(data);
         }
        })
        return false;
	 }	
	 return false;	
	}	
  }

function hideQuestion(question_id,tis) {	
	if(question_id != '')
	{			
		var r = confirm("Are you sure want to hide?");				
		if (r == true) {
			$(tis).parent().hide(); 		
	 	}	
	 return false;	
	}	
}

//* Load Category *// 
 $(function (){ 
	  $("#vendoritem-vendor_id").bind('change',function (){
	  		vendor_load();
	  });

	
 });
function vendor_load(){

	var vendor_id = $('#vendoritem-vendor_id').val();
        var path = "<?php echo Url::to(['/admin/category/vendorcategory']); ?> ";
        $.ajax({  
        type: 'POST',      
        url: path, //url to be called
        data: { vendor_id: vendor_id ,_csrf : csrfToken}, //data to be send
        success: function( data ) {			
             $('#vendoritem-category_id').html(data);
         }
        });
}
//* Load Sub Category *// 
$(function (){ 
    $("#vendoritem-category_id").change(function (){		
		var id = $('#vendoritem-category_id').val();
        var path = "<?php echo Url::to(['/admin/priorityitem/loadsubcategory']); ?> ";
        $('.loadingmessage').show();
        $.ajax({  
        type: 'POST',      
        url: path, //url to be called
        data: { id: id ,_csrf : csrfToken}, //data to be send
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
        url: path, //url to be called
        data: { id: id ,_csrf : csrfToken}, //data to be send
        success: function( data ) {
			$('.loadingmessage').hide();
             $('#vendoritem-child_category').html(data);
         }
        })
     });
 });

// Add questions 
var j=<?= $count_q;?>;
function addAddress(tis) 
{	
	
	$(tis).before('<div class="form-group" id="delete_'+j+'"> <div id="question-section_'+j+'" class="question-section"><input type="hidden" id="parentid_0" value="0" class="form-control" name="parent_id" placeholder="Parent Question ">Question <input type="text" id="question_text_'+j+'" class="form-control" name="Vendoritemquestion['+j+'][question_text][] style="margin:10px 0px;"> Question Type	<div class="append_address"><select id="vendoritemquestion-question_answer_type'+j+'" class="form-control vendoritemquestion-question_answer_type" name="Vendoritemquestion['+j+'][question_answer_type][]" parent_id="'+j+'" style="margin: 10px 0px;"><option value="">Choose type</option><option value="text">Text</option><option value="image">Image</option><option value="selection">Selection</option></select></div><input type="button" style="float:right; margin:0px 5px 5px 0px;" class="delete_'+j+'" onclick=deleteAddress("delete_'+j+'") value=Delete><input type="hidden" style="float:right; margin:0px 5px 5px 0px;" class="hide_'+j+'" onclick=hideQuestion("hide_'+j+'",this) value=Hide></div></div>');	j++;	
	
}

// single question view
function questionView(q_id,tis){
	var check = $('.show_ques'+q_id).html();
	if(check==''){
	var path = "<?php echo Url::to(['/admin/vendoritem/renderquestion']); ?> ";	
	$.ajax({
		type : 'POST',
		url :  path,
		data: { q_id: q_id ,_csrf : csrfToken}, //data to be send
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
/* END Themes and groups multiselect widget */
</script>
<style>
input#question{  margin: 10px 5px 10px 0px;  float: left;  width: 45%;}
input#price, input#image,{	margin: 10px 5px 10px 0px;    width: 45%;}
.selection_delete{	margin:15px 5px 10px 5px; }
.price_val{  width: 100%;  float: left;}
.image_val{  width: 100%;  float: left;}
.question-section input[type="text"] { margin:10px 0px;}
.superbox{ min-height:250px;}
.questionanswer li.parent_question{padding: 5px;  list-style: none;  border: 1px solid #000;}
.questionanswer li.level1_question{  padding: 5px;  list-style: none;  border: 1px solid #000;  margin: 5px 0px 0px 10px;}
.question_toggle{padding: 5px;  list-style: none;   margin: 5px 0px 0px 10px;}
.viewbutton,.savebutton{margin-right: 10px;float: left; }
.form-group li { list-style: none;}
.form-groups  { margin-top: 10px;}
.add_question, .save, .saves { float: left;  margin-right: 10px;}
.question_success{  color: green;  line-height: 5px;  font-weight: bold; display:none; float: left;  margin: 10px 5px 10px 0;}
.superbox-s > li > b { margin:10px 0px 5px 0px;}
.question_title{font-weight: bold;  margin-top: 15px;  line-height: 31px;  font-size: 15px;}
.upimage {margin: 5px 0px 10px 0px;}
</style>
	<?php if(!$model->isNewRecord && isset($_GET['create']) !='') {?>
    <script type="text/javascript">
    $(function (){ 
 	/* Begin when loading page last tab opened */ 	
 	$('.nav-tabs li:last').addClass("active");
 	$("#7").addClass("active");
 	}); 	
 	</script>
 	<?php } else if($model->isNewRecord || !$model->isNewRecord){ ?>
 	<script type="text/javascript">
    $(function (){ 
 	/* Begin when loading page first tab opened */
 	$('.nav-tabs li:first').addClass("active");
 	$(".tab-content div:first").addClass("active");
 	});
 	</script>
 	<?php } ?>
<script>
       <?php if($model->isNewRecord){
               //vendoritem-item_for_sale  ?>
       $('#vendoritem-item_for_sale').prop('checked', true);
       <?php }
       else
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
</script>
<!-- Add more for pricing -->
<script type="text/javascript">

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
			} 
			else
			{ 	
				$('.custom_description').show();		
				$('.guide_image').show();
			}
	<?php } ?>
});
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
</script>

<!-- BEGIN  item gallery script -->
<link rel="stylesheet" href="<?php echo Yii::$app->themeURL->createAbsoluteUrl(''); ?>plugins/jquery-superbox/css/style.css" rel="stylesheet" type="text/css" media="screen">
<script src="<?php echo Yii::$app->themeURL->createAbsoluteUrl(''); ?>plugins/jquery-superbox/js/superbox.js" type="text/javascript"></script>

<script type="text/javascript">
	var csrfToken = $('meta[name="csrf-token"]').attr("content");
	
	$(document).ready(function(){		
	/*  Begin Select all checkbox images */
	$('.check:button').toggle(function(){ 
        $('input:checkbox').attr('checked','checked');
			$(this).val('Uncheck all');
		},function(){
			$('input:checkbox').removeAttr('checked');
			$(this).val('Check all');        
		});
		return false;
	/* End Select all checkbox images */
 });
 

</script>
<!-- END item gallery script -->
<script type="text/javascript">
/* BEGIN bootstrap file input widget for image preview */
$(document).on('ready', function() {
	$('.file-block').hide();
    $("#vendoritem-image_path").fileinput({ 
    	resizeImage: true,   	
    	showUpload:false,
		showRemove:false,
		minImageWidth: 208,
    	minImageHeight: 221,
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
        data: { id: id,sort:sort,_csrf : csrfToken}, //data to be send
        success: function( data ) {
         }          	
		})  
	  }
	});



	$(".file-preview-initial > img").each(function(){
		$(this).parent().attr('data-key',$(this).attr('data-key'));
	});	
});

/* END bootstrap file input widget for image preview */

</script>

<!-- Validdate only on update scenario -->

<script>
$("#validone1").click(function() {

	if($('#test').val()==1)
	{
		return false;
	}

	if($("#vendoritem-vendor_id").val()=='')
	{
			$(".field-vendoritem-vendor_id").addClass('has-error');
			$(".field-vendoritem-vendor_id").find('.help-block').html('Select Vendor name');
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

$("#validtwo2").click(function() { 
	if($("#vendoritem-vendor_id").val()=='')
	{
			$(".field-vendoritem-vendor_id").addClass('has-error');
			$(".field-vendoritem-vendor_id").find('.help-block').html('Select Vendor name');
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
</script>


<script>
$("#validthree3").click(function() { 
	if($("#vendoritem-vendor_id").val()=='')
	{
			$(".field-vendoritem-vendor_id").addClass('has-error');
			$(".field-vendoritem-vendor_id").find('.help-block').html('Select Vendor name');
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

  var messageLength = CKEDITOR.instances['vendoritem-item_description'].getData().replace(/<[^>]*>/gi, '').length;
        if(!messageLength ) {
        $(".field-vendoritem-item_description").addClass('has-error');
		$(".field-vendoritem-item_description").find('.help-block').html('Item description cannot be blank.');
		return false;
     } 
  else
  {return true;}
});
</script>


<script>
$("#validfour4").click(function() { 
	if($("#vendoritem-vendor_id").val()=='')
	{
			$(".field-vendoritem-vendor_id").addClass('has-error');
			$(".field-vendoritem-vendor_id").find('.help-block').html('Select Vendor name');
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

$("#validfive5").click(function() { 	
	if($("#vendoritem-vendor_id").val()=='')
	{
			$(".field-vendoritem-vendor_id").addClass('has-error');
			$(".field-vendoritem-vendor_id").find('.help-block').html('Select Vendor name');
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
  	else
  {return true;}
});

$("#validsix6").click(function() { 
	
	if($(".file-preview-thumbnails img").length <= 0)
	{
		$('.file-block').show();
		return false;
	}
	else if($(".file-preview-thumbnails img").length >= 1)
 	{
 		$('.file-block').hide();
 		return true;			
 	} 

	if($("#vendoritem-vendor_id").val()=='')
	{
			$(".field-vendoritem-vendor_id").addClass('has-error');
			$(".field-vendoritem-vendor_id").find('.help-block').html('Select Vendor name');
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


  	if($(".file-preview-thumbnails > img").length <= 0)
	{			
		$('.file-block').show();
			return false;
 	}
 	else if($(".file-preview-thumbnails > img").length >= 1)
 	{
 		$('.file-block').hide();			
 	}  
	  else
  	{return true;}
});

$(document).ready(function () {
  $("#vendoritem-item_amount_in_stock").keypress(function (e) {
     if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
          $(".field-vendoritem-item_amount_in_stock").find('.help-block').html('Item number of stock must be an integer.').animate({ color: "#a94442" }).show().fadeOut(2000);
         return false;
    }
   });
});

$(document).ready(function () {
  $("#vendoritem-item_how_long_to_make").keypress(function (e) {
     if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
          $(".field-vendoritem-item_how_long_to_make").find('.help-block').html('No of days delivery must be an integer.').animate({ color: "#a94442" }).show().fadeOut(2000);
         return false;
    }
   });
});

$(document).ready(function () {
  $("#vendoritem-item_default_capacity").keypress(function (e) {
     if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
          $(".field-vendoritem-item_default_capacity").find('.help-block').html('Item default capacity must be an integer.').animate({ color: "#a94442" }).show().fadeOut(2000);
         return false;
    }
   });
});

$(document).ready(function () {
  $("#vendoritem-item_minimum_quantity_to_order").keypress(function (e) {
     if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
          $(".field-vendoritem-item_minimum_quantity_to_order").find('.help-block').html('Item minimum quantity to order must be an integer.').animate({ color: "#a94442" }).show().fadeOut(2000);
         return false;
    }
   });
});

$(document).ready(function () {
  $("#vendoritem-item_price_per_unit").keypress(function (e) {
     if (e.which != 46 && e.which != 45 && e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
          $(".field-vendoritem-item_price_per_unit").find('.help-block').html('Item price per unit must be a number.').animate({ color: "#a94442" }).show().fadeOut(2000);
         return false;
    }
   });
});

/* BEGIN  avoid paste text in item name text box */ 
$(document).ready(function(){
 $('#vendoritem-item_name').bind("paste",function(e) {
     e.preventDefault();
 }); 
});
/* END  avoid paste text in item name text box */ 


 /* BEGIN Dialog box for sales guide image */
 function checkupload(tis)
{	
	var question_id = $(tis).parent().find('input.saves').attr('id');	
	
	var path = "<?php echo Url::to(['/admin/vendoritem/salesguideimage']); ?> ";
	$.ajax({  
        type: 'POST',              
        url: path, //url to be called
        data: {question_id : question_id }, //data to be send
        success: function( data ) {    	    
        	$(".modal-content").html(data);
  		}
  	});   
}

function deletequestionselection(selection_val)
{
	var option = $(selection_val).attr('id');
	$(selection_val).parent().remove();
	if(option != undefined)
	{
		var path = "<?php echo Url::to(['/admin/vendoritemquestionansweroption/deletequestionoptions']); ?> ";
		$.ajax({  
        type: 'POST',              
        url: path, //url to be called
        data: {option : option }, //data to be send
        success: function( data ) {    	    
        	alert(data);
  		}
  		}); 
	}	
 }

</script>
<div class="modal fade" id="myModal" role="dialog">
      <div class="modal-dialog">    
      <!-- Modal content-->
      <div class="modal-content">       
      </div>      
    </div>
</div>
<!-- END Dialog box for sales guide image -->


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
        url: path, //url to be called
        data: { item: mail ,item_id : item_id, _csrf : csrfToken}, //data to be send
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
	//}
  }
});
}); 
/* END Vendor item check exist or not */ 
</script>
<!-- CKEDITOR-->
<script src="<?php echo Yii::$app->themeURL->createAbsoluteUrl(''); ?>plugins/ckeditor/ckeditor.js" type="text/javascript"></script>
<!-- CKEDITOR-->

<!-- Bootatrap file input widget -->
<link rel="stylesheet" href="<?php echo Yii::$app->themeURL->createAbsoluteUrl(''); ?>plugins/bootstrap-fileinput/fileinput.min.css" rel="stylesheet" type="text/css" media="screen">
<script src="<?php echo Yii::$app->themeURL->createAbsoluteUrl(''); ?>plugins/bootstrap-fileinput/fileinput.min.js" type="text/javascript"></script>
<!-- Bootatrap file input widget -->

<!-- multi select begin -->
<script type="text/javascript" src="<?php echo Yii::$app->themeURL->createAbsoluteUrl(''); ?>plugins/bootstrap-multiselect/dist/js/bootstrap-multiselect.js"></script>
<link href="<?php echo Yii::$app->themeURL->createAbsoluteUrl(''); ?>plugins/bootstrap-multiselect/dist/css/bootstrap-multiselect.css" rel="stylesheet" type="text/css" />
<!-- multi select end -->
