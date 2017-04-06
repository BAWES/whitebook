
$(function() {

	$('#vendorpayment-vendor_id').change(function() {

		$.post($('#unapid_url').val(), { vendor_id : $(this).val() }, function(json) {

			$html = '';

	    	$.each(json.unpaid_bookings, function(index, value) {
	    		$html += '<option value="' + value.booking_id + '" data-amount="' + value.amount + '">';
	    		$html += '	Booking - #' + value.booking_id;
	    		$html += '</option>';
	    	});

	    	$('.booking-wrapper select').html($html);

	    	$('#vendorpayment-amount').val(0).trigger('change');
		});

	});

	$('#vendorpayment-vendor_id').trigger('change');

	//update amount on booking change 

	$('.booking-wrapper select').change(function() {

		$total = 0;

		$('.booking-wrapper option:selected').each(function() {			
			$total += parseFloat($(this).attr('data-amount'));
		});

		$('#vendorpayment-amount').val($total.toFixed(3)).trigger('change');
	});

	$('#vendorpayment-amount').change(function() {

		console.log(0);

		if($(this).val() > 0) 
		{
			console.log(1);

			$('.btn-submit').removeAttr('disabled');
		}
		else
		{
			console.log(2);

			$('.btn-submit').attr('disabled', 'disabled');
		}
	});
});		
