//glyphicon-ok-sign

$(function(){	
	address();
});

//load address selection form 
function address() {

	$('.checkout-wizard .text-success').removeClass('text-success');

	$.get(address_url, function(html) {
		if(html) {
			$('.checkout_content_wrapper').html(html);	
			jQuery('.selectpicker').selectpicker();
		}else{
			location = cart_url;
		}
	});

	$('html, body').animate({ scrollTop: 0 }, 'slow');
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

$(document).delegate('.address_block', 'click', function(){
	$(this).parent().find('.active').removeClass('active');
	$('input["address_id"]').val($(this).attr('data-id'));
	$(this).addClass('active');
});

$(document).delegate('#modal_create_address form', 'submit', function(e){
	
	$('.has-error').removeClass('has-error');
    $('.has-success').removeClass('has-success');

    //check all textarea 
    $('#modal_create_address textarea').each(function(){
        if(!$(this).val()){
            $(this).parent().addClass('has-error');
        }
    })

    //check address type
    var address_type_id = $('#customeraddress-address_type_id').val();

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

    $(document).delegate('#customeraddress-address_type_id', 'change', function (){
        var csrfToken = $('meta[name=\"csrf-token\"]').attr('content');
        var address_type_id = $('#customeraddress-address_type_id').val();
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

