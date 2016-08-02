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
    var event_slider_url = '".Url::toRoute('/product/event_slider')."';
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

    //language variables 
    var tick_the_terms_of_services_and_privacy_policy = '".Yii::t('frontend','Tick the terms of services and privacy policy')."';

    var password_should_contain_minimum_six_letters = '".Yii::t('frontend','Password should contain minimum six letters')."';

    var confirm_password_should_be_equal_to_password  = '".Yii::t('frontend','Confirm password should be equal to password')."';

    var password_and_confirm_password_should_be_minimum_six_letters_and_same = '".Yii::t('frontend','Password and Confirm password should be minimum six letters and same')."';

    var the_field_is_required = '".Yii::t('frontend','The field is required')."';

    var choose_date_and_month_of_birth = '".Yii::t('frontend','Choose date and month of birth')."';

    var choose_date_and_year_of_birth = '".Yii::t('frontend','Choose date and year of birth')."';

    var choose_month_and_year_of_birth = '".Yii::t('frontend','Choose month and year of birth')."';

    var choose_month_of_birth = '".Yii::t('frontend', 'Choose month of birth')."';

    var choose_year_of_birth = '".Yii::t('frontend', 'Choose year of birth')."';

    var choose_day_of_birth = '".Yii::t('frontend', 'Choose day of birth')."';

    var entered_email_id_is_already_exists = '".Yii::t('frontend', 'Entered email id is already exists')."';

    var enter_a_valid_email_id = '".Yii::t('frontend', 'Enter a valid email id')."';

    var kindly_select_event_type = '".Yii::t('frontend', 'Kindly select Event type')."';

    var no_record_found = '".Yii::t('frontend', 'No Record found')."';
    

", View::POS_HEAD);

Yii::$app->session->set('default',0);
Yii::$app->session->set('favourite_status','');
Yii::$app->session->set('create_event','0'); 
Yii::$app->session->set('reset_password_mail','');
Yii::$app->session->set('event_status','0');
Yii::$app->session->set('reset_password_mail','');
Yii::$app->session->set('register',0);
Yii::$app->session->set('key','0');

$this->registerJsFile('@web/js/jquery.touchSwipe.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('@web/js/search.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('@web/js/footer.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('http://ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/jquery.validate.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);