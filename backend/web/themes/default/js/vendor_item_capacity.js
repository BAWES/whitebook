var forbidden = [''];

$('#vendoritemcapacityexception-exception_date').datepicker({
	startDate: 'today',
    autoclose: true,
	format: 'dd-mm-yyyy',
	beforeShowDay:function(Date){
        //
        var curr_day = Date.getDate();
        var curr_month = Date.getMonth()+1;
        var curr_year = Date.getFullYear();
        var curr_date = curr_month+'/'+curr_day+'/'+curr_year;

        if (forbidden.indexOf(curr_date)>-1) return false;
    }
});

//$(function(){
//    $('#vendoritemcapacityexception-item_id').multiselect({
//        'enableFiltering': true,
//        'buttonWidth': '660px',
//        'filterPlaceholder': 'Select Item...',
//        'includeSelectAllOption': true
//    });
//});
//
//$('#submit1').click(function()
//{
//    //var csrfToken = $('meta[name=\"csrf-token\"]').attr('content');
//    var item_id = $('#vendoritemcapacityexception-item_id').val();
//    var exception_date = $('#vendoritemcapacityexception-exception_date').val();
//    var exception_date = exception_date.split("-").reverse().join("-");
//    var path = check_item_url;
//    var update = update_value;
//
//    $.ajax({
//        type: 'POST',
//        async:false,
//        url: path,
//        data: { item_id: item_id ,exception_date: exception_date ,update: update}, //data to be send
//        success: function( data ) {
//            if(data==2){
//                $('.field-vendoritemcapacityexception-exception_date').addClass('has-error');
//                $('.field-vendoritemcapacityexception-item_id').find('.help-block').html('Item already exists in same date!');
//                $('.field-vendoritemcapacityexception-exception_capacity').find('.help-block').html('Item already exists in same date!');
//                $('#date_error').html('Item already exists in same date!').animate({ color: "#a94442" }).show();
//                $('#vendoritemcapacityexception-default').val('');
//                return false;
//            }else{
//              $('#vendoritemcapacityexception-default').val('1');
//            }
//            return false;
//        }
//    });
// });