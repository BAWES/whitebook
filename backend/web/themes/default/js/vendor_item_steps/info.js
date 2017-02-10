

$(function() {

	$('form').submit(function(e) {

		if($('input[name="category[]"]').length == 0) {
			
			e.preventDefault();
			e.stopPropagation();

			$html  = '<div class="alert alert-warning"><i class="fa fa-exclamation"></i>';
			$html += '	Please, select category';
			$html += '	<button type="button" class="close" data-dismiss="alert"></button>';
			$html += '</div>';

			$('.message_wrapper').html($html);

			$('html, body').animate({ scrollTop: 0 }, 'slow');

			return false;
		}
	});
});
