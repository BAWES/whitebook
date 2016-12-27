
var csrfToken = $('meta[name="csrf-token"]').attr("content");
var isNewRecord = $('#isNewRecord').val();

var c1 = true;
  
//trigger add package 
$package_count = $('.table-package-list tbody tr').length;

function add_package() {

	$('.package-list-error').html('');

	if($('#package_end_date').val() == '' || $('#package_start_date').val().length == '') {
		return false;
	}

	//show indicator 
	$('.loadingmessage').show();

	//disable create button 
	$('#6 .btn-success').attr('disabled', 'disabled');

	$('.datepicker-days').hide();

	$.post(validate_vendor_url, $('form').serialize(), function(json) {

		//hide indicator 
		$('.loadingmessage').hide();

		//enable create button 
		$('#6 .btn-success').removeAttr('disabled');

		if(json.errors.length > 0) {

			$.each(json.errors, function(key, value) {
				$('.package-list-error').append('<div class="alert alert-warning">' + value + '</div>');	
			});

			return false;
		}

		$html  = '<tr>';
		$html += '	<td>';
		$html += 		$('#package_id option:selected').text();
		$html += '		<input value="' + $('#package_id').val() + '" type="hidden"';
		$html += '			name="vendor_packages['+$package_count+'][package_id]" />';
		$html += '	</td>';
		
		$html += '	<td>';
		$html += 		$('#package_start_date').val();
		$html += '		<input value="' + $('#package_start_date').val() + '" type="hidden"';
		$html += '			name="vendor_packages['+$package_count+'][package_start_date]" />';
		$html += '	</td>';

		$html += '	<td>';
		$html += 		$('#package_end_date').val();
		$html += '		<input value="' + $('#package_end_date').val() + '" type="hidden"';
		$html += '			name="vendor_packages['+$package_count+'][package_end_date]" />';
		$html += '	</td>';

		$html += '	<td>';
		$html += '		<button class="btn btn-danger" type="button">';
		$html += '			<i class="glyphicon glyphicon-trash"></i>';
		$html += '		</button>';
		$html += '	</td>';
		$html += '</tr>';

		$('.table-package-list tbody').append($html);

		$('#package_start_date').val('');
		$('#package_end_date').val('');
	});

	$package_count++;
}

$(document).delegate('.table-package-list .btn-danger','click', function(){
	$(this).parent().parent().remove();
});

$('.btn-add-address').click(function(){
	
	$html  = '<tr>';
	$html += '	<td>';
	$html += '		<input value="" name="vendor_order_alert_emails[]" class="form-control" />';
	$html += '	</td>';
	$html += '	<td>';
	$html += '		<button class="btn btn-danger" type="button">';
	$html += '			<i class="glyphicon glyphicon-trash"></i>';
	$html += '		</button>';
	$html += '	</td>';
	$html += '</tr>';

	$('.table-email-list tbody').append($html);
});

$(document).delegate('.table-email-list .btn-danger','click', function(){
	$(this).parent().parent().remove();
});

$phone_no_count = $('.table-phone-list tbody tr').length;

//phone no 
$('.btn-add-phone-no').click(function(){
	
	$html  = '<tr>';
	$html += '	<td>';
	$html += '		<input value="" name="phone['+$phone_no_count+'][phone_no]" class="form-control" />';
	$html += '	</td>';
	$html += '	<td>';
	$html += '		<select name="phone['+$phone_no_count+'][type]" class="form-control">';
	$html += '		 	<option>Office</option>';
	$html += '		 	<option>Mobile</option>';
	$html += '		 	<option>Fax</option>';
	$html += '		 	<option>Whatsapp</option>';
	$html += '		</select>';
	$html += '	</td>';
	$html += '	<td>';
	$html += '		<button class="btn btn-danger" type="button">';
	$html += '			<i class="glyphicon glyphicon-trash"></i>';
	$html += '		</button>';
	$html += '	</td>';
	$html += '</tr>';

	$('.table-phone-list tbody').append($html);

	$phone_no_count++;
});

$(document).delegate('.table-phone-list .btn-danger','click', function(){
	$(this).parent().parent().remove();
});

$('.controls1').find('#remove').remove();
//$('.datepicker').datepicker();
$('select#package').hide();

var ck_vendor_return_policy = ''; 
var ck_vendor_return_policy_ar = '';

$(function()
{	
 	$config = {};
	$config.allowedContent = true;

	ck_vendor_return_policy = CKEDITOR.replace('text-editor', $config);
	ck_vendor_return_policy_ar = CKEDITOR.replace('text-editor-2', $config);

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

	$('.btnNext').click(function(){
	  $('.nav-tabs > .active').next('li').find('a').trigger('click');
	  $('html, body').animate({ scrollTop: 0 }, 'slow');
	  location.hash = $(this).parents('.tab-pane').attr('id') + 1;
	  $('.alert-warning').remove();
	});

	$('.btnPrevious').click(function(){
	  $('.nav-tabs > .active').prev('li').find('a').trigger('click');
	  $('html, body').animate({ scrollTop: 0 }, 'slow');
	  location.hash = $(this).parents('.tab-pane').attr('id') - 1;
	  $('.alert-warning').remove();
	});
});

function removePhone(phone) {
	$(".controls"+phone).remove();
}

$(document).ready(function () {

	$('#option').hide();

	/*
	$(".vendor-category_id:last-child").css({"clear" : "both","float" :"inherit"});
 	$('#vendor-category_id').multiselect({
		'enableFiltering': true,
		'filterPlaceholder': 'Search for something...'
	});

	$('#package_start_date').datepicker({  
		format: 'dd-mm-yyyy', 
		startDate: 'today'
	}).on('changeDate', function() {
		add_package();
	});

	$('#package_end_date').datepicker({  
		format: 'dd-mm-yyyy', 
		startDate: 'today'
	})
	.on('changeDate', function() {
		add_package();
	});*/

	//called when key is pressed in textbox
    $("#vendor-vendor_contact_number").keypress(function (e) {
     	//if the letter is not digit then display error and don't type anything
     	if ( e.which  != 43  && e.which  != 45 && e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        
        	//display error message
	    	$(".field-vendor-vendor_contact_number")
	    		.find('.help-block')
	    		.html('Contact number digits only.')
	    		.animate({ color: "#a94442" })
	    		.show()
	    		.fadeOut(2000);
               
            return false;
     	}
    });
});

$(document).ready(function(){

 	if(isNewRecord) { 
	
		$('#vendor-vendor_status').prop('checked', true);
	
	} else { 
		if(vendor_status == 'Active') {
			$('#vendor-vendor_status').prop('checked', true);
		} else { 
			$('#vendor-vendor_status').prop('checked', false);
		} 
	} 

	if(isNewRecord) { 

		$('#vendor-approve_status').prop('checked', true);

	} else {

		if(approve_status=='Yes') {
			$('#vendor-approve_status').prop('checked', true);
		} else { 
			$('#vendor-approve_status').prop('checked', false);
		} 
	} 

});//on doc ready 

/** 
 * Save tab 1 data on click of tab 2 or on click of next in tab 1 
 */
$('#tab_2').click(function(e) {
	save_logo();
});

/** 
 * Save tab 2 data on click of tab 3 or on click of next in tab 1 
 */
$('#tab_3').click(function(e) {
	save_basic_info();
});

/** 
 * Save tab 3 data on click of tab 4 or on click of next in tab 1 
 */
$('#tab_4').click(function(e) {
	save_main_info();
});

/** 
 * Save tab 4 data on click of tab 5 or on click of next in tab 1 
 */
$('#tab_5').click(function(e) {
	save_additional_info();
});

/** 
 * Save tab 5 data on click of tab 4 or on click of next in tab 1 
 */
$('#tab_6').click(function(e) {
	save_social_info();
});

$(function() {

	$('.image-editor').cropit();

	$('.btn-crop-upload').click(function(){

    	//remove old warning 
    	$('.message_wrapper .alert').remove();

    	var imageData = $('.image-editor').cropit('export');

		if(!imageData) {
			$html  = '<div class="alert alert-warning alert-image-size">';
			$html += '	Please upload valid image with size of atlease 450px x 450px!';
			$html += '	<button class="close" data-dismiss="alert"></button>';
			$html += '</div>';

			$('.message_wrapper').html($html);

			$('html, body').animate({ scrollTop: 0 }, 'slow');
			
			return false;
		}

		$(this).attr('disabled', 'disabled');
    	$(this).html('Uploading...');

    	//upload image 
    	$.post($('#croped_image_upload_url').val(), { image : imageData }, function(json) {
			
			$('#inpt_vendor_logo_path').val(json.image);
			$('.vendor-logo-thumbnail img').attr('src', json.image_url);

    		$('.btn-crop-upload').html('Upload');
    		$('.btn-crop-upload').removeAttr('disabled');

    		$('.cropit-preview-image').attr('src', 
    			'https://placeholdit.imgix.net/~text?txtsize=20&txt=Drag%20and%20Drop%20Image%20Here&w=450&h=450');
    	});
    });

	$('.working_hours_wrapper input').datetimepicker({
		//inline: true,
        //sideBySide: true,
        format: 'LT'
    });
});

//append ckeditor data 
function get_form_data($is_autosave) {

	//CKEDITOR + validation.js issue 
	for (var i in CKEDITOR.instances)
	{
	    CKEDITOR.instances[i].updateElement();
	}

	$data = $('form').serialize();

	if($is_autosave) {
		$data += '&is_autosave=' + 1;
	}else{
		$data += '&is_autosave=' + 0;
	}

	/*
	$data += '&Vendor[vendor_return_policy]=' + ck_vendor_return_policy.getData(); 
	$data += '&Vendor[vendor_return_policy_ar]=' + ck_vendor_return_policy_ar.getData();
	*/
	
	return $data;
}

function save_logo($is_autosave = false) {

	if(!$is_autosave) {	
		$('.loadingmessage').hide();
	}

	$.post($('#logo_url').val(), get_form_data($is_autosave), function(json) {

		$('.loadingmessage').hide();
	
		//switch to edit mode 
		if(json['vendor_id']) {
			$('input[name="vendor_id"]').val(json['vendor_id']);	
		}
		
		if(json['edit_url']) {
			$('#w0').attr('action', json['edit_url']);	
		}

		if($is_autosave)
			return true;

		if(json['success']) 
		{
			//redirect 
			if(isNewRecord > 0) 
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

function save_basic_info($is_autosave = false) {

	if(!$is_autosave) {	
		$('.loadingmessage').hide();
	}

	$.post($('#basic_info_url').val(), get_form_data($is_autosave), function(json) {

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

function save_main_info($is_autosave = false) {

	if(!$is_autosave) {	
		$('.loadingmessage').hide();
	}

	$.post($('#main_info_url').val(), get_form_data($is_autosave), function(json) {

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

function save_additional_info($is_autosave = false) {

	if(!$is_autosave) {	
		$('.loadingmessage').hide();
	}

	$.post($('#additional_info_url').val(), get_form_data($is_autosave), function(json) {

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

function save_social_info($is_autosave = false) {

	if(!$is_autosave) {	
		$('.loadingmessage').hide();
	}

	$.post($('#social_info_url').val(), get_form_data($is_autosave), function(json) {

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

function save_email_addresses($is_autosave = false) {

	if(!$is_autosave) {	
		$('.loadingmessage').hide();
	}

	$.post($('#email_addresses_url').val(), get_form_data($is_autosave), function(json) {

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
 * Click in final submit button 
 */
$('.btn-complete').click(function()
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
	
	$.post($('#vendor_validate_url').val(), get_form_data(false), function(json) {

		if(json['errors']) 
		{
			show_errors(json);

			$('.btn-complete').removeAttr('disabled');
			$('.btn-complete').html('Complete');
		}

		if(json['success']) 
		{
			$('.btn-complete').parents('form').submit();
		}
	});
});

function show_errors(json) 
{
	$('.has-error').removeClass('has-error');
	$('.form-group .help-block').html('');
	$('.alert-warning').remove();

	$html  = '<div class="alert alert-warning">';
	$html += '	Please check form carefully!';
	$html += '	<button class="close" data-dismiss="alert"></button>';
	$html += '</div>';

	$('.loadingmessage').after($html);

	$('.loadingmessage').hide();

	if(json['errors']['vendor_name']) 
	{
		$(".field-vendor-vendor_name").removeClass('has-success');
		$(".field-vendor-vendor_name").addClass('has-error');
		$(".field-vendor-vendor_name").find('.help-block').html(json['errors']['vendor_name']);
	}

	if(json['errors']['vendor_contact_email']) 
	{
		$(".field-vendor-vendor_contact_email").removeClass('has-success');
		$(".field-vendor-vendor_contact_email").addClass('has-error');
		$(".field-vendor-vendor_contact_email").find('.help-block').html(json['errors']['vendor_contact_email']);
	}

	if(json['errors']['vendor_contact_name']) 
	{
		$(".field-vendor-vendor_contact_name").removeClass('has-success');
		$(".field-vendor-vendor_contact_name").addClass('has-error');
		$(".field-vendor-vendor_contact_name").find('.help-block').html(json['errors']['vendor_contact_name']);
	}

	if(json['errors']['vendor_contact_number']) 
	{
		$(".field-vendor-vendor_contact_number").removeClass('has-success');
		$(".field-vendor-vendor_contact_number").addClass('has-error');
		$(".field-vendor-vendor_contact_number").find('.help-block').html(json['errors']['vendor_contact_number']);
	}

	$('html, body').animate({ scrollTop: 0 }, 'slow');
}

/** 
 * Autosave active tab fields 
 */ 
setInterval(function(){

	if($('#tab_1').parent().hasClass('active')){
		save_logo(true);
	}
	
	if($('#tab_2').parent().hasClass('active')){
		save_basic_info(true);
	}

	if($('#tab_3').parent().hasClass('active')){
		save_main_info(true);
	}

	if($('#tab_4').parent().hasClass('active')){
		save_additional_info(true);
	}

	if($('#tab_5').parent().hasClass('active')){
		save_social_info(true);
	}

	if($('#tab_6').parent().hasClass('active')){
		save_email_addresses(true);
	}

}, 2000);