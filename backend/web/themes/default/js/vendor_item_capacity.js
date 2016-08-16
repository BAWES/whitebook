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