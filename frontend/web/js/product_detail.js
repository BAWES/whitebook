
    jQuery(document).ready(function () {

        $notice_period_type = $('#notice_period_type').val()

        $notice_period = parseInt($('#item_how_long_to_make').val());

        if($notice_period_type == 'Hour')
        {
            $start_date = new Date();
            $start_date.setHours($start_date.getHours() + $notice_period);    
        }
        else
        {
            $start_date = new Date();
            $start_date.setDate($start_date.getDate() + $notice_period);   
        }
        
        $('#item_delivery_date').datepicker({
            format: 'dd-mm-yyyy',
            startDate: $start_date,
            autoclose:true,
        });

        $('[data-toggle="tooltip"]').tooltip(); 

        /* client say slider start*/
        jQuery('.flexslider2').flexslider({
            animation: "slide",
            controlNav: false,
            animationLoop: true,
            slideshow: true,
            itemWidth: 223,
            itemMargin: 0,
            pauseOnHover: true,
            slideshowSpeed: 3000,
            move: 1,
            minItems: 1,
            maxItems: 5,
            start: function (slider) {
                jQuery('body').removeClass('loading');
            }
        });
        /* client say slider end*/
 
        /* FeatureD Products end*/
        /*carousel slider*/

        window.curSlide;

        jQuery('#carousel').flexslider({
            animation: "slide",
            controlNav: true,
            animationLoop: false,
            directionNav: true,
            slideshow: false,
            touch: true,
            itemWidth: 150,
            itemMargin: 10,
            asNavFor: '#slider',
            /*
             animation: "slide",
             controlNav: false,
             animationLoop: false,
             slideshow: true,
             itemWidth: 150,
             itemMargin: 10,
             asNavFor: "#slider"*/

        });

        jQuery('#slider').flexslider({
            animation: "fade",
            slideshow: false,
            directionNav: true,
            controlNav: true,
            touch: true,
            useCSS: false,
            slideshowSpeed: 1000,
            animationSpeed: 100,
            sync: "#carousel",
            /*animation: "slide",
             controlNav: false,
             animationLoop: false,
             slideshow: false,
             sync: "#carousel",*/
            start: function (slider) {
                jQuery('#carousel .slides li img').click(function (event) {
                    event.preventDefault();
                    slider.flexAnimate(slider.currentSlide);
                });
            },
        });
        //jQuery(":file").filestyle({buttonText: "Find file"});
    });
    /*carousel slider end*/

    jQuery('#description_click').click(function () {
        jQuery("i", this).toggleClass("flaticon-up151 flaticon-downwards");

        jQuery('#contact_click i').addClass("flaticon-downwards");
        jQuery('#additional_click i').addClass("flaticon-downwards");
        jQuery('#custom_click i').addClass("flaticon-downwards");
        jQuery('#price_click i').addClass("flaticon-downwards");

        jQuery('#contact_click i').removeClass("flaticon-up151");
        jQuery('#additional_click i').removeClass("flaticon-up151");
        jQuery('#custom_click i').removeClass("flaticon-up151");
        jQuery('#price_click i').removeClass("flaticon-up151");
    });

    jQuery('#additional_click').click(function () {
        jQuery("i", this).toggleClass("flaticon-up151");

        jQuery('#contact_click i').addClass("flaticon-downwards");
        jQuery('#price_click i').addClass("flaticon-downwards");
        jQuery('#description_click i').addClass("flaticon-downwards");
        jQuery('#custom_click i').addClass("flaticon-downwards");

        jQuery('#contact_click i').removeClass("flaticon-up151");
        jQuery('#description_click i').removeClass("flaticon-up151");
        jQuery('#custom_click i').removeClass("flaticon-up151");
        jQuery('#price_click i').removeClass("flaticon-up151");
    });

    jQuery('#contact_click').click(function () {
        jQuery("i", this).toggleClass("flaticon-up151");

        jQuery('#additional_click i').addClass("flaticon-downwards");
        jQuery('#price_click i').addClass("flaticon-downwards");
        jQuery('#description_click i').addClass("flaticon-downwards");
        jQuery('#custom_click i').addClass("flaticon-downwards");

        jQuery('#additional_click i').removeClass("flaticon-up151");
        jQuery('#description_click i').removeClass("flaticon-up151");
        jQuery('#custom_click i').removeClass("flaticon-up151");
        jQuery('#price_click i').removeClass("flaticon-up151");
    });

    jQuery('#price_click').click(function () {
        jQuery("i", this).toggleClass("flaticon-up151");

        jQuery('#additional_click i').addClass("flaticon-downwards");
        jQuery('#contact_click i').addClass("flaticon-downwards");
        jQuery('#description_click i').addClass("flaticon-downwards");
        jQuery('#custom_click i').addClass("flaticon-downwards");

        jQuery('#additional_click i').removeClass("flaticon-up151");
        jQuery('#description_click i').removeClass("flaticon-up151");
        jQuery('#custom_click i').removeClass("flaticon-up151");
        jQuery('#contact_click i').removeClass("flaticon-up151");
    });

    jQuery('#custom_click').click(function () {
        jQuery("i", this).toggleClass("flaticon-up151");

        jQuery('#additional_click i').addClass("flaticon-downwards");
        jQuery('#price_click i').addClass("flaticon-downwards");
        jQuery('#description_click i').addClass("flaticon-downwards");
        jQuery('#contact_click i').addClass("flaticon-downwards");

        jQuery('#additional_click i').removeClass("flaticon-up151");
        jQuery('#description_click i').removeClass("flaticon-up151");
        jQuery('#contact_click i').removeClass("flaticon-up151");
        jQuery('#price_click i').removeClass("flaticon-up151");
    });

    function setupLabel() {
        if (jQuery('.label_check input').length) {
            jQuery('.label_check').each(function () {
                jQuery(this).removeClass('c_on');
            });
            jQuery('.label_check input:checked').each(function () {
                jQuery(this).parent('label').addClass('c_on');
            });
        }
        ;
        if (jQuery('.label_radio input').length) {
            jQuery('.label_radio').each(function () {
                jQuery(this).removeClass('r_on');
            });
            jQuery('.label_radio input:checked').each(function () {
                jQuery(this).parent('label').addClass('r_on');
            });
        }
        ;
    }
    ;
    jQuery(document).ready(function () {
        jQuery('.label_check, .label_radio').click(function () {
            setupLabel();
        });
        setupLabel();

        jQuery(".custom-select").change(function () {
            var selectedOption = jQuery(this).find(":selected").text();
            jQuery(this).next(".holder").text(selectedOption);
        }).trigger('change');
    });

    jQuery('#sub_category_cakes').click(function () {
        jQuery("span", this).toggleClass("minus_acc plus_acc");

        jQuery('#sub_category_bakery span').removeClass("plus_acc");
        jQuery('#sub_category_bakery span').removeClass("minus_acc");

        jQuery('#sub_category_bakery span').addClass("plus_acc");
    });

    jQuery('#sub_category_bakery').click(function () {
        jQuery("span", this).toggleClass("minus_acc plus_acc");

        jQuery('#sub_category_cakes span').removeClass("minus_acc");
        jQuery('#sub_category_cakes span').addClass("plus_acc");
    });

    jQuery('#open_search').click(function () {
        jQuery("#open_search").toggleClass("active");
    });

    jQuery(document).ready(function () {
        /*product mobile responsive carousel slider start*/
        var owl = jQuery("#mobile-slider");

        owl.owlCarousel({
            itemsCustom: [
                [0, 1],
                [450, 1],
                [600, 1],
                [800, 1],
                [1000, 1],
                [1200, 1],
                [1400, 1],
                [1600, 1]
            ],
            navigation: true,
            autoWidth: true,
            loop: true,
            autoPlay: false,
            pagination: true,
            dots: true
        });
        /*product mobile responsive carousel slider end*/


        var owl = jQuery("#owl-demo2");

        owl.owlCarousel({
            itemsCustom: [
                [0, 1],
                [450, 2],
                [600, 2],
                [700, 3],
                [1000, 4],
                [1200, 5],
                [1400, 5],
                [1600, 5]
            ],
            navigation: true,
            autoWidth: true,
            loop: true
        });


        jQuery('.listing_right .events_listing ul li:nth-child(4n)').addClass("margin-rightnone");
        jQuery('.thing_items li:nth-child(8n)').addClass("margin-rightnone");
        jQuery(".dropdown").hover(
                function () {
                    jQuery('.dropdown-menu', this).stop(true, true).slideDown("fast");
                    jQuery(this).toggleClass('open');
                },
                function () {
                    jQuery('.dropdown-menu', this).stop(true, true).slideUp("fast");
                    jQuery(this).toggleClass('open');
                }
        );

        var owl = jQuery("#owl-demo");
        
        owl.owlCarousel({
            itemsCustom: [
                [0, 1],
                [450, 2],
                [600, 3],
                [700, 4],
                [1000, 4],
                [1200, 5],
                [1400, 5],
                [1600, 5]
            ],
            navigation: true,
            autoWidth: true,
            loop: true
        });
    });


/* BEGIN Buy Item */

jQuery(document).delegate('#form_product_option', 'submit', function(e) {

    e.preventDefault();
    // code commented to allow user to add to cart without login
    // if (isGuest) {
    //     show_login_modal(-2);
    //     $('#myModal').modal('show');
    // }

    jQuery.post(
        addtobasket_url,
        jQuery('#form_product_option').serialize(),
        function (data)
        {
            jQuery('#form_product_option .error').html('');

            if(data['success']) {
                location = location;
            } else {

                $.each(data.errors, function(index, errors) {
                    $.each(errors, function(key, value) {
                        jQuery('#form_product_option .error.' + index).append('<p>' + this + '</p>');
                    });
                });

                if($('#collapse-options').length > 0 && !$('#collapse-options').hasClass('in')) {
                    $('a[href="#collapse-options"]').trigger('click');
                }

                if($('#form_product_option .error p').length > 0)
                {
                    $('html, body').animate({ scrollTop: $('#form_product_option .error p').offset().top - 300 }, 'slow');
                }
                else
                {
                    $('html, body').animate({ scrollTop: $('#form_product_option').offset().top - 300 }, 'slow');        
                }
            }
        }
    );
});

// Shop product page quantity increment and decrement stepper
jQuery(document).on('click','.btn-stepper',function() {

    $qty = parseInt($('input[name="quantity"]').val());
    $capacity = parseInt($('#capacity').val());
    $item_type_name = $('#item_type_name').val();

    $minimum_increment = $('#minimum_increment').val();

    if($minimum_increment) {
        $minimum_increment = parseInt($minimum_increment);
    }else{
        $minimum_increment = 1;
    }

    if (jQuery(this).data('case') == 0) {
        if ($qty >= parseInt(jQuery('#quantity').data('min')) + $minimum_increment) {
            jQuery('#quantity').val($qty - $minimum_increment);
            update_price();
            update_option_menu_title_hint();
            update_option_menu_item_qty();//remove option qty for max option rule             
        }
    } else if (jQuery(this).data('case') == 1 && ($item_type_name == 'Product' || ($qty + $minimum_increment <= $capacity))) {
        jQuery('#quantity').val($qty + $minimum_increment);
        update_price();
        update_option_menu_title_hint();
    }

    return false;
});

function deliveryTimeSlot(date){
    var myDate = new Date()
    time = myDate.getHours()+':'+myDate.getMinutes()+':'+myDate.getSeconds(),
    currentDate = myDate.getDate()+ '-' +("0" + (myDate.getMonth() + 1)).slice(-2)+ '-' +myDate.getFullYear();
    jQuery.ajax({
        type: 'POST',
        url: getdeliverytimeslot_url,
        data: { 'vendor_id': vendor_id, 'sel_date': date,'time':time,currentDate:currentDate},
        success: function (data)
        {
            if (jQuery.trim(data) == 0) {
                $('.timeslot_id_div').show();
                $('.timeslot_id_div .text').html($('#txt-timeslot-not-available').val());
                $('.timeslot_id_select').hide();
                jQuery('#timeslot_id').html('');
            } else {
                $('.timeslot_id_div').hide();
                $('.timeslot_id_select').show();
                jQuery('#timeslot_id').html(data);
                jQuery('#timeslot_id').selectpicker('refresh');
                jQuery('.error.timeslot_id').html('');
            }
        }
    });
}

//validation-product-available

function productAvailability(date){
    $('.timeslot_id_div .text').html('Please Wait...');
    jQuery.ajax({
        type: 'POST',
        url: product_availability,
        data: $('#form_product_option').serialize(),
        success: function (json)
        {
            if (json['error']) {
                $('.timeslot_id_div').show();
                $('.timeslot_id_div .text').html(json['error']);
                $('.timeslot_id_select').hide();
                $('#timeslot_id').html('');
                $('.button-signin button').html('Out of stock');
                $('.button-signin button').attr('disabled',true);
                return false;

            } else {
                $('.button-signin button').html('ADD TO CART');
                if (json['price'] == 0) {
                    $('.button-signin button').attr('disabled',true);
                    $('.small.price_warning').show();
                } else {
                    $('.button-signin button').attr('disabled',false);
                    $('.small.price_warning').hide();
                }

                deliveryTimeSlot(date);

                //set capacity for given date 
                
                $('#capacity').val(json['capacity']);

                $qty = $('input[name="quantity"]').val();

                $item_type_name = $('#item_type_name').val();

                //if qty selected exceed capacity 

                if($item_type_name != 'Product' && $qty > json['capacity']) {
                    $('input[name="quantity"]').val(json['capacity']);   
                    update_price();
                    update_option_menu_title_hint();    
                    update_option_menu_item_qty();             
                }

            } 
        }
    });
}

// pre set value for Delivery date on product detail page
if (deliver_date) {
    productAvailability(jQuery('#item_delivery_date').val());
}

// product detail page Delivery Date change event
$("#item_delivery_date").on("changeDate", function(e) {
    $('.error.cart_delivery_date').html('');
    productAvailability(jQuery(this).val());
});

$(document).delegate('.menu-item-qty-box .fa-minus', 'click', function() {
   
    /*if (isGuest) {
        show_login_modal('-2');
        $('#myModal').modal('show');
        return false;
    }*/

    $qty_input = $(this).parent().find('input');

    $qty = parseInt($qty_input.val());

    if($qty - 1 >= 0)
    {
        $qty_input.val($qty - 1);    
    }  

    update_price();
});

$(document).delegate('.menu-item-qty-box .fa-plus', 'click', function() {

    /*if (isGuest) {
        show_login_modal('-2');
        $('#myModal').modal('show');
        return false;
    }*/

    $qty = $('input[name="quantity"]').val();

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
    
    update_price();
});

$(document).delegate('.menu-items .checkbox input', 'click', function(e) {

    /*if (isGuest) {
        show_login_modal('-2');
        $('#myModal').modal('show');
        return false;
    }*/

    $qty = $('input[name="quantity"]').val();

    //max quantity for menu 

    $max = $(this).parents('.menu-items').attr('data-max-quantity');

    //get total qty in menu

    $menu_total_qty = 0;

    $.each($(this).parents('.menu-items').find('input:checked'), function() {
        $menu_total_qty += parseInt($(this).val());
    });

    // if max defined && total not exceeding max allowed

    if($max > 0 && $menu_total_qty > $max) {
        return false;
    }    
    
    update_price();
});


//final-price
function update_price() {
    $.post($('#final_price_url').val(), $('#form_product_option').serialize(), function(json) {
        $('.item-final-price').html(json.price);
        if (json.price == 'KD 0') { // disable add to cart button in case 0 price
            $('.button-signin button').attr('disabled',true);
            $('.price_warning').show();
            $('.item-final-price').empty();
        } else {
            $('.button-signin button').attr('disabled',false);
            $('.price_warning').hide();
        }
    });
}

$(document).delegate('.btn-booking-service', 'click', function() {

    $('#modal_booking_service .error').html('');
    
    $have_error = 0;

    $name = $('#modal_booking_service input[name="name"]').val();
    $phone = $('#modal_booking_service input[name="phone"]').val();
    $email = $('#modal_booking_service input[name="email"]').val();

    if($name.length == 0) {
        $('#modal_booking_service .error.name').html('This field is required');
        $have_error = 1;
    }

    if($phone.length == 0) {
        $('#modal_booking_service .error.phone').html('This field is required');
        $have_error = 1;
    }

    if(!$phone.match(/^\d+$/)) {
        $('#modal_booking_service .error.phone').html('Enter a valid phone number');
        $have_error = 1;
    }

    if($email.length == 0) {
        $('#modal_booking_service .error.email').html('This field is required');
        $have_error = 1;
    }

    $emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
    
    if( !$emailReg.test($email) ) {
        $('#modal_booking_service .error.email').html('Enter a valid email');
        $have_error = 1;
    }

    if(!$have_error)  {
        $('#modal_booking_service form').submit();
    }
});

$(document).delegate("#modal_booking_service input[name=\"phone\"]", 'keypress', function (e) {
    //if the letter is not digit then display error and don't type anything
    if (  e.which  != 43   && e.which  != 45 && e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57 )) {
        //display error message
        $(this).parent().find('.error').html('Contact number digits only+.');
        return false;
    }
});

$(document).delegate(".btn-booking-modal", 'click', function(){
    $('#modal_booking_service').modal('show');
});

$(document).delegate("#timeslot_id", 'changed.bs.select', function() {
    
    $timeslot_id = $(this).find('option:selected').val();

    $.post($('#save-delivery-timeslot-url').val(), { 'deliver-timeslot' : $timeslot_id });
});

$(document).delegate('input[name="quantity"]', 'change', function() {
    update_price();
    update_option_menu_title_hint();
    update_option_menu_item_qty();
});

function update_option_menu_title_hint() {

    $.each($('.menu-hint'), function() {

        $max = $(this).attr('data-max-quantity');
        $min = $(this).attr('data-min-quantity');
        $qty = $('#quantity').val();

        //build html 

        $html = $('#txt-select').val();

        if($min > 0) {
            $html += $('#txt-min').val().replace('{qty}', $min);//* $qty
        }
        
        if($min > 0 && $max > 0) { 
            $html += ' , ';
        }

        if($max > 0) {
            $html += $('#txt-max').val().replace('{qty}', $max);// * $qty
        }

        $(this).html($html);
    });    
}

function update_option_menu_item_qty() {

    $.each($('#collapse-options ul.menu-items'), function() {

        $max = $(this).attr('data-max-quantity');// * $('input[name="quantity"]').val();

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
        
$(document).delegate('.lnk-price-chart', 'click', function() {

    $('.price_chart_wrapper').toggleClass('hidden');

    if($('.price_chart_wrapper').hasClass('hidden')) {
        $('.lnk-price-chart .fa-minus-square-o').removeClass('fa-minus-square-o').addClass('fa-plus-square-o');
    }else{
        $('.lnk-price-chart .fa-plus-square-o').removeClass('fa-plus-square-o').addClass('fa-minus-square-o');
    }
});


$(function(){
    update_price();
})