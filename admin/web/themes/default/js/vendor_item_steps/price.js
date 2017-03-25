
$(function() {

	$('form').submit(function(e) {

		$error = '';

	    if ($('#vendoritem-item_for_sale').prop('checked') == true)
	    {
    		if($('#vendoritem-item_default_capacity').val() == '') 
	    	{
				$error += '<p><i class="fa fa-exclamation"></i> Maximum quantity ordered per day field require.</p>';	    		
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
