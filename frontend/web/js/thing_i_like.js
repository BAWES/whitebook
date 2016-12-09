
$(document).delegate('.wishlist_category_wrapper a', 'click', function() {
	$.get($(this).attr('data-href'), function(html) {
		$('.wishlist_item_wrapper').html(html);
		imgError();
	});
});

$(document).delegate('.wishlist_item_wrapper .btn-wishlist-remove', 'click', function() {
	
	$this = $(this);
	
	$.get($(this).attr('data-href'), function() {
		$this.parents('.wishlist_item').remove();
	});
});
