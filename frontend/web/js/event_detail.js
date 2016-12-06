var event_id = $('#event_id').val();
var event_name = $('#event_name').val();
var add_invite = $('#add_invite').val();
var invite_detail = $('#invite_detail').val();
var update_invite = $('#update_invite').val();
var delete_invite = $('#delete_invite').val();
var event_mark_incomplete = $('#event_mark_incomplete').val();
var event_mark_complete = $('#event_mark_complete').val();
var event_save_note = $('#event_save_note').val();
var edit_popup_url = $('#edit_popup_url').val();
var txt_delete_confirm = $('#txt_delete_confirm').val();
var delete_event_url = $('#delete_event_url').val();

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

function editevent(event_id)
{
    jQuery.ajax({
        type:'POST',
        url: edit_popup_url,
        data:{
            'event_id':event_id
        },
        success:function(data)
        {

            jQuery('#editeventModal').html(data);
            jQuery('.selectpicker').selectpicker('refresh');
            jQuery('#edit_event_date').datepicker({
                format: 'dd-mm-yyyy',
                startDate:'today',
                autoclose:true,
            });
            jQuery('#EditeventModal').modal('show');
        }
    });
}

$(document).ready(function () {
    jQuery('#collapse0').attr('aria-expanded', 'true');
    jQuery('#collapse0').attr('class', 'panel-collapse collapse in');
});

function isEmail(email) {
    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email);
}

$('label#search-labl3').click(function(){
    jQuery.pjax.reload({container:'#invitee-grid'});
});

function deleteeventitem(item_link_id, category_name,category_id,event_id,tis)
{
    if (confirm(txt_delete_confirm)) {

        jQuery.ajax({
            url: delete_event_url,
            type:'POST',
            data: {
                'item_link_id':item_link_id,
                'category_id':category_id,
                'event_id':event_id
            },
            success:function(data)
            {
                if(data!=-1)
                {
                    jQuery('#'+tis).parents('.panel-default').find('span#item_count').html(data);
                    jQuery('#'+tis).parents('li').remove();
                    jQuery('#login_success').modal('show');
                    jQuery('#login_success #success').html('<span class=\"sucess_close\">&nbsp;</span><span class=\"msg-success\" >Success! Item removed from the '+category_name+'.</span>');
                    window.setTimeout(function() {jQuery('#login_success').modal('hide');},2000);
                }
                else{
                    jQuery('#login_success').modal('show');
                    jQuery('#success').html('<span class=\"sucess_close\">&nbsp;</span><span class=\"msg-success\">Error! Something went wrong.</span>');
                    window.setTimeout(function() {jQuery('#login_success').modal('hide');}, 2000);
                }
            }
        })
    }
}