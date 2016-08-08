
    jQuery(document).ready(function () {
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
    });

    /* FeatureD Products end*/
    /*carousel slider*/
    jQuery(document).ready(function () {
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



    /* nav content js*/
    jQuery(document).ready(function () {
        var menu = jQuery('.category_listing_nav')

        menu.hide();

        jQuery('#plan_down').hover(
            function () {
                jQuery('.category_listing_nav').stop(true, true).slideDown(400);
            },
            function () {
                jQuery('.category_listing_nav').stop(true, true).slideUp(400);
            }
        );
    });
    /*end*/


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

    /* Mega menu */
    jQuery(document).ready(function () {
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
    });
    /* Mega menu ends */

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
// Define custom and unlimited items depending from the width
// If this option is set, itemsDeskop, itemsDesktopSmall, itemsTablet, itemsMobile etc. are disabled
// For better preview, order the arrays by screen size, but it's not mandatory
// Don't forget to include the lowest available screen size, otherwise it will take the default one for screens lower than lowest available.
// In the example there is dimension with 0 with which cover screens between 0 and 450px

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
// Define custom and unlimited items depending from the width
// If this option is set, itemsDeskop, itemsDesktopSmall, itemsTablet, itemsMobile etc. are disabled
// For better preview, order the arrays by screen size, but it's not mandatory
// Don't forget to include the lowest available screen size, otherwise it will take the default one for screens lower than lowest available.
// In the example there is dimension with 0 with which cover screens between 0 and 450px
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
        jQuery('.buy_item').click(function () {
            
            var item_id = jQuery(this).attr('id');
            
            jQuery.ajax({
                type: 'POST',
                url: addtobasket_url,
                data: {'item_id': item_id, 'cust_id': customer_id },
                success: function (data)
                {
                    // alert(data);
                }
            });
        });
    }


    /* END BUY Item */
    jQuery('#delivery_date').on('change', function () {
        jQuery.ajax({
            type: 'POST',
            url: getdeliverytimeslot_url,
            data: {'vendor_id': vendor_id, 'sel_date': jQuery(this).val()},
            success: function (data)
            {
                jQuery('.bs-docs-example #delivery-time').selectpicker('refresh');
                jQuery('.bs-docs-example #delivery-time').html(data);
            }
        });
    });