$(document).delegate('#review-form', 'submit', function(e) {
	$(this).attr('disabled', 'disabled');
	$('#review-form .alert').remove();

	$.post(reviewUrl, $('#review-form').serialize(), function(json) {

		if(json.operation == 'success') 
		{
			location = location;

			/*var html = '<div class="alert alert-warning">';
			html += json.message;
			html += '</div>';
			$('#review-form').fadeOut();
			$('#review-form').after(html);*/
		}

		if(json.operation == 'error') 
		{
			var html = '<div class="alert alert-warning"><ul>';
			$.each(json.message, function(index, value) {
				$.each(value, function(key, err) {
					html += '<li>' + err + '</li>';
				});				
			});			
			html += '</ul></div>';
			$('#review-form').append(html);
		}

		$(this).removeAttr('disabled');
	});

	e.preventDefault();
});

$(document).delegate('.rating .fa', 'click', function() {
    //remove active class
    $('.rating li').removeClass('active');
    //add active class
    $(this).parents('li').addClass('active');
    $(this).parents('li').prevAll().addClass('active');
    //set value 
    $('#vendorreview-rating').val($(this).parents('li').attr('data-value'));
});

$(document).delegate('.category_listing_nav a', 'click', function(e) {

    $('.left-main-cat').val($(this).attr('data-slug')).change();

    $('html, body').animate({ scrollTop: $('.listing_right').offset().top }, 'slow');

    e.preventDefault();
});

jQuery(function()
{
    //open return policy tab 

    if(location.hash == '#collapse2')
    {
        $('a[href=\"#collapse2\"]').trigger('click');

        $('html, body').animate({ scrollTop: $('.vendor-profile-detail').offset().top }, 'slow');
    }
});
