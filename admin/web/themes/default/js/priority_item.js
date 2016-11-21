
$('#blocked_error').hide();

var csrfToken = $('meta[name=\"csrf-token\"]').attr('content');

if(!is_new_record) {
    $('input#priorityitem-priority_start_date').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true,
    });

    $('input#priorityitem-priority_end_date').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true
    });
}

$(function (){
    $("#priorityitem-item_id").on("change",function (){
        var csrfToken = $('meta[name="csrf-token"]').attr("content");
        var item = $('#priorityitem-item_id').val();
        var path = $('#load_date_time_url').val();
        
        $('.loadingmessage').show();

        $.ajax({
            type: 'POST',
            dataType:"json",
            url: path, //url to be called
            data: { item: item ,priority_id : priority_id,_csrf : csrfToken}, //data to be send
            success: function( data ) {
                $('.loadingmessage').hide();
                $('.field-priorityitem-priority_start_date').find('input').remove();
                $('.field-priorityitem-priority_start_date').find('label').after(data.input1);
                $('.field-priorityitem-priority_end_date').find('input').remove();
                $('.field-priorityitem-priority_end_date').find('label').after(data.input2);
                //
                $('#blocked_dates').attr('value',data.date1);

                var forbidden=data.date;

                $('#priorityitem-priority_start_date,#priorityitem-priority_end_date').datepicker({
                    format: 'dd-mm-yyyy',
                    startDate:'d',
                    autoclose: true,
                    beforeShowDay:function(Date){
                        var curr_date = Date.toJSON().substring(0,10);

                        if (forbidden.indexOf(curr_date)>-1) return false;
                    }
                });
            }
        });
    });
});


$( "#priorityitem-priority_start_date" ).click(function() {

    if(!is_new_record) {
        return true;
    }

    var path = $('#load_date_time_url').val();
    var item = $('#priorityitem-item_id').val();
        
    $('.loadingmessage').show();
    
    $.ajax({
        type: 'POST',
        dataType:"json",
        url: path, //url to be called
        data: { item: item ,priority_id : priority_id, _csrf : csrfToken}, //data to be send
        success: function( data ) {

            $('.loadingmessage').hide();
            $('.field-priorityitem-priority_start_date').find('input').remove();
            $('.field-priorityitem-priority_start_date').find('label').after(data.input1);

            var forbidden=data.date;

            $('input#priorityitem-priority_start_date').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                beforeShowDay:function(Date){
                    var curr_date = Date.toJSON().substring(0,10);
                    if (forbidden.indexOf(curr_date)>-1) return false;
                }
            });
        }

    });
});

$( "#priorityitem-priority_end_date" ).click(function() {

    if(!is_new_record) {
        return true;
    }

    var path = $('#load_date_time_url').val();
    var item = $('#priorityitem-item_id').val();
    
    $('.loadingmessage').show();
    
    $.ajax({
        type: 'POST',
        dataType:"json",
        url: path, //url to be called
        data: { item: item ,priority_id : priority_id, _csrf : csrfToken}, //data to be send
        success: function( data ) {

            $('.loadingmessage').hide();
            $('.field-priorityitem-priority_end_date').find('input').remove();
            $('.field-priorityitem-priority_end_date').find('label').after(data.input2);

            var forbidden = data.date;

            $('input#priorityitem-priority_end_date').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                beforeShowDay:function(Date){
                    var curr_date = Date.toJSON().substring(0,10);

                    if (forbidden.indexOf(curr_date)>-1) 
                        return false;
                }
            });
        }
    });
});

$(function(){

    if(is_new_record) {

        var item = $("#priorityitem-item_id").val();
        var path = $('#load_date_time_url').val();
     
        $.ajax({
            type: 'POST',
            dataType:"json",
            url: path, //url to be called
            data: { item: item, priority_id: priority_id, _csrf : csrfToken}, //data to be send
            success: function( data ) {
                $('.loadingmessage').hide();
                $('#blocked_dates').attr('value',data.date1);
            }
        });
    }
});

$("#submit1").click(function () {
    if($("#priorityitem-item_id").val()=='')
    {
            $(".field-priorityitem-item_id").addClass('has-error');
            $(".field-priorityitem-item_id").find('.help-block').html('Select item name');
            return false;
    }
    if($("#priorityitem-priority_level").val()=='')
    {
            $(".field-priorityitem-priority_level").addClass('has-error');
            $(".field-priorityitem-priority_level").find('.help-block').html('Select priority level');
            return false;
    }
    if($("#priorityitem-priority_start_date").val()=='')
    {
            $(".field-priorityitem-priority_start_date").addClass('has-error');
            $(".field-priorityitem-priority_start_date").find('.help-block').html('Select start date');
            return false;
    }
    if($("#priorityitem-priority_end_date").val()=='')
    {
            $(".field-priorityitem-priority_end_date").addClass('has-error');
            $(".field-priorityitem-priority_end_date").find('.help-block').html('Select end date');
            return false;
    }

    var va='';
    var path = $('#check_priority_date_url').val();
    var blocked_dates = $('#blocked_dates').val();
    var item = $('#priorityitem-item_id').val();
    var start = $('#priorityitem-priority_start_date').val();
    var start = start.split("-").reverse().join("-");
    var end = $('#priorityitem-priority_end_date').val();
    var end = end.split("-").reverse().join("-");

    $.ajax({
        type: 'POST',
        dataType:"json",
        url: path, //url to be called
        data: { item: item, start: start,end: end,blocked_dates : blocked_dates,priority_id : priority_id,_csrf : csrfToken}, //data to be send
        success: function( data ) {
            if(data==1)
            {
                $('.loadingmessage').hide();
                $('#blocked_error').show();
                $("#priorityitem-priority_end_date").removeClass('has-success');
                $("#priorityitem-priority_end_date").addClass('has-error');
                var va = false;
            } else if(data==0){
                $('#blocked_error').hide();
                $('.loadingmessage').hide();
                $('form#formId').submit();
            }

            $('.loadingmessage').hide();
        }
    });
});
