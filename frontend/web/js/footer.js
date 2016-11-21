function validateEmail(email) {
    // http://stackoverflow.com/a/46181/11236
    var re = /^[a-zA-Z0-9!#$%&'*+\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&'*+\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?$/;
    return re.test(email);
}

jQuery(window).resize(function(){

    //make dropdown box fullwidth
    jQuery('.mega-dropdown-menu').css('width', jQuery(window).width());

    //set left position for dropdown ment
    $left = jQuery('.nav.navbar-nav').offset().left;

    if(jQuery('.plan_menu').hasClass('rtl')) {
        jQuery('.plan_menu').css('left', '-' + ($left + 222.297) + 'px');
    }else{
        jQuery('.plan_menu').css('left', '-' + ($left) + 'px');
    }
    
    if(jQuery(window).width() <= 990) {
        jQuery('#home_slider').css('padding-top', $('#top_header').height() + 'px');
    }
});

jQuery(document).ready(function () {

    if(session_show_login_modal == 1) {
        jQuery('#myModal').modal('show');
    }

    /*home slider new start*/
    var owl = jQuery("#home-banner-slider");

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

    var owl = jQuery("#feature-group-slider,#similar-products-slider");

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

    jQuery(window).trigger('resize');

    jQuery('#phone,#reg_email').bind("paste",function(e) {
        e.preventDefault();
    });

    jQuery("#search_list_fail1").hide();
    /*Popup modal script start*/
    jQuery('.new_btn').click(function(e) {
        jQuery('#myModal').modal('hide');
    });

    /* mobile hover menu start */
    jQuery(".mobile-menu .dropdown").click(function () {
        jQuery(this).addClass('open');
    },
    function () {
        jQuery(this).removeClass('open');
    });
    /* mobile hover menu end */

    /* web hover menu start */
    jQuery(".desktop-menu .dropdown").hover(function () {
        jQuery('.dropdown-menu1', this).stop(true, true).slideDown("fast");
        jQuery(this).addClass('open');
    },
    function () {
        jQuery('.dropdown-menu1', this).stop(true, true).slideUp("fast");
        jQuery(this).removeClass('open');
    });
    /* web hover menu end */

    /* registration form checkbox  start  */
    jQuery('label#label_check1').click(function()
    {
        if (jQuery('#agree_terms').attr('checked')) {
            jQuery("#agree_terms").attr("checked",false);
            jQuery("#agree_terms").val('0');
            jQuery('#agree').html(tick_the_terms_of_services_and_privacy_policy);
            jQuery('label#label_check1').removeClass('c_onn');
            jQuery('label#label_check1').addClass('c_off');
        } else {
            jQuery("#agree_terms").attr("checked",true);
            jQuery("#agree_terms").val('1');
            jQuery("#agree").html('');
            jQuery('label#label_check1').removeClass('c_off');
            jQuery('label#label_check1').addClass('c_onn');
        }
    });
    /* registration form checkbox  end */

    /*Responsive menu script start*/
    function isTouchDevice() {
        return 'ontouchstart' in window
    };

    if( isTouchDevice() ) {
        /*
        jQuery("body").swipe({
            swipe:function(event, direction, distance, duration, fingerCount, fingerData) {
                if ( direction == 'left' ) { jQuery('html').removeClass('ma5-menu-active');}
                if ( direction == 'right' ) { jQuery('html').addClass('ma5-menu-active');}
            },
            allowPageScroll: "vertical"
        });
        */
    };
    /*Responsive menu script end*/
});//end document ready

jQuery('#dp3,#delivery_date').datepicker({
    format: 'dd-mm-yyyy',
    startDate:'today',
    autoclose:true,
});

jQuery('#delivery_date_2').datepicker({
    format: 'dd-mm-yyyy',
    startDate:'today',
    autoclose:true,
}).on("changeDate", function (e) {
    filter();
    jQuery(this).parents('.panel-default').find('a.filter-clear').css('display','inline-block');
});



// Edit Cart Section

function deliveryTimeSlotCart(date){
    var myDate = new Date()
    time = myDate.getHours()+':'+myDate.getMinutes()+':'+myDate.getSeconds(),
        currentDate = myDate.getDate()+ '-' +("0" + (myDate.getMonth() + 1)).slice(-2)+ '-' +myDate.getFullYear();
    jQuery.ajax({
        type: 'POST',
        url: getdeliverytimeslot_url,
        data: { vendor_id : jQuery('#vendor_id').val(), sel_date: date,time:time,currentDate:currentDate},
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

function productAvailabilityCart(date) {
    jQuery.ajax({
        type: 'POST',
        url: product_availability,
        data: $('#form-update-cart').serialize(),
        success: function (data)
        {
            if (jQuery.trim(data) != '1') {
                $('.timeslot_id_div').show();
                $('.timeslot_id_div .text').html(data);
                $('.timeslot_id_select').hide();
                $('#timeslot_id').html('');
                return false;
            }else{
                deliveryTimeSlotCart(date);
            }
        }
    });
}


jQuery(function() {
    jQuery('#update-cart-modal').on('shown.bs.modal', function() {
        jQuery('#delivery_date3').datepicker({
            format: 'dd-mm-yyyy',
            startDate:'today',
            autoclose:true,
            container: '#update-cart-modal modal-body'
        }).on("changeDate", function(e) {
            $('.error.cart_delivery_date,.cart_quantity').html('');
            $('#quantity').val(' ');
            productAvailabilityCart(jQuery(this).val());
        });
    });
});

jQuery('body').on('click','.btn-cart-change',function(){
    jQuery.ajax({
        type: 'POST',
        url: update_cart_url,
        data: jQuery('#form-update-cart').serialize(),
        success: function (data)
        {
            jQuery('#form-update-cart .error').html('');

            if(data['success']) {
                location = location;
            } else {

                $.each(data['errors'], function(index, errors) {
                    $.each(errors, function() {
                        jQuery('#form-update-cart .error.' + index).append('<p>' + this + '</p>');
                    });
                });

            }
        }
    });
    console.log(jQuery('#update-cart').serialize());
    return false;
});

// Edit Cart Section



// megamenu script end

// plan last:child script
jQuery(document).ready(function() {
    jQuery('.plan_sections ul li:nth-child(3n)').addClass("margin-rightnone");
});
// plan last:child script end -->

// FeatureD Products script  -->
jQuery(window).load(function () {

    /* client say slider start*/
    jQuery('.flexslider2').flexslider({
        animation: "",
        controlNav: false,
        animationLoop: true,
        slideshow: false,
        itemWidth: 217,
        itemMargin: 0,
        pauseOnHover: true,
        slideshowSpeed: 3000,
        move: 1,
        asNavFor: '#slider',
        minItems: 1,
        maxItems: 5,
        autoPlay:false,
        start: function (slider) {
            jQuery('body').removeClass('loading');
        }
    });
    /* client say slider end*/

});

jQuery('.accor-link').click(function()
{
    (jQuery(this).hasClass('accor-link-min'))?jQuery(this).removeClass('accor-link-min'):jQuery(this).addClass('accor-link-min');
});

jQuery('.accor-link-min').click(function()
{
    jQuery('.accor-link-min').addClass('accor-link');
    jQuery('.accor-link-min').removeClass('accor-link-min');
});

/* Forgot password completed start  */
jQuery("#reset_button").click( function()
{
    resetpwdcheck();
});

jQuery('#resetForm input ').keydown(function(e) {
    if (e.keyCode == 13)
    {
        resetpwdcheck();
        //jQuery('#login_msg').hide();
    }
});

function resetpwdcheck()
{
    var passwordlength=jQuery('#new_password').val();
    var x=(passwordlength.length);
    var password=jQuery('#new_password').val();
    var userid=jQuery('#userid1').val();
    var conPassword=jQuery('#confirm_password').val();

    if((jQuery('#resetForm').valid()))
    {}else{return false;}
    var k=0;
    if(x<6)
    {
        jQuery('#reset_pwd_result').show();
        jQuery('#reset_pwd_result').html(password_should_contain_minimum_six_letters);
        return false;
    }
    if(password==conPassword)
    {
        jQuery('#reset_pwd_result').hide();
        k=1;
    }
    else
    {
        jQuery('#reset_pwd_result').show();
        jQuery('#reset_pwd_result').html(confirm_password_should_be_equal_to_password);
        return false;
    }

    if((jQuery('#resetForm').valid()) && (k==1))
    {
        jQuery.ajax({
            url: password_reset_link,
            type:"POST",
            data:"id="+userid+"&password="+password+"&_csrf="+_csrf,
            async: false,
            success:function(data)
            {
                console.log(data);
                if(data==1)
                {
                    jQuery('#reset_loader').hide();
                    jQuery('#resetPwdModal').modal('hide');
                    jQuery('#login_success').modal('show');
                    jQuery('#success').html('<span class="sucess_close">&nbsp;</span><span class="msg-success" style="margin-top: 5px; width: 320px; float: left; text-align: left;">'+pwd_reset_msg+'</span>');
                    window.setTimeout(function() {jQuery('#login_success').modal('hide');}, 2000);
                    location.reload();
                    //window.setTimeout(function() {jQuery('#login_success').modal('hide');}, 2000);
                    all_form_reset();
                }
            }
        });
    }
}
/* Forgot password completed end */

jQuery("#login_button").click( function()
{
    logincheck();
});

jQuery('#loginForm input ').keydown(function(e) {
    if (e.keyCode == 13)
    {  logincheck();
        //jQuery('#login_msg').hide();
    }
});


function logincheck()
{
    jQuery.noConflict();
    if(jQuery('#loginForm').valid())
    {
        jQuery('#login_loader').show();
        jQuery('#login_button').html('Please Wait...');
        jQuery('#login_button').attr('disabled',true);
        var email=jQuery('#email').val();
        var password=jQuery('#password').val();
        var _csrf=jQuery('#_csrf').val();
        if(validateEmail(email) == true){
            jQuery.ajax({
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
                        jQuery('#login_msg').addClass('alert-warning alert fade in');
                        jQuery('#login_msg').html(not_activate_msg+'<a id="boxclose" name="boxclose" class="boxclose" onclick="MyFunction();"></a>').animate({ color: "red" }).show();
                        jQuery('#login_forget').show();
                        jQuery('#loader').hide();
                    }
                    else if(status==-2)
                    {
                        console.log(user_blocked_msg);
                        jQuery('#login_msg').addClass('alert-warning alert fade in');
                        jQuery('#login_msg').html(user_blocked_msg+'<a id="boxclose" name="boxclose" class="boxclose" onclick="MyFunction();"></a>').animate({ color: "red" }).show();
                    }
                    else if(status==-3)
                    {
                        console.log(email_not_exist);
                        jQuery('#login_msg').addClass('alert-warning alert fade in');
                        jQuery('#login_msg').html(email_not_exist+'<a id="boxclose" class="boxclose" onclick="MyFunction();"></a>').animate({ color: "red" }).show();
                    }
                    else if(status==-4)
                    {
                        console.log(email_not_match);
                        jQuery('#login_msg').addClass('alert-warning alert fade in');
                        jQuery('#login_msg').html(email_not_match+'<a id="boxclose" class="boxclose" onclick="MyFunction();"></a>').animate({ color: "red" }).show();
                    }
                    else if(status==1)
                    {
                        jQuery('#myModal').modal('hide');

                        if(favourite_status>0){
                            jQuery('#login_success').modal('show');
                            var success_fav_added = success_fav_added1 + '"' + item_name + '"' + success_fav_added2;
                            jQuery('#success').html('<span class="sucess_close">&nbsp;</span><span class="msg-success" style="margin-top: 5px; width: 320px; float: left; text-align: left;"> '+success_fav_added+' </span>');
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
                    jQuery('#login_loader').hide();
                    jQuery('#login_button').html('Login');
                    jQuery('#login_button').removeAttr('disabled');

                },
                error:function(data)
                {

                }
            });
        }
        else
        {
            jQuery('#login_loader').hide();
            jQuery('#login_button').html('Login');
            jQuery('#login_button').removeAttr('disabled');
            //jQuery('#loginErrorMsg').addClass('alert-failure alert fade in');
            jQuery('#login_msg').addClass('alert-warning alert fade in');
            jQuery('#login_msg').html("Enter Valid Email ID"+'<a id="boxclose" class="boxclose" onclick="MyFunction();"></a>').animate({ color: "red" }).show();

        }
    }
}

//Register save function ajax
jQuery("#register").click(function()
{
    jQuery.noConflict();
    var gender=jQuery('#gender').val();
    var bday=jQuery('#bday').val();
    var bmonth=jQuery('#bmonth').val();
    var byear=jQuery('#byear').val();
    var x=jQuery("#reg_email").val();
    var password=jQuery('#userpassword').val();
    var password_length=password.length;
    var conPassword=jQuery('#conpassword').val();
    var _csrf=jQuery('#_csrf1').val();

    var i=j=k=l=m=z=0;
    var c=jQuery('input[type=checkbox]:checked').length;

    if(jQuery('input[type=checkbox]:checked').length == 0)
    {
        if(jQuery('#agree_terms').val()==0)
        {
            jQuery('#agree').show();
            jQuery('#agree').html(tick_the_terms_of_services_and_privacy_policy);
        }

    }else
    {
        jQuery('#agree').hide();
        jQuery('#agree').html('');
        m=1;
    }
    if(password_length>=6){
        z=1;
    }else{
        z=0;
    }
    if((password==conPassword) && (password!=''))
    {
        k=1;
    }
    else{
        K=0;
    }
    if((k==1)&&(z==1))
    {
        jQuery('#con_pass').hide();
        jQuery('#con_pass').html('');
    }
    else{
        jQuery('#con_pass').show();
        jQuery('#con_pass').html(password_and_confirm_password_should_be_minimum_six_letters_and_same);
    }

    if(gender==0)
    {
        jQuery('#gen_er').show();
        jQuery('#gen_er').html(the_field_is_required);
    }
    else
    {
        jQuery('#gen_er').hide();
        i=1;
    }
    if(bday == '' || bmonth == '' || byear == '')
    {
        jQuery('#dob_er').show();
        jQuery('#dob_er').text(the_field_is_required);
    }
    else
    {
        jQuery('#dob_er').hide();
        j=1;
    }

    if(validateEmail(x) == true){

        jQuery.ajax({

            url: email_check,
            type:"post",
            //data:"email="+x+"&_csrf="+_csrf,
            data:"email="+x,
            async: false,
            success:function(data)
            {
                if(data==1)
                {
                    jQuery("#customer_email").show();
                    jQuery("#customer_email").html(entered_email_id_is_already_exists);
                    l=0;
                    jQuery("#loader1").hide();
                }
                else if(data==0)
                {
                    l=1;
                    jQuery("#customer_email").html('');
                    jQuery("#customer_email").hide();
                    jQuery("#loader1").hide();
                }
            }
        });
    }
    else
    {
        if(x != ''){
            l=0;
            jQuery("#customer_email").show();
            jQuery("#customer_email").html(enter_a_valid_email_id);
        }
    }

    jQuery.noConflict();

    var form = jQuery("#register_form");

    if(form.valid() && i==1 && j==1 && l==1 && m==1 && k==1&& z==1)
    {
        jQuery('#register_loader').show();
        var fname=jQuery('#fname').val();
        var lname=jQuery('#lname').val();
        var reg_email=jQuery('#reg_email').val();
        var phone=jQuery('#phone').val();
        var password=jQuery('#userpassword').val();
        var conPassword=jQuery('#conpassword').val();
        var terms=jQuery('#terms').val();
        var _csrf=jQuery('#_csrf1').val();
        var dob=bday+'-'+bmonth+'-'+byear;
        var customer_name=fname+' '+lname;
        jQuery.ajax({
            url: signup,
            async:false,
            type:"post",
            data:"customer_name="+fname+"&customer_last_name="+lname+"&customer_email="+reg_email+"&bday="+bday+"&bmonth="+bmonth+"&byear="+byear+"&customer_gender="+gender+"&customer_mobile="+phone+"&customer_password="+password+"&confirm_password="+conPassword+"&_csrf="+_csrf,
            success:function(data)
            {
                if(data==0)
                {
                    jQuery('#myModal1').modal('hide');
                    window.setTimeout(function(){location.reload()});
                }
                else if(data==2)
                {
                    jQuery('#myModal1').modal('hide');
                    window.setTimeout(function(){location.reload()})
                }
                else if(data==1)
                {
                    jQuery('#myModal1').modal('hide');

                    window.setTimeout(function(){
                        location.reload()
                    });
                }
            }
        });
    }
});


jQuery("#phone,#invitees_phone").keypress(function (e) {
    //if the letter is not digit then display error and don't type anything
    if (  e.which  != 43   && e.which  != 45 && e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57 )) {
        //display error message
        jQuery(".field-vendor-vendor_contact_number").find('.help-block').html('Contact number digits only+.').animate({ color: "#a94442" }).show().fadeOut(2000);
        return false;
    }
});

/* BEGIN ADD TO EVENT */
jQuery('#create_event_button').click(function(){
    jQuery.noConflict();
    var i=0;
    //var element = jQuery(this).parents('form');
    var event_type=jQuery('#event_type').val();
    if(event_type==''){
        jQuery('#type_error').html(kindly_select_event_type);
        i=1;
    }else{
        i=0;
        jQuery('#type_error').hide();
    }
    if(jQuery('#create_event').valid()&& (i==0))
    {
        jQuery('#event_loader').show();
        var event_date=jQuery('#event_date').val();
        var item_id=jQuery('#item_id').val();
        var event_name=jQuery('#event_name').val();
        var _csrf=jQuery('#_csrf').val();
        if(item_id!=0)
        {
            var item_name=jQuery('.desc_popup_cont h3').text();
        }else{
            var item_name='item ';}
            jQuery.ajax({
                url: create_event,
                type:"post",
                data:"event_date="+event_date+"&item_id="+item_id+"&event_name="+event_name+"&item_name="+item_name+"&event_type="+event_type+"&_csrf="+_csrf,
                success:function(data,slider)
                {
                    jQuery('.directory_slider,.container_eventslider').load('events_slider', function(){
                        jQuery(this).css('background','transparent','important');
                    });
                    /* Hide BG FOR EVENT SLIDER*/
                    if(data==-1)
                    {
                        jQuery('#event_loader').hide();
                        jQuery('#eventresult').addClass('alert-success alert fade in');
                        jQuery('#eventresult').html(event_exist+'<a id="boxclose" class="boxclose" onclick="MyEventFunction();"></a>').animate({ color: "red" }).show();
                    }
                    else if(data==1)
                    {
                        jQuery(".eventErrorMsg").html('');
                        jQuery('#EventModal').modal('hide');
                        window.setTimeout(function(){location.reload()});
                    }
                    else if(data==2)
                    {
                        jQuery('#event_loader').hide();
                        jQuery(".eventErrorMsg").html('');
                        jQuery('#EventModal').modal('hide');
                        window.setTimeout(function(){location.reload()});
                    }
                },
                error:function(data)
                {

                }
            })
    }
});
/* END ADD TO EVENT */

jQuery('#cancel_button').click(function(){
    var create_event = jQuery( "#create_event" ).validate();
    create_event.resetForm();
    jQuery(':input','#create_event')
    .not(':button, :submit, :reset, :hidden')
    jQuery('#type_error').html('');

});

/* BEGIN EDIT TO EVENT */
jQuery(document).on('click',"#update_event_button",function()
{
    var event_type = jQuery('#edit_event_type').val();
    if(event_type==''){
        jQuery('#type_error').html(kindly_select_event_type);
        i=1;
    }else{
        i=0;
        jQuery('#type_error').hide();
    }
    if(jQuery('#update_event').valid()&& (i==0))
    {
        jQuery('#event_loader').show();
        var event_date=jQuery('#edit_event_date').val();
        var item_id=jQuery('#item_id').val();
        var event_name=jQuery('#edit_event_name').val();
        var _csrf=jQuery('#_csrf').val();
        jQuery.ajax({
            url: update_event,
            type:"post",
            data:"event_id="+jQuery('#edit_event_id').val()+"&event_date="+event_date+"&item_id="+item_id+"&event_name="+event_name+"&event_type="+event_type+"&_csrf="+_csrf,
            success:function(data)
            {
                if(data==-1)
                {
                    jQuery('#event_loader').hide();
                    jQuery('#eventresult').addClass('alert-success alert fade in');
                    jQuery('#eventresult').html(event_exists+'<a id="boxclose" class="boxclose" onclick="MyEventFunction();"></a>').animate({ color: "red" }).show();
                    //jQuery(".eventErrorMsg").html('Same event name already exists!');
                    // window.setTimeout(function(){location.reload()},2000);
                }
                else
                {
                    jQuery('#event_loader').hide();
                    jQuery(".eventErrorMsg").html('');

                    jQuery('#EditeventModal').modal('hide');
                    jQuery('#login_success').modal('show');
                    jQuery('#success').html('<span class="sucess_close">&nbsp;</span><span class="msg-success" style="margin-top: 5px; width: 320px; float: left; text-align: left;">'+update_msg+'</span>');
                    setTimeout(function() {$('#login_success').modal('hide');}, 2000);
                    //jQuery('#EventModal').modal('hide');
                    setTimeout(function(){
                        window.location = event_details + '?slug='+data;
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
    jQuery.noConflict();
    jQuery('#myModal').modal('hide');
    jQuery('#forgotPwdModal').modal('hide');
}

function show_login_modal(x)
{
    jQuery.noConflict();
    jQuery('.ma5-menu-active').removeClass('ma5-menu-active');
    jQuery('#register').modal('hide');
    jQuery('#forgotPwdModal').modal('hide');
    jQuery('#event_status').val(x);
}

function show_login_modal_wishlist(x)
{
    jQuery.noConflict();
    jQuery('#register').modal('hide');
    jQuery('#forgotPwdModal').modal('hide');
    jQuery('#favourite_status').val(x);
}

function show_mydata()
{
    jQuery.noConflict();
    jQuery('#event_status').val(0);
    jQuery('#forgotPwdModal').modal('hide');
    jQuery('#myModal1').modal('hide');
}

function create_event_values(){
    jQuery('#item_id').val(0);
}

function forgot_modal()
{
    jQuery.noConflict();
    jQuery('#event_status').val(0);
    jQuery('#myModal').modal('hide');
    jQuery('#Signupmodel').modal('hide');
}

function add_create_event(x)
{
    jQuery('#add_to_event'+x).modal('hide');
    jQuery('#myModal').modal('hide');
    jQuery('#item_id').val(x);
    jQuery('#Signupmodel').modal('hide');
    jQuery('#forgotPwdModal').modal('hide');
}

function add_event_login(x)
{
    jQuery('#event_status').val(x);
}

function show_create_event_form()
{
    jQuery.noConflict();
    jQuery('#forgotPwdModal').modal('hide');
    jQuery('#myModal').modal('hide');
    jQuery('#Signupmodel').modal('hide');
}

var reg_email=jQuery('#reg_email').val();

jQuery(function () {
    jQuery.noConflict();
    jQuery("#reg_email").on('keyup keypress focusout',function () {
        var x=jQuery("#reg_email").val();
        var _csrf=jQuery('#_csrf').val();
        if(validateEmail(x) == true){
            jQuery.ajax({
                url: email_check,
                type:"post",
                data:"email="+x+"&_csrf="+_csrf,
                success:function(data)
                {
                    if(data==1)
                    {
                        jQuery("#customer_email").show();
                        jQuery("#customer_email").html(entered_email_id_is_already_exists);
                    }
                    else if(data==0)
                    {

                        jQuery("#customer_email").html('');
                        jQuery("#customer_email").hide();
                    }
                }
            });
        }
    });
});

jQuery("#forgot_button").click( function()
{
    forgot_password();
});

jQuery('#forgotForm input ').keydown(function(e) {
    if (e.keyCode == 13)
    {
        if(validateEmail(reg_email)==false)
        {jQuery('#forgot_loader').hide();
        jQuery('#forgot_result').addClass('alert-success alert fade in');
        jQuery('#forgot_result').html('Enter registered Email-id!<a id="boxclose" class="boxclose" onclick="ForgotFunction();"></a>').animate({ color: "red" }).show();
        // alert(45);
        forgot_password();
        return false;}
        forgot_password();
    }
});

function forgot_password()
{
    jQuery.noConflict();
    var form = jQuery("#forgotForm");
    var reg_email=jQuery('#forget_email').val();
    var _csrf=jQuery('#_csrf').val();
    i=0;
    if(validateEmail(reg_email)==true)
    {
        jQuery('span.forgotpwd').hide();
        jQuery('#forgot_loader').show();

        jQuery.ajax({
            url: forgot_password_url,
            type:"post",
            async:false,
            data:"email="+reg_email+"&_csrf="+_csrf,
            async: false,
            success:function(data)
            {
                if(data==1)
                {
                    jQuery('#forgot_loader').hide();
                    jQuery('#forgotPwdModal').modal('hide');
                    jQuery('#MyModal').modal('hide');
                    jQuery('#EventModal').modal('hide');
                    jQuery('#login_success').modal('show');
                    jQuery('#success').html('<span class="sucess_close">&nbsp;</span><span class="msg-success" style="margin-top: 5px; width: 320px; float: left; text-align: left;">'+receive_email+'</span>');
                    //window.setTimeout(function(){location.reload()},2000)
                    window.setTimeout(function() {jQuery('#login_success').modal('hide');}, 3000);
                }
                else if(data==-1)
                {
                    jQuery('#forgot_loader').hide();
                    jQuery('#forgot_result').addClass('alert-success alert fade in');
                    jQuery('#forgot_result').html(contact_admin+'<a id="boxclose" class="boxclose" onclick="ForgotFunction();"></a>').animate({ color: "red" }).show();

                }
            }
        });

    } else {
        jQuery('#forgot_loader').hide();
        jQuery('#forgot_result').addClass('alert-success alert fade in');
        jQuery('#forgot_result').html(reg_email_id+'<a id="boxclose" class="boxclose" onclick="ForgotFunction();"></a>').animate({ color: "red" }).show();
    }
}

jQuery('#search-terms1').keydown(function(e) {
    
    jQuery("#search_list_fail1").html('');
    
    if (e.keyCode == 13)
    {
        var search1=jQuery("#search-terms1").val();
        var search2 = search1.replace(' ', '-');
        var url = search_result_url;
        var path = url.concat(search2);
        window.location.replace(path);
    }
});

jQuery('#search-terms2').keydown(function(e) {
    
    jQuery("#search_list_fail1").html('');

    if (e.keyCode == 13)
    {
        var url1 = home_url;
        var search1=jQuery("#search-terms2").val();
        var search2 = search1.replace(' ', '-');
        var url = search_result_url;
        var path = url.concat(search2);

        window.location.replace(path);
    }
});

jQuery("#search-terms1").on('keyup',function () {
    jQuery("#search_list_fail1").html('');
    var search = jQuery("#search-terms1").val();
    search_data(search);
});

function search_data(search){
    if((search.length>3) && (search!='')){
        jQuery("#search_list_fail1").html('');
        var _csrf=jQuery('#_csrf').val();
        if(search != ''){
            jQuery.ajax({
                url: site_search,
                type:"post",
                async:true,
                data:"search="+search+"&_csrf="+_csrf,
                success:function(data)
                {
                    if(data==0)
                    {
                        jQuery("#search_list").html('');
                        jQuery("#search_list_fail1").html('<p>' + no_record_found + '</p>');
                    }
                    else
                    {
                        jQuery("#search_list").html(data);
                        jQuery("#search_list_fail1").html('');
                    }
                }
            });
        }else{
            jQuery("#search_list").html('');
        }
    }else{
        jQuery("#search_list").html('');
    }
}

function mobile_search_data(search){
    if(search.length>3){
        var _csrf=jQuery('#_csrf').val();
        if(search != ''){
            jQuery.ajax({
                url: site_search,
                type:"POST",
                async:false,
                data:"search="+search+"&_csrf="+_csrf,
                success:function(data)
                {
                    if(data==0)
                    {
                        jQuery("#mobile_search_list").html(no_record_found);
                    }
                    else
                    {
                        jQuery("#mobile_search_list").html(data);
                    }
                }
            });
        }
        else
        {
            jQuery("#mobile_search_list").html('');
        }
    }
    else
    {
        jQuery("#mobile_search_list").html('');
    }
}//END function mobile_search_data

jQuery("#search-terms2").keyup(function(e){if(e.keyCode == 8)
    {
        var search=jQuery("#search-terms2").val();
        mobile_search_data(search);
    }
});

jQuery("#search-terms2").on('keyup',function () {
    var search=jQuery("#search-terms2").val();
    mobile_search_data(search);
});

jQuery("#search_input_header").on('keyup',function () {
    var search=jQuery("#search_input_header").val();
    search_data(search);
});

jQuery('#search-labl').bind('click',function(){
    jQuery("#search_list").html('');
});

function add_to_favourite(x)
{
    jQuery.ajax({
        url: add_to_wishlist_url,
        type:"post",
        data:"item_id="+x+"&_csrf="+_csrf,
        async: false,
        success:function(data)
        {
            var modal_name='add_to_event'+x;

            if(data==1)
            {
                jQuery('#add_to_event_loader').hide();
                jQuery('#add_to_event_success'+x).modal('hide');
                jQuery('#login_success').modal('show');
                jQuery('#success').html('<span class="sucess_close">&nbsp;</span><span class="msg-success" style="margin-top: 5px; width: 320px; float: left; text-align: left;">' + item_add_to_wishlist_success + '</span>');

                window.setTimeout(function(){
                    location.reload()},1000
                )
            }
            else if(data==-1)
            {
                jQuery('#add_to_event_loader').hide();
                jQuery('#add_to_event_failure'+x).html(item_add_to_wishlist_failed);
                //window.setTimeout(function(){location.reload()},1000)
            }
            else if(data==-2)
            {
                jQuery('#add_to_event_loader').hide();
                jQuery('#add_to_event_success'+x).html(item_add_to_wishlist_already_exist);
            }
        }
    });
}

function remove_from_favourite(x)
{
    var strconfirm = confirm("Are you sure you want to delete?");
    if (strconfirm == true)
    {
        jQuery.ajax({
            url: remove_from_wishlist,
            type:"post",
            data:"item_id="+x,
            async: false,
            success:function(data)
            {
                jQuery('#wishlist #'+x).remove();
                //alert(data);
                if(data==1)
                {
                    var item_removed = item_removed_fav;

                    jQuery("#oner").load(event_slider_url);
                    jQuery('#login_success').modal('show');
                    jQuery('#success').html('<span class="sucess_close">&nbsp;</span><span class="msg-success" style="margin-top: 5px; width: 320px; float: left; text-align: left;">'+item_removed+'</span>');
                    window.setTimeout(function() {jQuery('#login_success').modal('hide');}, 3000);
                    wishlistfilter();
                    //jQuery('#add_to_event_success'+x).html('Item add to your event list');
                    //window.setTimeout(function(){location.reload()},1000)

                    // jQuery('.faverited_icons').addClass('faver_icons');
                    //jQuery('.faverited_icons').removeClass('faverited_icons');
                }
                else if(data==-1)
                {
                    (jQuery(this).hasClass('faverited_icons'))?jQuery(this).removeClass('faverited_icons'):jQuery(this).addClass('faverited_icons');
                }
            }
        });
    }
}

//add to favorites
jQuery(".add_to_favourite").click(function(e){

    jQuery('#loading_img_list').show();
    jQuery('#loading_img_list').html('<img id="loading-image" src="'+giflink+'" alt="Loading..." />');

    item_id=(jQuery(this).attr('id'));
    jQueryelement = jQuery(this);
    jQuery(jQueryelement).parent().toggleClass("faverited_icons");

    var _csrf=jQuery('#_csrf').val();

    jQuery.ajax({
        url: add_to_wishlist_url,
        type:"post",
        data:"item_id="+item_id+"&_csrf="+_csrf,
        //async: false,
        success:function(data)
        {
            jQuery('#heart_fave').html(data);
            jQuery('#loading_img_list').hide();
        }
    });

    e.preventDefault();
    e.stopPropagation();
});

jQuery(".faver_evnt_product").click(function(){

        jQuery('#loading_img').show();

        item_id=(jQuery(this).attr('id'));
        jQueryelement = jQuery(this)
        jQuery(jQueryelement).find('span').toggleClass("heart-product-hover");

        var _csrf=jQuery('#_csrf').val();

        jQuery.ajax({
            url: add_to_wishlist_url,
            type:"post",
            data:"item_id="+item_id+"&_csrf="+_csrf,
            success:function(data)
            {
                jQuery('#heart_fave').html(data);
                jQuery('#loading_img').hide();
            }
        });
});

function add_to_event(x)
{
    var event_id = jQuery('#eventlist'+x).val();
    var event_name = jQuery('#eventlist'+x+' option:selected').text();

    if(event_id!=''){
        jQuery('#add_to_event_loader').show();
        var _csrf=jQuery('#_csrf').val();

        jQuery.ajax({
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
                    jQuery("#event-slider").load("<?= Url::toRoute('/product/event-slider'); ?>");
                    jQuery('#add_to_event_loader').hide();
                    jQuery('#add_to_event').modal('hide');
                    jQuery('#login_success').modal('show');
                    jQuery('#success').html('<span class="sucess_close">&nbsp;</span><span class="msg-success" style="margin-top: 5px; width: 320px; float: left; text-align: left;">'+data.message+'</span>');
                    window.setTimeout(function() {jQuery('#login_success').modal('hide');}, 3000);
                    //jQuery('#add_to_event_success'+x).html('Item Add to Your event list');
                }
                else if(data.status==-1)
                {
                    jQuery('#add_to_event_loader').hide();
                    jQuery('#add_to_event_success'+x).html(data.message);
                }
            }
        });
    }
    else
    {
        jQuery('#add_to_event_error'+x).html(kindly_select_event_type);
    }
}

function MyFunction()
{
    jQuery('#login_msg').fadeOut('fast');
}

function MyEventFunction()
{
    jQuery('#eventresult').fadeOut('fast');
}

function ForgotFunction()
{
    jQuery('#forgot_result').fadeOut('fast');
}

jQuery("#bday,#bmonth,#byear").change(function () {
    var day=jQuery('#bday').val();
    var mon=jQuery('#bmonth').val();
    var year=jQuery('#byear').val();
    if(day!='' && mon!='' && year!=''){
        jQuery('#dob_er').text('');
    }
});

jQuery("#gender").change(function () {
    var day=jQuery('#gender').val();
    if(day!=''){
        jQuery('#gen_er').text('');
    }
});

jQuery("#event_type").change(function () {
    var event_type=jQuery('#event_type').val();
    if(event_type==''){
        //jQuery('#gen_er').html('');
        jQuery('#type_error').html(kindly_select_the_event_type);

    }else
    {
        jQuery('#type_error').html('');
    }
});

jQuery("#boxclose").click(function () {
    //jQuery('#result').text('');
    jQuery('#result').hide();
});

/*jQuery("#reload_page").click(function () {
    window.setTimeout(function(){location.reload()},1000)
});*/

jQuery(".close").click(function () {
    all_form_reset();
});
function all_form_reset(){

    var loginForm = jQuery( "#loginForm" ).validate({errorLabelContainer: "#login_msg"});
    loginForm.resetForm();
    jQuery(':input','#loginForm')
    .not(':button, :submit, :reset, :hidden')
    .val('')
    .removeAttr('checked')
    .removeAttr('selected');
    jQuery('#result').html('');
    jQuery("#result").removeClass("alert-success alert fade in");

    var register_form = jQuery( "#register_form" ).validate();
    register_form.resetForm();
    jQuery(':input','#register_form')
    .not(':button, :submit, :reset, :hidden')
    .val('')
    .removeAttr('checked')
    .removeAttr('selected');
    jQuery('#customer_email').html('');
    jQuery('#agree').html('');
    jQuery('#con_pass').html('');
    jQuery('#dob_er').html('');
    jQuery('#gen_er').html('');
    //jQuery("#agree_terms").attr("checked",false);
    //jQuery('.selectpicker').selectpicker('refresh');
    jQuery('input:checkbox').removeAttr('checked');
    jQuery('input[type=checkbox]').attr('checked',false);
    jQuery('label#label_check1').removeClass('c_onn');
    jQuery('label#label_check1').addClass('c_off');
    //jQuery("#bday").select2("val", "");
    /*jQuery('#bday').val('').trigger('change');
    jQuery('#bmonth').val('').('change');
    jQuery('#byear').val('').('change');*/

    var forgotForm = jQuery( "#forgotForm" ).validate();
    forgotForm.resetForm();
    jQuery(':input','#forgotForm')
    .not(':button, :submit, :reset, :hidden')
    .val('')
    .removeAttr('checked')
    .removeAttr('selected');
    jQuery('#forgot_result').removeClass('alert-success alert fade in');
    jQuery('#forgot_result').html('');
    var create_event = jQuery( "#create_event" ).validate();
    create_event.resetForm();
    jQuery(':input','#create_event')
    .not(':button, :submit, :reset, :hidden')
    .val('')
    .removeAttr('checked')
    .removeAttr('selected');

    jQuery('#type_error').html('');
    jQuery('#event_type').selectpicker('refresh');
    jQuery('#dob_er').hide();
    jQuery('#gen_er').hide();
    jQuery('#agree').hide();
    jQuery('#forgot_result').hide();
}

// BEGIN EVENT DETAILS TOGGLE SCRIPT  Pages : product detail,vendor profile,event detail page.
jQuery('.collapse').on('shown.bs.collapse', function(){
    jQuery(this).parent().find(".glyphicon-menu-right").removeClass("glyphicon-menu-right").addClass("glyphicon-menu-down");
}).on('hidden.bs.collapse', function(){
    jQuery(this).parent().find(".glyphicon-menu-down").removeClass("glyphicon-menu-down").addClass("glyphicon-menu-right");
});
/* END EVENT DETAILS TOGGLE SCRIPT */

/*Onkey press search close script 19-10-2015-start-->*/
function show_close(){
    if(jQuery('#search-terms').val()!='')
    {

        jQuery('#search-close').addClass('visible');
    }
    else
    {
        jQuery('#search-close').removeClass('visible');
    }
}

function show_close3(){
    if(jQuery('#search_input_header').val()!='')
    {

        jQuery('#search-close1').addClass('visible');
    }
}
/*clear search 16/dec/2015 */
/*jQuery('.icon-search_clear').click(function(){
jQuery('#search-close1').removeClass('visible');
});*//**/
/*Onkey press search close script end 19-10-2015*/

/*open-search part add class 23-11-2015*/
jQuery('.search-lbl-mobile').click(function()
{
    if(jQuery('.mobile-menu').hasClass('open-search-menu'))
    {
        jQuery("#mobile-sid").removeClass('open-search-menu');
    } else {
        jQuery("#mobile-sid").addClass('open-search-menu');
        $('.mobile-menu #search_form #search-terms2').focus();
    }
});

jQuery('#search-close').click(function(){
    jQuery( "#mobile_search_list" ).html('');
    jQuery( "#mobile_search_fail" ).html('');
    jQuery( "#search-terms2" ).val('');
});

jQuery('.js-search-cancel,#search_list ul li a').click(function()
{
    jQuery('.open-search-menu').removeClass('open-search-menu');
    jQuery( "#mobile_search_list" ).html('');
    jQuery( "#mobile_search_fail" ).html('');
    jQuery( "#search-terms2" ).val('');
});
/*open-search part add class 23-11-2015*/

/*home slider new end*/
function Searchinvitee(event_id)
{
    var search_value;
    if(jQuery('#inviteesearch').val() !="")
    {
        search_value = jQuery('#inviteesearch').val();
    }
    else if( jQuery('#inviteesearch1').val()!="")
    {
        search_value = jQuery('#inviteesearch1').val();
    }

    var path = eventinvitees_url;

    jQuery.ajax({
        url:path,
        type:'POST',
        data:{event_id:event_id,search_val:search_value},
        success:function(data)
        {
            jQuery('.add_contact_table').html(data);
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
    jQuery.ajax({
        type:'POST',
        url: eventinvitees_add_event_url,
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

function default_session_data(x)
{
    jQuery('#login_success').modal('show');

    if(x==1)
    {
        jQuery('#success').html('<span class="sucess_close">&nbsp;</span><span class="msg-success" style="margin-top: 5px; width: 320px; float: left; text-align: left;">'+login_success_msg+'</span>');
        window.setTimeout(function() {jQuery('#login_success').modal('hide');}, 2000);
    }
    else
    {
        login_update_msg = you_are_login_and + '"'+x+'"' + add_to_favourite_successfully;
        jQuery('#success').html('<span class="sucess_close">&nbsp;</span><span class="msg-success" style="margin-top: 5px; width: 320px; float: left; text-align: left;">'+login_update_msg+' </span>');
    }
    window.setTimeout(function() {
        jQuery('#login_success').modal('hide');
    }, 2000);
}

if(session_default == 1) {
    window.onload=default_session_data(1);
}

if(session_favourite_status) {
    window.onload = default_session_data(session_favourite_status);
}

function display_event_modal()
{
    jQuery('#EventModal').modal('show');
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
            jQuery('#resetPwdModal').modal('show');
            jQuery('#userid1').val(session_reset_password);
        }else{
            jQuery('#login_success').modal('show');
            jQuery('#success').html('<span class="sucess_close">&nbsp;</span><span class="msg-success" style="margin-top: 5px; width: 320px; float: left; text-align: left;">'+pwd_fail_msg+'</span>');
            window.setTimeout(function() {
                jQuery('#login_success').modal('hide');
            }, 2000);
        }
    }
    window.onload=display_reset_password_modal;
}//END if(session_reset_password){

function addevent1(item_id)
{
    jQuery.ajax({
        type:'POST',
        url: product_add_event_url,
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

if(session_event_status>0){
    var x = session_event_status;
    window.onload=addevent1(x);
}

function show_activate_modal_true()
{
    jQuery('#login_activate').modal('show');
    setTimeout(function(){
        window.location.replace(home_url);
    }, 1000);

    /* jQuery('#success').text('Your Account Activated successfully!'); */
    jQuery("#reload_page1 ,#reload_page2").click(function () {
        /* window.location.replace("<?= Yii::$app->homeUrl; ?>"); */
    });
}

/* BEGIN RESPONSIVE MENU SINGLE CLICK TO OPEN SUB MENUS
jQuery(document).on('touchstart', function() {
    documentClick = true;
});

jQuery(document).on('touchmove', function() {
    documentClick = false;
});

jQuery(document).on('click touchend', function(event) {
    if (event.type == "click") documentClick = true;
    if (documentClick){
        //doStuff();
    }
});
/* END RESPONSIVE MENU SINGLE CLICK TO OPEN SUB MENUS */


// Registration Completed start
function show_register_modal_true()
{
    jQuery('#login_success').modal('show');
    jQuery('#success').html('<span class="sucess_close">&nbsp;</span><span class="msg-success" style="margin-top: 5px; width: 320px; float: left; text-align: left;">'+reg_success_msg+'</span>');
    window.setTimeout(function() {jQuery('#login_success').modal('hide');}, 2000);
}

// Registration Completed start
function show_event_modal_true()
{
    jQuery('#login_success').modal('show');
    if(!item_name.length){
        var event_created_msg = '"'+ text_event + ' ' + event_name + ' ' + created_successfully + '"';
        jQuery('#success').html('<span class="sucess_close">&nbsp;</span><span class="msg-success" style="margin-top: 5px; width: 320px; float: left; text-align: left;">'+event_created_msg+'</span>');
    } else {
        var event_added_msg = '"'+ text_event + ' ' + event_name + ' ' + created_successfully_and + ' ' + item_name +' '+ added_to +' '+ event_name +'"';
        jQuery('#success').html('<span class="sucess_close">&nbsp;</span><span class="msg-success" style="margin-top: 5px; width: 320px; float: left; text-align: left;">'+event_added_msg+' </span>');
    }
    window.setTimeout(function() {jQuery('#login_success').modal('hide');}, 2000);
}

if(session_register > 0) {
    window.onload=show_register_modal_true();
}

if(session_key == 1){
    window.onload=show_activate_modal_true();
}

if(session_key == 2){
    function show_password_reset_modal_true()
    {

        jQuery('#login_success').modal('show');
        jQuery('#success').html('<span class="sucess_close">&nbsp;</span><span class="msg-success" style="margin-top: 5px; width: 320px; float: left; text-align: left;">'+pwd_success_msg+'</span>');
        window.setTimeout(function() {jQuery('#login_success').modal('hide');}, 2000);
    }
    window.onload=show_password_reset_modal_true();
}



// filter page

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



jQuery('.listing_right .events_listing ul li:nth-child(4n)').addClass('margin-rightnone');
jQuery('.thing_items li:nth-child(8n)').addClass('margin-rightnone');

jQuery('.label_check, .label_radio').click(function () {
    setupLabel();
});


//setupLabel();

jQuery('.custom-select').change(function () {
    var selectedOption = jQuery(this).find(':selected').text();
    jQuery(this).next('.holder').text(selectedOption);
}).trigger('change');

//nav content js
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

jQuery('.collapse').on('shown.bs.collapse', function(){
    jQuery(this).parent().find('.plus_acc').removeClass('plus_acc').addClass('minus_acc');
}).on('hidden.bs.collapse', function(){
    jQuery(this).parent().find('.minus_acc').removeClass('minus_acc').addClass('plus_acc');
});

$('#open_search').click(function () {
    jQuery('#open_search').toggleClass('active');
});


jQuery('.dropdown').hover(
    function() {
        jQuery('.dropdown-menu', this).stop( true, true ).slideDown('fast');
        jQuery(this).toggleClass('open');
    },
    function() {
        jQuery('.dropdown-menu', this).stop( true, true ).slideUp('fast');
        jQuery(this).toggleClass('open');
    }
);


/* Mega menu ends */
if ((jQuery('.test_scroll').length)>0) {
    (function (jQuery) {
        jQuery(window).load(function () {
            jQuery('.test_scroll').mCustomScrollbar({
                theme: 'rounded-dark',
                mouseWheelPixels: 50,
                scrollInertia: 0
            });
        });
    })(jQuery);
}

/* BEGIN filter item list */
var csrfToken = jQuery('meta[name=\"csrf-token\"]').attr('content');
var url = window.location.href;     // Returns full URL
setupLabel();

jQuery('.label_check input').on('change',function() {
    filter();
});

if ((jQuery('#delivery_area_filter').length)>0) {
    jQuery('#delivery_area_filter').on('change', function () {
        filter();
    });
}

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

jQuery('button#loadmore').click(function(event) {
    jQuery('#planloader').show();

    setupLabel();
    limit = limit+4;
    var path = load_more_items;

    jQuery.ajax({
        type:'POST',
        url:path,
        data:{ limit:limit, slug:slug, _csrf : csrfToken},
        success:function(data){
            jQuery('.events_listing ul li:last-child').after(data);
            // Every fourth li change margin
            jQuery('#planloader').hide();
        }
    });
});


/* BEGIN load category and reload the page */
jQuery('#main-category').change(function(){
    var s = jQuery('#main-category :selected').val();
    var hostname = window.location.href;
    var newUrl1 = url.substring(0, url.indexOf('plan'));
    window.location.href = jQuery(this).val();
});
/* END load category and reload the page */

var loadmore = 0;

function filter(){
    var ajax_data = {},
        date = '',
        areas = 'All',
        slug = '',
        search = '',
        category_name = '',
        theme_name = '',
        price_val = '',
        vendor_name = '',
        for_sale = '',
        url_path = '',
        url = window.location.href;

    url_path += '?filter=1'; //intializing url_path

    jQuery('#planloader').show();

    jQuery('.listing_right').css({'opacity' : '0.5', 'position' : 'relative'});

    var category_name = jQuery('input[name=category]:checked').map(function() {
        return this.value;
    }).get();

    if ((jQuery('input[name=themes]').length)>0) {
        var theme_name = jQuery('input[name=themes]:checked').map(function () {
            return this.value;
        }).get();
    }

    if ((jQuery('input[name=for_sale]').length)>0) {
        var for_sale = jQuery('input[name=for_sale]:checked').map(function () {
            return this.value;
        }).get();
    }
    
    if ((jQuery('input[name=vendor]').length)>0) {
        var vendor_name = jQuery('input[name=vendor]:checked').map(function () {
            return this.value;
        }).get();
    }

    if ((jQuery('.price_slider').length)>0) {
        var price_val = jQuery('.price_slider').val().replace(',', '-');
    }

    if (jQuery('#delivery_date_2').length>0) {
        var date = jQuery('#delivery_date_2').val();
    }
    if (jQuery('#delivery_area_filter').length>0) {
        var areas = jQuery('#delivery_area_filter').val();
    }

    if (typeof product_slug !== "undefined") {
        slug = product_slug;
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

    if (for_sale != '') {
        url_path += '&for_sale='+for_sale;
        ajax_data.for_sale = for_sale;
    }

    if (search != '') {
        url_path += '&search=' + search;
        ajax_data.search = search;
    }

    if (category_name != '') {
        url_path += '&category[]=' + category_name;
        ajax_data.category = category_name;
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

    if(areas != '') {
        url_path += '&location=' + areas;
        ajax_data.location = areas;
    }

    var path = load_items;
    var url = path +'/'+slug+'?filter=1';

    if (current_page == 'browse') {
        path = path +'/'+slug+'?filter=1&'+$.param(ajax_data)
    } else if (current_page == 'theme') {
        var url = path +'/'+slug+'/'+theme_name+'?filter=1';
        path = path +'/'+slug+'/'+theme_name+'?filter=1&'+$.param(ajax_data);
    } else if (current_page == 'vendor') {
        var url = path +'/'+vendor_name+'?slug='+slug+'/'+'&filter=1';
        path = path +'/'+vendor_name+'?slug='+slug+'&filter=1&'+$.param(ajax_data);
    }

    jQuery.ajax({
        type:'GET',
        url:url,
        data:ajax_data,
        success:function(data){
            //window.history.pushState('test', 'Title', $.param(ajax_data));
            window.history.pushState(null, null, path);
            jQuery('.listing_right').html(data);
            jQuery('#planloader').hide();
            jQuery('.listing_right').css({'opacity' : '1.0', 'position' : 'relative'});
            imgError(); // to initialize after result comes
        }
    }).done(function(){
        jQuery('.add_to_favourite').click(function() {
            jQuery('#loading_img_list').show();
            jQuery('#loading_img_list').html('<img id=\"loading-image\" src=\"".giflink."\" alt=\"Loading...\" />');

            item_id = jQuery(this).attr('id');
            jQueryelement = jQuery(this);
            jQuery(jQueryelement).parent().toggleClass('faverited_icons');
            var _csrf = jQuery('#_csrf').val();
            jQuery.ajax({
                url : add_to_wishlist_url,
                type : 'post',
                data : 'item_id=' + item_id + '&_csrf='+_csrf,
                success:function(data) {
                    jQuery('#heart_fave').html(data);
                    jQuery('#loading_img_list').hide();
                }
            });
        });
    });
}//end of function



/* BEGIN RESPONSIVE FILTER NAVIGATION */
/*var trigger = jQuery('.filter_butt'),
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
}*/

/*jQuery("#left_side_cate nav").removeClass ("navbar navbar-fixed-top ");
jQuery("#left_side_cate ul").removeClass ("nav sidebar-nav ");
jQuery("#left_side_cate nav").removeAttr ("id")

if (jQuery(window).width() < 991) {
    jQuery("#left_side_cate nav").addClass ("navbar navbar-fixed-top ");
    jQuery("#left_side_cate ul").addClass ("nav sidebar-nav ");
    jQuery("#left_side_cate nav").attr ('id','sidebar-wrapper')
}
*/

function imgError() {
    $("img").error(function () {
        $(this).unbind("error").attr("src", "https://placeholdit.imgix.net/~text?txtsize=20&txt=No%20Image&w=210&h=208");
    });
}
imgError(); // to initialize on page load



/* =========================== Event Actions ===================================*/


function addinvitees()
{
    var action = '';

    if(jQuery('#invitees_name').val() =='')
    {
        alert('Enter invitees name.');
        return false;
    }

    if(jQuery('#invitees_email').val() =='')
    {
        alert('Enter invitees email');
        return false;

    } else if(isEmail(jQuery('#invitees_email').val()) == false ){
        alert('Enter valid email');
        return false;
    }

    var act = '';
    if(jQuery('#invitees_id').val() =='')
    {
        action = 'addinvitees';
        act = 'new';
    }
    else{
        action = 'updateinvitees';
        act = 'updated';
    }

    var path = add_invite;

    jQuery.ajax({
        type :'POST',
        url:path,
        data: {
            invitees_id: jQuery('#invitees_id').val(),
            event_id: event_id,
            name: jQuery('#invitees_name').val(),
            email:jQuery('#invitees_email').val(),
            phone_number: jQuery('#invitees_phone').val(),
            event_name:event_name,
            action:act
        },
        success:function(data)
        {
            if(data==2)
            {
                jQuery('.invite_error').show();
            }
            else if(data==1)
            {
                jQuery('#login_success').modal('show');
                jQuery('#success').html('<span class=\"sucess_close\">&nbsp;</span><span class=\"msg-success\">Success! Invitee '+act+' successfully!</span>');
                window.setTimeout(function(){location.reload()},2000)
            }
            // jQuery.pjax.reload({container:'#itemtype'});
        }
    });
}
function inviteeDetail(invitee_id)
{
    jQuery.ajax({
        url: invite_detail,
        type : 'POST',
        data :{id:invitee_id},
        dataType:'JSON',
        success : function(data)
        {
            jQuery('#invitees_id').val(data.invitees_id);
            jQuery('#invitees_name').val(data.name);
            jQuery('#invitees_email').val(data.email);
            jQuery('#invitees_phone').val(data.phone_number);
            jQuery('#submit').val('Update');
        }

    });
    return false;
}

/* =========================== Event Actions ===================================*/


jQuery('#open-filter,#close-search-div').click(function(){
    $("#top_header").toggleClass('mobile-search-enable');
    $(".mobile-view-form-popup").slideToggle(500,"linear", function () {
        // actions to do when animation finish
    });
});
