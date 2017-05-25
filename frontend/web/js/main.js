
function resize() 
{
    //only for browse page 
    
    if($('.mobile_only_filter').length == 0)
        return true;

    if($(document).width() < 992) 
    {
        $contents  = '<div class="col-lg-3 date-filter">' + $('.overlay_filter .date-filter').html() + '</div>';
        $contents += '<div class="col-lg-3 event-filter">' + $('.overlay_filter .event-filter').html() + '</div>';
        $contents += '<div class="col-lg-3 location-filter">' + $('.overlay_filter .location-filter').html() + '</div>';

        if($('.overlay_filter .date-filter').length > 0)
        {
            $('.mobile_only_filter').html($contents);

            //$a = '<div class="col-lg-3 padding-left-0 theme-filter">' + $('.overlay_filter .theme-filter').html() + '</div>';
            //$('.filter_title').after($a);

            $('.overlay_filter .date-filter').remove();
            $('.overlay_filter .event-filter').remove();
            $('.overlay_filter .location-filter').remove();
            //$('.overlay_filter .theme-filter').remove();

            //move category block 
            //$('.listing_sub_cat1').contents().appendTo('.overlay_filter');
        }
    }
    else
    {
        $contents = $('.mobile_only_filter').html();

        if($contents.indexOf("<div") >= 0)
        {
            $('.mobile_only_filter').contents().appendTo('.overlay_filter');

            //move category block 
            // $('.filter_title').after('<div class="listing_sub_cat1">' + $('.listing_sub_cat1').html() + '</div>');
            //$('.overlay_filter .listing_sub_cat1').remove();
        }
    }
}

$(window).resize(function() {
    resize();
});

function loadCart() 
{    
    $.get(mini_cart_url, function(html) {
        $('.min-cart-wrapper').html(html);
    });
}

$(document).ready(function(){

    resize();

    loadCart();

    $(document).delegate('.min-cart-wrapper .btn-mini-cart', 'click', function() {
        loadCart();
    });

    $('body').append('<div class="ma5-mobile-menu-container"/>');
    $('.ma5-menu-mobile').find('ul').clone().addClass('ma5-menu-panel').appendTo('.ma5-mobile-menu-container').find('ul').remove();

    $('.ma5-toggle-menu').on('click touch', function () {
        if($(".plan_venues").hasClass("toggled")){
            $(".plan_venues").removeClass("toggled");
            $(".overlay").css("display","none");
            isClosed = false;
        }

        $('html').toggleClass('ma5-menu-active');
    });

    $('.ma5-btn-enter').on('click touch', function () {
        $('.ma5-menu-panel').removeClass('ma5-active-ul');
        $('.ma5-menu-panel li').removeClass('ma5-active-li');
        var itemPath = $(this).parent().attr('class').replace("li", "ul");
        var itemParent = $(this).parent().attr('class').replace("li", "ul").split('-');
        var spliced = itemParent.splice(-1, 1);
        var itemParent = itemParent.join("-");
        $('.ma5-menu-panel').removeClass('ma5-active-leave ma5-parent-leave ma5-active-enter ma5-parent-enter');
        $('.ma5-menu-panel.' + itemParent).addClass('ma5-parent-enter');
        $('.ma5-menu-panel.' + itemPath).addClass('ma5-active-enter');
    });

    $('.ma5-leave-bar').on('click touch', function () {
        var itemParent = $(this).parent().attr('class').replace("li", "ul").split('-');
        var splicedParent = itemParent.splice(-1, 1);
        var splicedParent = itemParent.splice(-1, 1);
        var itemParent = itemParent.join("-");
        var itemPath = $(this).parent().attr('class').replace("li", "ul").split('-');
        var spliced = itemPath.splice(-1, 1);
        var itemPath = itemPath.join("-");
        $('.ma5-menu-panel').removeClass('ma5-active-leave ma5-parent-leave ma5-active-enter ma5-parent-enter');
        $('.ma5-menu-panel.' + itemParent).addClass('ma5-parent-leave');
        $('.ma5-menu-panel.' + itemPath).addClass('ma5-active-leave');
    });
});

//clear filter 
$(document).delegate('a#filter-clear', 'click', function(){
    $(this).parents('.panel-default').find('label.label_check').removeClass('c_on');
    $(this).parents('.panel-default').find('label.label_check input').prop('checked', false);
    $(this).hide();
    filter();
});

$(document).delegate('[data-toggle="offcanvas"]', 'click', function () {
    $('#wrapper').toggleClass('toggled');
});

//mobile - filter button 

$(document).delegate('.btn-close-filter', 'click', function(){
    $(this).removeClass('visible-xs').removeClass('visible-sm').hide();
    $('.btn-open-filter').addClass('visible-xs').addClass('visible-sm');
    toggleFilter();
});

$(document).delegate('.btn-open-filter', 'click', function(){
    $(this).removeClass('visible-xs').removeClass('visible-sm').hide();
    $('.btn-close-filter').addClass('visible-xs').addClass('visible-sm');
    toggleFilter();
});

function toggleFilter() 
{
    $('#left_side_cate').toggleClass('hidden-sm');
    $('#left_side_cate').toggleClass('hidden-xs');

    if($('.vendor-filter').length > 0) {
        $('html, body').animate({ scrollTop: $('.vendor-filter').offset().top - 120 }, 'slow');
    }else{
        $('html, body').animate({ scrollTop: 0 }, 'slow');
    }    
}

//mobile search close 
$(document).delegate('.mobile-menu #search_form .btn-close', 'click', function() {
    $('.mobile-menu').removeClass('open-search-menu');
});

function validateEmail(email) {
    // http://stackoverflow.com/a/46181/11236
    var re = /^[a-zA-Z0-9!#$%&'*+\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&'*+\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?$/;
    return re.test(email);
}

$(window).resize(function() {
    
    if($(window).width() <= 990) {
        $('#home_slider').css('padding-top', $('#top_header').height() + 'px');
    }
});

$(document).ready(function () {

    if(session_show_login_modal == 1) {
        $('#myModal').modal('show');
    }

    /*home slider new start*/
    var owl = $("#home-banner-slider");

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
        autoPlay:false
    });

    var owl = $("#feature-group-slider,#similar-products-slider");

    owl.owlCarousel({

        itemsCustom: [
            [0, 1],
            [450, 1],
            [600, 2],
            [700, 3],
            [800, 3],
            [1000, 4],
            [1200, 5],
            [1400, 5],
            [1600, 5]
        ],
        navigation: true,
        autoWidth: true,
        loop: true,
        autoPlay:false
    });

    $(window).trigger('resize');

    $('#phone,#reg_email').bind("paste",function(e) {
        e.preventDefault();
    });

    $("#search_list_fail1").hide();

    /*Popup modal script start*/
    $(document).delegate('.new_btn', 'click', function(e) {
        $('#myModal').modal('hide');
    });

    /* mobile hover menu start */
    $(".mobile-menu .dropdown").click(function () {
        $(this).addClass('open');
    },
    function () {
        $(this).removeClass('open');
    });
    /* mobile hover menu end */

    /* registration form checkbox  start  */
    $('label#label_check1').click(function()
    {
        if ($('#agree_terms').attr('checked')) {
            $("#agree_terms").attr("checked",false);
            $("#agree_terms").val('0');
            $('#agree').html(tick_the_terms_of_services_and_privacy_policy);
            $('label#label_check1').removeClass('c_onn');
            $('label#label_check1').addClass('c_off');
        } else {
            $("#agree_terms").attr("checked",true);
            $("#agree_terms").val('1');
            $("#agree").html('');
            $('label#label_check1').removeClass('c_off');
            $('label#label_check1').addClass('c_onn');
        }
    });
    /* registration form checkbox  end */

    /*Responsive menu script start*/
    function isTouchDevice() {
        return 'ontouchstart' in window
    };

    if( isTouchDevice() ) {
        /*
        $("body").swipe({
            swipe:function(event, direction, distance, duration, fingerCount, fingerData) {
                if ( direction == 'left' ) { $('html').removeClass('ma5-menu-active');}
                if ( direction == 'right' ) { $('html').addClass('ma5-menu-active');}
            },
            allowPageScroll: "vertical"
        });
        */
    };
    /*Responsive menu script end*/
});//end document ready


// plan last:child script
$(document).ready(function() {
    $('.plan_sections ul li:nth-child(3n)').addClass("margin-rightnone");
});
// plan last:child script end -->

$(document).delegate('.accor-link', 'click', function()
{
    ($(this).hasClass('accor-link-min'))?$(this).removeClass('accor-link-min'):$(this).addClass('accor-link-min');
});

$('.accor-link-min').click(function()
{
    $('.accor-link-min').addClass('accor-link');
    $('.accor-link-min').removeClass('accor-link-min');
});

/* Forgot password completed start  */
$(document).delegate("#reset_button", 'click', function()
{
    resetpwdcheck();
});

$(document).delegate('#resetForm input', 'keydown', function(e) {
    if (e.keyCode == 13)
    {
        resetpwdcheck();
    }
});

function resetpwdcheck()
{
    var passwordlength=$('#new_password').val();
    var x=(passwordlength.length);
    var password=$('#new_password').val();
    var userid=$('#userid1').val();
    var conPassword=$('#confirm_password').val();

    if(($('#resetForm').valid()))
    {}else{return false;}
    var k=0;
    if(x<6)
    {
        $('#reset_pwd_result').show();
        $('#reset_pwd_result').html(password_should_contain_minimum_six_letters);
        return false;
    }

    if(password==conPassword)
    {
        $('#reset_pwd_result').hide();
        k=1;
    }
    else
    {
        $('#reset_pwd_result').show();
        $('#reset_pwd_result').html(confirm_password_should_be_equal_to_password);
        return false;
    }

    if(($('#resetForm').valid()) && (k==1))
    {
        $.ajax({
            url: password_reset_link,
            type:"POST",
            data:"id="+userid+"&password="+password+"&_csrf="+_csrf,
            async: false,
            success:function(data)
            {
                console.log(data);
                if(data==1)
                {
                    $('#reset_loader').hide();
                    $('#resetPwdModal').modal('hide');
                    $('#login_success').modal('show');
                    $('#success').html('<span class="sucess_close">&nbsp;</span><span class="msg-success" style="margin-top: 5px; width: 320px; float: left; text-align: left;">'+pwd_reset_msg+'</span>');
                    window.setTimeout(function() {$('#login_success').modal('hide');}, 2000);
                    location.reload();
                    //window.setTimeout(function() {$('#login_success').modal('hide');}, 2000);
                    all_form_reset();
                }
            }
        });
    }
}
/* Forgot password completed end */

$(document).delegate('#conpassword', 'keyup', function() {

    if($(this).val() != $('#userpassword').val()) {
        $('#con_pass').show();
        $('#con_pass').html(password_and_confirm_password_should_be_minimum_six_letters_and_same);
    }else{
        $('#con_pass').hide();
        $('#con_pass').html('');
    }
});

$(document).delegate('#login_button', 'click', function()
{
    logincheck();
});

$(document).delegate('#loginForm input', 'keydown', function(e) {
    if (e.keyCode == 13)
    {  
        logincheck();
    }
});

function logincheck()
{
    if($('#loginForm').valid())
    {
        $('#login_loader').show();
        $('#login_button').html('Please Wait...');
        $('#login_button').attr('disabled', true);
        
        var email = $('#email').val();
        var password = $('#password').val();
        var _csrf = $('#_csrf').val();

        if(validateEmail(email) == true) {

            $.ajax({
                url: user_login,
                type:"POST",
                async:false,
                data:"email="+email+"&password="+password+"&_csrf="+_csrf,
                success:function(data)
                {
                    var parsed = JSON.parse(data);
                    var arr = [];

                    for(var x in parsed){
                        arr.push(parsed[x]);
                    }

                    status=arr[0];
                    item_name=arr[1];

                    if(status==-1)
                    {
                        console.log(not_activate_msg);
                        $('#login_msg').addClass('alert-warning alert fade in');
                        $('#login_msg').html(not_activate_msg+'<a id="boxclose" name="boxclose" class="boxclose" onclick="MyFunction();"></a>').animate({ color: "red" }).show();
                        $('#login_forget').show();
                        $('#loader').hide();
                    }
                    else if(status==-2)
                    {
                        console.log(user_blocked_msg);
                        $('#login_msg').addClass('alert-warning alert fade in');
                        $('#login_msg').html(user_blocked_msg+'<a id="boxclose" name="boxclose" class="boxclose" onclick="MyFunction();"></a>').animate({ color: "red" }).show();
                    }
                    else if(status==-3)
                    {
                        console.log(email_not_exist);
                        $('#login_msg').addClass('alert-warning alert fade in');
                        $('#login_msg').html(email_not_exist+'<a id="boxclose" class="boxclose" onclick="MyFunction();"></a>').animate({ color: "red" }).show();
                    }
                    else if(status==-4)
                    {
                        console.log(email_not_match);
                        $('#login_msg').addClass('alert-warning alert fade in');
                        $('#login_msg').html(email_not_match+'<a id="boxclose" class="boxclose" onclick="MyFunction();"></a>').animate({ color: "red" }).show();
                    }
                    else if(status==1)
                    {
                        $('#myModal').modal('hide');

                        if(favourite_status>0){
                            $('#login_success').modal('show');
                            var success_fav_added = success_fav_added1 + '"' + item_name + '"' + success_fav_added2;
                            $('#success').html('<span class="sucess_close">&nbsp;</span><span class="msg-success" style="margin-top: 5px; width: 320px; float: left; text-align: left;"> '+success_fav_added+' </span>');
                            window.setTimeout(function(){location.reload()},1000)
                        } else if(event_status==-1){
                            window.setTimeout(function(){location.reload()})
                        }
                        else if(event_status>0){
                            window.setTimeout(function(){location.reload()})
                        }
                        else{
                            window.setTimeout(function(){location.reload()},1000)
                        }
                    }
                    $('#login_loader').hide();
                    $('#login_button').html('Login');
                    $('#login_button').removeAttr('disabled');

                },
                error:function(data)
                {

                }
            });
        }
        else
        {
            $('#login_loader').hide();
            $('#login_button').html('Login');
            $('#login_button').removeAttr('disabled');
            //$('#loginErrorMsg').addClass('alert-failure alert fade in');
            $('#login_msg').addClass('alert-warning alert fade in');
            $('#login_msg').html("Enter Valid Email ID"+'<a id="boxclose" class="boxclose" onclick="MyFunction();"></a>').animate({ color: "red" }).show();

        }
    }
}

//Register save function ajax

$(document).delegate('#register_form', 'submit', function(e)
{
    e.preventDefault();
    e.stopPropagation();

    $have_errors = false;

    $('#register').html($('#txt_loading').val());
    $('#register').attr('disabled', 'disabled');
    
    if($('#agree_terms:checked').length == 0)
    {
        $('#agree').show();
        $('#agree').html(tick_the_terms_of_services_and_privacy_policy);
        $have_errors = true;
    }

    if($have_errors)
    {
        $('#register').html($('#txt_register').val());
        $('#register').removeAttr('disabled');
        return false;
    }

    $.post(signup, $('#register_form').serialize(), function(json) 
    { 
        $('#register').html($('#txt_register').val());
        $('#register').removeAttr('disabled');

        if(json.operation == 'success')
        {
            $('#myModal1').modal('hide');
            window.setTimeout(function() { location.reload(); });
        }

        window.setTimeout(function(){
            $('#register').html($('#txt_register').val());
            $('#register').removeAttr('disabled');
        }, 1000);
    });
});


$(document).delegate("#phone, #invitees_phone", 'keypress', function (e) {
    //if the letter is not digit then display error and don't type anything
    if (  e.which  != 43   && e.which  != 45 && e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57 )) {
        //display error message
        $(".field-vendor-vendor_contact_number").find('.help-block').html('Contact number digits only+.').animate({ color: "#a94442" }).show().fadeOut(2000);
        return false;
    }
});

/* BEGIN ADD TO EVENT */
$(document).delegate('#EventModal #create_event_button', 'click', function(){
   
    var i=0;
    //var element = $(this).parents('form');
    var event_type=$('#EventModal  #event_type').val();
    
    if(event_type==''){
        $('#EventModal #type_error').html(kindly_select_event_type);
        i=1;
    }else{
        i=0;
        $('#EventModal #type_error').hide();
    }

    if($('#create_event').valid()&& (i==0))
    {
        $('#EventModal #event_loader').show();

        var event_date = $('#EventModal #event_date').val();
        var item_id = $('#EventModal #item_id').val();
        var event_name = $('#EventModal #event_name').val();
        var no_of_guests = $('#EventModal #no_of_guests').val();

        var _csrf=$('#_csrf').val();

        if(item_id!=0)
        {
            var item_name=$('.desc_popup_cont h3').text();
        }else{
            var item_name='item ';
        }

        $.ajax({
            url: create_event,
            type:"post",
            data:"event_date="+event_date+"&item_id="+item_id+"&event_name="+event_name+"&item_name="+item_name+"&event_type="+event_type+"&_csrf="+_csrf + "&no_of_guests=" + no_of_guests,
            success:function(data,slider)
            {
                $('.directory_slider,.container_eventslider').load('events_slider', function(){
                    $(this).css('background','transparent','important');
                });
                /* Hide BG FOR EVENT SLIDER*/
                if(data==-1)
                {
                    $('#EventModal #event_loader').hide();
                    $('#EventModal #eventresult').addClass('alert-success alert fade in');
                    $('#EventModal #eventresult').html(event_exist+'<a id="boxclose" class="boxclose" onclick="MyEventFunction();"></a>').animate({ color: "red" }).show();
                }
                else if(data==1)
                {
                    $("#EventModal .eventErrorMsg").html('');
                    $('#EventModal').modal('hide');
                    window.setTimeout(function(){location.reload()});
                }
                else if(data==2)
                {
                    $('#EventModal #event_loader').hide();
                    $("#EventModal .eventErrorMsg").html('');
                    $('#EventModal').modal('hide');
                    window.setTimeout(function(){location.reload()});
                }
            }
        });
    }
});
/* END ADD TO EVENT */

$(document).delegate('#cancel_button', 'click', function(){
    var create_event = $( "#create_event" ).validate();
    create_event.resetForm();
    $(':input','#create_event').not(':button, :submit, :reset, :hidden')
    $('#type_error').html('');
});

/* BEGIN EDIT TO EVENT */
$(document).delegate("#update_event_button", 'click', function()
{
    var event_type = $('#edit_event_type').val();

    if(event_type==''){
        $('#type_error').html(kindly_select_event_type);
        i = 1;
    } else {
        i = 0;
        $('#type_error').hide();
    }

    if($('#update_event').valid() && i == 0)
    {
        $('#event_loader').show();

        var event_date = $('#edit_event_date').val();
        var item_id = $('#item_id').val();
        var event_name = $('#edit_event_name').val();
        var no_of_guests = $('#update_event input[name="no_of_guests"]').val();

        console.log($('#no_of_guests'));

        var _csrf = $('#_csrf').val();

        $.ajax({
            url: update_event,
            type:"post",
            data:"event_id="+$('#edit_event_id').val()+"&event_date="+event_date+"&item_id="+item_id+"&event_name="+event_name+"&event_type="+event_type+"&no_of_guests="+no_of_guests+"&_csrf="+_csrf,
            success:function(data)
            {
                if(data==-1)
                {
                    $('#event_loader').hide();
                    $('#eventresult').addClass('alert-success alert fade in');
                    $('#eventresult').html(event_exists+'<a id="boxclose" class="boxclose" onclick="MyEventFunction();"></a>').animate({ color: "red" }).show();
                    //$(".eventErrorMsg").html('Same event name already exists!');
                    // window.setTimeout(function(){location.reload()},2000);
                }
                else
                {
                    $('#event_loader').hide();
                    $(".eventErrorMsg").html('');

                    $('#EditeventModal').modal('hide');
                    $('#login_success').modal('show');
                    $('#success').html('<span class="sucess_close">&nbsp;</span><span class="msg-success" style="margin-top: 5px; width: 320px; float: left; text-align: left;">'+update_msg+'</span>');
                    setTimeout(function() {$('#login_success').modal('hide');}, 2000);
                    //$('#EventModal').modal('hide');
                    setTimeout(function(){
                        window.location = data;//event_details + '?slug='+data;
                    },2000);
                }
            },
            error:function(data)
            {

            }
        })
    }
})
/* END EDIT TO EVENT */

function locat(loc)
{
    window.location = event_details + loc;
}

function show_register_modal()
{
    $('#myModal').modal('hide');
    $('#forgotPwdModal').modal('hide');
}

function show_login_modal(x)
{
    $('.ma5-menu-active').removeClass('ma5-menu-active');
    $('#register').modal('hide');
    $('#forgotPwdModal').modal('hide');
    $('#event_status').val(x);
}

function show_login_modal_wishlist(x)
{
    $('#register').modal('hide');
    $('#forgotPwdModal').modal('hide');
    $('#favourite_status').val(x);
}

function show_mydata()
{
    $('#event_status').val(0);
    $('#forgotPwdModal').modal('hide');
    $('#myModal1').modal('hide');
}

function create_event_values(){
    $('#item_id').val(0);
}

function forgot_modal()
{
    $('#event_status').val(0);
    $('#myModal').modal('hide');
    $('#Signupmodel').modal('hide');
}

function add_create_event(x)
{
    $('#add_to_event'+x).modal('hide');
    $('#myModal').modal('hide');
    $('#item_id').val(x);
    $('#Signupmodel').modal('hide');
    $('#forgotPwdModal').modal('hide');
}

function add_event_login(x)
{
    $('#event_status').val(x);
}

function show_create_event_form()
{
    $('#forgotPwdModal').modal('hide');
    $('#myModal').modal('hide');
    $('#Signupmodel').modal('hide');
}

var reg_email = $('#reg_email').val();

$(function () {

    $(document).delegate("#reg_email", 'keyup keypress focusout', function() {

        var x = $("#reg_email").val();
        var _csrf = $('#_csrf').val();

        if(validateEmail(x) == true){
            $.ajax({
                url: email_check,
                type:"post",
                data:"email="+x+"&_csrf="+_csrf,
                success:function(data)
                {
                    if(data==1)
                    {
                        $("#customer_email").show();
                        $("#customer_email").html(entered_email_id_is_already_exists);
                    }
                    else if(data==0)
                    {

                        $("#customer_email").html('');
                        $("#customer_email").hide();
                    }
                }
            });
        }
    });
});

$(document).delegate("#forgot_button", 'click', function()
{
    forgot_password();
});

$(document).delegate('#forgotForm input', 'keydown', function(e) {
    if (e.keyCode == 13)
    {
        if(validateEmail(reg_email)==false)
        {
            $('#forgot_loader').hide();
            $('#forgot_result').addClass('alert-success alert fade in');
            $('#forgot_result').html('Enter registered Email-id!<a id="boxclose" class="boxclose" onclick="ForgotFunction();"></a>').animate({ color: "red" }).show();
        
            forgot_password();
            return false;
        }
        forgot_password();
    }
});

function forgot_password()
{
    var form = $("#forgotForm");
    var reg_email = $('#forget_email').val();
    var _csrf = $('#_csrf').val();
    i = 0;

    if(validateEmail(reg_email)==true)
    {
        $('span.forgotpwd').hide();
        $('#forgot_loader').show();

        $.ajax({
            url: forgot_password_url,
            type:"post",
            async:false,
            data:"email="+reg_email+"&_csrf="+_csrf,
            async: false,
            success:function(data)
            {
                if(data==1)
                {
                    $('#forgot_loader').hide();
                    $('#forgotPwdModal').modal('hide');
                    $('#chkForgotPwdModal').modal('hide');
                    $('#MyModal').modal('hide');
                    $('#EventModal').modal('hide');
                    $('#login_success').modal('show');
                    $('#success').html('<span class="sucess_close">&nbsp;</span><span class="msg-success" style="margin-top: 5px; width: 320px; float: left; text-align: left;">'+receive_email+'</span>');
                    //window.setTimeout(function(){location.reload()},2000)
                    window.setTimeout(function() {
                        $('#login_success').modal('hide');
                    }, 3000);
                }
                else if(data==-1)
                {
                    $('#forgot_loader').hide();
                    $('#forgot_result').addClass('alert-success alert fade in');
                    $('#forgot_result').html(contact_admin+'<a id="boxclose" class="boxclose" onclick="ForgotFunction();"></a>').animate({ color: "red" }).show();

                }
            }
        });

    } else {
        $('#forgot_loader').hide();
        $('#forgot_result').addClass('alert-success alert fade in');
        $('#forgot_result').html(reg_email_id+'<a id="boxclose" class="boxclose" onclick="ForgotFunction();"></a>').animate({ color: "red" }).show();
    }
}

$(document).delegate('#search-terms1', 'keydown', function(e) {
    
    $("#search_list_fail1").html('');
    
    if (e.keyCode == 13)
    {
        var search1=$("#search-terms1").val();
        var search2 = search1.replace(' ', '-');
        var url = search_result_url;
        var path = url.concat(search2);
        window.location.replace(path);
    }
});

$(document).delegate('#search-terms2', 'keydown', function(e) {
    
    $("#search_list_fail1").html('');

    if (e.keyCode == 13)
    {
        var url1 = home_url;
        var search1=$("#search-terms2").val();
        var search2 = search1.replace(' ', '-');
        var url = search_result_url;
        var path = url.concat(search2);

        window.location.replace(path);
    }
});

$(document).delegate("#search-terms1", 'keyup', function () {
    $("#search_list_fail1").html('');
    var search = $("#search-terms1").val();
    search_data(search);
});

function search_data(search){
    if((search.length > 0) && (search != '')){
        $("#search_list_fail1").html('');
        var _csrf = $('#_csrf').val();

        if(search != ''){
            $.ajax({
                url: site_search,
                type:"post",
                async:true,
                data:"search="+search+"&_csrf="+_csrf,
                success:function(data)
                {
                    if(data==0)
                    {
                        $("#search_list").html('');
                        $("#search_list_fail1").html('<p>' + no_record_found + '</p>');
                    }
                    else
                    {
                        $("#search_list").html(data);
                        $("#search_list_fail1").html('');
                    }
                }
            });
        }else{
            $("#search_list").html('');
        }
    }else{
        $("#search_list").html('');
    }
}

function mobile_search_data(search){
    if(search.length>0){
        var _csrf = $('#_csrf').val();

        if(search != ''){
            $.ajax({
                url: site_search,
                type:"POST",
                async:false,
                data:"search="+search+"&_csrf="+_csrf,
                success:function(data)
                {
                    if(data==0)
                    {
                        $("#mobile_search_list").html(no_record_found);
                    }
                    else
                    {
                        $("#mobile_search_list").html(data);
                    }
                }
            });
        }
        else
        {
            $("#mobile_search_list").html('');
        }
    }
    else
    {
        $("#mobile_search_list").html('');
    }
}//END function mobile_search_data

$(document).delegate("#search-terms2", 'keyup', function(e) {
    if(e.keyCode == 8)
    {
        var search=$("#search-terms2").val();
        mobile_search_data(search);
    }
});

$(document).delegate("#search-terms2", 'keyup',function () {
    var search=$("#search-terms2").val();
    mobile_search_data(search);
});

$(document).delegate("#search_input_header", 'keyup',function () {
    var search=$("#search_input_header").val();
    search_data(search);
});

$(document).delegate('#search-labl', 'click', function(){
    $("#search_list").html('');
});

function add_to_favourite(x)
{
    $.ajax({
        url: add_to_wishlist_url,
        type:"post",
        data:"item_id="+x+"&_csrf="+_csrf,
        async: false,
        success:function(data)
        {
            var modal_name = 'add_to_event'+x;

            if(data == 1)
            {
                $('#add_to_event_loader').hide();
                $('#add_to_event_success'+x).modal('hide');
                $('#login_success').modal('show');
                $('#success').html('<span class="sucess_close">&nbsp;</span><span class="msg-success" style="margin-top: 5px; width: 320px; float: left; text-align: left;">' + item_add_to_wishlist_success + '</span>');

                window.setTimeout(function(){
                    location.reload()},1000
                )
            }
            else if(data==-1)
            {
                $('#add_to_event_loader').hide();
                $('#add_to_event_failure'+x).html(item_add_to_wishlist_failed);
                //window.setTimeout(function(){location.reload()},1000)
            }
            else if(data==-2)
            {
                $('#add_to_event_loader').hide();
                $('#add_to_event_success'+x).html(item_add_to_wishlist_already_exist);
            }
        }
    });
}

function remove_from_favourite(x)
{
    var strconfirm = confirm("Are you sure you want to delete?");

    if (strconfirm == true)
    {
        $.ajax({
            url: remove_from_wishlist,
            type:"post",
            data:"item_id="+x,
            async: false,
            success:function(data)
            {
                $('#wishlist #'+x).remove();
               
                if(data == 1)
                {
                    var item_removed = item_removed_fav;

                    $("#oner").load(event_slider_url);
                    $('#login_success').modal('show');
                    $('#success').html('<span class="sucess_close">&nbsp;</span><span class="msg-success" style="margin-top: 5px; width: 320px; float: left; text-align: left;">'+item_removed+'</span>');
                    window.setTimeout(function() {$('#login_success').modal('hide');}, 3000);
                    wishlistfilter();
                    //$('#add_to_event_success'+x).html('Item add to your event list');
                    //window.setTimeout(function(){location.reload()},1000)

                    // $('.faverited_icons').addClass('faver_icons');
                    //$('.faverited_icons').removeClass('faverited_icons');
                }
                else if(data==-1)
                {
                    ($(this).hasClass('faverited_icons'))?$(this).removeClass('faverited_icons'):$(this).addClass('faverited_icons');
                }
            }
        });
    }
}

//add to favorites

$(document).delegate(".add_to_favourite", 'click', function(e){
    //$('#loading_img_list').show();
    //$('#loading_img_list').html('<img id="loading-image" src="'+giflink+'" alt="Loading..." />');

    if ($(this).find('i').hasClass('fa-heart')) {
        $(this).find('i').removeClass('fa-heart');
        $(this).find('i').addClass('fa-heart-o');
    } else {
        $(this).find('i').removeClass('fa-heart-o');
        $(this).find('i').addClass('fa-heart');
    }

    item_id=($(this).attr('id'));
    $element = $(this);

    $($element).parent().toggleClass("faverited_icons");

    var _csrf=$('#_csrf').val();

    $.ajax({
        url: add_to_wishlist_url,
        type:"post",
        data:"item_id="+item_id+"&_csrf="+_csrf,
        //async: false,
        success:function(data)
        {
            $('#heart_fave').html(data);
            $('#loading_img_list').hide();
        }
    });

    e.preventDefault();
    e.stopPropagation();
});

$(document).delegate(".faver_evnt_product", 'click', function(){

        $('#loading_img').show();

        item_id=($(this).attr('id'));
        $element = $(this)
        $($element).find('span').toggleClass("heart-product-hover");

        var _csrf=$('#_csrf').val();

        $.ajax({
            url: add_to_wishlist_url,
            type:"post",
            data:"item_id="+item_id+"&_csrf="+_csrf,
            success:function(data)
            {
                $('#heart_fave').html(data);
                $('#loading_img').hide();
            }
        });
});

function add_to_event(x)
{
    var event_id = $('#eventlist'+x).val();
    var event_name = $('#eventlist'+x+' option:selected').text();

    if(event_id!=''){
        $('#add_to_event_loader').show();
        var _csrf=$('#_csrf').val();

        $.ajax({
            url:add_event_url,
            type:"post",
            data: {
                "event_name":event_name,
                "event_id":event_id,
                "item_id":x,
                "_csrf":_csrf
            },
            async: false,
            dataType:'JSON',
            success:function(data)
            {
                if(data.status==1)
                {
                    $("#event-slider").load("<?= Url::toRoute('/product/event-slider'); ?>");
                    $('#add_to_event_loader').hide();
                    $('#add_to_event').modal('hide');
                    $('#login_success').modal('show');
                    $('#success').html('<span class="sucess_close">&nbsp;</span><span class="msg-success" style="margin-top: 5px; width: 320px; float: left; text-align: left;">'+data.message+'</span>');
                    window.setTimeout(function() {$('#login_success').modal('hide');}, 3000);
                    //$('#add_to_event_success'+x).html('Item Add to Your event list');
                }
                else if(data.status==-1)
                {
                    $('#add_to_event_loader').hide();
                    $('#add_to_event_success'+x).html(data.message);
                }
            }
        });
    }
    else
    {
        $('#add_to_event_error'+x).html(kindly_select_event_type);
    }
}

function MyFunction()
{
    $('#login_msg').fadeOut('fast');
}

function MyEventFunction()
{
    $('#eventresult').fadeOut('fast');
}

function ForgotFunction()
{
    $('#forgot_result').fadeOut('fast');
}

$(document).delegate("#bday, #bmonth, #byear", 'change', function() {
    var day = $('#bday').val();
    var mon = $('#bmonth').val();
    var year = $('#byear').val();

    if(day != '' && mon != '' && year != ''){
        $('#dob_er').text('');
    }
});

$(document).delegate("#gender", 'change', function() {
    var day=$('#gender').val();
    if(day!=''){
        $('#gen_er').text('');
    }
});

$(document).delegate("#event_type", 'change', function() {
    var event_type = $('#event_type').val();

    if(event_type == '') {
        $('#type_error').html(kindly_select_the_event_type);
    }
    else
    {
        $('#type_error').html('');
    }
});

$(document).delegate("#boxclose", 'click', function() {
    $('#result').hide();
});

$(document).delegate(".close", 'click', function () {
    all_form_reset();
});

function all_form_reset(){

    var loginForm = $( "#loginForm" ).validate({errorLabelContainer: "#login_msg"});
    loginForm.resetForm();
    $(':input','#loginForm')
    .not(':button, :submit, :reset, :hidden')
    .val('')
    .removeAttr('checked')
    .removeAttr('selected');
    $('#result').html('');
    $("#result").removeClass("alert-success alert fade in");

    var register_form = $( "#register_form" ).validate();
    register_form.resetForm();
    $(':input','#register_form')
    .not(':button, :submit, :reset, :hidden')
    .val('')
    .removeAttr('checked')
    .removeAttr('selected');
    $('#customer_email').html('');
    $('#agree').html('');
    $('#con_pass').html('');
    $('#dob_er').html('');
    $('#gen_er').html('');
    //$("#agree_terms").attr("checked",false);
    //$('.selectpicker').selectpicker('refresh');
    $('input:checkbox').removeAttr('checked');
    $('input[type=checkbox]').attr('checked',false);
    $('label#label_check1').removeClass('c_onn');
    $('label#label_check1').addClass('c_off');
    //$("#bday").select2("val", "");
    /*$('#bday').val('').trigger('change');
    $('#bmonth').val('').('change');
    $('#byear').val('').('change');*/

    var forgotForm = $( "#forgotForm" ).validate();
    forgotForm.resetForm();
    $(':input','#forgotForm')
    .not(':button, :submit, :reset, :hidden')
    .val('')
    .removeAttr('checked')
    .removeAttr('selected');
    $('#forgot_result').removeClass('alert-success alert fade in');
    $('#forgot_result').html('');
    var create_event = $( "#create_event" ).validate();
    
    if(create_event) {
        create_event.resetForm();
    }

    $(':input','#create_event')
    .not(':button, :submit, :reset, :hidden')
    .val('')
    .removeAttr('checked')
    .removeAttr('selected');

    $('#type_error').html('');
    $('#event_type').selectpicker('refresh');
    $('#dob_er').hide();
    $('#gen_er').hide();
    $('#agree').hide();
    $('#forgot_result').hide();
}

// BEGIN EVENT DETAILS TOGGLE SCRIPT  Pages : product detail,vendor profile,event detail page.
$('.collapse').on('shown.bs.collapse', function(){
    $(this).parent().find(".glyphicon-menu-right").removeClass("glyphicon-menu-right").addClass("glyphicon-menu-down");
}).on('hidden.bs.collapse', function(){
    $(this).parent().find(".glyphicon-menu-down").removeClass("glyphicon-menu-down").addClass("glyphicon-menu-right");
});
/* END EVENT DETAILS TOGGLE SCRIPT */

/*Onkey press search close script 19-10-2015-start-->*/
function show_close(){
    if($('#search-terms').val()!='')
    {

        $('#search-close').addClass('visible');
    }
    else
    {
        $('#search-close').removeClass('visible');
    }
}

function show_close3(){
    if($('#search_input_header').val()!='')
    {

        $('#search-close1').addClass('visible');
    }
}
/*clear search 16/dec/2015 */
/*$('.icon-search_clear').click(function(){
$('#search-close1').removeClass('visible');
});*//**/
/*Onkey press search close script end 19-10-2015*/

/*open-search part add class 23-11-2015*/
$(document).delegate('.search-lbl-mobile', 'click', function()
{
    if($('.mobile-menu').hasClass('open-search-menu'))
    {
        $("#mobile-sid").removeClass('open-search-menu');
    } else {
        $("#mobile-sid").addClass('open-search-menu');
        $('.mobile-menu #search_form #search-terms2').focus();
    }
});

$(document).delegate('#search-close', 'click', function(){
    $( "#mobile_search_list" ).html('');
    $( "#mobile_search_fail" ).html('');
    $( "#search-terms2" ).val('');
});

$(document).delegate('.js-search-cancel,#search_list ul li a', 'click', function()
{
    $('.open-search-menu').removeClass('open-search-menu');
    $( "#mobile_search_list" ).html('');
    $( "#mobile_search_fail" ).html('');
    $( "#search-terms2" ).val('');
});

function Searchinvitee(event_id)
{
    var search_value;
    if($('#inviteesearch').val() !="")
    {
        search_value = $('#inviteesearch').val();
    }
    else if( $('#inviteesearch1').val()!="")
    {
        search_value = $('#inviteesearch1').val();
    }

    var path = eventinvitees_url;

    $.ajax({
        url:path,
        type:'POST',
        data:{event_id:event_id,search_val:search_value},
        success:function(data)
        {
            $('.add_contact_table').html(data);
        }
    });
}

$(document).delegate('.btn_add_to_event', 'click', function(e) {
    addevent($(this).parents('.item').attr('data-id'));    
    e.preventDefault();
    e.stopPropagation();    
});

/* BEGIN ADD EVENT */
function addevent(item_id)
{
    $.ajax({
        type:'POST',
        url: eventinvitees_add_event_url,
        data:{'item_id':item_id},
        success:function(data)
        {
            $('#addevent').html(data);
            $('#eventlist'+item_id).selectpicker('refresh');
            $('#add_to_event').modal('show');

        }
    });
}
/* END ADD EVENT */

function default_session_data(x)
{
    $('#login_success').modal('show');

    if(x==1)
    {
        $('#success').html('<span class="sucess_close">&nbsp;</span><span class="msg-success" style="margin-top: 5px; width: 320px; float: left; text-align: left;">'+login_success_msg+'</span>');
        window.setTimeout(function() {$('#login_success').modal('hide');}, 2000);
    }
    else
    {
        login_update_msg = you_are_login_and + '"'+x+'"' + add_to_favourite_successfully;
        $('#success').html('<span class="sucess_close">&nbsp;</span><span class="msg-success" style="margin-top: 5px; width: 320px; float: left; text-align: left;">'+login_update_msg+' </span>');
    }
    window.setTimeout(function() {
        $('#login_success').modal('hide');
    }, 2000);
}

function display_event_modal()
{
    $('#EventModal').modal('show');
}

function addevent1(item_id)
{
    $.ajax({
        type:'POST',
        url: product_add_event_url,
        data:{'item_id':item_id},
        success:function(data)
        {
            $('#addevent').html(data);
            $('#eventlist'+item_id).selectpicker('refresh');
            $('#add_to_event').modal('show');
        }
    });
}
/* END ADD EVENT */

function show_activate_modal_true()
{
    $('#login_activate').modal('show');
    setTimeout(function(){
        window.location.replace(home_url);
    }, 1000);

    /* $('#success').text('Your Account Activated successfully!'); */
    $("#reload_page1 ,#reload_page2").click(function () {
        /* window.location.replace("<?= Yii::$app->homeUrl; ?>"); */
    });
}

/* BEGIN RESPONSIVE MENU SINGLE CLICK TO OPEN SUB MENUS
$(document).on('touchstart', function() {
    documentClick = true;
});

$(document).on('touchmove', function() {
    documentClick = false;
});

$(document).on('click touchend', function(event) {
    if (event.type == "click") documentClick = true;
    if (documentClick){
        //doStuff();
    }
});
/* END RESPONSIVE MENU SINGLE CLICK TO OPEN SUB MENUS */


// Registration Completed start
function show_register_modal_true()
{
    $('#login_success').modal('show');
    $('#success').html('<span class="sucess_close">&nbsp;</span><span class="msg-success" style="margin-top: 5px; width: 320px; float: left; text-align: left;">'+reg_success_msg+'</span>');
    
    window.setTimeout(function() {
        $('#login_success').modal('hide');
    }, 2000);
}

// Registration Completed start
function show_event_modal_true($message)
{
    $('#login_success').modal('show');
    
    $('#success').html('<span class="sucess_close">&nbsp;</span><span class="msg-success" style="margin-top: 5px; width: 320px; float: left; text-align: left;">'+$message+' </span>');
    
    window.setTimeout(function() {
        $('#login_success').modal('hide');
    }, 2000);
}

function show_password_reset_modal_true()
{
    $('#login_success').modal('show');
    $('#success').html('<span class="sucess_close">&nbsp;</span><span class="msg-success" style="margin-top: 5px; width: 320px; float: left; text-align: left;">'+pwd_success_msg+'</span>');
    window.setTimeout(function() {$('#login_success').modal('hide');}, 2000);
}

function setupLabel() {
    if($('.label_check input').length ) {

        $('.label_check').each(function () {

            $(this).removeClass('c_on');
            if($(this).parents('.panel-body').find('label.c_on').length == 0){
                $(this).parents('.panel-default').find('a.filter-clear').css('display','none');
            }else{
                $(this).parents('.panel-default').find('a.filter-clear').css('display','inline-block');
            }

        });
        $('.label_check input:checked').each(function () {
            $(this).parent('label').addClass('c_on');
            if($(this).parents('.panel-body').find('label.c_on').length == 0){
                $(this).parents('.panel-default').find('a.filter-clear').css('display','none');
            }else{
                $(this).parents('.panel-default').find('a.filter-clear').css('display','inline-block');
            }
        });
    }

    if ($('.label_radio input').length) {

        $('.label_radio').each(function () {
            $(this).removeClass('r_on');
        });

        $('.label_radio input:checked').each(function () {
            $(this).parent('label').addClass('r_on');
        });
    }
}

$('.listing_right .events_listing ul li:nth-child(4n)').addClass('margin-rightnone');
$('.thing_items li:nth-child(8n)').addClass('margin-rightnone');

$(document).delegate('.label_check, .label_radio', 'click', function () {
    setupLabel();
});

$(document).delegate('.custom-select', 'change', function () {
    var selectedOption = $(this).find(':selected').text();
    $(this).next('.holder').text(selectedOption);
}).trigger('change');

//nav content js
var menu = $('.category_listing_nav')
menu.hide();

$('.collapse').on('shown.bs.collapse', function(){
    $(this).parent().find('.plus_acc').removeClass('plus_acc').addClass('minus_acc');
}).on('hidden.bs.collapse', function(){
    $(this).parent().find('.minus_acc').removeClass('minus_acc').addClass('plus_acc');
});

$('#open_search').click(function () {
    $('#open_search').toggleClass('active');
});

if (($('.test_scroll').length)>0) {
    (function ($) {
        $(window).load(function () {
            $('.test_scroll').mCustomScrollbar({
                theme: 'rounded-dark',
                mouseWheelPixels: 50,
                scrollInertia: 0
            });
        });
    })($);
}

/* BEGIN filter item list */
var csrfToken = $('meta[name=\"csrf-token\"]').attr('content');
var url = window.location.href;     // Returns full URL

/* BEGIN GET SLUG FROM URL */
var url = window.location.href;
var newUrl = url.substring(0, url.indexOf('?'));
var slug;

if(newUrl !='')
{
    slug = newUrl.substring(newUrl.lastIndexOf('/') + 1);
}
else
{
    slug = url.substring(url.lastIndexOf('/') + 1);
}
/* END GET SLUG FROM URL */

var limit = 1;

$(document).delegate('button#loadmore', 'click', function(event) {

    $('#planloader').show();

    setupLabel();
    limit = limit+4;
    var path = load_more_items;

    $.ajax({
        type:'POST',
        url:path,
        data:{ limit:limit, slug:slug, _csrf : csrfToken},
        success:function(data){
            $('.events_listing ul li:last-child').after(data);
            // Every fourth li change margin
            $('#planloader').hide();
        }
    });
});


// load category and reload the page 
$(document).delegate('#main-category', 'change', function() {

    $slug = $(this).find('option:selected').val();
    
    //load child categories 
    $.get('browse/sub-category-filter?slug=' + $slug, function(html) {
        $('.sub-category-wrapper').html(html);

        //load items from selected cat 
        filter($slug);
    });
});


var loadmore = 0;

function filter(slug = '') {
    var ajax_data = {},
        date = '',
        event_time = '',
        areas = 'All',
        search = '',
        category_name = '',
        theme_name = '',
        price_val = '',
        vendor_name = '',
        url_path = '',
        url = window.location.href;

    url_path += '?filter=1'; //intializing url_path

    $('#planloader').show();

    $('.listing_right').css({'opacity' : '0.5', 'position' : 'relative'});

    var arr_category_id = $('input[name=category]:checked').map(function() {
        return this.value;
    }).get();

    if (($('input[name=themes]').length)>0) {
        var theme_name = $('input[name=themes]:checked').map(function () {
            return this.value;
        }).get();
    }

    if (($('#theme_filter').length)>0) {
        var theme_name = $('#theme_filter option:selected').map(function () {
            return this.value;
        }).get();
    }
    
    if (($('input[name=vendor]').length)>0) {
        var vendor_name = $('input[name=vendor]:checked').map(function () {
            return this.value;
        }).get();
    }

    if (($('.price_slider').length)>0) {
        var price_val = $('.price_slider').val().replace(',', '-');
    }

    if ($('#delivery_date_2').length>0) {
        var date = $('#delivery_date_2').val();
    }

    if ($('#event_time').length>0) {
        event_time = $('#event_time').val();
    }

    if ($('#delivery_area_filter').length>0) {
        var areas = $('#delivery_area_filter').val();
    }

    if (!slug && typeof product_slug !== "undefined") {
        slug = product_slug;
    }else{
        product_slug = slug;//update slug on top cat change
    }

    if (typeof search_keyword !== "undefined") {
        search = search_keyword;
    }

    // for theme page
    if (typeof theme !== "undefined") {
        theme_name = theme;
    }

    // for vendor profile page
    if (typeof vendor_profile !== "undefined") {
        vendor_name = vendor_profile;
    }


    //if (slug != '') {
    //    url_path += '&slug='+slug;
    //    ajax_data.slug = slug;
    //}

    if (search != '') {
        url_path += '&search=' + search;
        ajax_data.search = search;
    }

    if (arr_category_id != '') {
        url_path += '&category[]=' + arr_category_id;
        ajax_data.category = arr_category_id;
    }

    if(theme_name != '' && current_page != 'theme') {
        url_path += '&themes=' + theme_name;
        ajax_data.themes = theme_name;
    }

    if(vendor_name != '' && current_page != 'vendor') {
        url_path += '&vendor=' + vendor_name;
        ajax_data.vendor = vendor_name;
    }

    if (price_val != '') {
        url_path += '&price=' + price_val;
        ajax_data.price = price_val;
    }

    if(date != '') {
        url_path += '&date=' + date;
        ajax_data.date = date;
    }

    if(event_time != '') {
        url_path += '&event_time=' + event_time;
        ajax_data.event_time = event_time;
    }
    
    if(areas != '') {
        url_path += '&location=' + areas;
        ajax_data.location = areas;
    }

    var path = load_items;

    var url = path +'/'+slug+'?filter=1';

    if (current_page == 'search') {
        var url = path + '?filter=1';
        path = path + '?' + $.param(ajax_data);
    } else if (current_page == 'browse') {
        path = path +'/'+slug+'?filter=1&'+$.param(ajax_data);
    } else if (current_page == 'theme') {
        var url = path +'/'+slug+'/'+theme_name+'?filter=1';
        path = path +'/'+slug+'/'+theme_name+'?filter=1&'+$.param(ajax_data);
    } else if (current_page == 'vendor') {
        var url = path +'/'+vendor_name+'?slug='+slug+'&filter=1';
        path = path +'/'+vendor_name+'?slug='+slug+'&filter=1&'+$.param(ajax_data);
    }

    $.ajax({
        type:'GET',
        url:url,
        data:ajax_data,
        success:function(data){
            //window.history.pushState('test', 'Title', $.param(ajax_data));
            window.history.pushState(null, null, path);
            $('.listing_right').html(data);
            $('#planloader').hide();
            $('.listing_right').css({'opacity' : '1.0', 'position' : 'relative'});
            imgError(); // to initialize after result comes
        }
    }).done(function(){
        $('.add_to_favourite').click(function() {
            $('#loading_img_list').show();
            $('#loading_img_list').html('<img id=\"loading-image\" src=\"".giflink."\" alt=\"Loading...\" />');

            item_id = $(this).attr('id');
            $element = $(this);
            $($element).parent().toggleClass('faverited_icons');
            var _csrf = $('#_csrf').val();
            $.ajax({
                url : add_to_wishlist_url,
                type : 'post',
                data : 'item_id=' + item_id + '&_csrf='+_csrf,
                success:function(data) {
                    $('#heart_fave').html(data);
                    $('#loading_img_list').hide();
                }
            });
        });
    });
}//end of function

function imgError() {
    $(".events_items img, .owl-item img").error(function () {
        $(this).unbind("error").attr("src", item_default_image);
    });
}

/* =========================== Event Actions ===================================*/

function addinvitees()
{
    var action = '';

    if($('#invitees_name').val() =='')
    {
        alert('Enter invitees name.');
        return false;
    }

    if($('#invitees_email').val() =='')
    {
        alert('Enter invitees email');
        return false;

    } else if(isEmail($('#invitees_email').val()) == false ){
        alert('Enter valid email');
        return false;
    }

    var act = '';
    if($('#invitees_id').val() =='')
    {
        action = 'addinvitees';
        act = 'new';
    }
    else{
        action = 'updateinvitees';
        act = 'updated';
    }

    var path = add_invite;

    $.ajax({
        type :'POST',
        url:path,
        data: {
            invitees_id: $('#invitees_id').val(),
            event_id: event_id,
            name: $('#invitees_name').val(),
            email:$('#invitees_email').val(),
            phone_number: $('#invitees_phone').val(),
            event_name:event_name,
            action:act
        },
        success:function(data)
        {
            if(data==2)
            {
                $('.invite_error').show();
            }
            else if(data==1)
            {
                $('#login_success').modal('show');
                $('#success').html('<span class=\"sucess_close\">&nbsp;</span><span class=\"msg-success\">Success! Invitee '+act+' successfully!</span>');
                window.setTimeout(function(){location.reload()},2000)
            }
            // $.pjax.reload({container:'#itemtype'});
        }
    });
}

function inviteeDetail(invitee_id)
{
    $.ajax({
        url: invite_detail,
        type : 'POST',
        data :{id:invitee_id},
        dataType:'JSON',
        success : function(data)
        {
            $('#invitees_id').val(data.invitees_id);
            $('#invitees_name').val(data.name);
            $('#invitees_email').val(data.email);
            $('#invitees_phone').val(data.phone_number);
            $('#submit').val('Update');
        }

    });
    return false;
}

/* =========================== Event Actions ===================================*/


$(document).delegate('#open-filter, #close-search-div', 'click', function(){
    $("#top_header").toggleClass('mobile-search-enable');
    $(".mobile-view-form-popup").slideToggle(500,"linear", function () {
        // actions to do when animation finish
    });
});

$(function() {

    $('#dp3,#delivery_date').datepicker({
        format: 'dd-mm-yyyy',
        startDate:'today',
        autoclose:true,
    });

    $('#delivery_date_2').datepicker({
        format: 'dd-mm-yyyy',
        startDate:'today',
        autoclose:true,
    }).on("changeDate", function (e) {
        filter();
        $(this).parents('.panel-default').find('a.filter-clear').css('display','inline-block');
    });    

    if(session_register > 0) {
        window.onload = show_register_modal_true();
    }

    if(session_key == 1){
        window.onload = show_activate_modal_true();
    }

    if(session_key == 2){
        window.onload = show_password_reset_modal_true();
    }

    if(session_default == 1) {
        window.onload=default_session_data(1);
    }

    if(session_favourite_status) {
        window.onload = default_session_data(session_favourite_status);
    }

    if(session_create_event > 0) {
        window.onload = display_event_modal();
    }

    //if((!empty($reset_password))&&($reset_password!=0)){
    if(session_reset_password){
        function display_reset_password_modal()
        {
            var x = session_reset_password;
            if(x!=1){
                $('#resetPwdModal').modal('show');
                $('#userid1').val(session_reset_password);
            }else{
                $('#login_success').modal('show');
                $('#success').html('<span class="sucess_close">&nbsp;</span><span class="msg-success" style="margin-top: 5px; width: 320px; float: left; text-align: left;">'+pwd_fail_msg+'</span>');
                window.setTimeout(function() {
                    $('#login_success').modal('hide');
                }, 2000);
            }
        }
        window.onload=display_reset_password_modal;
    }//END if(session_reset_password){

    if(session_event_status>0){
        var x = session_event_status;
        window.onload=addevent1(x);
    }

    //display placeholder on image error on vendor item list 
    imgError();

    //top menu hover  

    $(".desktop-menu .dropdown").hover(function () {
        $('.dropdown-menu', this).stop( true, true ).slideDown('fast');
        $(this).toggleClass('open');
    },
    function () {
        $('.dropdown-menu', this).stop( true, true ).slideUp('fast');
        $(this).toggleClass('open');
    });

    //trigger filter changes in item listing 
    setupLabel();

    $(document).delegate('.label_check input', 'change', function() {
        filter();
    });

    if (($('#delivery_area_filter').length)>0) {
        $(document).delegate('#delivery_area_filter', 'change', function() {
            filter();
        });
    }

    //package detail page js 

    $('.package_description select[name="event_id"]').selectpicker();

    $('.package_description .btn-add-to-event').click(function() {
        $.ajax({
            type:'POST',
            url: $('#add_to_event_url').val(),
            data:{ 
                'package_id' : $('#package_id').val(),
                'event_id' : $('select[name="event_id"]').val() 
            },
            success:function(json)
            {
                if(json.error)
                {
                    $html  = '<div class="alert alert-danger">';
                    $html += json.error;
                    $html += '<button class="close" data-dismiss="alert">x</button>';
                    $html += '</div>';  
                    $('.alert_wrapper').html($html);

                    $('html, body').animate({ scrollTop: 0 }, 'slow');
                }
                else
                {
                    location = json.event_url;    
                }                
            }
        });
    });
});


/* BEGIN ADD TO EVENT */
$(document).delegate('#modal_event_from_package #create_event_button', 'click', function(){
   
    var i=0;
    //var element = $(this).parents('form');
    var event_type=$('#modal_event_from_package  #event_type').val();
    
    if(event_type==''){
        $('#modal_event_from_package #type_error').html(kindly_select_event_type);
        i=1;
    }else{
        i=0;
        $('#modal_event_from_package #type_error').hide();
    }

    if($('#modal_event_from_package form').valid()&& (i==0))
    {
        $('#modal_event_from_package #event_loader').show();

        var event_date = $('#modal_event_from_package #event_date').val();
        var item_id = $('#modal_event_from_package #item_id').val();
        var event_name = $('#modal_event_from_package #event_name').val();
        var no_of_guests = $('#modal_event_from_package #no_of_guests').val();
        
        var _csrf = $('#_csrf').val();

        $.ajax({
            url: $('#package_event_url').val(),
            type:"post",
            data:"event_date="+event_date+"&package_id="+$('#package_id').val()+"&event_name="+event_name+"&event_type="+event_type+"&_csrf="+_csrf + "&no_of_guests=" + no_of_guests,
            success:function(json)
            {
                if(json.success)
                {
                    $('#modal_event_from_package').modal('hide');
                    location = json.event_url;
                }

                if(json.error)
                {
                    $('#modal_event_from_package #event_loader').hide();
                    $('#modal_event_from_package #eventresult').addClass('alert-success alert fade in');
                    $('#modal_event_from_package #eventresult').html(event_exist+'<a id="boxclose" class="boxclose" onclick="MyEventFunction();"></a>').animate({ color: "red" }).show();
                }
            }
        });
    }
});
/* END ADD TO EVENT */



//Register save function ajax

$(document).delegate('#vendor_send', 'click', function()
{

    $('.errors,.success-block').html('');

    if ($('#name_of_business').val() == '') {
        $('.vendor_name_of_business.errors').html('Please ente name of business');
        return false;
    }

    if ($('#contact_person').val() == '') {
        $('.vendor_contact_person.errors').html('Please enter contact person');
        return false;
    }

    $(this).attr('disabled', 'disabled');
    $('#vendor_send').html('Please wait...');
    $('#vendor_send').val('Please wait...');
    var form = $("#vendor_request_form");
    $.ajax({
        url: vendor_request_url,
        async:false,
        type:"post",
        data:form.serialize(),
        success:function(data)
        {
            $('#vendor_send').removeAttr('disabled');
            $('#vendor_send').html('Send');
            if (data==1) {
                $('#success-block').html('Request sent successfully. Admin will contact you soon.');
            } else {
                $('#success-block').html('Error while sending request. Please try after some time.');
            }
            window.setTimeout(function () {
                $('#vendor_request_form')[0].reset();
                $('.errors,.success-block').html('');
                $('#vendor-sign-up').modal('hide');
            }, 2000);
            return false;
        }
    });
});
