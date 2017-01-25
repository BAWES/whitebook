
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

    $max = $(this).parents('.menu-items').attr('data-max-quantity');

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