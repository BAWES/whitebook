
$(function() {

	$('form').submit(function(e) {

		$error = [];

		$.each($('.txt_menu_name'), function() {
			if($(this).val() == '') {
				$error['txt_menu_name'] = '<p><i class="fa fa-exclamation"></i> Menu name field require.</p>';
			}
		});

		$.each($('.txt_menu_name_ar'), function() {
			if($(this).val() == '') {
				$error['txt_menu_name_ar'] = '<p><i class="fa fa-exclamation"></i> Menu name - Arabic field require.</p>';
			}
		});

		$.each($('.txt_menu_item_name'), function() {
			if($(this).val() == '') {
				$error['txt_menu_item_name'] = '<p><i class="fa fa-exclamation"></i> Menu item name field require.</p>';
			}
		});

		$.each($('.txt_menu_item_name_ar'), function() {
			if($(this).val() == '') {
				$error['txt_menu_item_name_ar'] = '<p><i class="fa fa-exclamation"></i> Menu item name - Arabic field require.</p>';
			}
		});

		$.each($('.txt_price'), function() {
			if(!$.isNumeric($(this).val())) {
				$error['txt_price'] = '<p><i class="fa fa-exclamation"></i> Price field not valid.</p>';
			}
		});

	    if($error['txt_price'] || $error['txt_menu_item_name_ar'] 
	    	|| $error['txt_menu_item_name'] || $error['txt_menu_name'] || $error['txt_menu_name_ar']) {

		    e.preventDefault();
			e.stopPropagation();

			$html  = '<div class="alert alert-warning">';
			$html += '	<button type="button" class="close" data-dismiss="alert"></button>';
			
			if($error['txt_menu_name']) {
				$html += $error['txt_menu_name'];
			}

			if($error['txt_menu_name_ar']) {
				$html += $error['txt_menu_name_ar'];
			}

			if($error['txt_menu_item_name']) {
				$html += $error['txt_menu_item_name'];
			}

			if($error['txt_menu_item_name_ar']) {
				$html += $error['txt_menu_item_name_ar'];
			}

			if($error['txt_price']) {
				$html += $error['txt_price'];
			}

			$html += '</div>';

			$('.message_wrapper').html($html);

			$('html, body').animate({ scrollTop: 0 }, 'slow');

			return false;
		}
	});
});
