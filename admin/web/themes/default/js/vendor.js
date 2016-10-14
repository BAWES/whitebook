
var csrfToken = $('meta[name="csrf-token"]').attr("content");
var c1 = true;
  
$package_count = 0;

$('.btn-add-package').click(function() {

	$('.package-list-error').html('');

	$.post(validate_vendor_url, $('form').serialize(), function(json) {

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
});

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

$('.controls1').find('#remove').remove();
$('.datepicker').datepicker();
$('select#package').hide();

$(function()
{	/* Begin when loading page first tab opened */
 	$('.nav-tabs li:first').addClass("active");
 	$(".tab-content div:first").addClass("active");
 	/* End when loading page first tab opened */

	CKEDITOR.replace('text-editor');
	CKEDITOR.replace('text-editor-2');
});

function removePhone(phone) {
	$(".controls"+phone).remove();
}

/* Begin Tabs NEXT & PREV buttons */
$('.btnNext').click(function(){
  $('.nav-tabs > .active').next('li').find('a').trigger('click');
});

$('.btnPrevious').click(function(){
  $('.nav-tabs > .active').prev('li').find('a').trigger('click');
});

//category add drop downlist
$(".vendor-category_id:last-child").css({"clear" : "both","float" :"inherit"});
$('#option').hide();

$(function(){
 	$('#vendor-category_id').multiselect({
		'enableFiltering': true,
		'filterPlaceholder': 'Search for something...'
	});
});

$(document).ready(function () {

	$('#package_start_date').datepicker({  format: 'dd-mm-yyyy', startDate: 'today',});
	$('#package_end_date').datepicker({  format: 'dd-mm-yyyy', });

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


 	$('#vendor-vendor_name').bind("paste",function(e) {
    	e.preventDefault();
 	});

 	$("#vendor-vendor_name").on('focusout',function () {//keyup keypress 

		if($("#vendor-vendor_name").val().length > 3) {
			var mail = $("#vendor-vendor_name").val();
	        
	        var path = vendornamecheck_url;
	        
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
					
					} else {

						$(".field-vendoritem-item_name").find('.help-block').html('');
						$('.loadingmessage').hide();
						$('#test1').val(0);
						return false;
					}
		        }
	        });
		}
	});
});

function validateEmail(email) {
    var re = /^[a-zA-Z0-9!#$%&'*+\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&'*+\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?$/;
    return re.test(email);
}

$(document).ready(function(){

  	$('#vendor-vendor_contact_number').bind("paste",function(e) {
    	e.preventDefault();
  	});

  	$('#vendor-vendor_contact_email').bind("paste",function(e) {
    	e.preventDefault();
  	});
 
 	$("#vendor-vendor_contact_email").on('focusout', function () {	 	
	 	validateEmailAjax();
	});//on #vendor-vendor_contact_email focusout 

 	if(is_new_record) { 
	
		$('#vendor-vendor_status').prop('checked', true);
	
	} else { 
		if(vendor_status == 'Active') {
			$('#vendor-vendor_status').prop('checked', true);
		} else { 
			$('#vendor-vendor_status').prop('checked', false);
		} 
	} 

	if(is_new_record) { 

		$('#vendor-approve_status').prop('checked', true);

	} else {

		if(approve_status=='Yes') {
			//$("#vendor-vendor_logo_path").val('image');
			$('#vendor-approve_status').prop('checked', true);
		} else { 
			$('#vendor-approve_status').prop('checked', false);
		} 
	} 

});//on doc ready 

$(".onevalid1" ).click(function() {
	return validate_step1();
});

$( ".twovalid2" ).click(function() {
	return validate_step2();
});//validation of second step 

//------------------------- validation step : 1 -----------------------------//

function validate_step1() {

	if($('#test').val()==1) {
		return false;
	}

	if($('#test1').val()==1)
	{
		return false;
	}

	if(is_new_record && $("#vendor-vendor_logo_path").val()=='')
	{
			$(".field-vendor-vendor_logo_path").addClass('has-error');
			$(".field-vendor-vendor_logo_path").find('.help-block').html('Please upload a file.');
			c1=false;
  	}

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
  	
  	if(is_new_record && $("#vendor-vendor_password").val()!='')
	{
			var pass=$("#vendor-vendor_password").val();
			if(pass.length<6)
			{$(".field-vendor-vendor_password").addClass('has-error');
			$(".field-vendor-vendor_password").find('.help-block').html('Password should contain minimum 6 Letter.');
			c1=false;}
  	}
  	
  	if(is_new_record && $("#vendor-confirm_password").val()=='')
	{
			$(".field-vendor-confirm_password").addClass('has-error');
			$(".field-vendor-confirm_password").find('.help-block').html('Confirm password cannot be blank.');
			c1 = false;
  	
  	} else if(is_new_record && $("#vendor-confirm_password").val()!=$("#vendor-vendor_password").val()) {

			$(".field-vendor-confirm_password").addClass('has-error');
			$(".field-vendor-confirm_password").find('.help-block').html('Password and confirm password not same.');
			c1 = false;
  	}
  	
    if($("#vendor-vendor_contact_name").val()=='')
	{
			$(".field-vendor-vendor_contact_name").addClass('has-error');
			$(".field-vendor-vendor_contact_name").find('.help-block').html('Contact name  cannot be blank.');
			c1 = false;
  	}

    if($("#vendor-vendor_contact_number").val()=='')
	{
			$(".field-vendor-vendor_contact_number").addClass('has-error');
			$(".field-vendor-vendor_contact_number").find('.help-block').html('Contact number cannot be blank.');
			c1 = false;
  	}

	if(c1 == false)
	{
		c1='';
		return false;
	}

	var item_len = $("#vendor-vendor_name").val().length;
    
    if($("#vendor-vendor_name").val()=='')
	{
	 	$(".field-vendor-vendor_name").addClass('has-error');
		$(".field-vendor-vendor_name").find('.help-block').html('Item name cannot be blank.');
		c1 = false;
	
	} else if(item_len < 3) {

		$(".field-vendor-vendor_name").addClass('has-error');
		$(".field-vendor-vendor_name").find('.help-block').html('Item name minimum 4 letters.');
		c1 = false;
	}

	if(!c1) {
		return c1;
	}

	if($("#vendor-vendor_contact_email").val()!='')
	{
		return validateEmailAjax();
  	}

  	return true;
}

//------------------------- validation step : 2 -----------------------------//

function validate_step2() {

	if($("#vendoritem-item_amount_in_stock").val()=='')
	{
			$(".field-vendor-vendor_logo_path").addClass('has-error');
			$(".field-vendor-vendor_logo_path").find('.help-block').html('Please upload a file.');
			return false;
  	}

  	if(!$('#vendor-category_id').val())
	{
		$(".field-vendor-category_id").addClass('has-error');
		$(".field-vendor-category_id").find('.help-block').html('category name cannot be blank.');
		return false;
  	} 

  	return validate_step1();
}

function validateEmailAjax() {

	if(validateEmail($("#vendor-vendor_contact_email").val()) == true && is_new_record){
			
		$(".field-vendor-vendor_contact_email").removeClass('has-success');
		$(".field-vendor-vendor_contact_email").addClass('has-error');
		$('.loadingmessage').show();

		var mail = $("#vendor-vendor_contact_email").val();
		var path = emailcheck_url;
		
		$.ajax({
			type: 'POST',
			url: path, //url to be called
			async:true,
			data: { id: mail ,_csrf : csrfToken}, //data to be send
			success: function( data ) {
				
				$("input[name=email_valid]").val(data);
				
				if(data > 0)
				{
					$(".field-vendor-vendor_contact_email").removeClass('has-success');
					$(".field-vendor-vendor_contact_email").addClass('has-error');
					$(".field-vendor-vendor_contact_email").find('.help-block').html('Email already exists.');
					$('.loadingmessage').hide();
					c1 = false;
					return false;
				
				} else {
					$(".field-vendor-vendor_contact_email").removeClass('has-error');
					$(".field-vendor-vendor_contact_email").addClass('has-success');
					$(".field-vendor-vendor_contact_email").find('.help-block').html('');
					$('.loadingmessage').hide();
					$('#test').val(0);			
					c1 = true;				
				}
			 }
		});

	} else {
		c1 = false;
		return false;
	}
}

var j = $('input[name="Vendor[vendor_contact_number][]"]').length;

function addPhone(current)
{
	$html  = '<div class="controls'+j+'">';
	$html += '<input type="text" id="vendor-vendor_contact_number'+j+'" class="form-control" name="Vendor[vendor_contact_number][]" multiple = "multiple" maxlength="15" Placeholder="Phone Number" style="margin:5px 0px;">';
	$html += '<input type="button" name="remove" id="remove" value="Remove" onClick="removePhone('+j+')" style="margin:5px;" />';
	$html += '</div>';
	
	$('#addnumber').before($html);

  	$("#vendor-vendor_contact_number" + j ).keypress(function (e) {
     	
     	if (e.which != 43 && e.which != 45 && e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
          	
          	$(".field-vendor-vendor_contact_number")
          		.find('.help-block')
          		.html('Contact number digits only.')
          		.animate({ color: "#a94442" })
          		.show()
          		.fadeOut(2000);
            
            return false;
    	}
    });

    j++;
}
