
$(function() {

	$('form').submit(function(e) {

		$error = '';

    	$type = $('#vendoritem-type_id option:selected').html();

		if($('#vendoritem-item_default_capacity').val() == '') 
    	{
			$error += '<p><i class="fa fa-exclamation"></i> Maximum quantity ordered per day field require.</p>';	    		
    	}	
    
    	if($('#vendoritem-item_how_long_to_make').val() == '') 
    	{
			$error += '<p><i class="fa fa-exclamation"></i> No of days delivery field require.</p>';	    		
    	}

    	if($('#vendoritem-item_minimum_quantity_to_order').val() == '') 
    	{
			$error += '<p><i class="fa fa-exclamation"></i> Item Minimum Quantity to Order field require.</p>';	    		
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
