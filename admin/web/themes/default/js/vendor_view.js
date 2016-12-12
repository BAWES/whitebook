$('.btnNext').click(function(){
	$('.nav-tabs > .active').next('li').find('a').trigger('click');
});

$('.btnPrevious').click(function(){
	$('.nav-tabs > .active').prev('li').find('a').trigger('click');
});

$(function (){

	$('.nav-tabs li:first').addClass("active");
 	$(".tab-content div:first").addClass("active");
 	
 	$(".themelists:last-child").css({"clear" : "both","float" :"inherit"});
 	
	$('#option').hide();

  	$(document).delegate(".vendoritemquestion-question_answer_type", 'change', function (){
		
    	var type = $(this).val();

		if(type =='selection')
		{
			$(this).next('.price_val').remove();
			
      		var j = $(this).attr('id').replace(/vendoritemquestion-question_answer_type/, '');
			
      		$('#option').show();
			
      		$(this).after('<div class="selection"><input type="text" class="form-control" name="VendorItemQuestion['+j+'][text][0][]" placeholder="Question" id="question" style="width:50%;float:left;"><input type="text" class="form-control" name="VendorItemQuestion['+j+'][price][0][]" placeholder="Price (Optional)" id="price" style="width:40%;float:left;"><input type="button" class="add_question" id="add_question'+j+'" name="Addss" value="Add Selection"></div>');

		} else if(type =='image' ||  type =='text') {

			$(this).next('.selection').remove();
			
      		$(this).next('.price_val').remove();
			
      		var j = $(this).attr('id').replace(/vendoritemquestion-question_answer_type/, '');
			
      		$('#option').show();

			$(this).after('<div class="price_val"><input type="text" class="form-control" name="VendorItemQuestion['+j+'][price][]" placeholder="Price (Optional)" id="price" style="width:40%;float:left;"></div>');
		}
  	});

	var p = 1;

	$(document).delegate('.add_question', 'click', function(){
			
      var j = $(this).attr('id').replace(/add_question/, '');
			
      $(this).before('<div class="selection"><input type="text" class="form-control" name="VendorItemQuestion['+j+'][text]['+p+'][]" placeholder="Question" id="question" style="width:50%;float:left;"><input type="text" class="form-control" name="VendorItemQuestion['+j+'][price]['+p+'][]" placeholder="Price (Optional)" id="price" style="width:40%;float:left;"></div>');
      
      p++;

	});

	$(".edit").hide();

	var start_date = $('#vendor-package_start_date').val();
	var end_date = $('#vendor-package_end_date').val();
	
	if(start_date=='0000-00-00'){
		$('#vendor-package_start_date').val('');
	}

	if(end_date=='0000-00-00'){
		$('#vendor-package_end_date').val('');
	}
});


function check_validation()
{
  	var csrfToken = $('meta[name="csrf-token"]').attr("content");
  	var id = $('#vendor-package_id').val();
  	var vid = $('#vendor-vendor_id').val();
  	var start_dat = $('#vendor-package_start_date').val(); //
  	var start_date = start_dat.split("-").reverse().join("-");	// change date format
  	var end_dat = $('#vendor-package_end_date').val(); //
  	var end_date = end_dat.split("-").reverse().join("-");	// change date format
  	var package_pricing = $('#vendor-package_pricing').val();
  	
    if(!(id && start_date && end_date)) {      	
		$("#result").html('<div class="alert alert-failure"><button type="button" data-dismiss="alert" class="close"></button>Kindly Enter Valid value!</div>');
      	return false;
  	}

    var path = jQuery('#change_package_url').val(); 

    $('.loadingmessage').show();
    
    $.ajax({
      	type: 'POST',
      	url: path, //url to be called
      	data: { 
	        id: id,
	        vid: vid,
	        start_date: start_date,
	        end_date: end_date,
	        package_pricing: package_pricing,
	        _csrf : csrfToken
      	}, //data to be send
      	success: function(json){
	        
          	$('.loadingmessage').hide();
			    
          	if(json.errors.length > 0) {

				$msg = '';

				$.each(json.errors, function(index, value) {
				  	$msg += value + '<br />';
				}); 

				$("#result").html('<div class="alert alert-failure"><button type="button" class="close"></button>' + $msg + '</div>');

				exit;

          	} else {
				
				$('#vendor-package_id').val('');
				$('#vendor-package_start_date').val('');
				$('#vendor-package_end_date').val('');
				$("#result").html('<div class="alert alert-success"><button type="button" class="close"></button>Package added successfully!</div>');
				$("#vendor-package_start_date").attr("disabled","disabled");

				$("#vendor-package_end_date").attr("disabled","disabled");
				
				$('.alert .close').on("click", function(e){
				        $(this).parent().fadeTo(500, 0).slideUp(500);
				});

				$('#myTable tr').removeClass("update_row");
				$("#myTable tbody tr:first").after(json['html']);
          	}
      	}
    });
}

function packagedelete(id)
{
	var r = confirm("Are you sure want to delete?");

    if (r == true) {

		var vid = $('#vendor-vendor_id').val();
		var csrfToken = $('meta[name="csrf-token"]').attr("content");
	    var path = jQuery('#package_delete_url').val();

    	$('.loadingmessage').show();

	    $.ajax({
		    type: 'POST',
		    url: path, //url to be called
		    data: { packid: id,vid:vid,_csrf : csrfToken}, //data to be send
		    success: function(data) {
				$('.loadingmessage').hide();
				$('#myTable tr#tr-'+id).remove();
				$('#myTable tr').removeClass("update_row");
					
				$("#result").html('<div class="alert alert-success"><button type="button" class="close"></button>Package deleted successfully!</div>');
		      	
		      	$('.alert .close').on("click", function(e){
		            $(this).parent().fadeTo(500, 0).slideUp(500);
		        });

				$(".add").show();
				$(".edit").hide();

				$('.edit_package').attr('value','');
				$('.edit_start').val('');
				$('.edit_end').val('');

				$("#vendor-package_start_date").attr("disabled","disabled");
				$("#vendor-package_end_date").attr("disabled","disabled");

				$('#vendor-package_id').val('');
				$('#vendor-package_start_date').val('');
				$('#vendor-package_end_date').val('');
		    }
	    });
    }
}

function packageedit(id)
{
	$('#information_fail').html('');
	$("#information").html('');
	$('#update_information_fail').html('');
	$("#update_information").html('');

	$(".add").hide();
	$(".edit").show();
	$("#information").html('');
	$("#vendor-package_start_date").datepicker("refresh");

	var vid = $('#vendor-vendor_id').val();
	var csrfToken = $('meta[name="csrf-token"]').attr("content");
    var path = jQuery('#package_update_url').val();

    $('.loadingmessage').show();

    $.ajax({
	    type: 'POST',
	    url: path, //url to be called
	    data: { packid: id,vid:vid,_csrf : csrfToken}, //data to be send
	    success: function(data) {
			
			obj = JSON.parse(data);

			$('.loadingmessage').hide();
			
			var packageid=obj.packid;
			var start=obj.start;
			var start = start.split("-").reverse().join("-");
			var end=obj.end;
			var end = end.split("-").reverse().join("-");
			var forbidden=obj.date;
			var input1=obj.input1;
			var input2=obj.input2;
			
			$('#packedit').val(id);
			$('.mystart').html(data.input1);
			$('.myend').html(data.input2);
	        $('#vendor-package_id option[value="'+packageid+'"]').attr("selected", "selected");
			$('.edit_start').remove();
			$('.mystart1').html('<input type="text" id="vendor-package_start_date" class="edit_start" name="Vendor[package_start_date]" value="" maxlength="128" placeholder="Start">');
			$('.edit_start').val(start);
			$('.edit_end').remove();
			$('.myend1').html('<input type="text" id="vendor-package_end_date" class="edit_end" name="Vendor[package_end_date]" value="" maxlength="128" placeholder="End date">');
			$('.edit_end').val(end);
			
			$('.edit_start,.edit_end').datepicker({
				format: 'dd-mm-yyyy',
				autoclose: true,
				startDate:'d',
				/*beforeShowDay:function(Date){
					var curr_date = Date.toJSON().substring(0,10);
					if (forbidden.indexOf(curr_date)>-1) return false;
				}*/
	      	});
	    }
  	});
}

$(function (){
   
    $(document).delegate("#vendor-package_id", "change", function (){

		var csrfToken = $('meta[name="csrf-token"]').attr("content");
      	var id = $('#vendor-vendor_id').val();
      	var path = $('#load_package_date_url').val();
      
      	$('.loadingmessage').show();

      	$.ajax({
        	type: 'POST',
		    dataType:"json",
        	url: path, //url to be called
        	data: { id: id ,_csrf : csrfToken}, //data to be send
        	success: function( data ) {

        		$('.loadingmessage').hide();
    			$('.mystart').html(data.input1);
    			$('.myend').html(data.input2);
    			
    			var forbidden = data.date;

          		if($("#vendor-package_id").val()!=''){
	            	$('#vendor-package_start_date,#vendor-package_end_date').datepicker({
		            	format: 'dd-mm-yyyy',
		            	autoclose: true,
		            	startDate:'d',
		              	/* beforeShowDay:function(Date){
		                    var curr_date = Date.toJSON().substring(0,10);
		                    if (forbidden.indexOf(curr_date)>-1) return false;
		                }*/
		            });
          		}
        	}//success 
      	});
    });//on change
});//on ready 


function check_edit_validation()
{
	var csrfToken = $('meta[name="csrf-token"]').attr("content");
	var id = $('.edit_package').val();
	var vid = $('#vendor-vendor_id').val();
	var packedit = $('#packedit').val();
	var start_date = $('.edit_start').val();
	var end_date = $('.edit_end').val();

	if((id==null)||(id=='')||(start_date==null)||(start_date=='')||(end_date==null)||(end_date=='')){
			$("#result").html('<div class="alert alert-failure"><button type="button" class="close"></button>Kindly enter valid value!</div>');
          $('.alert .close').on("click", function(e){
                $(this).parent().fadeTo(500, 0).slideUp(500);
             });
    	return false;
	
	} else {

		var start_date = start_date.split("-").reverse().join("-");	// change date format
		var end_date = end_date.split("-").reverse().join("-");	// change date format

        var path = jQuery('#change_edit_package_url').val();

        $('.loadingmessage').show();
        
        $.ajax({
	        type: 'POST',
	        url: path, //url to be called
	        data: { id: id ,vid: vid ,packedit:packedit,start_date: start_date ,end_date: end_date ,_csrf : csrfToken}, //data to be send
	        success: function( data ){
	        	
	        	$('.loadingmessage').hide();
				
				if(data==1){
					
					$("#result").html('<div class="alert alert-failure"><button type="button" class="close"></button>Blocked dates available in between start date and end date!</div>');
	         	 	
	         	 	$('.alert .close').on("click", function(e){
	                	$(this).parent().fadeTo(500, 0).slideUp(500);
	             	});
					
					return false;
				}

				if(data==2){
					$("#result").html('<div class="alert alert-failure"><button type="button" class="close"></button>Start date and end date are different ranges!</div>');
	          		
	          		$('.alert .close').on("click", function(e){
	                	$(this).parent().fadeTo(500, 0).slideUp(500);
	            	});

					return false;
				}
				else{
					
					$("#result").html('<div class="alert alert-success"><button type="button" class="close"></button>Package updates successfully!</div>');
		            
		            $('.alert .close').on("click", function(e){
		            	$(this).parent().fadeTo(500, 0).slideUp(500);
		            });

					$("#vendor-package_start_date").attr("disabled", "disabled");
					$("#vendor-package_end_date").attr("disabled", "disabled");
					$('#vendor-package_id').val('');
					$('#vendor-package_start_date').val('');
					$('#vendor-package_end_date').val('');
					$('#myTable tr#tr-'+packedit).remove();

					$('#myTable tr').removeClass("update_row");

					$("#myTable tbody tr:first").after(data);
					$(".add").show();
					$(".edit").hide();
					$('.edit_package').attr('value','');
					$('.edit_start').val('');
					$('.edit_end').val('');
				}
			}
		});
	}
}

function cancel()
{
 	$(".add").show();
	$('#vendor-package_id').val('');
	$('#vendor-package_start_date').val('');
	$('#vendor-package_end_date').val('');
	$(".edit").hide();

	$('.edit_package').attr('value','');
	$('.edit_start').val('');
	$('.edit_end').val('');
}