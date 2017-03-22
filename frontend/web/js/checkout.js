
$(function(){	
	
	if($('#ar-step-login').length > 0) {
		login();
	} else {
		address();	
	}
});

function login() {

	$('.checkout-wizard .text-success').removeClass('text-success');

	$.get(login_url, function(html) {
		$('.checkout_content_wrapper').html(html);
	});

	$('html, body').animate({ scrollTop: 0 }, 'slow');
}

//load address selection form 
function address() {

	$('#ar-step-address').removeClass('text-success');

	$.get(address_url, function(html) {
		if(html) {
			$('.checkout_content_wrapper').html(html);
			$('input[name="CustomerAddress[address_type_id]"]:checked').trigger('change');
			$('.selectpicker').selectpicker();
		}else{
			location = cart_url;
		}
	});

	$('html, body').animate({ scrollTop: 0 }, 'slow');
}

function save_guest_address() {

	$('form .error').html('');
	
	//check all input 

	$('.checkout_content_wrapper .has-error').removeClass('has-error');

    $('.checkout_content_wrapper input.required').each(function(){
        if(!$(this).val()){
            $(this).parent().addClass('has-error');
        }
    });

    if($('.checkout_content_wrapper .has-error').length > 0) {
    	console.log($('.has-error'));
    	return false;
    }

	$.post(
		save_guest_address_url, 
		$('.checkout_content_wrapper form').serialize(),
		function(json) {
			
			if(json.errors) {
				
				$.each(json['errors'], function(index, errors) {
		            $.each(errors, function() {
		                $('form .error.' + index).append('<p>' + this + '</p>');
		            });
		        });	

				$('html, body').animate({ scrollTop: 0 }, 'slow');
			} else {
				// payment();
                confirm();
			}
		}
	);
}

function save_address() {

	$('.checkout_message_wrapper').html('');

	$.post(
		save_address_url, 
		$('#address_selection_form').serialize(),
		function(data) {
			
			if(data.errors) {
				
				$html  = '<div class="alert alert-success">';
				$html += msg_please_select_address_for_each_items;
				$html += '<button class="close" data-dismiss="alert">&times;</button>';
				$html += '</div>';

				$('.checkout_message_wrapper').html($html);

				$('html, body').animate({ scrollTop: 0 }, 'slow');
			} else {
				// payment();
                confirm();
			}
		}
	);
}

function payment() {

	$('.checkout_message_wrapper').html('');

	$('.checkout-wizard .text-success').removeClass('text-success');
	$('#ar-step-address').addClass('text-success');

	$.get(payment_url, function(html) {
		if(html) {
			$('.checkout_content_wrapper').html(html);
			$('html, body').animate({ scrollTop: 0 }, 'slow');
		} else {
			location = cart_url;
		}		
	});
}

function save_payment() {
	$.post(
		save_payment_url, 
		{ 
			payment_method : $('input[name="payment_method"]:checked').val() 
		},
		function(data) {
					
			if(data.error) {

				$html  = '<div class="alert alert-success">';
				$html += data.error;
				$html += '<button class="close" data-dismiss="alert">&times;</button>';
				$html += '</div>';

				$('.checkout_message_wrapper').html($html);

				$('html, body').animate({ scrollTop: 0 }, 'slow');
			} else {
				confirm();
			}
		}
	);	
}

function confirm() {

	$('.checkout_message_wrapper').html('');
	
	$('#ar-step-address').addClass('text-success');
	// $('#ar-step-payment').addClass('text-success');

	$.get(confirm_url, function(html) {
		if(html) {
			$('.checkout_content_wrapper').html(html);
			$('html, body').animate({ scrollTop: 0 }, 'slow');	
		} else {
			location = cart_url;
		}
	});
}

$(document).delegate('.btn-guest-checkout', 'click', function(){
	$('#ar-step-login').addClass('text-success');
	address();
});

$(document).delegate('.frm_guest', 'submit', function(e) {

	$.post(login_url, $(this).serialize(), function(json) {
		
		$('.frm_guest .error').html('');

		if(json['status'] == 1) {
			location = location;
		}

		if(json['errors']) {				
			$.each(json['errors'], function(index, errors) {
	            $.each(errors, function() {
	                $('.frm_guest .error.' + index).append('<p>' + this + '</p>');
	            });
	        });	
		}
	});

	e.preventDefault();
});


$(document).delegate('.frm_login', 'submit', function(e){
	$.post(login_url, $(this).serialize(), function(json) {
		
		$('.frm_login .error').html('');

		if(json['status'] == 1) {
			location = location;
		}

		if(json['errors']) {				
			$.each(json['errors'], function(index, errors) {
	            $.each(errors, function() {
	                $('.frm_login .error.' + index).append('<p>' + this + '</p>');
	            });
	        });	
		}
	});

	e.preventDefault();
});

$(document).delegate('.address_block', 'click', function(){
	$(this).parent().find('.active').removeClass('active');
    $('#selected_address_id').val($(this).attr('data-id'));
	//$('input["address_id"]').val($(this).attr('data-id'));
	$(this).addClass('active');
});

$(document).delegate('#modal_create_address form', 'submit', function(e){
	
	$('.has-error').removeClass('has-error');
    $('.has-success').removeClass('has-success');

    //check all input 
    $('#modal_create_address input.required').each(function(){
        if(!$(this).val()){
            $(this).parent().addClass('has-error');
        }
    });

    //check address type
    var address_type_id = $('input[name="CustomerAddress[address_type_id]"]:checked').val();

    if(!address_type_id) {
        $('.field-customeraddress-address_type_id').addClass('has-error');
    }

    //address name
    var address_name = jQuery('#customeraddress-address_name').val();

    if(!address_name) {
        jQuery('.field-customeraddress-address_name').addClass('has-error');
    }

    if($('#modal_create_address .has-error').length > 0){
        return false;
    }

	$.post(add_address_url, $(this).serialize(), function(data) {

		if(data['errors']) {

			$.each(data['errors'], function(index, errors) {
	            $.each(errors, function() {
	                $('#modal_create_address form .error.' + index).append('<p>' + this + '</p>');
	            });
	        });

		} else{
			jQuery('#modal_create_address').modal('hide');//hide modal 
			address();//load new address 	
		}
	});

	e.preventDefault();
});

$(function (){

    $(document).delegate('input[name="CustomerAddress[address_type_id]"]', 'change', function (){
        
        var csrfToken = $('meta[name=\"csrf-token\"]').attr('content');
        var address_type_id = $(this).val();//$('#customeraddress-address_type_id input:selected').val()
        var path = questions_url;
        
        $.ajax({
            type: 'POST',
            url: path, //url to be called
            data: { address_type_id: address_type_id ,_csrf : csrfToken}, //data to be send
            success: function( data ) {
                 $('.question_wrapper').html(data);
            }
        });
    });

	$(document).delegate('.address_insert_block', 'click', function(){
	    $('input[name="cart_id"]').val($(this).attr('data-id'));
	});
});

