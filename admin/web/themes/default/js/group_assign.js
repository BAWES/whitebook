
$(document).delegate('#chk_all', 'change', function() {
    if($(this).prop('checked')) 
    {
        $('.themes-assign table input').prop('checked', true);    
    }
    else
    {
        $('.themes-assign table input').prop('checked', false);    
    }
});

$(document).delegate('#search', 'change', function() {

    $query = $(this).val();

    if(!$query) {
        $('#tbl_items tbody tr').show();
        return true;
    }

    $('#tbl_items tbody tr').each(function() {

        if($(this).text().indexOf($query) !== -1) {
            $(this).show();
        } else {
            $(this).hide();
            $(this).find('input').prop('checked', false);
        }
    }); 
});

