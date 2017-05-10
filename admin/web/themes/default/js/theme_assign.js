
$(document).delegate('#search', 'change', function() {

    $query = $(this).val();

    if(!$query) {
        $('.item-listing label').show();
        return true;
    }

    $('.item-listing label').each(function() {

        if($(this).text().indexOf($query) !== -1) {
            $(this).show();
        } else {
            $(this).hide();
            $(this).find('input').prop('checked', false);
        }
    }); 
});

