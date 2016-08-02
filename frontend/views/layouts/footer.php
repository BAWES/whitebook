<?php
use yii\helpers\Html;
use yii\helpers\Url;
use frontend\models\Users;
?>
<!-- Modal Login -->
<div class="modal fade" id="myModal" tabindex="-1" data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content  modal_member_login row">
            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <div class="text-center">
                    <span class="yellow_top"></span>
                </div>
                <h4 class="modal-title text-center" id="myModalLabel">
                    <span><?= Yii::t('frontend', 'SIGN IN') ?></span>
                </h4>
            </div>
            <div class="modal-body">
                <form class="form col-md-12 center-block" id="loginForm" name="loginForm" method="POST">
                    <input type="hidden" id="_csrf" name="_csrf" value="<?= Yii::$app->request->csrfToken; ?>" />

                    <div class="login-padding">
                        <div class="form-group">
                            <label><?= Yii::t('frontend', 'Email Address') ?></label>
                            <input type="text" placeholder="" name="email" id="email" class="form-control input-lg validation required" data-msg-required="<?= Yii::t('frontend', 'This field is required.'); ?>">
                            <input type="hidden" placeholder="" name="event_status" id="event_status" value="0" class="form-control input-lg">
                            <input type="hidden" placeholder="" name="favourite_status" id="favourite_status" value="0" class="form-control input-lg">
                            <span class="help-block"></span>
                            <span class="customer_email errors"></span>
                        </div>
                        <div class="form-group">
                            <label><?= Yii::t('frontend', 'Password') ?></label>
                            <input type="password" placeholder="" name="password" id="password" class="form-control input-lg validation required" data-msg-required="<?= Yii::t('frontend', 'This field is required.') ?>">
                            <span class="help-block"></span>
                            <span class="customer_password password errors"></span>
                        </div>
                        <div id="login_msg"></div>
                        <div id="result" style="color:red"></div>
                        <div id="loginErrorMsg" style="color:red"></div>
                        <span class="customer_status errors"></span>
                        <div class="form-group">
                            <div class="button-signin">
                                <button type="button" class="btn btn-primary btn-lg btn-block new_btn" id="signup_button" data-toggle="modal" data-target="#myModal1" onclick="show_register_modal();"><?= Yii::t('frontend','New User');?></button>
                                <button type="button" class="btn btn-primary btn-lg btn-block login_btn" id="login_button">
                                    <?= Yii::t('frontend','Login');?></button>
                                </div>
                                <div id="login_loader" style="display:none;text-align:center;margin-bottom: 10px;"><img src="<?=  Url::to('@web/images/ajax-loader.gif',true);?>"  title="<?= Yii::t('frontend', 'Loader') ?>"></div>
                                <span class="text-center forgotpwd"><a data-target="#forgotPwdModal" onclick="forgot_modal();"  data-dismiss="modal" data-toggle="modal" title="Signup" class="actionButtons" href="#forgotPwdModal"> <?= Yii::t('frontend', 'Forgot your password') ?></a></span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- end -->
    <div class="modal fade" id="login_success" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false" >
        <div class="modal-dialog">
            <div class="modal-content  modal_member_login row">
                <div class="modal-header">
                    <button type="button" id="reload_page1" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div id="success" style="width: 360px;"></div>
            </div>
        </div>
    </div>
    <!-- login succcess  start-->
    <!-- Modal Login -->
    <!-- Selva success modal start  -->
    <div class="modal fade" id="login_activate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false" >
        <div class="modal-dialog">
            <div class="modal-content  modal_member_login row">
                <div class="modal-header">
                    <button type="button" class="close"  id="reload_page1" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div id="success" style="width: 360px;"><span class="sucess_close">&nbsp;</span><span class="msg-success" style="margin-top: 5px; width: 320px; float: left; text-align: left;"><?= Yii::t('frontend', 'YOUR ACCOUNT ACTIVATED SUCCESSFULLY') ?></span></div>
            </div>
        </div>
    </div>
    <!-- end -->
    <!-- login fail -->
    <!-- Model end -->

    <!-- forgot password Modal -->
    <div class="modal fade" id="forgotPwdModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false" >
        <div class="modal-dialog">
            <div class="modal-content  modal_member_login row">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <div class="text-center">
                        <span class="yellow_top"></span>
                    </div>
                    <h4 class="modal-title text-center" id="myModalLabel">
                        <span><?= Yii::t('frontend', 'FORGOT PASSWORD') ?></span></h4>
                </div>
                <div class="modal-body">
                    <form id="forgotForm" name="forgotForm" method="POST" class="form col-md-12 center-block">
                        <div class="login-padding">
                            <div class="form-group">
                                <label><?= Yii::t('frontend', 'Email Address') ?></label>
                                <input type="text" placeholder="" name="forget_email" id="forget_email" class="form-control input-lg validation required" data-msg-required="<?= Yii::t('frontend', 'This field is required.') ?>">
                                <span class="help-block"></span>
                            </div>
                            <div id="forgot_result" style="color:red"></div>
                            <div class="button-signin">
                                <button type="button" class="btn btn-primary btn-lg btn-block new_btn" id="signup_button" data-toggle="modal" data-target="#myModal1" onclick="show_register_modal();"><?= Yii::t('frontend', 'NEW USER') ?></button>
                                <button type="button" class="btn btn-primary btn-lg btn-block login_btn" id="forgot_button" name="forgot_button"><?= Yii::t('frontend', 'Send') ?></button>
                            </div>
                            <div id="forgot_loader" style="display:none;text-align:center;margin-bottom: 10px;"><img src="<?php  echo Url::to('@web/images/ajax-loader.gif',true);?>"  title="<?= Yii::t('frontend', 'Loader') ?>"></div>

                            <span class="text-center forgotpwd">
                                <a data-target="#myModal" data-dismiss="modal" data-toggle="modal" title="Sign in" class="actionButtons" href="#forgotPwdModal"><?= Yii::t('frontend', 'SIGN IN') ?></a>
                            </span>
                            <div class="button-signin">
                            </div>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- end -->

<!-- Reset password Modal -->
<div class="modal fade" id="resetPwdModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false" >
    <div class="modal-dialog">
        <div class="modal-content  modal_member_login row">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <div class="text-center">
                    <span class="yellow_top"></span>
                </div>
                <h4 class="modal-title text-center" id="myModalLabel"><span><?= Yii::t('frontend', 'RESET PASSWORD') ?></span></h4>
            </div>
            <div class="modal-body">
                <form id="resetForm" name="resetForm" method="POST" class="form col-md-12 center-block">
                    <div class="login-padding">
                        <div class="form-group">
                            <label><?= Yii::t('frontend', 'New Password') ?></label>
                            <input type="hidden" id="userid1" name="userid1" value="1">
                            <input type="password" placeholder="" name="new_password" id="new_password" class="form-control input-lg validation required" data-msg-required="<?= Yii::t('frontend', 'This field is required.') ?>">
                            <span class="help-block"></span>
                        </div>
                        <div class="form-group">
                            <label><?= Yii::t('frontend', 'تأكيد كلمة المرور') ?></label>
                            <input type="password" placeholder="" name="confirm_password" id="confirm_password" class="form-control input-lg validation required" data-msg-required="<?= Yii::t('frontend', 'This field is required.') ?>">
                            <span class="help-block"></span>
                        </div>
                        <div id="reset_pwd_result" style="color:red"></div>
                        <div class="button-signin">
                            <button type="button" class="btn btn-primary btn-lg btn-block login_btn" id="reset_button" name="reset_button"><?= Yii::t('frontend', 'Submit') ?></button>
                        </div>
                        <div id="reset_loader" style="display:none;text-align:center;margin-bottom: 10px;"><img src="<?php  echo Url::to('@web/images/ajax-loader.gif',true);?>"  title="<?= Yii::t('frontend', 'Loader') ?>"></div>
                        <div class="button-signin">

                        </div>

                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
<!-- Reset Password end -->

<!-- Sign-up Modal -->
<div class="modal fade signup_modal_form" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false" >
    <div class="modal-dialog">
        <div class="modal-content  modal_member_signup row">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <div class="text-center">
                    <div id="registration_msg" style="color:green;margin-bottom: 10px;"></div>
                </div>
                <h4 class="modal-title text-center" id="myModalLabel">
                    <span> <?= Yii::t('frontend', 'Register') ?></span>
                </h4>
            </div>
            <div class="modal-body">
                <form class="form col-md-12 center-block" id="register_form">
                    <div class="login-padding">
                        <div class="col-md-6 col-sm-6 col-xs-12 padding-right0">
                            <div class="form-group">
                                <label><?= Yii::t('frontend', 'First Name') ?></label>
                                <input type="text" placeholder="" name="fname" id="fname" class="form-control input-lg validation required" data-msg-required="<?= Yii::t('frontend', 'This field is required.') ?>">
                                <input type="hidden" id="_csrf1" name="_csrf" value="<?= Yii::$app->request->csrfToken; ?>" />
                                <span class="customer_fname errors"></span>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12 padding-left0">
                            <div class="form-group">
                                <label><?= Yii::t('frontend', 'Last Name') ?></label>
                                <input type="text" placeholder="" name="lname" id="lname" class="form-control input-lg validation required" data-msg-required="<?= Yii::t('frontend', 'This field is required.') ?>">
                                <span class="customer_lname errors"></span>
                            </div>
                        </div>
                        <div class="clearfix"></div>

                        <div class="col-md-6 col-sm-6 col-xs-12 padding-right0">
                            <div class="form-group">
                                <label><?= Yii::t('frontend', 'Email Address') ?></label>
                                <input type="text" placeholder="" name="reg_email" id="reg_email" class="form-control input-lg validation required" data-msg-required="<?= Yii::t('frontend', 'This field is required.') ?>">
                                <span class="customer_email errors"></span>
                                <div id="customer_email" class="error"></div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12 padding-left0">
                            <div class="form-group">
                                <label><?= Yii::t('frontend', 'Date of Birth') ?></label>
                                <div class="col-md-12 padding0 birth_date_drop">
                                    <ul class="padding0">
                                        <li class="day-select">
                                            <select name="bday" id="bday" class="selectpicker" data-style="btn-primary" style="display: none;">
                                                <option value=""><?= Yii::t('frontend', 'Day') ?></option>
                                                <?php for($i=1;$i<=31;$i++)
                                                { ?>
                                                    <option value="<?php echo $i; ?>" <?php if(isset($model['bday']) && $model['bday']==$i) { echo "selected=selected"; } ?>><?php echo $i; ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </li>
                                        <li class="month-select">

                                            <select name="bmonth"  id="bmonth" class="selectpicker" data-style="btn-primary" style="display: none;">
                                                <option value=""><?= Yii::t('frontend', 'Month') ?></option>
                                                <option value="1" ><?= Yii::t('frontend', 'Jan') ?></option>
                                                <option value="2" ><?= Yii::t('frontend', 'Feb') ?></option>
                                                <option value="3" ><?= Yii::t('frontend', 'Mar') ?></option>
                                                <option value="4" ><?= Yii::t('frontend', 'Apr') ?></option>
                                                <option value="5" ><?= Yii::t('frontend', 'May') ?></option>
                                                <option value="6" ><?= Yii::t('frontend', 'Jun') ?></option>
                                                <option value="7" ><?= Yii::t('frontend', 'Jul') ?></option>
                                                <option value="8" ><?= Yii::t('frontend', 'Aug') ?></option>
                                                <option value="9" ><?= Yii::t('frontend', 'Sep') ?></option>
                                                <option value="10"><?= Yii::t('frontend', 'Oct') ?></option>
                                                <option value="11"><?= Yii::t('frontend', 'Nov') ?></option>
                                                <option value="12"><?= Yii::t('frontend', 'Dec') ?></option>
                                            </select>
                                        </li>
                                        <li class="year-select">
                                            <select class="selectpicker" id="byear" name="byear" data-style="btn-primary" style="display: none;">
                                                <option value=''><?= Yii::t('frontend', 'Year') ?></option>
                                                <?php
                                                $current= date('Y');
                                                $current= $current-5;
                                                for($i=$current; $i>1950; $i--) {
                                                    $sel='';
                                                    print('<option value="'.$i.'" '.$sel.' >'.$i.'</option>'."\n");
                                                }
                                                ?>
                                            </select>
                                        </li>
                                    </ul>
                                    <div id="dob_er" class="error"></div>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-md-6 col-sm-6 col-xs-12 padding-right0">
                            <div class="form-group reg_gender">
                                <label><?= Yii::t('frontend', 'Gender') ?></label>
                                <div class="col-md-12 padding0 gender-select">
                                    <select class="selectpicker" data-style="btn-primary" id="gender" name="gender" style="display: none;">
                                        <option value=""><?= Yii::t('frontend', 'Choose Gender') ?></option>
                                        <option value="Male"><?= Yii::t('frontend', 'Male') ?></option>
                                        <option value="Female"><?= Yii::t('frontend', 'Female') ?></option>
                                    </select>
                                </div>
                                <div class="clearfix">
                                    <span class="customer_status errors"></span>
                                    <div id="gen_er" class="error"></div>
                                </div>
                            </div>


                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12 padding-left0">
                            <div class="form-group">
                                <label><?= Yii::t('frontend', 'Mobile Number') ?></label>
                                <input type="text" placeholder="" name="phone" id="phone" class="form-control input-lg validation required" data-msg-required="<?= Yii::t('frontend', 'This field is required.') ?>">
                                <span class="customer_mobile errors"></span>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-md-6 col-sm-6 col-xs-12 padding-right0">
                            <div class="form-group">
                                <label><?= Yii::t('frontend', 'Password') ?></label>
                                <input type="password" placeholder="" name="userpassword" id="userpassword" class="form-control input-lg validation required" data-msg-required="<?= Yii::t('frontend', 'This field is required.') ?>">
                                <span class="customer_password password errors"></span>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12 padding-left0">
                            <div class="form-group">
                                <label><?= Yii::t('frontend', 'Confirm Password') ?></label>
                                <input type="password" placeholder="" name="conpassword" id="conpassword" class="form-control input-lg validation required" data-msg-required="<?= Yii::t('frontend', 'This field is required.') ?>">
                                <span class="customer_conpassword password errors"></span>

                                <div id="con_pass"  class="error"></div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="form-group">
                           <div class="i-agree text-center col-xs-12">
                            <label for="checkbox-50" class="label_check c_off" id="label_check1">
                                <input type="checkbox" id="agree_terms" name="agree_terms" value="0"><?= Yii::t('frontend', 'I agree to the') ?> </label> <a href="<?=  Url::toRoute('/privacy-policy',true);?>" title="<?= Yii::t('frontend', 'Privacy Policy') ?>">&nbsp;<?= Yii::t('frontend', 'Privacy Policy') ?>&nbsp;</a>&amp;<a href="<?=  Url::toRoute('/terms-conditions',true);?>" title="<?= Yii::t('frontend', 'Terms of Service') ?>">&nbsp;<?= Yii::t('frontend', 'Terms of Service') ?></a>
                                <div id="agree" class="error"></div>
                            </div>

                            <div id="register_loader" style="display:none;text-align:center;margin-bottom: 10px;"><img src="<?= Url::to('@web/images/ajax-loader.gif',true);?>"  title="<?= Yii::t('frontend', 'Loader') ?>"></div>

                            <div class="button-signin">
                                <div id="loader1" style="display:none;text-align:center;margin-bottom: 10px;"><img src="<?=  Url::to('@web/images/ajax-loader.gif',true);?>"  title="<?= Yii::t('frontend', 'Loader') ?>"></div>
                                <button type="button" id="register" name="register" class="btn btn-primary btn-lg btn-block login_btn"><?= Yii::t('frontend', 'Register') ?></button>
                            </div>
                            <span class="text-center forgotpwd"><?= Yii::t('frontend', 'Already a member?') ?><a data-target="#myModal" onclick="show_mydata();" data-toggle="modal" title="<?= Yii::t('frontend', 'SIGN IN') ?>" class="actionButtons" href="">
                                <?= Yii::t('frontend', 'Sign in here') ?> </a></span>

                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<!-- end -->

<!-- begin Add to event modal-->
<div class="modal fade" id="add_to_event" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content  modal_member_login signup_poupu row">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" id="boxclose" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <div class="text-center">
                    <span class="yellow_top"></span>
                </div>
                <h4 class="modal-title text-center" id="myModalLabel"><?php echo Yii::t('frontend','Add to Event');?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12 text-center" id="addevent">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
<!-- end Add to event modal-->

<!-- footer start cntent -->
<footer id="footer_sections">
    <div class="container paddng0">
        <div class="top_footer">
            <div class="col-md-7 padding_left0">
                <span class="footer_logo">
                    <a href="<?= Url::toRoute('site/index');?>" title="Logo"><?= Html::img("@web/images/footer_logo.png") ?></a>
                </span>
                <p class="bot_desc"><!-- Description here --></p>
            </div>
            <div class="col-md-1"><div class="divider_bot"></div></div>
            <div class="col-md-4 padding_left0">
                <address class="address_bot">
                    <h3><?= Yii::t("frontend", "We're here to help") ?></h3>
                    <p><?= Yii::t("frontend", "Our team is ready to help via email") ?></p>
                    <a style='color:white' href='mailto:hello@thewhitebook.com.kw'>hello@thewhitebook.com.kw</a>
                </address>
            </div>
        </div>
        <div class="bootom_footer">
            <div class="col-md-7 padding_left0">
                <ul class="treams_of_ser margin_top">
                    <li><a href="<?= Url::toRoute('/about-us',true);?>"> <?= Yii::t("frontend", "About Us") ?></a></li>
                    <li><a href="<?= Url::toRoute('/contact-us',true);?>"> <?= Yii::t("frontend", "Contact & FAQ") ?></a></li>
                    <li><a href="<?= Url::toRoute('site/directory',true); ?>"> <?= Yii::t("frontend", "Directory") ?></a></li>
                </ul>
                <ul class="treams_of_ser">
                    <li> &COPY;2015 - The White Book </li>
                    <li><a href="<?= Url::toRoute('/terms-conditions',true);?>"><?= Yii::t("frontend", "Terms and Conditions") ?></a></li>
                    <li><a href="<?= Url::toRoute('/privacy-policy',true);?>"><?= Yii::t("frontend", "Privacy Policy") ?></a></li>
                </ul>
            </div>
            <div class="col-md-5"></div>
        </div>

        <div class="bootom_footer_responsive">
            <span class="footer_logo text-center col-xs-12">
                <a href="#" title="Logo"><?= Html::img('@web/images/mobile_logo.svg', ['alt' => 'The White Book']); ?></a>
            </span>
            <div class="col-md-7 col-sm-12 padding_left0"  id="accordion">
                <a class="accor-link" data-toggle="collapse" data-parent="#accordion" href="#collapsefoot">Useful Information</a>
                <div id="collapsefoot" class="panel-collapse collapse">
                    <ul class="treams_of_ser margin_top">

                        <li><a href="<?= Url::toRoute('/about-us',true);?>"> <?= Yii::t("frontend", "About Us") ?></a></li>
                        <li><a href="<?= Url::toRoute('/contact-us',true);?>"> <?= Yii::t("frontend", "Contact & FAQ") ?></a></li>
                        <li><a href="<?= Url::toRoute('/brands-product',true); ?>/"> <?= Yii::t("frontend", "Directory") ?></a></li>
                        <li><a href="<?= Url::toRoute('/terms-conditions',true);?>"><?= Yii::t("frontend", "Terms and Conditions") ?></a></li>
                        <li><a href="<?= Url::toRoute('/privacy-policy',true);?>"><?= Yii::t("frontend", "Privacy Policy") ?></a></li>
                    </ul>
                </div>

            </div>
            <ul class="treams_of_ser copyright col-md-7 col-sm-12 padding_left0">
                <li>   &COPY;2015 - The White Book </li>

            </ul>
            <div class="col-md-5"></div>
        </div>
    </div>
</footer>

<!-- megamenu script -->
<style>
    .datepicker{z-index:1151 !important;}
</style>

<?php 

require(__DIR__ . '/footer_js.php'); 

$event=Yii::$app->session->get('event_name');

if(!empty($event)){
    $this->registerJs('
        window.onload=show_event_modal_true();
        /* Registration Completed start*/
    ');

    Yii::$app->session->set('event_name','');
    Yii::$app->session->set('item_name','');
} 

?>
