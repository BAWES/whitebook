
$("#auth_item").select2({
    placeholder: "Select User Function.."
});

$(function (){
	$(".admin").hide();
	$(".ctrlnew").hide();
	$(".functionnew").hide();
 });

$(function (){
    $("#accesscontroller-admin_id").change(function (){
		$('#myTable').addClass('has-error');
   });
 });

$(function (){
    $("#accesscontroller-controller").change(function (){
		var csrfToken = $('meta[name="csrf-token"]').attr("content");
        var admin_id = $('#accesscontroller-admin_id').val();
        var controller_id = $('#accesscontroller-controller').val();
        var path = auth_item_url;
        
        $.ajax({
            type: 'POST',
            url: path, //url to be called
            data: { admin_id: admin_id ,controller_id : controller_id ,_csrf : csrfToken}, //data to be send
            success: function( data ) {
                $('#auth_item').html(data);
            }
        });
     });
});

function check_validation()
{
	var ids = $("input[id=ctrl]:checked").get();
	var create = $("input[id=create]:checked").get();
	var update = $("input[id=update]:checked").get();
	var delete1 = $("input[id=delete]:checked").get();
	var manage = $("input[id=manage]:checked").get();
	var view = $("input[id=view]:checked").get();

    if(ids.length == 0)
    {
		$(".ctrlnew").show();
		$('#myTable').addClass('has-error');
		return false;
    }
    
    if(create.length == 0 && update.length == 0 && delete1.length == 0 && view.length == 0 && manage.length == 0)
    {
		$(".ctrlnew").hide();
		$(".functionnew").show();
		$('#myTable').addClass('has-error');
		return false;
    } else {
    	$('#myTable').removeClass();
        return true;
    }

    return false;
 }
 //33
 