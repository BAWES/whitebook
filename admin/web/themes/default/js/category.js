$(document).delegate('.btn-move-items', 'click', function() {
	
	$('#from_id').val($(this).attr('data-id'));

	$parent_id = $(this).attr('data-parent-id');

	$.post($('#load_sub_category_url').val(), { id : $parent_id }, function(data) {
		$('#modal_move_cat_items select').html(data);
		$('#modal_move_cat_items').modal('show');
	});
});

$(document).delegate('.btn-move-submit', 'click', function() {

	$t = $(this);

	$t.attr('disabled', 'disabled').html('Loading...');

	$.post($('#move_url').val(), 
		{ 
			from_id : $('#from_id').val(),
			to_id : $('#to_id').val()
		}, 
		function(json) {
			if(json.success) {
				location = location;
			}

			if(json.error) {
				alert(json.error);
				$t.removeAttr('disabled').html('Submit');
			}			
		}
	);
});


