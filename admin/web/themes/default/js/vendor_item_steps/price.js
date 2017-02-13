
$(function() {

	$('#vendoritem-type_id').change(function(){

		if($(this).find('option:selected').html() == 'Product') {
			$('.field-vendoritem-item_amount_in_stock').show();
			$('.field-vendoritem-item_default_capacity').hide();
		}else{
			$('.field-vendoritem-item_default_capacity').show();
			$('.field-vendoritem-item_amount_in_stock').hide();
		}
	});

	$('#vendoritem-type_id').trigger('change');

	$('form').submit(function(e) {

		$error = '';

	    if ($('#vendoritem-item_for_sale').prop('checked') == true)
	    {
	    	$type = $('#vendoritem-type_id option:selected').html();

	    	if($type == 'Product') 
	    	{
	    		if($('#vendoritem-item_amount_in_stock').val() == '') 
		    	{
					$error += '<p><i class="fa fa-exclamation"></i> Item # of stock field require.</p>';	    		
		    	}
	    	} else 
	    	{
	    		if($('#vendoritem-item_default_capacity').val() == '') 
		    	{
					$error += '<p><i class="fa fa-exclamation"></i> Maximum quantity ordered per day field require.</p>';	    		
		    	}
	    	}

	    	if($('#vendoritem-item_minimum_quantity_to_order').val() == '') 
	    	{
				$error += '<p><i class="fa fa-exclamation"></i> Minimum Quantity to Order field require.</p>';	    		
	    	}
	    }

	    if($error) {

		    e.preventDefault();
			e.stopPropagation();

			$html  = '<div class="alert alert-warning">';
			$html += '	<button type="button" class="close" data-dismiss="alert"></button>';
			$html += 	$error;
			$html += '</div>';

			$('.message_wrapper').html($html);

			$('html, body').animate({ scrollTop: 0 }, 'slow');

			return false;
		}
	});
});
