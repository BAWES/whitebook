var count_q = $('#count_q').val();
var appImageUrl = $('#appImageUrl').val();
var image_order_url = $('#image_order_url').val();
var deletequestionoptions_url = $('#deletequestionoptions_url').val();
var salesguideimage_url = $('#salesguideimage_url').val();
var request_create = $('#request_create').val();
var isNewRecord = $('#isNewRecord').val();
var item_for_sale = $('#item_for_sale').val();
var item_status = $('#item_status').val();
var item_id = $('#item_id').val();
var item_name_check = $('#item_name_check').val();
var add_question_url = $('#add_question_url').val();
var guideimage_url = $('#guideimage_url').val();
var exist_question = $('#exist_question').val();
var removequestion_url = $('#removequestion_url').val();
var vendorcategory_url = $('#vendorcategory_url').val();
var loadsubcategory_url = $('#loadsubcategory_url').val();
var loadchildcategory_url = $('#loadchildcategory_url').val();
var renderquestion_url = $('#renderquestion_url').val();

$(function()
{
	$config = {};
	$config.allowedContent = true;

	ck_item_description = CKEDITOR.replace('vendoritem-item_description', $config);
	ck_additional_info = CKEDITOR.replace('vendoritem-item_additional_info', $config);
	ck_price_description = CKEDITOR.replace('vendoritem-item_price_description', $config);
	ck_customization_description = CKEDITOR.replace('vendoritem-item_customization_description', $config);
	ck_item_description_ar = CKEDITOR.replace('vendoritem-item_description_ar', $config);
	ck_additional_info_ar = CKEDITOR.replace('vendoritem-item_additional_info_ar', $config);
	ck_price_description_ar = CKEDITOR.replace('vendoritem-item_price_description_ar', $config);
	ck_customization_description_ar = CKEDITOR.replace('vendoritem-item_customization_description_ar', $config);

	var hash = location.hash.substr(1);

	if(hash) {
		$('.nav-tabs .active').removeClass('active');
		$('.tab-content .active').removeClass('active');
		
		$('#tab_' + hash).parent().addClass('active');
		$('#' + hash + '.tab-pane').addClass('active');
	} else {
		$('.nav-tabs li:first').addClass("active");
		$('.tab-content div:first').addClass('active');	
	}	
});

var csrfToken = $('meta[name="csrf-token"]').attr("content");

/*if(!isNewRecord && request_create) { 
 	$('.nav-tabs li:last').addClass("active");
 	$("#7").addClass("active");
} else { 
	$('.nav-tabs li:first').addClass("active");
 	$(".tab-content div:first").addClass("active");
} */

/* Begin Tabs NEXT & PREV buttons */
$('.btnNext').click(function(){
  $('.nav-tabs > .active').next('li').find('a').trigger('click');
  $('html, body').animate({ scrollTop: 0 }, 'slow');
});

$('.btnPrevious').click(function(){
  $('.nav-tabs > .active').prev('li').find('a').trigger('click');
  $('html, body').animate({ scrollTop: 0 }, 'slow');
});
/* End Tabs NEXT & PREV buttons */

$(function (){

 	/* For themes and groups list checkbox alignment*/
 	$(".themelists:last-child").css({"clear" : "both","float" :"inherit"});
 	/* For themes and groups list checkbox alignment*/

	$('#option').hide();
});

// if it is new record //
$(document).delegate(".vendoritemquestion-question_answer_type", 'change', function (){
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
		$(this).after('<div class="selection"><input type="text" class="form-control question temp_qa" name="'+level_text+'" placeholder="Answer" id="question" style="width:40%;float:left;"><input type="text" class="form-control temp_qa" name="'+level_price+'" placeholder="Price (Optional)" id="price" style="width:35%;float:left;"><input type="hidden" name="VendorItemQuestion[0][hidden][0][]" class="form-control answer" style="width:5%;float:left;"><input type="hidden" id="subquestion" value="Add" class="add-sub-question temp_qa" data-name="'+level_text+'" onclick="addsubquestions(this)"><input type="button" id="viewquestion" value="View" class="add-sub-question temp_qa" data-name="'+level_text+'" onclick="viewsubquestions(this)"></div><input type="button" class="add_question" id="add_question'+j1+'" data-name="'+level_text+'" data-parent ="'+parent+'" value="Add Selection"> <input type="button" class="save" name="save" value="Save" onclick="savequestion(\''+type+'\','+parent_id+',this)"><input type="button" value="Guide Image" id="" class="saves" data-toggle="modal" data-target="#myModal" onclick="checkupload(this)"><div class="question_success">Successfully added</div>');
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

		$(this).after('<div class="image_val"><input type="file" class="form-control upimage"  multiple="true" name="VendorItemQuestion['+j1+'][image][]" placeholder="Image (Optional)" id="guide_image" style="width:40%;"><input type="button" class="savebutton" name="save" value="Save" onclick="savequestion(\''+type+'\','+parent_id+',this)"><input type="button" value="Guide Image" id="" class="saves" data-toggle="modal" data-target="#myModal" onclick="checkupload(this)"><div class="question_success">Successfully added</div></div>');
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

		$(this).after('<div class="price_val"><input type="text" class="form-control" name="VendorItemQuestion['+j1+'][price][]" placeholder="Price (Optional)" id="price" style="width:40%;"><input type="button" class="savebutton" name="save" value="Save" onclick="savequestion(\''+type+'\','+parent_id+',this)"><input type="button" value="Guide Image" id="" class="saves" data-toggle="modal" data-target="#myModal" onclick="checkupload(this)"><div class="question_success">Successfully added</div></div>');
		$(this).parent().find('input.saves').hide(); //hide before add
	}
	// Add selection for questions //
});

$(document).delegate('.add_question', 'click',function(){

	var j = $(this).attr('id').replace(/add_question/, '');
	var par = $(this).attr('data-parent');
	var p = ($(this).parent().find('.add-sub-question').length);
	var na = $(this).attr('data-name');
	
	console.log(na);

	var new_n = na.substring(0,(na.lastIndexOf('[]')-3));
	var new_p = na.substring(0,(na.lastIndexOf('[]')-9));
	var ques_txt =(new_n+'['+p+'][]');
	var ques_ans =(new_p+'[price]['+p+'][]');

	$html  = '<div class="selection">';
	$html += '<input type="text" class="form-control question" name="'+ques_txt+'" placeholder="Answer" id="question" style="width:40%;float:left;">';
	$html += '<input type="text" class="form-control"  placeholder="Price (Optional)" name="'+ques_ans+'" id="price" style="width:40%;float:left;">';
	$html += '<input type="hidden" class="form-control answer" name="VendorItemQuestion[0][hidden][0][]" style="width:5%;float:left;">';
	$html += '<img src="'+appImageUrl+'remove.png" class="selection_delete" onclick="deletequestionselection(this)" />';
	$html += '<input type="hidden" id="subquestion" value="Add" class="add-sub-question temp_qa" data-name="'+ques_txt+'" onclick="addsubquestions(this)">';
	$html += '<input type="button" id="viewquestion" value="View" class="add-sub-question temp_qa" data-name="'+ques_txt+'" onclick="viewsubquestions(this)">';
	$html += '</div>';

	$(this).before($html);	

	p++;

	$('input#subquestion').hide(); //hide before add
	$('input#viewquestion').hide();
});

function savequestion(typ, q_parent, tis)
{	
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

	var path = add_question_url;

	$.ajax({
        type: 'POST',
        dataType: 'json',
        url: path, //url to be called
        data: { serial_div : serial_div, item_id : item_id }, //data to be send
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
		        form_data.append('item_id', item_id);

		        var path = guideimage_url;
			    
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
    });
}

// Add sub questions
var i = exist_question  + 1;

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
			var path = removequestion_url;
	        $.ajax({
		        type: 'POST',
		        url: path, //url to be called
		        data: { question_id: question_id ,_csrf : csrfToken}, //data to be send
		        success: function( data ) {
		             alert(data);
		        }
	        });
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
    var path = vendorcategory_url;

    $.ajax({
	    type: 'POST',
	    url: path, //url to be called
	    data: { vendor_id: vendor_id ,_csrf : csrfToken}, //data to be send
	    success: function( data ) {
	        $('#vendoritem-category_id').html(data);
	    }
    });
}

// Add questions
var j = count_q;

function addAddress(tis)
{
	$(tis).before('<div class="form-group" id="delete_'+j+'"> <div id="question-section_'+j+'" class="question-section"><input type="hidden" id="parentid_0" value="0" class="form-control" name="parent_id" placeholder="Parent Question ">Question <input type="text" id="question_text_'+j+'" class="form-control" name="VendorItemQuestion['+j+'][question_text][] style="margin:10px 0px;"> Question Type	<div class="append_address"><select id="vendoritemquestion-question_answer_type'+j+'" class="form-control vendoritemquestion-question_answer_type" name="VendorItemQuestion['+j+'][question_answer_type][]" parent_id="'+j+'" style="margin: 10px 0px;"><option value="">Choose type</option><option value="text">Text</option><option value="image">Image</option><option value="selection">Selection</option></select></div><input type="button" style="float:right; margin:0px 5px 5px 0px;" class="delete_'+j+'" onclick=deleteAddress("delete_'+j+'") value=Delete><input type="hidden" style="float:right; margin:0px 5px 5px 0px;" class="hide_'+j+'" onclick=hideQuestion("hide_'+j+'",this) value=Hide></div></div>');	j++;
}

// single question view
function questionView(q_id,tis){
	var check = $('.show_ques'+q_id).html();
	if(check==''){
		var path = renderquestion_url;
		$.ajax({
			type : 'POST',
			url :  path,
			data: { q_id: q_id ,_csrf : csrfToken}, //data to be send
	        success: function( data ) {
		        $('.show_ques'+q_id).html(data);
		        $(tis).toggleClass("expanded");
		        return false;
	        }
		});
	}else{
		$('.show_ques'+q_id).toggle();
		$(tis).toggleClass("expanded");
	}
}

// Add more for pricing 
$(function(){

	$('.custom_description').hide();
	$('.custom_description_ar').hide();
	if (item_for_sale == 0) {
		$('.guide_image').show();
	} else {
		$('.guide_image').hide();
	}
	$('.mandatory').show();

	$('#vendoritem-item_for_sale').click(function()
	{
		if($(this).is(':checked'))
		{
			$('.custom_description').hide();
			$('.custom_description_ar').hide();
			$('.guide_image').hide();
			$('.mandatory').show();
		}
		else
		{
			$('.mandatory').hide();
			$('.custom_description').show();
			$('.custom_description_ar').show();
			$('.guide_image').show();
		}
	});

	if(!isNewRecord) {
		if($("#vendoritem-item_for_sale").prop('checked') == true){
			$('.custom_description').hide();
			$('.custom_description_ar').hide();
			$('.guide_image').hide();
		}
		else
		{
			$('.custom_description').show();
			$('.custom_description_ar').show();
			$('.guide_image').show();
		}
	}
});

var j= 2;

function addPrice(tis)
{
	$(tis).before('<div class="controls'+j+'"><input type="text" id="vendoritem-item_from" class="form-control from_range_'+j+'" name="vendoritem-item_price[from][]" multiple = "multiple" Placeholder="From Quantity" /><input type="text" id="vendoritem-item_to" class="form-control to_range_'+j+'" name="vendoritem-item_price[to][]" multiple = "multiple" Placeholder="To Quantity" /><input type="text" id="item_price_per_unit" class="form-control price_kd'+j+'" name="vendoritem-item_price[price][]" multiple = "multiple" Placeholder="Price">KD<input type="button" name="remove" id="remove" value="Remove" class="remove_price" onClick="removePrice(this)" /></div>');
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

var csrfToken = $('meta[name="csrf-token"]').attr("content");

$(document).ready(function(){

	/*  Begin Select all checkbox images */
	$('.check:button').toggle(function(){
	    $('input:checkbox').attr('checked','checked');
        $(this).val('Uncheck all');
	}, function(){
		$('input:checkbox').removeAttr('checked');
		$(this).val('Check all');
	});

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

/* BEGIN Dialog box for sales guide image */
function checkupload(tis)
{
	var question_id = $(tis).parent().find('input.saves').attr('id');

	var path = salesguideimage_url;

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
		var path = deletequestionoptions_url;
		
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

//add categort 
$('.btn-add-category').click(function(){
	
	$category_id = $('#category_id').val();

	if($category_id.length == 0) {
		return false;
	}

	$html  = '<tr>';
	$html += '	<td>';
	$html += 		$('#category_id option:selected').html();
	$html += '		<input value="' + $category_id + '" name="category[]" type="hidden" />';
	$html += '	</td>';
	$html += '	<td>';
	$html += '		<button class="btn btn-danger" type="button">';
	$html += '			<i class="glyphicon glyphicon-trash"></i>';
	$html += '		</button>';
	$html += '	</td>';
	$html += '</tr>';

	$('.table-category-list tbody').append($html);

	$(".field-category-list").removeClass('has-error');
	$(".field-category-list").find('.help-block').html('');
});

$(document).delegate('.table-category-list .btn-danger','click', function(){
	$(this).parent().parent().remove();
});

/** 
 * Image crop and upload 
 */
 $(function() {

	var croped_image_upload_url = $('#croped_image_upload_url').val();
    var image_count = $('#image_count').val();
    
    $('.image-editor').cropit({ imageBackground: true });

    $('.btn-crop-upload').click(function(){

    	//remove old warning 
    	$('.alert-image-size').remove();

    	var imageData = $('.image-editor').cropit('export');

		if(!imageData) {
			$html  = '<div class="alert alert-warning alert-image-size">';
			$html += '	Please upload valid image with size of atlease 530px x 530px!';
			$html += '	<button class="close" data-dismiss="alert"></button>';
			$html += '</div>';

			$('.file-block').after($html);

			$('html, body').animate({ scrollTop: 0 }, 'slow');
			
			return false;
		}

		$(this).attr('disabled', 'disabled');
    	$(this).html('Uploading...');

    	//upload image 
    	$.post(croped_image_upload_url, { image : imageData }, function(json) {

    		var html = '<tr>';
			html +=	'<td>';
			html +=	'		<div class="vendor_image_preview">';
			html +=	'			<img src="' + json.image_url + '" />';
			html +=	'		</div>';
			html +=	'		<input type="hidden" name="images[' + image_count + '][image_path]" value="' + json.image + '" />';
			html +=	'	</td>';
			html +=	'	<td>';
			html +=	'		<input type="text" name="images[' + image_count + '][vendorimage_sort_order]" value="" />';
			html +=	'	</td>';
			html +=	'	<td>';
			html +=	'		<button class="btn btn-danger btn-delete-image">';
			html +=	'			<i class="fa fa-trash"></i>';
			html +=	'		</button>';
			html +=	'	</td>';
			html +=	'</tr>';

    		$('.table-item-image tbody').append(html);

    		$('.btn-crop-upload').html('Upload');
    		$('.btn-crop-upload').removeAttr('disabled');

    		image_count++;
    	});
    });

    //delete image from item image table 
    $(document).delegate('.table-item-image .btn-delete-image', 'click', function() {
    	$(this).parents('tr').remove();
    });
});

/** 
 * Save tab 1 data on click of tab 2 or on click of next in tab 1 
 */
$('#tab_2').click(function(e) {
	save_item_info();
});

/** 
 * Save tab 2 data on click of tab 3 or on click of next in tab 2
 */
$('#tab_3').click(function(e) {
	save_item_description();
});

/** 
 * Save tab 3 data on click of tab 4 or on click of next in tab 3
 */
$('#tab_4').click(function(e) {
	save_item_price();
});

/** 
 * Save tab 4 data on click of tab 5 or on click of next in tab 4
 */
$('#tab_5').click(function(e) {
	save_item_approval();
});

/** 
 * Save tab 5 data on click of tab 6 or on click of next in tab 5
 */
$('#tab_6').click(function(e) {
	save_item_images();
});

/** 
 * Click in final submit button 
 */
$('.complete').click(function()
{
	//CKEDITOR + validation.js issue 
	for (var i in CKEDITOR.instances)
	{
	    CKEDITOR.instances[i].updateElement();
	}

	//remove warning alert before each new call 
	$('.alert-warning').remove();

	$(this).attr('disabled', 'disabled');
	$(this).html('Please wait...');
			
	$('.loadingmessage').show();
	
	$.post($('#item_validate_url').val(), get_form_data(false), function(json) {

		if(json['errors']) 
		{
			show_errors(json);

			$html  = '<div class="alert alert-warning">';
			$html += '	Please check form carefully!';
			$html += '	<button class="close" data-dismiss="alert"></button>';
			$html += '</div>';

			$('.loadingmessage').after($html);

			$('.loadingmessage').hide();

			$('.complete').removeAttr('disabled');
			$('.complete').html('Complete');

			$('html, body').animate({ scrollTop: 0 }, 'slow');
		}

		if(json['success']) 
		{
			$('.complete').parents('form').submit();
		}
	});
});

function show_errors(json) 
{
	$('.has-error').removeClass('has-error');
	$('.form-group .help-block').html('');

	//step 1 
	
	if(json['errors']['vendor_id']) 
	{
		$(".field-vendoritem-vendor_id").removeClass('has-success');
		$(".field-vendoritem-vendor_id").addClass('has-error');
		$(".field-vendoritem-vendor_id").find('.help-block').html(json['errors']['vendor_id']);
	}

	if(json['errors']['item_name']) 
	{
		$(".field-vendoritem-item_name").removeClass('has-success');
		$(".field-vendoritem-item_name").addClass('has-error');
		$(".field-vendoritem-item_name").find('.help-block').html(json['errors']['item_name']);
	}
				
	if(json['errors']['category']) 
	{
		$(".field-category-list").addClass('has-error');
		$(".field-category-list").find('.help-block').html('Add Category.');
  	}

  	//step 2 

  	if(json['errors']['type_id'])
  	{
  		$('.field-vendoritem-type_id').addClass('has-error');
		$('.field-vendoritem-type_id').find('.help-block').html('Item type cannot be blank.');
  	}

  	if(json['errors']['item_description'])
  	{
        $('.field-vendoritem-item_description').addClass('has-error');
	    $('.field-vendoritem-item_description').find('.help-block').html('Item description cannot be blank.');
	}

	//step 3

	if(json['errors']['item_amount_in_stock'])
	{
		$('.field-vendoritem-item_amount_in_stock').addClass('has-error');
		$('.field-vendoritem-item_amount_in_stock').find('.help-block').html('Item number of stock cannot be blank.');
	}

	if(json['errors']['item_default_capacity'])
	{
		$('.field-vendoritem-item_default_capacity').addClass('has-error');
		$('.field-vendoritem-item_default_capacity').find('.help-block').html('Item default capacity cannot be blank.');
	}

	if(json['errors']['item_how_long_to_make'])
	{
		$('.field-vendoritem-item_how_long_to_make').addClass('has-error');
		$('.field-vendoritem-item_how_long_to_make').find('.help-block').html('No of days delivery cannot be blank.');
	}

	if(json['errors']['item_minimum_quantity_to_order'])
	{
		$('.field-vendoritem-item_minimum_quantity_to_order').addClass('has-error');
		$('.field-vendoritem-item_minimum_quantity_to_order').find('.help-block').html('Item minimum quantity to order cannot be blank.');
	}

	if(json['errors']['multiple_price']) 
	{
		$('.form-group.multiple_price').addClass('has-error');
	}

	if(json['errors']['images'])
	{
		$('.file-block').show();
	} 
	else 
	{
 		$('.file-block').hide();
 	}
}

//append ckeditor data 
function get_form_data($is_autosave) {

	$data = $('form').serialize();

	if($is_autosave) {
		$data += '&is_autosave=' + 1;
	}else{
		$data += '&is_autosave=' + 0;
	}

	$data += '&VendorItem[item_description]=' + ck_item_description.getData(); 
	$data += '&VendorItem[item_additional_info]=' + ck_additional_info.getData();
	$data += '&VendorItem[item_price_description]=' + ck_price_description.getData();
	$data += '&VendorItem[item_customization_description]=' + ck_customization_description.getData(); 
	$data += '&VendorItem[item_description_ar]=' + ck_item_description_ar.getData();
	$data += '&VendorItem[item_additional_info_ar]=' + ck_additional_info_ar.getData();
	$data += '&VendorItem[item_price_description_ar]=' + ck_price_description_ar.getData();
	$data += '&VendorItem[item_customization_description_ar]=' + ck_customization_description_ar.getData();	

	return $data;
}

/** 
 * We will not display errors and loading image on autosave 
 */ 
function save_item_info($is_autosave = false) {

	if(!$is_autosave) {	
		$('.loadingmessage').show();	
	}

	$.post($('#item_info_url').val(), get_form_data($is_autosave), function(json) {

		$('.loadingmessage').hide();

		//switch to edit mode 
		if(json['item_id']) {
			$('input[name="item_id"]').val(json['item_id']);	
		}
		
		if(json['edit_url']) {
			$('#w0').attr('action', json['edit_url']);	
		}

		if($is_autosave)
			return true;

		if(json['success']) 
		{
			//redirect 
			if(isNewRecord) 
			{
				location = json['edit_url'] + '#2';
			}
			else
			{
				//update active tab 
				$('.nav-tabs .active').removeClass('active');
				$('.tab-content .active').removeClass('active');
				
				$('#tab_2').parent().addClass('active');
				$('#2.tab-pane').addClass('active');	
			}			
		}

		if(json['errors']) 
		{
			show_errors(json);
		}
	});
}

function save_item_description($is_autosave = false) {

	if(!$is_autosave) {	
		$('.loadingmessage').hide();
	}

	$.post($('#item_description_url').val(), get_form_data($is_autosave), function(json) {

		$('.loadingmessage').hide();

		if($is_autosave)
			return true;

		if(json['success']) 
		{
			//update active tab 
			$('.nav-tabs .active').removeClass('active');
			$('.tab-content .active').removeClass('active');
			
			$('#tab_3').parent().addClass('active');
			$('#3.tab-pane').addClass('active');
		}

		if(json['errors']) 
		{
			show_errors(json);
		}
	});
}

function save_item_price($is_autosave = false) {

	if(!$is_autosave) {	
		$('.loadingmessage').hide();
	}

	$.post($('#item_price_url').val(), get_form_data($is_autosave), function(json) {

		$('.loadingmessage').hide();

		if($is_autosave)
			return true;

		if(json['success']) 
		{
			//update active tab 
			$('.nav-tabs .active').removeClass('active');
			$('.tab-content .active').removeClass('active');
			
			$('#tab_4').parent().addClass('active');
			$('#4.tab-pane').addClass('active');
		}

		if(json['errors']) 
		{
			show_errors(json);	
		}
	});
}

function save_item_approval($is_autosave = false) {

	if(!$is_autosave) {	
		$('.loadingmessage').hide();
	}

	$.post($('#item_approval_url').val(), get_form_data($is_autosave), function(json) {

		$('.loadingmessage').hide();

		if($is_autosave)
			return true;

		if(json['success']) 
		{
			//update active tab 
			$('.nav-tabs .active').removeClass('active');
			$('.tab-content .active').removeClass('active');
			
			$('#tab_5').parent().addClass('active');
			$('#5.tab-pane').addClass('active');
		}

		if(json['errors']) 
		{
			show_errors(json);	
		}
	});
}

function save_item_images($is_autosave = false) {

	if(!$is_autosave) {	
		$('.loadingmessage').hide();
	}

	$.post($('#item_images_url').val(), get_form_data($is_autosave), function(json) {

		$('.loadingmessage').hide();

		if($is_autosave)
			return true;

		if(json['success']) 
		{
			//update active tab 
			$('.nav-tabs .active').removeClass('active');
			$('.tab-content .active').removeClass('active');
			
			$('#tab_6').parent().addClass('active');
			$('#6.tab-pane').addClass('active');
		}

		if(json['errors']) 
		{
			show_errors(json);	
		}
	});
}

function save_item_themes_groups($is_autosave = false) {

	if(!$is_autosave) {	
		$('.loadingmessage').hide();
	}

	$.post($('#item_themes_groups').val(), get_form_data($is_autosave), function(json) {

		$('.loadingmessage').hide();

		if($is_autosave)
			return true;

		if(json['errors']) 
		{
			show_errors(json);	
		}
	});
}

/** 
 * Autosave active tab fields 
 */ 
setInterval(function(){

	if($('#tab_1').parent().hasClass('active')){
		save_item_info(true);
	}
	
	if($('#tab_2').parent().hasClass('active')){
		save_item_description(true);
	}

	if($('#tab_3').parent().hasClass('active')){
		save_item_price(true);
	}

	if($('#tab_4').parent().hasClass('active')){
		save_item_approval(true);
	}

	if($('#tab_5').parent().hasClass('active')){
		save_item_images(true);
	}

	if($('#tab_6').parent().hasClass('active')){
		save_item_themes_groups(true);
	}

}, 2000);

