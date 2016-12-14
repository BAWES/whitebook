$(document).delegate('.box_available li', 'click', function() {

	$item_id = $(this).attr('data-id');

	$html  = '<li>';
    $html += 	$(this).html();
    $html += '	<input type="hidden" name="items[]" value="' + $item_id + '" />';
    $html += '	<i class="fa fa-close"></i>';
    $html += '</li>';

    $('.box_selected ul').append($html);

	$(this).remove();

	update_package_items();
});

$(document).delegate('.box_selected .fa-close', 'click', function() {
	$(this).parents('li').remove();
	update_package_items();
});

$(document).delegate('.box_selected input', 'keyup', function() {

	$query = $(this).val();

	if(!$query) {
		$('.box_selected li').show();
		return true;
	}

	$('.box_selected li').each(function() {

		if($(this).text().indexOf($query) !== -1) {
			$(this).show();
		} else {
			$(this).hide();
		}
	});	
});

$(document).delegate('.box_available input', 'keyup', function() {

	$query = $(this).val();

	if(!$query) {
		$('.box_available li').show();
		return true;
	}

	$('.box_available li').each(function() {

		if($(this).text().indexOf($query) !== -1) {
			$(this).show();
		} else {
			$(this).hide();
		}
	});	
});

function update_package_items() {
	$.post($('#update_item_url').val(), $('#package_items').serialize());
}