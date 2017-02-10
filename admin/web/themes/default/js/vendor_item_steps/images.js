

$(function() {

	$('form').submit(function(e) {

		$error = '';

	    if ($('.table-item-image tbody tr').length == 0)
	    {
	    	$error += '<p><i class="fa fa-exclamation"></i> Please upload atleast 1 image.</p>';
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
