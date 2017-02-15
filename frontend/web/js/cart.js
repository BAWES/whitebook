
function deliveryTimeSlotCart(date){
    var myDate = new Date()
    time = myDate.getHours()+':'+myDate.getMinutes()+':'+myDate.getSeconds(),
        currentDate = myDate.getDate()+ '-' +("0" + (myDate.getMonth() + 1)).slice(-2)+ '-' +myDate.getFullYear();
    $.ajax({
        type: 'POST',
        url: getdeliverytimeslot_url,
        data: { vendor_id : $('#vendor_id').val(), sel_date: date,time:time,currentDate:currentDate},
        success: function (data)
        {
            if ($.trim(data) == 0) {
                $('.timeslot_id_div').show();
                $('.timeslot_id_div .text').html('Delivery not available for the selected date');
                $('.timeslot_id_select').hide();
                $('#timeslot_id').html('');
            } else {
                $('.timeslot_id_div').hide();
                $('.timeslot_id_select').show();
                $('#timeslot_id').html(data);
                $('#timeslot_id').selectpicker('refresh');
                $('.error.timeslot_id').html('');
            }
        }
    });
}

function productAvailabilityCart(date) {
    $.ajax({
        type: 'POST',
        url: product_availability,
        data: $('#form-update-cart').serialize(),
        success: function (json)
        {
            if (json['error']) {
                $('.timeslot_id_div').show();
                $('.timeslot_id_div .text').html(json['error']);
                $('.timeslot_id_select').hide();
                $('#timeslot_id').html('');
                return false;
            }else{
                deliveryTimeSlotCart(date);
            }
        }
    });
}

function update_cart_option_menu_title_hint() {

    $.each($('.menu-hint'), function() {

        $max = $(this).attr('data-max-quantity');
        $min = $(this).attr('data-min-quantity');

        $qty = $('#quantity').val();

        //build html 

        $html = $('#txt-select').val();

        if($min > 0) {
            $html += $('#txt-min').val().replace('{qty}', $min * $qty);
        }
        
        if($min > 0 && $max > 0) { 
            $html += ' , ';
        }

        if($max > 0) {
            $html += $('#txt-max').val().replace('{qty}', $max * $qty);
        }

        $(this).html($html);
    });    
}

function update_cart_option_menu_item_qty() {

    $.each($('#form-update-cart ul.menu-items'), function() {

        $max = $(this).attr('data-max-quantity') * $('input[name="quantity"]').val();

        $total = 0;

        $.each($(this).find('li'), function() {

            $qty_input = $(this).find('.menu-item-qty');

            $qty = parseInt($qty_input.val());

            //if exceed limit 
            if($total + $qty > $max) {

                //if checkbox, uncheck
                if($qty_input.attr('type') == "checkbox" && $qty_input.prop("checked") == true) 
                {
                    $qty_input.prop('checked', false);
                } 
                else if ($max - $total > 0) 
                {
                    $qty_input.val($max - $total);
                    console.log('Setting : max - total = ' + ($max-$total));
                } 
                else 
                {
                    $qty_input.val(0);
                    console.log('Setting : = ' + 0);
                }
            }

            $total += $qty;
        });
    });
}
        
$(document).delegate('input[name="quantity"]', 'change', function() {
    update_cart_option_menu_title_hint();
    update_cart_option_menu_item_qty();
});

$(document).delegate('.menu-item-qty-box .fa-minus', 'click', function() {
   
    $qty_input = $(this).parent().find('input');

    $qty = parseInt($qty_input.val());

    if($qty - 1 >= 0)
    {
        $qty_input.val($qty - 1);    
    }  

    //update_price();
});

$(document).delegate('.menu-item-qty-box .fa-plus', 'click', function() {

    //max quantity for menu 

    $qty = $('input[name="quantity"]').val();

    $max = $(this).parents('.menu-items').attr('data-max-quantity') * $qty;

    $qty_input = $(this).parent().find('input');

    $qty = parseInt($qty_input.val());

    //get total qty in menu

    $menu_total_qty = 0;

    $.each($(this).parents('.menu-items').find('input'), function() {
        $menu_total_qty += parseInt($(this).val());
    });

    // if max defined && total not exceeding max allowed

    if($menu_total_qty + 1 <= $max || $max == 0) {
        $qty_input.val($qty + 1);    
    }    
    
    //update_price();
});

$(document).delegate('.menu-items .checkbox input', 'click', function(e) {

    $qty = $('input[name="quantity"]').val();

    //max quantity for menu 

    $max = $(this).parents('.menu-items').attr('data-max-quantity') * $qty;

    //get total qty in menu

    $menu_total_qty = 0;

    $.each($(this).parents('.menu-items').find('input:checked'), function() {
        $menu_total_qty += parseInt($(this).val());
    });

    // if max defined && total not exceeding max allowed

    if($max > 0 && $menu_total_qty > $max) {
        return false;
    }    
});

$(function() {
    $('#update-cart-modal').on('shown.bs.modal', function() {
        $('#delivery_date3').datepicker({
            format: 'dd-mm-yyyy',
            startDate:'today',
            autoclose:true,
            container: '#update-cart-modal modal-body'
        }).on("changeDate", function(e) {
            $('.error.cart_delivery_date, .cart_quantity').html('');
            //$('#quantity').val(' ');
            productAvailabilityCart($(this).val());
        });
    });
});

$('body').on('click','.btn-cart-change',function(){
    $.ajax({
        type: 'POST',
        url: update_cart_url,
        data: $('#form-update-cart').serialize(),
        success: function (data)
        {
            $('#form-update-cart .error').html('');

            if(data['success']) {
                location = location;
            } else {

                $.each(data['errors'], function(index, errors) {
                    $.each(errors, function() {
                        $('#form-update-cart .error.' + index).append('<p>' + this + '</p>');
                    });
                });

            }
        }
    });
    return false;
});

$('.btn-danger').click(function() {
    $(this).parent().find('input').val(0);
    $('#cart-form').submit();
});

$('.fa-edit').click(function(){
    $.ajax({
        url: update_cart_popup_url,
        type:'post',
        data:{id:$(this).data('cart-id')},
        success:function(data)
        {
            $('#update-cart-modal').modal('show');
            $('#update-cart-modal .modal-body').html(data);
            $('#timeslot_id').selectpicker('refresh');
        }
    });
});