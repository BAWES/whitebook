var imagedata = $('#imagedata').val();
var img  = $('#img').val();
var action  = $('#action').val();
var guideimagedata = $('#guideimagedata').val();
var img1  = $('#img1').val();
var action1 = $('#action1').val();
var isNewRecord = $('#isNewRecord').val();
var item_for_sale = $('#item_for_sale').val();
var item_status = $('#item_status').val();
var item_id = $('#item_id').val();

var load_sub_category_url = $('#load_sub_category_url').val();
var load_child_category_url = $('#load_child_category_url').val();

var image_delete_url = $('#image_delete_url').val();
var remove_question_url = $('#remove_question_url').val();

var render_question_url = $('#render_question_url').val();
var item_name_check_url = $('#item_name_check_url').val();
var image_order_url = $('#image_order_url').val();

$('.btnNext').click(function(){
  	$('.nav-tabs > .active').next('li').find('a').trigger('click');
	$("html, body").animate({ scrollTop: 0 }, "slow");
});

 $('.btnPrevious').click(function(){
  	$('.nav-tabs > .active').prev('li').find('a').trigger('click');
 	$("html, body").animate({ scrollTop: 0 }, "slow");
});

$(function() {

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

var csrfToken = $('meta[name="csrf-token"]').attr('content');

$('#option').hide();

$('.vendoritemquestion-question_answer_type').on('change',function (){

	var type = $(this).val();

	if(type =='selection')
	{
		$(this).next('.price_val').remove();
		var j = $(this).attr('id').replace(/vendoritemquestion-question_answer_type/, '');
		$('#option').show();

		$(this).after('<div class="selection"><input type="text" class="form-control" name="VendorItemQuestion['+j+'][text][0][]" placeholder="Question" id="question" style="width:50%;float:left;"><input type="text" class="form-control" name="VendorItemQuestion['+j+'][price][0][]" placeholder="Price (Optional)" id="price" style="width:40%;float:left;"><input type="button" class="add_question" id=\add_question'+j+'" data-option-count="1" name="Addss" value="Add Selection"></div>');
	
	} else if(type =='image' ||  type =='text') {

		$(this).next('.selection').remove();
		$(this).next('.price_val').remove();
		var j = $(this).attr('id').replace(/vendoritemquestion-question_answer_type/, '');
		$('#option').show();

		$(this).after('<div class="price_val"><input type="text" class="form-control" name="VendorItemQuestion['+j+'][price][]" placeholder="Price (Optional)" id="price" style="width:40%;float:left;"></div>');
	}

	// Add selection for questions //
});

var p = 1;

$('.add_question').on('click',function(){
	var j = $(this).attr('id').replace(/add_question/, '');
	var p = $(this).attr('data-option-count');
	$(this).before('<div class="selection"><input type="text" class="form-control" name="VendorItemQuestion['+j+'][text]['+p+'][]" placeholder="Question" id="question" style="width:50%;float:left;"><input type="text" class="form-control" name="VendorItemQuestion['+j+'][price]['+p+'][]" placeholder="Price (Optional)" id="price" style="width:45%;float:left;"></div>');p++;
	$(this).attr('data-option-count',p);
});

function deletePhoto(image_id, loc){

	var path = image_delete_url;
    
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
    });
}

function deleteAddress(d,question_id) {
	if(question_id != '')
	{
		if (confirm('Are you sure want to delete?')) {
			$('#'+d).remove();
			var path = remove_question_url;
        
	        $.ajax({
		        type: 'POST',
		        url: path,
		        data: { question_id: question_id ,_csrf : csrfToken},
		        success: function( data ) {
		             alert(data);
		        }
	        });

	        return false;
		}
		return false;
	}
}

var ck_item_description = '';
var ck_additional_info = '';
var ck_price_description = '';
var ck_customization_description = '';
var ck_item_description_ar = '';
var ck_additional_info_ar = '';
var ck_price_description_ar = '';
var ck_customization_description_ar = '';

$(function() {

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
	$(tis).before('<div class="controls'+j+'"><input type="text" id="vendoritem-item_from" class="form-control from_range_'+j+'" name="vendoritem-item_price[from][]" multiple="multiple" Placeholder="From Quantity"><input type="text" id="vendoritem-item_to" class="form-control to_range_'+j+'" name="vendoritem-item_price[to][]" multiple = "multiple" Placeholder="To Quantity"><input type="text" id="item_price_per_unit" class="form-control price_kd'+j+'" name="vendoritem-item_price[price][]" multiple = "multiple" Placeholder="Price">KD<input type="button" name="remove" id="remove" value="Remove" class="remove_price" onClick="removePrice(this)" /></div>');
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
	
		/* BEGIN SORT code for item and guide images */
		var path = image_order_url;
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
		});
	  }
	});
	/* END SORT code for item and guide images */

	$(".file-preview-initial > img").each(function(){
		$(this).parent().attr('data-key',$(this).attr('data-key'));
	});
});

if(isNewRecord) {
    $('#vendoritem-item_for_sale').prop('checked', true);
} else {
	if(item_for_sale=='Yes') {
		 $('#vendoritem-item_for_sale').prop('checked', true);
	} else { 
		$('#vendoritem-item_for_sale').prop('checked', false);
	}
	
	if(item_status=='Active') {
		$('#vendoritem-item_status').prop('checked', true);
	} else { 
		$('#vendoritem-item_status').prop('checked', false);
	}
} 

$(function(){
	$('.custom_description').hide();
	$('.custom_description_ar').hide();
	$('.mandatory').show();

	$('#vendoritem-item_for_sale').on('change',function()
	{
		if($(this).val() == 'Yes')
		{
			$('.custom_description').hide();
			$('.custom_description_ar').hide();
			$('.guide_image').hide();
			$('.mandatory').show();
		} else {
			$('.mandatory').hide();
			$('.custom_description').show();
			$('.custom_description_ar').show();
			$('.guide_image').show();
		}
	});

	if(isNewRecord) {
		if($('#vendoritem-item_for_sale').prop('checked') == true){
			$('.custom_description').hide();
			$('.custom_description_ar').hide();
			$('.guide_image').hide();
			$('.mandatory').show();
		}
		else
		{
			$('.custom_description').show();
			$('.custom_description_ar').show();
			$('.guide_image').show();
			$('.mandatory').hide();
		}
	} 
});

// single question view
function questionView(q_id,tis){
	
	var check = $('.show_ques'+q_id).html();
	
	if(check==''){
		var path = render_question_url;
		$.ajax({
			type : 'POST',
			url :  path,
			data: { q_id: q_id ,_csrf : csrfToken},
	        success: function( data ) {
		        $('.show_ques'+q_id).html(data);
		        $(tis).toggleClass('expanded');
		        return false;
	        }
		});
	}else{
		$('.show_ques'+q_id).toggle();
		$(this).toggleClass('expanded');
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

    	$(this).attr('disabled', 'disabled');
    	$(this).html('Uploading...');

    	var imageData = $('.image-editor').cropit('export');

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
 * Click in final submit button 
 */
$('.complete').click(function()
{
	if($(".table-item-image img").length <= 0)
	{
		$('.file-block').show();
		return false;
	}
	else if($(".table-item-image img").length >= 1)
 	{
 		$('.file-block').hide();
 	}

	$(this).attr('disabled', 'disabled');
	$(this).html('Please wait...');
	$(this).parents('form').submit();
});


function show_errors(json) 
{
	$('.has-error').removeClass('has-error');
	$('.form-group .help-block').html('');

	//step 1 
	
	if(json['errors']['item_name']) 
	{
		$(".field-vendoritem-item_name").removeClass('has-success');
		$(".field-vendoritem-item_name").addClass('has-error');
		$(".field-vendoritem-item_name").find('.help-block').html('Item name already exists.');
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

	$data += '&VendorDraftItem[item_description]=' + ck_item_description.getData(); 
	$data += '&VendorDraftItem[item_additional_info]=' + ck_additional_info.getData();
	$data += '&VendorDraftItem[item_price_description]=' + ck_price_description.getData();
	$data += '&VendorDraftItem[item_customization_description]=' + ck_customization_description.getData(); 
	$data += '&VendorDraftItem[item_description_ar]=' + ck_item_description_ar.getData();
	$data += '&VendorDraftItem[item_additional_info_ar]=' + ck_additional_info_ar.getData();
	$data += '&VendorDraftItem[item_price_description_ar]=' + ck_price_description_ar.getData();
	$data += '&VendorDraftItem[item_customization_description_ar]=' + ck_customization_description_ar.getData();	

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
			if(isNewRecord) {
				location = json['edit_url'] + '#2';
			}

			//update active tab 
			$('.nav-tabs .active').removeClass('active');
			$('.tab-content .active').removeClass('active');
			
			$('#tab_2').parent().addClass('active');
			$('#2.tab-pane').addClass('active');
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

}, 2000);

