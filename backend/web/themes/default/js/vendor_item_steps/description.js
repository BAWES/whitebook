

$(function() {

	$('form').submit(function(e) {

		$error = '';

	    if (ck_item_description.getData() == '')
	    {
	    	$error += '<p><i class="fa fa-exclamation"></i> Please add item description.</p>';
	    }

	    if (ck_item_description_ar.getData() == '')
	    {
	    	$error += '<p><i class="fa fa-exclamation"></i> Please add item description - arabic.</p>';
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
