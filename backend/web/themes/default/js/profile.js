CKEDITOR.replace('text-editor');
CKEDITOR.replace('text-editor-2');

/* Begin when loading page first tab opened */
$(function(){
	$('.nav-tabs li:first').addClass('active');
	$('.tab-content div:first').addClass('active');
});
	
function removePhone(phone) {
	$('.controls' + phone).remove();
}

/* Begin Tabs NEXT & PREV buttons */
$('.btnNext').click(function(){
	console.log('profile page');
  $('.nav-tabs > .active').next('li').find('a').trigger('click');
});

$('.btnPrevious').click(function(){
  $('.nav-tabs > .active').prev('li').find('a').trigger('click');
});

var csrfToken = $('meta[name="csrf-token"]').attr('content');
var c1 = true;

$('.onevalid1').click(function() {

	if($('#test').val()==1)
	{
		return false;
	}
	
	if($('#test1').val()==1)
	{
		return false;
	}

  	if($('#vendordraft-vendor_name').val()=='')
	{
		$('.field-vendordraft-vendor_name').addClass('has-error');
		$('.field-vendordraft-vendor_name').find('.help-block').html('Vendor name cannot be blank.');
		c1=false;
  	}

  	if($('#vendordraft-vendor_contact_email').val()=='')
	{
		$('.field-vendordraft-vendor_contact_email').addClass('has-error');
		$('.field-vendordraft-vendor_contact_email').find('.help-block').html('Email cannot be blank.');
		c1=false;
  	}

    // check only if its new record

  	if($('#vendordraft-vendor_password').val()=='')
	{
		$('.field-vendordraft-vendor_password').addClass('has-error');
		$('.field-vendordraft-vendor_password').find('.help-block').html('Password cannot be blank');
		c1=false;
  	}

  	if($('#vendordraft-vendor_contact_name').val()=='')
	{
		$('.field-vendordraft-vendor_contact_name').addClass('has-error');
		$('.field-vendordraft-vendor_contact_name').find('.help-block').html('Contact name  cannot be blank.');
		c1=false;
  	}

    if($('#vendordraft-vendor_contact_number').val()=='')
	{
		$('.field-vendordraft-vendor_contact_number').addClass('has-error');
		$('.field-vendordraft-vendor_contact_number').find('.help-block').html('Contact number cannot be blank.');
		c1=false;
  	}
	  
	if(c1==false)
	{
	   c1='';
	   return false;
	}

	var item_len = $('#vendordraft-vendor_name').val().length;
     
    if($('#vendordraft-vendor_name').val()=='') {
	 	$('.field-vendordraft-vendor_name').addClass('has-error');
		$('.field-vendordraft-vendor_name').find('.help-block').html('Item name cannot be blank.');
		c1=false;
	} else if(item_len < 3){
		$('.field-vendordraft-vendor_name').addClass('has-error');
		$('.field-vendordraft-vendor_name').find('.help-block').html('Item name minimum 4 letters.');
		c1=false;
	}

	return c1;
});

$('.twovalid2').click(function() {

	if($('#vendordraft-vendor_name').val()=='')
	{
		$('.field-vendordraft-vendor_name').addClass('has-error');
		$('.field-vendordraft-vendor_name').find('.help-block').html('Vendor name cannot be blank.');
		return false;
		}

		if($('#vendordraft-vendor_contact_email').val()=='')
	{
		$('.field-vendordraft-vendor_contact_email').addClass('has-error');
		$('.field-vendordraft-vendor_contact_email').find('.help-block').html('Email cannot be blank.');
		return false;
		}

	if($('#vendordraft-vendor_contact_name').val()=='')
	{
		$('.field-vendordraft-vendor_contact_name').addClass('has-error');
		$('.field-vendordraft-vendor_contact_name').find('.help-block').html('Contact name  cannot be blank.');
		return false;
		}

    if($('#vendordraft-vendor_contact_number').val()=='')
	{
		$('.field-vendordraft-vendor_contact_number').addClass('has-error');
		$('.field-vendordraft-vendor_contact_number').find('.help-block').html('Contact number cannot be blank.');
		return false;
    } else {
		return true;
	}

    // if($('#vendordraft-vendor_contact_address').val()=='')
	// {
	// 	$('.field-vendordraft-vendor_contact_address').addClass('has-error');
	// 	$('.field-vendordraft-vendor_contact_address').find('.help-block').html('Contact address cannot be blank.');
	// 	return false;
	// } else {
	// 	return true;
	// }

});//twovalid2 click 

var j= ".($count_vendor + 1).";

function addPhone(current)
{
	$('#addnumber').before('<div class="controls'+j+'"><input type="text" id="vendordraft-vendor_contact_number'+j+'" class="form-control" name="VendorDraft[vendor_contact_number][]" multiple = "multiple" maxlength="15" Placeholder="Phone Number" style="margin:5px 0px;"><input type="button" name="remove" id="remove" value="Remove" onClick="removePhone('+j+')" style="margin:5px;" /></div>');

   j++;

  	$("#vendordraft-vendor_contact_number2").keypress(function (e) {
     	if (e.which  != 43   && e.which  != 45 && e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57 )) {
          	$(".field-vendordraft-vendor_contact_number").find('.help-block').html('Contact number digits only+.').animate({ color: "#a94442" }).show().fadeOut(2000);
            return false;
    	}
   });

    $("#vendordraft-vendor_contact_number3").keypress(function (e) {
     	if ( e.which  != 43   && e.which  != 45  && e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
          $(".field-vendordraft-vendor_contact_number").find('.help-block').html('Contact number digits only.').animate({ color: "#a94442" }).show().fadeOut(2000);
               return false;
    	}
   });

  	$("#vendordraft-vendor_contact_number4").keypress(function (e) {
     	if (e.which  != 43   && e.which  != 45 && e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
          	$(".field-vendordraft-vendor_contact_number").find('.help-block').html('Contact number digits only.').animate({ color: "#a94442" }).show().fadeOut(2000);
            return false;
    	}
    });

  	$("#vendordraft-vendor_contact_number5").keypress(function (e) {
     	if (e.which  != 43 && e.which  != 45 && e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
            $(".field-vendordraft-vendor_contact_number").find('.help-block').html('Contact number digits only.').animate({ color: "#a94442" }).show().fadeOut(2000);
            return false;
    	}
    });
}

$('.btn-add-address').click(function(){
	
	$html  = '<tr>';
	$html += '	<td>';
	$html += '		<input value="" name="vendor_order_alert_emails[]" class="form-control" />';
	$html += '		<span class="error"></span>';
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

$('form').submit(function(){

	$('.alert-warning').remove();

	//check if error 
	if($('.form-group.has-error').length > 0) 
	{
		$('.message_wrapper').html('<div class="alert alert-warning">Please check form carefully!</div>');
		$('html, body').animate({ scrollTop: 0 }, 'slow');
	}

	var imageData = $('.image-editor').cropit('export');

	if(typeof imageData != 'undefined' && imageData.length > 0)
	{
		$('input[name="image"]').val(imageData);
	}
});
	
$('.image-editor').cropit();

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

$(function(){
	$('.working_hours_wrapper input').datetimepicker({
		//inline: true,
        //sideBySide: true,
        format: 'LT'
    });
});
	