$('#deliverytimeslot-timeslot_start_time,#deliverytimeslot-timeslot_end_time').timepicker();

    $('#submit1').click(function()
    {
        var day = $('#deliverytimeslot-timeslot_day').val();
        var start_hr = $('#deliverytimeslot-start_hr').val();
        var start_min = $('#deliverytimeslot-start_min').val();
        var start_med = $('#deliverytimeslot-start_med').val();
        var colon=':';
        var end_hr = $('#deliverytimeslot-end_hr').val();
        var end_min = $('#deliverytimeslot-end_min').val();
        var end_med = $('#deliverytimeslot-end_med').val();

        var slot = $('#deliverytimeslot-timeslot_maximum_orders').val();
        var path = check_time_url;
        var update = update_value;

        if(start_hr!='' && start_min!=''  && start_med!='' && end_hr!='' && end_min!=''&& end_med!='' && day!='' && slot!=''){
        var sta=start_hr.concat(colon);
        var res1 = sta.concat(start_min);
        var start_time=res1.concat(start_med);

        var en=end_hr.concat(colon);
        var res2 = en.concat(end_min);
        var end_time=res2.concat(end_med);

        $.ajax({
            type: 'POST',
            async:false,
            url: path, //url to be called
            data: { day: day ,start: start_time ,end: end_time,update: update}, //data to be send
            success: function(json) {

                if(json['status']) {
                    $('#deliverytimeslot-default').val('1');
                    $('#deliverytimeslot-timeslot_start_time').val(start_time);
                    $('#deliverytimeslot-timeslot_end_time').val(end_time);
                    $('.deliverytimeslot-form form').submit();
                }else{
                    $('#deliverytimeslot-default').val('');
                    $("#result").html('<div class="alert alert-failure"><button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>' + json['message'] + '</div>');
                }

                /*else if(data==2){
                    $("#result").html('<div class="alert alert-failure"><button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>Day already exists in same time period!</div>');
                    $('#deliverytimeslot-default').val('');
                    return false;
                }*/
            }
        })
        }
     });