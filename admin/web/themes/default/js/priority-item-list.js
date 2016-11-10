$('#one').click(function() {
    var names = [];
    $('#selection input:checked').each(function() {
        names.push(this.name);
        alert (names);
    });
});

$('#clear').click(function()
{
	$('#filter_start').val("");
	$('#filter_end').val("");
	$('#status').val("All");
    $('#level').val("All");

    var csrfToken = $('meta[name="csrf-token"]').attr("content");

    // start and end date values
    var start = $('#filter_start').val();
    var start = start.split("-").reverse().join("-");
    var end = $('#filter_end').val();
    var end = end.split("-").reverse().join("-");
    var status = $('#status').val();
    var level = $('#level').val();
    $('.loadingmessage').show();
    var path = $('#priority_item_url').val(); 

    $.ajax({
        type: 'POST',
        url: path, //url to be called
        data: { start:start, end:end, status: status, level: level, _csrf : csrfToken}, //data to be send
        success: function( data ) {
            $('.loadingmessage').hide();
            $('#w0').remove();
            $('#filteritems').html(data);
        }
    });
});

var csrfToken = $('meta[name="csrf-token"]').attr("content");
var txt;

/* Change status for respective vendor items */
function Status(status){

	var keys = $('#priority').yiiGridView('getSelectedRows');
	var pathUrl = $('#priority_item_status').val();
	
    if(keys.length == 0) { 
        alert('Select Your priority item'); 
        return false;
    }
	
    var r = confirm("Are you sure want to " + status + "?");

	if (r == true) {
		$.ajax({
		   url: pathUrl,
		   type : 'POST',
		   data: {keylist: keys, status:status},
		   success : function(data)
		   {
				window.location.reload(true);
		   }

		});
		return false;
    }
	return false;
}

$('input#filter_start,input#filter_end').datepicker({
    format: 'dd-mm-yyyy',
    autoclose: true,
});

function prioritydatefilter()
{
    var csrfToken = $('meta[name="csrf-token"]').attr("content");
    // start and end date values
    var start = $('#filter_start').val();
    var end = $('#filter_end').val();
    var status = $('#status').val();
    var level = $('#level').val();
    $('.loadingmessage').show();
    var path = $('#priority_item_url').val(); 

    $.ajax({
        type: 'POST',
        url: path, //url to be called
        data: { start:start, end:end, status: status, level: level, _csrf : csrfToken}, //data to be send
        success: function( data ) {
            $('.loadingmessage').hide();
             $('#w0').remove();
            $('#filteritems').html(data);
        }
    });
}
/* END priority start and end date picker & filter */

function change(status, aid)
{
	var csrfToken = $('meta[name="csrf-token"]').attr("content");
    var path = $('#block_priority_url').val();

    $.ajax({
        type: 'POST',
        url: path, //url to be called
        data: { status: status, aid: aid, _csrf : csrfToken}, //data to be send
        success: function(data) {
    		var status1 = (status == 'Active') ? 'Deactive' : 'Active';
    		$('#image-'+aid).attr('src',data);
    		$('#image-'+aid).parent('a').attr('onclick',
    		"change('"+status1+"', '"+aid+"')");
        }
    });
}
