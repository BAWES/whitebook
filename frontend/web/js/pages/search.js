$(document).ready(function () {

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

function setupLabel() {
    if(jQuery('.label_check input').length ) {
        jQuery('.label_check').each(function () {
            jQuery(this).removeClass('c_on');

            if(jQuery(this).parents('.panel-body').find('label.c_on').length == 0){
                jQuery(this).parents('.panel-default').find('a.filter-clear').css('display','none');
            }else{
                jQuery(this).parents('.panel-default').find('a.filter-clear').css('display','inline-block');
            }

        });
        jQuery('.label_check input:checked').each(function () {
            jQuery(this).parent('label').addClass('c_on');
            if(jQuery(this).parents('.panel-body').find('label.c_on').length == 0){
                jQuery(this).parents('.panel-default').find('a.filter-clear').css('display','none');
            }else{
                jQuery(this).parents('.panel-default').find('a.filter-clear').css('display','inline-block');
            }
        });
    }

    if (jQuery('.label_radio input').length) {
        jQuery('.label_radio').each(function () {
            jQuery(this).removeClass('r_on');
        });
        jQuery('.label_radio input:checked').each(function () {
            jQuery(this).parent('label').addClass('r_on');
        });
    }
}

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

jQuery('.collapse').on('shown.bs.collapse', function(){
    jQuery(this).parent().find(".plus_acc").removeClass("plus_acc").addClass("minus_acc");
}).on('hidden.bs.collapse', function(){
    jQuery(this).parent().find(".minus_acc").removeClass("minus_acc").addClass("plus_acc");
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

/*filter code js end*/
$('#open_search').click(function () {
    jQuery("#open_search").toggleClass("active");
});

/* Mega menu */
jQuery(document).ready(function(){
    jQuery(".dropdown").hover(
        function() {
            jQuery('.dropdown-menu', this).stop( true, true ).slideDown("fast");
            jQuery(this).toggleClass('open');
        },
        function() {
            jQuery('.dropdown-menu', this).stop( true, true ).slideUp("fast");
            jQuery(this).toggleClass('open');
        }
    );
});

/* Mega menu ends */
(function(jQuery){
    jQuery(window).load(function(){
        jQuery(".test_scroll").mCustomScrollbar(
            {theme:"rounded-dark" ,
                mouseWheelPixels: 50,
                scrollInertia: 0
            });
    });
})(jQuery);


/* BEGIN filter item list */
var csrfToken = jQuery('meta[name="csrf-token"]').attr("content");
var url = window.location.href;     // Returns full URL
setupLabel();
jQuery('.label_check input').on('change',function() {
    filter();
});

var limit = 1;
jQuery('button#loadmore').click(function(event)
{
    setupLabel();
    limit = limit+1;
    var path = load_more;
    jQuery.ajax({
        type:'POST',
        url:path,
        data:{limit:limit, _csrf : csrfToken},
        success:function(data){
            jQuery('.events_listing ul li:last-child').after(data);
            // Every fourth li change margin
            jQuery('.listing_right .events_listing ul li:nth-child(4n)').addClass("margin-rightnone");
        }
    })
});

/* BEGIN load category and reload the page */
jQuery('#main-category').change(function(){
    var s = jQuery('#main-category :selected').val();
    var hostname = window.location.host;
    location.href = 'http://'+hostname+'/products/'+jQuery(this).val();
});
/* END load category and reload the page */

function filter(){
    jQuery("#planloader").show();
    jQuery(".events_listing").css({"opacity":"0.5","position":"relative"});

    var theme_name = jQuery("input[name=themes]:checked").map(function() {
        return this.value;
    }).get().join('+');

    var vendor_name = jQuery("input[name=vendor]:checked").map(function() {
        return this.value;
    }).get().join('+');

    var price_val = jQuery("input[name=price]:checked").map(function() {
        return this.value;
    }).get().join('+');
    /* URL format */

    var url_path;
    var url = window.location.href;
    var newUrl = url.substring(0, url.indexOf('?'));
// BEGIN Get main category from url
    var slug;
    if(newUrl !='') {
        slug = newUrl.substring(newUrl.lastIndexOf('/') + 1);
    } else {
        slug = url.substring(url.lastIndexOf('/') + 1);
    }

    /* if all checkbox uncheck load items based on category */
    if (theme_name =="" && vendor_name =="") {
        window.history.pushState("test", "Title", newUrl);
        slug = search;
    }

    if(theme_name !="" || vendor_name !="" || price_val !="") {
        url_path = '?themes='+theme_name+'&vendor='+vendor_name+'&price='+price_val;

    }
    jQuery.ajax({
        type:'POST',
        url:path,
        data:{themes : theme_name,vendor : vendor_name,price : price_val,slug: slug,search: search, _csrf : csrfToken},
        success:function(data){
            window.history.pushState("test", "Title", url_path);
            jQuery('.events_listing ul').html(data);
            jQuery('.listing_right .events_listing ul li:nth-child(4n)').addClass("margin-rightnone");
            jQuery("#planloader").hide();
            jQuery(".events_listing").css({"opacity":"1.0","position":"relative"});

        }
    }).done(function() {

        jQuery(".add_to_favourite").click(function(){

            jQuery('#loading_img_list').show();
            jQuery('#loading_img_list').html('<img id="loading-image" src="'+giflink+'" alt="Loading..." />');

            item_id=(jQuery(this).attr('id'));
            jQueryelement = jQuery(this)
            jQuery(jQueryelement).parent().toggleClass("faverited_icons");

            var _csrf=jQuery('#_csrf').val();
            jQuery.ajax({
                url:wishlist_url,
                type:"post",
                data:"item_id="+item_id+"&_csrf="+_csrf,
                success:function(data)
                {
                    jQuery('#heart_fave').html(data);
                    jQuery('#loading_img_list').hide();
                }
            });
        });

    });
}

/* BEGIN ADD EVENT */
function addevent(item_id)
{
    jQuery.ajax({
        type:'POST',
        url:addevent,
        data:{'item_id':item_id},
        success:function(data)
        {
            jQuery('#addevent').html(data);
            jQuery('#eventlist'+item_id).selectpicker('refresh');
            jQuery('#add_to_event').modal('show');

        }
    });
}
/* END ADD EVENT */

/* BEGIN RESPONSIVE FILTER NAVIGATION */
var trigger = jQuery('.filter_butt'),
    overlay = jQuery('.overlay'),
    isClosed = false;

trigger.click(function () {
    filter_butt();
});

function filter_butt() {

    if (isClosed == true) {
        overlay.hide();
        trigger.removeClass('ses_act');
        trigger.addClass('ses_dct');
        isClosed = false;
    } else {
        overlay.show();
        trigger.removeClass('ses_act');
        trigger.addClass('ses_dct');
        isClosed = true;
    }
}

jQuery("#left_side_cate nav").removeClass ("navbar navbar-fixed-top ");
jQuery("#left_side_cate ul").removeClass ("nav sidebar-nav ");
jQuery("#left_side_cate nav").removeAttr ("id")
if (jQuery(window).width() < 991) {
    jQuery("#left_side_cate nav").addClass ("navbar navbar-fixed-top ");
    jQuery("#left_side_cate ul").addClass ("nav sidebar-nav ");
    jQuery("#left_side_cate nav").attr ('id','sidebar-wrapper')
}