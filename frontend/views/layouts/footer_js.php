<?php

use Yii\helpers\Url;
use yii\web\View;

$this->registerJs("
    var password_reset_link = '".Url::toRoute(['/users/password_reset'])."';
    var pwd_reset_msg = '".Yii::t('frontend','Your password has been reset and you are now logged in')."';
    var user_login = '".Url::toRoute('/users/login')."';
    var not_activate_msg = '".Yii::t('frontend','Your account is inactive, please activate your account by verifying your email')."';
    var user_blocked_msg = '".Yii::t('frontend','User blocked')."';
    var email_not_match = '".Yii::t('frontend','Email and password does not match')."';
    var email_not_exist = '".Yii::t('frontend','Your email does not exist')."';
    var success_fav_added1 = '".Yii::t('frontend','You are now logged in and')."';
    var success_fav_added2 = '".Yii::t('frontend','Added to Things I like')."';
    var reg_email = '".Yii::t('frontend','Enter registered email')."';
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
    var contact_admin = '".Yii::t("frontend","Email not found")."';
    var reg_email_id = '".Yii::t('frontend','Enter registered email')."';
    var search_result_url = '".Url::toRoute('/search-result/')."/';
    var home_url = '".Yii::$app->homeUrl."';
    var site_search = '".Url::toRoute('/search/search')."';
    var event_slider_url = '".Url::toRoute('/product/event_slider')."';
    var item_removed_fav = '".Yii::t('frontend','Item removed from Things I like')."';
    var remove_from_wishlist = '".Url::toRoute('/users/remove_from_wishlist')."';
    var item_add_to_wishlist_failed = '".Yii::t('frontend','Failed adding the item to your event list')."';
    var item_add_to_wishlist_already_exist = '".Yii::t('frontend','Item already exists in this event')."';
    var item_add_to_wishlist_success = '".Yii::t('frontend','Item has been added to your event')."';
    var add_to_wishlist_url = '".Url::toRoute('/users/add_to_wishlist')."';
    var giflink = '".Url::to("@web/images/ajax-loader.gif")."';
    var event_name = '".Yii::$app->session->get('event_name')."';
    var item_name = '".Yii::$app->session->get('item_name')."';

    var text_event = '".Yii::t("frontend","Event")."';
    var created_successfully = '".Yii::t("frontend","successfully created")."';
    var created_successfully_and = '".Yii::t("frontend","successfully created and")."';
    var_added_to = '".Yii::t("frontend","added to")."';

    var isGuest = '".Yii::$app->user->isGuest."';
    var add_event_url = '".Url::toRoute('/users/add_event')."';
    var successfully_added_to = '".Yii::t('frontend',' successfully added to ')."';
    var category = '".Yii::t('frontend','Category')."';
    var eventinvitees_url = '".Url::toRoute(['eventinvitees/index'])."';
    var eventinvitees_add_event_url = '".Url::toRoute(['/eventinvitees/addevent'])."';
    var login_success_msg = '".Yii::t('frontend','You are now logged in')."';
    var session_default = '".Yii::$app->session->get('default')."';
    var session_favourite_status = '".Yii::$app->session->get('favourite_status')."';
    var session_create_event = '".Yii::$app->session->get('create_event')."';
    var session_reset_password = '".Yii::$app->session->get('reset_password_mail')."';
    var session_final_reset = '".Yii::$app->session->get('final_reset')."';
    var session_register = '".Yii::$app->session->get('register')."';
    var session_show_login_modal = '".Yii::$app->session->get('show_login_modal')."';
    var you_are_login_and = '".Yii::t('frontend','You are now logged in and')."';
    var session_key = '".Yii::$app->session->get('key')."';
    var add_to_favourite_successfully = '".Yii::t('frontend','Added to Things I like')."';
    var pwd_fail_msg = '".Yii::t('frontend','Password reset failed!')."';
    var product_add_event_url = '".Url::toRoute('product/addevent')."';
    var session_event_status = '".Yii::$app->session->get('event_status')."';
    var reg_success_msg = '".Yii::t('frontend','A confirmation link will be sent to your email to activate your account')."';
    var pwd_success_msg = '".Yii::t('frontend','Your password has been reset and you are now logged in')."';
    //language variables
    var tick_the_terms_of_services_and_privacy_policy = '".Yii::t('frontend','You must agree to the terms and conditions and the privacy policy')."';
    var password_should_contain_minimum_six_letters = '".Yii::t('frontend','Password should contain minimum six letters')."';
    var confirm_password_should_be_equal_to_password  = '".Yii::t('frontend','Password and confirm password should match')."';
    var password_and_confirm_password_should_be_minimum_six_letters_and_same = '".Yii::t('frontend','Password and confirm password should match and contain a minimum of 6 letters')."';
    var the_field_is_required = '".Yii::t('frontend','The field is required')."';
    var entered_email_id_is_already_exists = '".Yii::t('frontend', 'Email already exists')."';
    var enter_a_valid_email_id = '".Yii::t('frontend', 'Enter a valid email')."';
    var kindly_select_event_type = '".Yii::t('frontend', 'Kindly select Event type')."';
    var no_record_found = '".Yii::t('frontend', 'No Record found')."';
    var giflink             = '".Url::to("@web/images/ajax-loader.gif")."';
    var addevent            = '".Url::to(['/product/addevent'])."';
", View::POS_HEAD);

Yii::$app->session->set('default',0);
Yii::$app->session->set('favourite_status','');
Yii::$app->session->set('create_event','0');
Yii::$app->session->set('reset_password_mail','');
Yii::$app->session->set('event_status','0');
Yii::$app->session->set('reset_password_mail','');
Yii::$app->session->set('register',0);
Yii::$app->session->set('key','0');
Yii::$app->session->set('show_login_modal', 0);

//$this->registerJsFile('@web/js/jquery.touchSwipe.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
//$this->registerJsFile('@web/js/search.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('@web/js/footer.js?v=1.4', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('https://ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/jquery.validate.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
