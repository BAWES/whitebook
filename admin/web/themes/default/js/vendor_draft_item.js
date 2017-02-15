
$('.btn-reject').click(function(){
    $('input[name="draft_item_id"]').val($(this).attr('data-id'));
    $('.modal_reject').modal('show');
});

$('.btn-reject-submit').click(function(){
    $(this).attr('disabled', 'disabled');
    $(this).html('Please wait...');

    $.post($('#reject_url').val(), $('.modal_reject form').serialize() , function(json){

        $(this).removeAttr('disabled');
        $(this).html('Submit');
        $('.modal_reject').modal('hide');

        location = json.location;

    });
});
