<?php

use Yii\helpers\Url;
use yii\web\View;

$this->registerJs("
    var password_reset_link = '".Url::toRoute(['/users/password_reset'])."';
    var pwd_reset_msg = '".Yii::t('frontend','Password reset and login successfully')."';
    var user_login = '".Url::toRoute('/users/login')."';
    var not_activate_msg = '".Yii::t('frontend','Looks like you are not activated your account')."';
    var user_blocked_msg = '".Yii::t('frontend','User blocked')."';
    var email_not_match = '".Yii::t('frontend','Email and password does not match')."';
    var email_not_exist = '".Yii::t('frontend','Your email does not exist')."';
    var success_fav_added1 = '".Yii::t('frontend','Success! Your are login and')."';
    var success_fav_added2 = '".Yii::t('frontend','add to favourite successfully')."';
    var reg_email = '".Yii::t('frontend','Enter registered email-id')."';
    var email_check = '".Url::toRoute('/users/email_check')."';
    var signup = '".Url::toRoute('/users/signup')."';
    var create_event = '".Url::toRoute('/users/create_event')."';
    var event_exist = '".Yii::t('frontend','Same event name already exists')."';
    var update_event = '".Url::toRoute('/users/update_event')."';
    var event_exists = '".Yii::t('frontend','Same event name already exists')."';
    var update_msg = '".Yii::t('frontend','Event updated successfully')."';
    var event_details = '".Yii::$app->homeUrl."/event-details/';
    var forgot_password_url = '".Url::toRoute('users/forget_password')."';
    var receive_email = '".Yii::t("frontend","You will now receive an email to reset your password by email")."';
    var contact_admin = '".Yii::t("frontend","Entered email id not found in registred user email. Kindly contact admin!")."';
    var reg_email_id = '".Yii::t('frontend','Enter registered Email-id')."';
    var search_result_url = '".Url::toRoute('/search-result/')."/';
    var home_url = '".Yii::$app->homeUrl."';
    var site_search = '".Url::toRoute('/site/search')."';
    var event_slider_url = '".Url::toRoute('/product/event-slider')."';
    var item_removed_fav = '".Yii::t('frontend','Item remove from your favourite list')."';
    var remove_from_wishlist = '".Url::toRoute('/users/remove_from_wishlist')."';
    var item_add_to_wishlist_failed = '".Yii::t('frontend','Item add to your event list is failed')."';
    var item_add_to_wishlist_already_exist = '".Yii::t('frontend','Item already exists to this event!')."';
    var item_add_to_wishlist_success = '".Yii::t('frontend','Item add to your event list!')."';
    var add_to_wishlist_url = '".Url::toRoute('/users/add_to_wishlist')."';
    var giflink = '".Url::to("@web/images/ajax-loader.gif")."';
    var event_name = '".Yii::$app->session->get('event_name')."';
    var item_name = '".Yii::$app->session->get('item_name')."';

    var text_event = '".Yii::t("frontend","EVENT")."';
    var created_successfully = '".Yii::t("frontend","CREATED SUCCESSFULL")."';
    var created_successfully_and = '".Yii::t("frontend","CREATED SUCCESSFULLY AND")."';
    var_added_to = '".Yii::t("frontend","ADDED TO")."';

    var isGuest = '".Yii::$app->user->isGuest."';
    var add_event_url = '".Url::toRoute('/users/add_event')."';
    var successfully_added_to = '".Yii::t('frontend',' successfully added to ')."';
    var category = '".Yii::t('frontend','CATEGORY')."';
    var eventinvitees_url = '".Url::toRoute(['eventinvitees/index'])."';
    var eventinvitees_add_event_url = '".Url::toRoute(['/eventinvitees/addevent'])."';
    var login_success_msg = '".Yii::t('frontend','Your Login successfully')."';
    var session_default = '".Yii::$app->session->get('default')."';
    var session_favourite_status = '".Yii::$app->session->get('favourite_status')."';
    var session_create_event = '".Yii::$app->session->get('create_event')."';
    var session_reset_password = '".Yii::$app->session->get('reset_password_mail')."';
    var session_final_reset = '".Yii::$app->session->get('final_reset')."';
    var session_register = '".Yii::$app->session->get('register')."';
    var you_are_login_and = '".Yii::t('frontend','Success! Your are login and')."';
    var session_key = '".Yii::$app->session->get('key')."';
    var add_to_favourite_successfully = '".Yii::t('frontend','add to favourite successfully!')."';

    var pwd_fail_msg = '".Yii::t('frontend','Password reset failed!')."';
    var product_add_event_url = '".Url::toRoute('product/addevent')."';
    var session_event_status = '".Yii::$app->session->get('event_status')."';
    var reg_success_msg = '".Yii::t('frontend','Registration completed successfully.Confirmation link send to your registered email-id')."';
    var pwd_success_msg = '".Yii::t('frontend','Password reset successfully. Your login successfully!')."';
", View::POS_HEAD);

Yii::$app->session->set('default',0);
Yii::$app->session->set('favourite_status','');
Yii::$app->session->set('create_event','0');
Yii::$app->session->set('reset_password_mail','');
Yii::$app->session->set('event_status','0');
Yii::$app->session->set('reset_password_mail','');
Yii::$app->session->set('register',0);
Yii::$app->session->set('key','0');

use Yii\helpers\Url;
$this->registerJsFile('@web/js/search.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
?>
<script type="text/javascript">

    function validateEmail(email) {
        // http://stackoverflow.com/a/46181/11236
        var re = /^[a-zA-Z0-9!#$%&'*+\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&'*+\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?$/;
        return re.test(email);
    }

jQuery(document).ready(function () {
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
        jQuery('#agree').html('Tick the terms of services and privacy policy');
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
    jQuery("body").swipe({
        swipe:function(event, direction, distance, duration, fingerCount, fingerData) {
            if ( direction == 'left' ) { jQuery('html').removeClass('ma5-menu-active');}
            if ( direction == 'right' ) { jQuery('html').addClass('ma5-menu-active');}
        },
        allowPageScroll: "vertical"
    });
};
/*Responsive menu script end*/
});

jQuery('#dp3,#delivery_date').datepicker({
    format: 'dd-mm-yyyy',
    startDate:'today',
    autoclose:true,
});

</script>

<!-- megamenu script end -->

<!-- plan last:child script -->
<script type="text/javascript">
jQuery(document).ready(function () {

    jQuery('.plan_sections ul li:nth-child(3n)').addClass("margin-rightnone");
})


</script>
<!-- plan last:child script end -->

<!-- FeatureD Products script  -->
<script type="text/javascript">

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
jQuery('.index_redirect').click(function()
{
    var a = jQuery(this).attr('data-hr');
    window.location.href = a; //Will take you to Google.
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
</script>

<!-- -->
<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/jquery.validate.min.js"></script>
<script>
/* Forgot password completed start  */
jQuery("#reset_button").click( function()
{
    resetpwdcheck();
});


jQuery('#resetForm input ').keydown(function(e) {
    if (e.keyCode == 13)
    {  resetpwdcheck();
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
        jQuery('#reset_pwd_result').html('Password should contain minimum six letters');return false;
    }
    if(password==conPassword)
    {
        jQuery('#reset_pwd_result').hide();
        k=1;
    }
    else
    {
        jQuery('#reset_pwd_result').show();
        jQuery('#reset_pwd_result').html('Confirm password should be equal to password');return false;
    }

    if((jQuery('#resetForm').valid()) && (k==1))
    {
        jQuery.ajax({
            url:"<?= Url::toRoute(['/users/password_reset']); ?>",
            type:"POST",
            data:"id="+userid+"&password="+password+"&_csrf="+_csrf,
            async: false,
            success:function(data)
            {
              //  console.log(data); return false;
                if(data==1)
                {
                     var pwd_reset_msg = "<?php echo Yii::t('frontend','Password reset and login successfully') ?>";
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
        var email=jQuery('#email').val();
        var password=jQuery('#password').val();
        var _csrf=jQuery('#_csrf').val();
        if(validateEmail(email) == true){
            jQuery.ajax({
                url:"<?= Url::toRoute('/users/login');?>",
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
                        var not_activate_msg = "<?php echo Yii::t('frontend','Looks like you are not activated your account') ?>";
                        jQuery('#login_loader').hide();
                        jQuery('#result').addClass('alert-success alert fade in');
                        jQuery('#result').html(+not_activate_msg+'<a id="boxclose" name="boxclose" class="boxclose" onclick="MyFunction();"></a>').animate({ color: "red" }).show();
                        jQuery('#login_forget').show();
                        jQuery('#loader').hide();
                    }
                    else if(status==-2)
                    {
                        var user_blocked_msg = "<?php echo Yii::t('frontend','User blocked') ?>";
                        jQuery('#login_loader').hide();
                        jQuery('#result').html(+user_blocked_msg+'<a id="boxclose" name="boxclose" class="boxclose" onclick="MyFunction();"></a>').animate({ color: "red" }).show();
                    }
                    else if(status==-3)
                    {
                        var email_not_exist = "<?php echo Yii::t('frontend','Your email does not exist') ?>";
                        jQuery('#login_loader').hide();
                        jQuery('#result').addClass('alert-success alert fade in');
                        jQuery('#result').html(+email_not_exist+'<a id="boxclose" class="boxclose" onclick="MyFunction();"></a>').animate({ color: "red" }).show();
                    }
                    else if(status==-4)
                    {
                        var email_not_match = "<?php echo Yii::t('frontend','Email and password does not match') ?>";
                        jQuery('#login_loader').hide();
                        jQuery('#result').addClass('alert-success alert fade in');
                        jQuery('#result').html(+email_not_match+'<a id="boxclose" class="boxclose" onclick="MyFunction();"></a>').animate({ color: "red" }).show();
                    }
                    else if(status==1)
                    {
                        jQuery('#login_loader').hide();
                        jQuery('#myModal').modal('hide');

                        if(favourite_status>0){
                             var success_fav_added = "<?php echo Yii::t('frontend','Success! Your are login and') ?>"+ '"'+item_name+'"' +"<?php echo Yii::t('frontend','add to favourite successfully') ?>";

                            jQuery('#login_success').modal('show');
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

                },
                error:function(data)
                {

                }
            });
        }
        else
        {
             var reg_email = "<?php echo Yii::t('frontend','Enter registered email-id') ?>";
            jQuery('#login_loader').hide();
            //jQuery('#loginErrorMsg').addClass('alert-failure alert fade in');
            jQuery('#result').addClass('alert-success alert fade in');
            jQuery('#result').html(+reg_email+'<a id="boxclose" class="boxclose" onclick="MyFunction();"></a>').animate({ color: "red" }).show();

        }
    }
}


// Register save function ajax
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
            jQuery('#agree').html('Tick the terms of services and privacy policy');
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
        jQuery('#con_pass').html('Password and Confirm password should be minimum six letters and same');

    }




    if(gender==0)
    {
        jQuery('#gen_er').show();
        jQuery('#gen_er').html('The field is required');
    }
    else
    {
        jQuery('#gen_er').hide();
        i=1;
    }
    if(bday=='' && bmonth=='' && byear=='')
    {
        jQuery('#dob_er').show();
        jQuery('#dob_er').text("The field is required ");
    }
    else if(bday=='' && bmonth=='')
    {
        jQuery('#dob_er').show();
        jQuery('#dob_er').text("Choose date and month of birth");
    }
    else if(bday=='' && byear=='')
    {
        jQuery('#dob_er').show();
        jQuery('#dob_er').text("Choose date and year of birth");
    }
    else if(bmonth=='' && byear=='')
    {
        jQuery('#dob_er').show();
        jQuery('#dob_er').text("Choose month and year of birth");
    }
    else if(bmonth=='')
    {
        jQuery('#dob_er').show();
        jQuery('#dob_er').text("Choose month of birth");
    }
    else if(byear=='')
    {
        jQuery('#dob_er').show();
        jQuery('#dob_er').text("Choose year of birth");
    }
    else if(bday=='')
    {
        jQuery('#dob_er').show();
        jQuery('#dob_er').text("Choose day of birth");
    }
    else
    {
        jQuery('#dob_er').hide();
        j=1;
    }


    if(validateEmail(x) == true){

        jQuery.ajax({

            url:"<?= Url::toRoute('/users/email_check')?>",
            type:"post",
            //data:"email="+x+"&_csrf="+_csrf,
            data:"email="+x,
            async: false,
            success:function(data)
            {
                if(data==1)
                {
                    jQuery("#customer_email").show();
                    jQuery("#customer_email").html('Entered email id is already exists');
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
            jQuery("#customer_email").html('Enter a valid email id');
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
            url:"<?= Url::toRoute('/users/signup'); ?>",
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
                    window.setTimeout(function(){location.reload()})
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
</script>



<script>

/* BEGIN ADD TO EVENT */
jQuery('#create_event_button').click(function(){
    jQuery.noConflict();
    var i=0;
    //var element = jQuery(this).parents('form');
    var event_type=jQuery('#event_type').val();
    if(event_type==''){
        jQuery('#type_error').html('Kindly select Event type');
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
                url:"<?= Url::toRoute('/users/create_event'); ?>",
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
                          var event_exist = "<?php echo Yii::t('frontend','Same event name already exists') ?>";
                        jQuery('#event_loader').hide();
                        jQuery('#eventresult').addClass('alert-success alert fade in');
                        jQuery('#eventresult').html(+event_exist+'<a id="boxclose" class="boxclose" onclick="MyEventFunction();"></a>').animate({ color: "red" }).show();
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
            jQuery('#type_error').html('Kindly select Event type');
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
                url:"<?= Url::toRoute('/users/update_event'); ?>",
                type:"post",
                data:"event_id="+jQuery('#edit_event_id').val()+"&event_date="+event_date+"&item_id="+item_id+"&event_name="+event_name+"&event_type="+event_type+"&_csrf="+_csrf,
                success:function(data)
                {
                    if(data==-1)
                    {
                      var event_exists = "<?php echo Yii::t('frontend','Same event name already exists') ?>";
                        jQuery('#event_loader').hide();
                        jQuery('#eventresult').addClass('alert-success alert fade in');
                        jQuery('#eventresult').html(+event_exists+'<a id="boxclose" class="boxclose" onclick="MyEventFunction();"></a>').animate({ color: "red" }).show();
                        //jQuery(".eventErrorMsg").html('Same event name already exists!');
                        // window.setTimeout(function(){location.reload()},2000);
                    }
                    else
                    {
                        jQuery('#event_loader').hide();
                        jQuery(".eventErrorMsg").html('');

                        jQuery('#EditeventModal').modal('hide');
                        jQuery('#login_success').modal('show');
                        var update_msg = "<?php echo Yii::t('frontend','Event updated successfully') ?>";
                        jQuery('#success').html('<span class="sucess_close">&nbsp;</span><span class="msg-success" style="margin-top: 5px; width: 320px; float: left; text-align: left;">'+update_msg+'</span>');
                        setTimeout(function() {$('#login_success').modal('hide');}, 2000);
                        //jQuery('#EventModal').modal('hide');
                        setTimeout(function(){locat(data)},2000);
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
        window.location = "<?= Yii::$app->homeUrl; ?>/event-details/"+loc;
    }
    </script>
    <script type="text/javascript">
    function show_register_modal()
    {
        jQuery.noConflict();
        jQuery('#myModal').modal('hide');
        jQuery('#forgotPwdModal').modal('hide');
    }


    function show_login_modal(x)
    {
        jQuery.noConflict();
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
                    url:"<?= Url::toRoute('/users/email_check'); ?>",
                    type:"post",
                    data:"email="+x+"&_csrf="+_csrf,
                    success:function(data)
                    {
                        if(data==1)
                        {
                            jQuery("#customer_email").show();
                            jQuery("#customer_email").html('Entered email id is already exists');
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
                url:"<?= Url::toRoute('users/forget_password'); ?>",
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
                        var receive_email = '<?php echo Yii::t("frontend","You will now receive an email to reset your password by email") ?>';
                        jQuery('#success').html('<span class="sucess_close">&nbsp;</span><span class="msg-success" style="margin-top: 5px; width: 320px; float: left; text-align: left;">'+receive_email+'</span>');
                        //window.setTimeout(function(){location.reload()},2000)
                        window.setTimeout(function() {jQuery('#login_success').modal('hide');}, 3000);
                        all_form_reset();
                    }
                    else if(data==-1)
                    {
                        var contact_admin = '<?php echo Yii::t("frontend","Entered email id not found in registred user email. Kindly contact admin!") ?>';
                        jQuery('#forgot_loader').hide();
                        jQuery('#forgot_result').addClass('alert-success alert fade in');
                        jQuery('#forgot_result').html(contact_admin+'<a id="boxclose" class="boxclose" onclick="ForgotFunction();"></a>').animate({ color: "red" }).show();
                    }
                }
            });

        }else {
            //if(reg_email!='')
            var reg_email_id = "<?php echo Yii::t('frontend','Enter registered Email-id'); ?> ";
            jQuery('#forgot_loader').hide();
            jQuery('#forgot_result').addClass('alert-success alert fade in');
            jQuery('#forgot_result').html(+reg_email_id+'<a id="boxclose" class="boxclose" onclick="ForgotFunction();"></a>').animate({ color: "red" }).show();

            //jQuery("#forgerErrorMsg").show();
            //jQuery("#forgerErrorMsg").html('Enter registered mail id');
        }
    }


    jQuery('#search_input_header').keydown(function(e) {
        jQuery("#search_list_fail1").html('');
        if (e.keyCode == 13)
        {
            var search1=jQuery("#search_input_header").val();
            var search2 = search1.replace(' ', '-');

            var url="<?php echo Url::toRoute('/search-result').'/'; ?>";
            var path=url.concat(search2);
            //alert(path);

            window.location.href=path;
        }
    });


    jQuery('#sear_button_submit').click(function(e) {
        jQuery("#search_list_fail1").html('');

        var search1=jQuery("#search_input_header").val();
        if(search1!=''){
            var search2 = search1.replace(' ', '-');

            var url="<?php echo Url::toRoute('/search-result/').'/'; ?>";
            var path=url.concat(search2);
            //alert(path);

            window.location.href=path;
        }

    });
    jQuery('#search-terms1').keydown(function(e) {
        jQuery("#search_list_fail1").html('');
        if (e.keyCode == 13)
        {
            var search1=jQuery("#search-terms1").val();
            var search2 = search1.replace(' ', '-');
            var url="<?php echo Url::toRoute('/search-result/').'/'; ?>";
            var path=url.concat(search2);
            window.location.replace(path);
        }
    });
    jQuery('#search-terms2').keydown(function(e) {
        jQuery("#search_list_fail1").html('');
        if (e.keyCode == 13)
        {
            var url1="<?= Yii::$app->homeUrl; ?>";
            var search1=jQuery("#search-terms2").val();
            var search2 = search1.replace(' ', '-');
            var url="<?php echo Url::toRoute('/search-result/').'/';?>";
            var path=url.concat(search2);

            window.location.replace(path);

        }
    });

    jQuery("#search_input_header").keyup(function(e){if(e.keyCode == 8)
        {

            jQuery("#search_list_fail1").html('');
            var search=jQuery("#search_input_header").val();
            search_data(search);
        }
    });
    jQuery("#search-terms1").on('keyup',function () {
        jQuery("#search_list_fail1").html('');
        var search=jQuery("#search-terms1").val();

        search_data(search);
    });
    function search_data(search){
        if((search.length>3) && (search!='')){
            jQuery("#search_list_fail1").html('');
            var _csrf=jQuery('#_csrf').val();
            if(search != ''){
                jQuery.ajax({
                    url:"<?= Url::toRoute('/site/search');?>",
                    type:"post",
                    async:true,
                    data:"search="+search+"&_csrf="+_csrf,
                    success:function(data)
                    {
                        if(data==0)
                        {
                            jQuery("#search_list").html('');
                            jQuery("#search_list_fail1").html('<p>No Record found</p>');
                        }
                        else
                        {jQuery("#search_list").html(data);
                        jQuery("#search_list_fail1").html('');}

                    }
                });
            }else{jQuery("#search_list").html('');}
        }else{jQuery("#search_list").html('');}
    }

    function mobile_search_data(search){
        if(search.length>3){
            var _csrf=jQuery('#_csrf').val();
            if(search != ''){
                jQuery.ajax({
                    url:"<?= Url::toRoute('/site/search'); ?>",
                    type:"POST",
                    async:false,
                    data:"search="+search+"&_csrf="+_csrf,
                    success:function(data)
                    {
                        if(data==0)
                        {jQuery("#mobile_search_list").html('No data found');
                    }
                    else
                    {jQuery("#mobile_search_list").html(data);}
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
}

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
    // if(search.length>1){
    search_data(search);
    //}
});

jQuery('#search-labl').bind('click',function(){

    jQuery("#search_list").html('');
});




function add_to_favourite(x)
{

    jQuery.ajax({
        url:"<?= Url::toRoute('/users/add_to_wishlist'); ?>",
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
                var added_success = "<?php echo Yii::t('frontend','Item add to your event list!'); ?> ";
                jQuery('#success').html('<span class="sucess_close">&nbsp;</span><span class="msg-success" style="margin-top: 5px; width: 320px; float: left; text-align: left;">'+added_success+'</span>');
                //jQuery('#add_to_event_success'+x).html('Item add to your event list');
                window.setTimeout(function(){location.reload()},1000)
            }
            else if(data==-1)
            {
                var add_failed = "<?php echo Yii::t('frontend','Item add to your event list is failed'); ?> ";
                jQuery('#add_to_event_loader').hide();
                jQuery('#add_to_event_failure'+x).html(add_failed);
                //window.setTimeout(function(){location.reload()},1000)
            }
            else if(data==-2)
            {
                var already_exist = "<?php echo Yii::t('frontend','Item already exists to this event!'); ?> ";
                jQuery('#add_to_event_loader').hide();
                jQuery('#add_to_event_success'+x).html(already_exist);
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
            url:"<?= Url::toRoute('/users/remove_from_wishlist'); ?>",
            type:"post",
            data:"item_id="+x,
            async: false,
            success:function(data)
            {

                jQuery('#wishlist #'+x).remove();
                //alert(data);
                if(data==1)
                {
                    var item_removed = "<?php echo Yii::t('frontend','Item remove from your favourite list'); ?> ";

                    jQuery("#oner").load("<?= Url::toRoute('/product/event-slider'); ?>");
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



//Add or remove favourites
<?php $giflink = Url::to("@web/images/ajax-loader.gif"); ?>

jQuery(".add_to_favourite").click(function(){

    jQuery('#loading_img_list').show();
    jQuery('#loading_img_list').html('<img id="loading-image" src="<?= $giflink;?>" alt="Loading..." />');

    item_id=(jQuery(this).attr('id'));
    jQueryelement = jQuery(this)
    jQuery(jQueryelement).parent().toggleClass("faverited_icons");

    var _csrf=jQuery('#_csrf').val();
    jQuery.ajax({
        url:"<?= Url::toRoute('/users/add_to_wishlist');  ?>",
        type:"post",
        data:"item_id="+item_id+"&_csrf="+_csrf,
        //async: false,
        success:function(data)
        {
            jQuery('#heart_fave').html(data);
            jQuery('#loading_img_list').hide();
        }
    });
});


jQuery(".faver_evnt_product").click(function(){
    <?php if(!Yii::$app->user->isGuest){?>
        jQuery('#loading_img').show();

        item_id=(jQuery(this).attr('id'));
        jQueryelement = jQuery(this)
        jQuery(jQueryelement).find('span').toggleClass("heart-product-hover");

        var _csrf=jQuery('#_csrf').val();
        jQuery.ajax({

            url:"<?= Url::toRoute('/users/add_to_wishlist');  ?>",
            type:"post",
            data:"item_id="+item_id+"&_csrf="+_csrf,
            success:function(data)
            {
                jQuery('#heart_fave').html(data);
                jQuery('#loading_img').hide();
            }
        });
        <?php }?>
    });

    function add_to_event(x)
    {
        //alert(jQuery(data).find('desc_popup_cont').text());
        var item_name=jQuery('.desc_popup_cont h3').text();
        var event_id=jQuery('#eventlist'+x).val();
        var event_name=jQuery('#eventlist'+x+' option:selected').text();

        if(event_id!=''){
            jQuery('#add_to_event_loader').show();
            var _csrf=jQuery('#_csrf').val();
            jQuery.ajax({
                url:"<?= Url::toRoute('/users/add_event'); ?>",
                type:"post",
                data:{"event_name":event_name,"item_name":item_name,
                "event_id":event_id,"item_id":x,"_csrf":_csrf},
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
            jQuery('#add_to_event_error'+x).html('Kindly select the event type');
        }

    }

    function MyFunction()
    {
        jQuery('#result').fadeOut('fast');
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
            jQuery('#type_error').html('Kindly select Event type');

        }else
        {
            jQuery('#type_error').html('');
        }
    });

    //

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

    var loginForm = jQuery( "#loginForm" ).validate();
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
</script>

<style>
a.boxclose{
    float:right;
    width:26px;
    height:26px;
    background:transparent url(http://tympanus.net/Tutorials/CSSOverlay/images/cancel.png) repeat top left;
    margin-top:-5px;
    margin-right:-10px;
    cursor:pointer;
}
</style>

<script>
/* BEGIN EVENT DETAILS TOGGLE SCRIPT  Pages : product detail,vendor profile,event detail page.*/
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

    }
    else{

        jQuery("#mobile-sid").addClass('open-search-menu');
    }
    //(jQuery('.mobile-menu').hasClass('open-search-menu'))?jQuery(this).removeClass('open-search-menu'):jQuery(this).addClass('open-search-menu');
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
</script>



<script>
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

/*home slider new end*/
</script>
<script type="text/javascript">
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

    var path = "<?= Url::toRoute(['eventinvitees/index']); ?>" ;
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

/* BEGIN ADD EVENT */
function addevent(item_id)
{
    jQuery.ajax({
        type:'POST',
        url:"<?= Url::toRoute(['/eventinvitees/addevent']); ?>",
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
    var login_success_msg = "<?php echo Yii::t('frontend','Your Login successfully'); ?> ";
    jQuery('#success').html('<span class="sucess_close">&nbsp;</span><span class="msg-success" style="margin-top: 5px; width: 320px; float: left; text-align: left;">'+login_success_msg+'</span>');
    window.setTimeout(function() {jQuery('#login_success').modal('hide');}, 2000);}
    else
    {
        var login_update_msg = "<?php echo Yii::t('frontend','Success! Your are login and'); ?>"+ '"'+x+'"' +"<?php echo Yii::t('frontend','add to favourite successfully!'); ?>";
        jQuery('#success').html('<span class="sucess_close">&nbsp;</span><span class="msg-success" style="margin-top: 5px; width: 320px; float: left; text-align: left;">'+login_update_msg+' </span>');}
        /*  jQuery('#success').html('<span class="sucess_close">&nbsp;</span><span class="msg-success" >Your Login successfully</span>');*/
        window.setTimeout(function() {jQuery('#login_success').modal('hide');}, 2000);
    }
    <?php if(Yii::$app->session->get('default')==1){ ?>
        window.onload=default_session_data(1);
        <?php Yii::$app->session->set('default',0); }?>
        </script>

        <script>
        <?php $fav=Yii::$app->session->get('favourite_status');
        if(!empty($fav)){?>
            window.onload=default_session_data(<?= Yii::$app->session->get('favourite_status')?>);
            <?php Yii::$app->session->set('favourite_status',''); }?>

            function display_event_modal()
            {
                jQuery('#EventModal').modal('show');
            }


            <?php if(Yii::$app->session->get('create_event')==1) { ?>
                window.onload=display_event_modal();
                <?php Yii::$app->session->set('create_event','0'); } ?>
                </script>

                <?php
                echo $reset_password=Yii::$app->session->get('reset_password_mail');
                echo $final_reset=Yii::$app->session->get('final_reset');
                //if((!empty($reset_password))&&($reset_password!=0)){
                if((!empty($reset_password))){
                    ?>
                    <script type="text/javascript">
                    function display_reset_password_modal()
                    {
                        var x='<?=$reset_password; ?>';
                        if(x!=1){
                            jQuery('#resetPwdModal').modal('show');
                            jQuery('#userid1').val('<?= $reset_password;?>');
                            <?php $reset_password=Yii::$app->session->set('reset_password_mail',''); ?>
                        }else{
                            var pwd_fail_msg = "<?php echo Yii::t('frontend','Password reset failed!'); ?>";

                            jQuery('#login_success').modal('show');
                            jQuery('#success').html('<span class="sucess_close">&nbsp;</span><span class="msg-success" style="margin-top: 5px; width: 320px; float: left; text-align: left;">'+pwd_fail_msg+'</span>');
                            window.setTimeout(function() {jQuery('#login_success').modal('hide');}, 2000);
                            <?php $reset_password=Yii::$app->session->set('reset_password_mail',''); ?>

                        }
                    }
                    window.onload=display_reset_password_modal;

                    </script>
                    <?php } ?>
                    <script type="text/javascript">
                    /* BEGIN ADD EVENT */
                    function addevent1(item_id)
                    {
                        jQuery.ajax({
                            type:'POST',
                            url:"<?= Url::toRoute('product/addevent'); ?>",
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
                    </script>
                    <?php
                    $event_status=Yii::$app->session->get('event_status');
                    if($event_status>0){
                        ?>
                        <script type="text/javascript">
                        var x='<?= $event_status;?>';
                        window.onload=addevent1(x);
                        </script>
                        <?php Yii::$app->session->set('event_status','0');} ?>


                        <script type="text/javascript">
                        function show_activate_modal_true()
                        {
                            jQuery('#login_activate').modal('show');
                            setTimeout(function(){
                                window.location.replace("<?= Yii::$app->homeUrl; ?>");
                            }, 1000);
                            /* jQuery('#success').text('Your Account Activated successfully!'); */
                            jQuery("#reload_page1 ,#reload_page2").click(function () {
                                /* window.location.replace("<?= Yii::$app->homeUrl; ?>"); */
                            });
                        }

                        /* BEGIN RESPONSIVE MENU SINGLE CLICK TO OPEN SUB MENUS */
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
                        </script>
                        <?php
                        if(Yii::$app->session->get('key')==1){
                            ?>
                            <script type="text/javascript">
                            window.onload=show_activate_modal_true();
                            </script>
                            <?php Yii::$app->session->set('key','0');} ?>

                            <?php
                            if(Yii::$app->session->get('key')==2){
                                ?>
                                <script type="text/javascript">
                                function show_password_reset_modal_true()
                                {
                                    var pwd_success_msg = "<?php echo Yii::t('frontend','Password reset successfully. Your login successfully!'); ?>";

                                    jQuery('#login_success').modal('show');
                                    jQuery('#success').html('<span class="sucess_close">&nbsp;</span><span class="msg-success" style="margin-top: 5px; width: 320px; float: left; text-align: left;">'+pwd_success_msg+'</span>');
                                    window.setTimeout(function() {jQuery('#login_success').modal('hide');}, 2000);
                                }
                                window.onload=show_password_reset_modal_true();
                                </script>
                                <?php Yii::$app->session->set('key','0');
                            }
                            ?>

<script type="text/javascript">
/* Registration Completed start*/
function show_register_modal_true()
{
    var reg_success_msg = "<?php echo Yii::t('frontend','Registration completed successfully.Confirmation link send to your registered email-id'); ?>";
    jQuery('#login_success').modal('show');
    jQuery('#success').html('<span class="sucess_close">&nbsp;</span><span class="msg-success" style="margin-top: 5px; width: 320px; float: left; text-align: left;">'+reg_success_msg+'</span>');
    window.setTimeout(function() {jQuery('#login_success').modal('hide');}, 2000);
}
</script>

<?php

$this->registerJsFile('@web/js/search.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('@web/js/footer.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('http://ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/jquery.validate.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
?>

<script type="text/javascript">
/* Registration Completed start*/
function show_event_modal_true()
{
    var event_name='<?=Yii::$app->session->get('event_name');?>';
    var item_name='<?=Yii::$app->session->get('item_name');?>';
    var created_msg = '"<?php echo Yii::t("frontend","EVENT"); ?> '+' '+event_name+' '+'<?php echo Yii::t("frontend","CREATED SUCCESSFULL"); ?> "';
    var added_msg = '"<?php echo Yii::t("frontend","EVENT"); ?> '+' '+event_name+' '+'<?php echo Yii::t("frontend","CREATED SUCCESSFULLY AND"); ?> '+' '+item_name+' '+'<?php echo Yii::t("frontend","ADDED TO");?>'+' '+event_name+'"';

    jQuery('#login_success').modal('show');
    if(!item_name.length){
        jQuery('#success').html('<span class="sucess_close">&nbsp;</span><span class="msg-success" style="margin-top: 5px; width: 320px; float: left; text-align: left;">'+created_msg+'</span>');
    }
    else {
        jQuery('#success').html('<span class="sucess_close">&nbsp;</span><span class="msg-success" style="margin-top: 5px; width: 320px; float: left; text-align: left;">'+added_msg+' </span>');
    }
    window.setTimeout(function() {jQuery('#login_success').modal('hide');}, 2000);
}
</script>
