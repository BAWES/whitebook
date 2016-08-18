//glyphicon-ok-sign

jQuery(function(){	
	address();
});

//load address selection form 
function address() {

	$('.checkout-wizard .text-success').removeClass('text-success');

	$.get(address_url, function(html) {
		$('.checkout_content_wrapper').html(html);
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
				payment();
			}
		}
	);
}

function payment() {

	$('.checkout-wizard .text-success').removeClass('text-success');
	$('#ar-step-address').addClass('text-success');

	$.get(payment_url, function(html) {
		$('.checkout_content_wrapper').html(html);

		$('html, body').animate({ scrollTop: 0 }, 'slow');
	});
}

function save_payment() {
	$.post(
		save_payment_url, 
		{ 
			payment_method : $('input[name="payment_method"]').val() 
		},
		function(data) {
					
			if(data.errors) {

			} else {
				confirm();
			}
		}
	);	
}

function confirm() {

	$('#ar-step-address').addClass('text-success');
	$('#ar-step-payment').addClass('text-success');

	$.get(confirm_url, function(html) {
		$('.checkout_content_wrapper').html(html);
		$('html, body').animate({ scrollTop: 0 }, 'slow');
	});
}

$(document).delegate('.address_block', 'click', function(){
	$(this).parent().find('.active').removeClass('active');
	$(this).parent().parent().find('input').val($(this).attr('data-id'));
	$(this).addClass('active');
});

$(document).delegate('#modal_create_address form', 'submit', function(e){
	
	$.post(add_address_url, $(this).serialize(), function(data) {

		if(data['errors']) {

			$.each(data['errors'], function(index, errors) {
	            $.each(errors, function() {
	                jQuery('#modal_create_address form .error.' + index).append('<p>' + this + '</p>');
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

    $(document).delegate('#customeraddress-country_id', 'change', function (){
        var csrfToken = $('meta[name=\"csrf-token\"]').attr('content');
        var country_id = $('#customeraddress-country_id').val();
        var path = city_url;
        
        $.ajax({
            type: 'POST',
            url: path, //url to be called
            data: { country_id: country_id ,_csrf : csrfToken}, //data to be send
            success: function( data ) {
                $('#customeraddress-city_id').html(data);
            }
        });
    });

    $(document).delegate('#customeraddress-city_id', 'change', function (){
        var csrfToken = $('meta[name=\"csrf-token\"]').attr('content');
        var city_id = $('#customeraddress-city_id').val();
        var path = area_url;
        
        $.ajax({
            type: 'POST',
            url: path, //url to be called
            data: { city_id: city_id ,_csrf : csrfToken}, //data to be send
            success: function( data ) {
                $('#customeraddress-area_id').html(data);
            }
        });
    });
});

