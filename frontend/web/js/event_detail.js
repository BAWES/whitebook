$(document).delegate('.note_wrapper .btn-edit', 'click', function() {
	$note_wrapper = $(this).parents('.note_wrapper');
	$note_wrapper.find('p').hide();
	$note_wrapper.find('form').fadeIn();
});

$(document).delegate('.note_wrapper .btn-save', 'click', function() {
	
	$data = $(this).parents('form').serialize();

	$.post(event_save_note, $data, function(json) {

		$note_wrapper.find('textarea').html(json.note);
		$note_wrapper.find('p span').html(json.note);

		$note_wrapper.find('p').fadeIn();
		$note_wrapper.find('form').hide();
	});
});

$(document).delegate('.btn-mark-incomplete', 'click', function() {
	
	$event_id = $(this).attr('data-event-id');
	$category_id = $(this).attr('data-cat-id');

	$.post(event_mark_incomplete, { 
			event_id : $event_id,
			category_id : $category_id
		}, function(json) {

			$('.progressbar_wrapper').html(json.progress);

			$('button[data-cat-id="' + $category_id + '"]')
				.html(json.btn_text)				
				.removeClass('btn-mark-incomplete')
				.addClass('btn-mark-complete');
		}
	);
});

$(document).delegate('.btn-mark-complete', 'click', function() {

	$event_id = $(this).attr('data-event-id');
	$category_id = $(this).attr('data-cat-id');

	$.post(event_mark_complete, { 
			event_id : $event_id,
			category_id : $category_id
		}, function(json) {

			$('.progressbar_wrapper').html(json.progress);
			
			$('button[data-cat-id="' + $category_id + '"]')
				.html(json.btn_text)
				.addClass('btn-mark-incomplete')
				.removeClass('btn-mark-complete');
		}
	);
});
