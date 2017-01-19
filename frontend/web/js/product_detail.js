
    jQuery(document).ready(function () {

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
if (!isGuest) {

    jQuery(document).delegate('#form_product_option', 'submit', function(e) {
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

                }
            }
        );

        e.preventDefault();
    });

    jQuery('.buy_item').click(function (e) {

        var item_id = jQuery(this).attr('id');

        jQuery.get(
            addtobasket_url,
            {
                'item_id': item_id,
                'cust_id': customer_id
            },
            function (data)
            {
                jQuery('#option_modal_wrapper').html(data);
                jQuery('#productOptionModal').modal('show');
                jQuery('.selectpicker').selectpicker();

                jQuery('.date').datepicker({
                    container:'#delivery_date_wrapper'
                });
            }
        );

        e.preventDefault();
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
                    $('.timeslot_id_div .text').html('Delivery not available for the selected date');
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

    function productAvailability(date){
        jQuery.ajax({
            type: 'POST',
            url: product_availability,
            data: $('#form_product_option').serialize(),
            success: function (data)
            {
                if (jQuery.trim(data) != '1') {
                    $('.timeslot_id_div').show();
                    $('.timeslot_id_div .text').html(data);
                    $('.timeslot_id_select').hide();
                    $('#timeslot_id').html('');
                    return false;
                }else{
                    deliveryTimeSlot(date);
                }
            }
        });
    }

    // pre set value for Delivery date on product detail page
    if (deliver_date) {
        productAvailability(jQuery('#delivery_date').val());
    }

    // Shop product page quantity increment and decrement stepper
    jQuery(document).on('click','.btn-stepper',function(){
        if (jQuery(this).data('case') == 0) {
            if (parseInt(jQuery('#quantity').val()) >= parseInt(jQuery('#quantity').data('min'))+1) {
                jQuery('#quantity').val(parseInt(jQuery('#quantity').val()) - 1);
            }
        } else if (jQuery(this).data('case') == 1) {
            jQuery('#quantity').val(parseInt(jQuery('#quantity').val())+1);
        }
        return false;
    });
}

// product detail page Delivery Date change event
$("#delivery_date").on("changeDate", function(e) {
    $('.error.cart_delivery_date').html('');
    productAvailability(jQuery(this).val());
});

$(document).delegate('.menu-item-qty-box .fa-minus', 'click', function() {
   
    $qty_input = $(this).parent().find('input');

    $qty = parseInt($qty_input.val());

    $qty_input.val($qty - 1);

    update_price();
});

$(document).delegate('.menu-item-qty-box .fa-plus', 'click', function() {
   
    $qty_input = $(this).parent().find('input');

    $qty = parseInt($qty_input.val());

    $qty_input.val($qty + 1);

    update_price();
});

function update_price() {
    $.post($('#final_price_url').val(), $('.menu-item-detail input').serialize(), function(json) {
        $('.item-final-price').html(json.price);
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