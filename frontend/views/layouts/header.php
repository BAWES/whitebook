<?php

use yii\helpers\Html;
use yii\helpers\Url;
use frontend\models\Website;
use common\models\CustomerCart;
$action = Yii::$app->controller->action->id;

?>
<!-- header main start  -->
<header id="top_header" class="ma5-page">
    <!-- home top header login det -->
    <div class="border-top-yellow" role="navigation">

        <div class="container">
            <div class="mobile-menu" id="mobile-sid">
                <div class="logo-sec">
                    <a href="#0" class="ma5-toggle-menu">
                        <span class="demo">
                            <?= Html::img('@web/images/responsive-button.png', ['alt' => 'Menu']); ?>
                        </span>
                    </a>
                </div>
                <div class="logo_header col-xs-10 text-center padding-right0">
                    <a href="<?= Url::toRoute('site/index', true); ?>" title="THEWHITEBOOK">
                        <?= Html::img('@web/images/mobile_logo.svg', ['alt' => 'Whitebook']); ?>
                    </a>
                    <div class="search_header col-xs-3 ses_act">
                        <div id="label1">
                            <label for="search-terms" id="search-label" class="search-lbl-mobile"></label>
                        </div>
                    </div>
                </div>
                <form id="search_form" method="get" action="<?= Url::to(['search/index']); ?>">
                    <div id="input1" class="left_slider">
                        <input type="text" name="search" id="search-terms2" class="search-box" placeholder="Search here" autocomplete="off" />
                        <button class="btn btn-primary"><i class="fa fa-arrow-right"></i></button>
                        <button type="button" class="btn btn-default btn-close"><i class="fa fa-close"></i></button>
                    </div>
                </form><!-- END #search_form -->
                <div id="mobile_search_list" class="mobile-search-term"></div>
                <div id="mobile_search_fail"></div>
                <div id="desktop_search_fail"></div>
            </div>
            <div class="desktop-menu">
                <div class="col-md-12">
                    <div class="col-md-3 col-xs-12 col-sm-4 respo_hidde"></div>
                    <div class="col-md-6 col-xs-12 col-sm-6">
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                                <span class="sr-only"><?= Yii::t('frontend', 'Toggle navigation') ?></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                        </div>
                        <div class="logo_header">
                            <a href="<?= Url::toRoute('site/index', true); ?>"  title="THEWHITEBOOK">
                                <?= Html::img('@web/images/logo.svg', [
                                    'alt' => 'The White Book',
                                    'title' => 'The Whitebook',
                                    'width' => 239,
                                    'height' => 33,
                                    'style' => 'width:239px;height:33px;'
                                ]) ?>
                            </a>
                        </div>

                        <div class="navbar-collapse collapse">
                            <ul class="nav navbar-nav">
                                <li class="">
                                    <?= Html::a(Yii::t('frontend', 'Browse'), ['browse/list','slug'=>'all'], ['title' => Yii::t('frontend', 'Product')]); ?>
                                </li>

                                <?php if(Yii::$app->params['feature.packages.enabled'] == true){ ?>
                                    <li class="<?php if ($action == "packages") { echo "active";} ?>">
                                        <a href="<?= Url::toRoute('packages/index', true); ?>" title="<?php echo Yii::t('frontend', 'Packages'); ?>">
                                            <?php echo Yii::t('frontend', 'Packages'); ?>
                                        </a>
                                    </li>
                                <?php } ?>

                                <li  class="<?php if ($action == "experience") { echo "active";} ?>">
                                    <a href="<?= Url::toRoute('site/experience', true); ?>" title="<?php echo Yii::t('frontend', 'Experience'); ?>">
                                        <?php echo Yii::t('frontend', 'Experience'); ?>
                                    </a>
                                </li>
                                <li  class="<?php if ($action == "themes") { echo "active";} ?>">
                                    <a href="<?= Url::toRoute('/themes/index', true); ?>" title="<?php echo Yii::t('frontend', 'Themes'); ?>">
                                        <?php echo Yii::t('frontend', 'Themes'); ?>
                                    </a>
                                </li>
                                <li class="<?php if ($action == "directory") { echo "active";} ?>">
                                    <a href="<?= Url::toRoute('/directory/index', true); ?>" title="<?php echo Yii::t('frontend', 'Directory'); ?>">
                                        <?php echo Yii::t('frontend', 'Directory'); ?>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-3 col-xs-12 col-sm-6 padding-left0 <?php if (!Yii::$app->user->isGuest) { echo 'new_user_name'; } ?>">
                        <?php if (!Yii::$app->user->isGuest) { ?>
                        <div class="user_name_cont">
                            <p>
                            <?= Yii::t('frontend', 'Hi, {customer_name}!', [
                                    'customer_name' => Yii::$app->user->identity->customer_name
                                ]) ?>
                            </p>
                        </div>
                        <?php } ?>

                        <?php if(Yii::$app->language == "en"){ ?>
                            <a  href="<?= Url::current(['language'=>'ar', ]) ?>"
                                style="position: absolute; top: 9px; right: <?= Yii::$app->user->isGuest?0:120 ?>px;" class="respo_hidde">العربية</a>
                        <?php }else{ ?>
                            <a  href="<?= Url::current(['language'=>'en']) ?>"
                                style="position: absolute; top: 9px; right: <?= Yii::$app->user->isGuest?0:120 ?>px;" class="respo_hidde">English</a>
                        <?php } ?>


                        <ul class="logout_part">
                        <?php if (Yii::$app->user->isGuest) { ?>
                            <li class="">
                                <a href="" data-toggle="modal"  onclick="show_login_modal('-2');" data-target="#myModal"
                                title="<?php echo Yii::t('frontend', 'Sign in / Register'); ?>">
                                    <?php echo Yii::t('frontend', 'Sign in / Register'); ?>
                                </a>
                            </li>

                            <li class="">
                                <a href="<?= Url::toRoute(['orders/view'],true);?>">
                                    <?= Yii::t('frontend', 'Track order'); ?>
                                </a>
                            </li>

                            <li class="">
                                <a href="<?= Url::toRoute('/contact-us',true);?>">
                                    <?= Yii::t('frontend', 'Become a Vendor'); ?>
                                </a>
                            </li>

                        <?php } else { ?>

                        <li>
                            <a data-toggle="dropdown" title="<?php echo Yii::t('frontend', 'My Account'); ?>">
                                <?php echo Yii::t('frontend', 'My Account'); ?>
                            </a>
                            <ul class="account-dropdown-menu">
                                <li><a href="<?php echo Url::to(['booking/pending']); ?>" title="<?php echo Yii::t('frontend', 'My Pending Booking'); ?>"><?php echo Yii::t('frontend', 'My Pending Booking'); ?></a></li>
                                <li><a href="<?php echo Url::toRoute('/users/account_settings', true); ?>" title="<?php echo Yii::t('frontend', 'Account Settings'); ?>"><?php echo Yii::t('frontend', 'Account Settings'); ?></a></li>
                                <li><a href="<?php echo Url::toRoute('/users/address', true); ?>" title="<?php echo Yii::t('frontend', 'Address Book'); ?>"><?php echo Yii::t('frontend', 'Address Book'); ?></a></li>
                                <li><a href="<?php echo Url::to(['things-i-like/index'], true); ?>" title="<?php echo Yii::t('frontend', 'Things I like'); ?>"><?php echo Yii::t('frontend', 'Things I like'); ?></a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="<?php echo Url::to(['cart/index']); ?>">
                                <?php echo Yii::t('frontend', 'Cart ({count})', ['count' => CustomerCart::item_count()]); ?>
                            </a>
                        </li>
                        <?php
                            /*
                                @TODO Removed Event Section
                        ?>
                        <li><a href="<?php echo Url::toRoute(['/events/index']) ?>" title="<?php echo Yii::t('frontend', 'My Events'); ?>"><?php echo Yii::t('frontend', 'My Events'); ?></a></li>
                        <?php */ ?>
                        <li><a href="<?php echo Url::toRoute('/users/logout', true); ?>" title="<?php echo Yii::t('frontend', 'Logout'); ?>"><?php echo Yii::t('frontend', 'Logout'); ?></a></li>
                        <?php } ?>

                        </ul><!-- END .logout_part -->

                        <div class="search_header">
                            <div class="input-group">
                                <div id="navigation-bar">
                                    <form id="search_form" method="get" action="<?=Url::toRoute(['/search/index'],true); ?>">
                                        <?php
                                        if (!Yii::$app->user->isGuest) {
                                            $search_div = '<div class="form-group has-feedback" style="margin-bottom:0px">';
                                        } else {
                                            $search_div = '<div class="form-group has-feedback">';
                                        }
                                        echo $search_div;
                                        ?>
                                        <label for="search" class="sr-only"><?= Yii::t("frontend", "Search") ?></label>
                                        <input type="search" class="form-control sear_ip_head" onkeyup="show_close3()" autofocus name="search" id="search_input_header" autocomplete="off" title="search" placeholder="<?= Yii::t("frontend", "SEARCH FOR...") ?>">
                                        <button id="search-close1" class="search-clear icon-search_clear" type="reset"><?= Yii::t("frontend", "Clear") ?></button>
                                        <a id="sear_button" href="#" class=" "></a>
                                        <input type="submit" id="sear_button_submit"/>
                                        <div id="desk-search-label" class="search_for">
                                            <?= Yii::t("frontend", "Search") ?>
                                        </div>
                                        <div id="search_list"></div>
                                        <div id="search_list_fail1"></div>
                                    </form>
                                </div><!-- END .navigation-bar -->
                            </div><!-- END .input-group -->
                        </div><!-- END .search_header -->
                    </div>
                    </div><!-- END .col-md-3 -->
                </div>

                <div class="clearfix"></div>
                
                <div class="category_listing_nav plan_menu
                    <?php if(Yii::$app->language == 'ar') echo 'rtl'; ?>">

                    <ul class="nav-list list-inline">
                        <li>
                            <a title="<?php echo Yii::t('frontend', 'Venues'); ?>  " href="<?= Url::to(["browse/list", 'slug' => 'venues']); ?>">
                                <span class="venus_icon"></span>
                                <span> <?php echo Yii::t('frontend', 'Venues'); ?></span>
                            </a>
                        </li>
                        <li>
                            <a title=" <?php echo Yii::t('frontend', 'Invitations'); ?>" href="<?= Url::to(["browse/list", 'slug' => 'invitations']); ?>">
                                <span class="invit_icon"></span>
                                <span> <?php echo Yii::t('frontend', 'Invitations'); ?></span>
                            </a>
                        </li>
                        <li>
                            <a title="<?php echo Yii::t('frontend', 'Food & Beverage'); ?>" href="<?= Url::to(["browse/list", 'slug' => 'food-beverage']); ?>">
                                <span class="food_map"></span>
                                <span><?php echo Yii::t('frontend', 'Food & Beverage') ?></span>
                            </a>
                        </li>
                        <li>
                            <a title="<?php echo Yii::t('frontend', 'Decor'); ?>" href="<?= Url::to(["browse/list", 'slug' => 'decor']); ?>">
                                <span class="decor"></span>
                                <span><?php echo Yii::t('frontend', 'Decor'); ?> </span>
                            </a>
                        </li>
                        <li>
                            <a title="<?php echo Yii::t('frontend', 'Supplies'); ?>" href="<?= Url::to(["browse/list", 'slug' => 'supplies']); ?>">
                                <span class="supplies"></span>
                                <span><?php echo Yii::t('frontend', 'Supplies'); ?> </span>
                            </a>
                        </li>


                        <li>
                            <a title="<?php echo Yii::t('frontend', 'Entertainment'); ?>" href="<?= Url::to(["browse/list", 'slug' => 'entertainment']); ?>">
                                <span class="entert"></span>
                                <span><?php echo Yii::t('frontend', 'Entertainment'); ?></span>
                            </a>
                        </li>
                        <li>
                            <a title=" <?php echo Yii::t('frontend', 'Services'); ?>" href="<?= Url::to(["browse/list", 'slug' => 'services']); ?>">
                                <span class="serv"></span>
                                <span> <?php echo Yii::t('frontend', 'Services'); ?></span>
                            </a>
                        </li>
                        <li>
                            <a title="<?php echo Yii::t('frontend', 'Other'); ?>" href="<?= Url::to(["browse/list", 'slug' => 'others']); ?>">
                                <span class="other"></span>
                                <span><?php echo Yii::t('frontend', 'Other'); ?></span>
                            </a>
                        </li>
                        <li><a title="<?php echo Yii::t('frontend', 'Gift Favors'); ?>" href="<?= Url::to(["browse/list", 'slug' => 'gift-favors']); ?>">
                                <span class="say_thank"></span>
                                <span><?php echo Yii::t('frontend', 'Gift Favors'); ?></span>
                            </a></li>
                    </ul>
                </div>

            </div><!-- END .desktop-menu -->
        
    </div>
</div>
</header>
<!-- header main end  -->

<?php if(!Yii::$app->user->isGuest) { ?>
<!-- BEGIN Create event Modal Box -->
<div class="modal fade" id="EventModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" id="eventModal">
        <div class="modal-content  modal_member_login signup_poupu row">
            <div class="modal-header">
                <button type="button" class="close" id="boxclose" name="boxclose" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <div class="text-center">
                    <span class="yellow_top"></span>
                </div>
                <h4 class="modal-title text-center" id="myModalLabel"><?php echo Yii::t('frontend', 'Create New Event'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-8 col-xs-offset-2">
                        <div class="product_popup_signup_box">
                            <div class="product_popup_signup_log">
                                <form name="create_event" id="create_event">
                                    <input type="hidden" id="_csrf" name="_csrf" value="<?= Yii::$app->request->csrfToken; ?>" />
                                    <div class="form-group">
                                        <input type="text" name="event_name" class="form-control required" id="event_name" placeholder="<?php echo Yii::t('frontend', 'Enter Event Name'); ?>" title="<?php echo Yii::t('frontend', 'Enter Event Name'); ?>">
                                    </div>
                                    <div class="form-group">
                                        <input type="hidden" name="item_id" class="form-control required" id="item_id" value="0">
                                    </div>
                                    <div class="form-group top_calie_new">
                                        <div data-date-format="dd-mm-yyyy" data-date="12-02-2012" id="dp3" class="input-append date">
                                            <input type="text"  name="event_date" id="event_date" readonly size="16" class="form-control required datetimepicker date1" placeholder="<?php echo Yii::t('frontend', 'Choose Event Date'); ?>" title="<?php echo Yii::t('frontend', 'Choose Event Date'); ?>">
                                            <span class="add-on position_news"> <i class="flaticon-calendar189"></i></span>
                                        </div>
                                        <label for="event_date" class="error"></label>
                                    </div>
                                    <div class="form-group new_popup_common">
                                        <div class="bs-docs-example">
                                            <select class="selectpicker required trigger" name="event_type" style="btn-primary" id="event_type" >
                                            <option value="">
                                                <?php echo Yii::t('frontend', 'Select event type') ?>
                                            </option>
                                            <?php $event_type = Website::get_event_types();
                                            foreach ($event_type as $e) { ?>
                                            <option value="<?php echo $e['type_name']; ?>">
                                                <?php echo $e['type_name']; ?>

                                            </option>
                                            <?php } ?>
                                            </select>

                                            <div class="error" id="type_error"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="no_of_guests" class="form-control required" id="no_of_guests" placeholder="<?php echo Yii::t('frontend', 'Enter number of guests'); ?>" />
                                    </div>

                                    <div id="eventresult"></div>
                                    <div class="eventErrorMsg error"></div>
                                    <div class="event_loader"><img src="<?php echo Url::to('@web/images/ajax-loader.gif', true); ?>" title="Loader"></div>
                                    <div class="buttons">
                                        <div class="creat_evn_sig">
                                            <button type="button" id="create_event_button" name="create_event_button" class="btn btn-default" title="<?php echo Yii::t('frontend', 'Create Event'); ?>"><?php echo Yii::t('frontend', 'Create Event'); ?></button>
                                        </div>
                                        <div class="cancel_sig">
                                            <input class="btn btn-default" data-dismiss="modal"  id="cancel_button" name="cancel_button" type="button" value="<?php echo Yii::t('frontend', 'Cancel'); ?>" title="<?php echo Yii::t('frontend', 'Cancel'); ?>">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>
<!-- END Create event Modal Box -->

<!-- BEGIN EDIT EVENT MODAL BOX -->
<div class="modal fade" id="EditeventModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" id="editeventModal">
    </div>
</div>
<!-- END EDIT EVENT MODAL BOX -->

<!--navigation mobile menu start-->
<nav class="ma5-menu-mobile mobile-menu">
    <div class="navbar-collapse">
        <ul class="nav navbar-nav ma5-ul" style=" border:none;">
            <li class="ma5-li-0">
                <a title="<?php echo Yii::t('frontend', 'Home'); ?>" href="<?= Url::toRoute(['/site/index'], true); ?>">
                    <span> <?php echo Yii::t('frontend', 'Home'); ?></span>
                </a>
            </li>
            <li class="ma5-li-1">
                <a class="ma5-path-to-active ma5-btn-enter" href="#node1"><?= Yii::t('frontend', 'Browse'); ?></a>
                <ul class="ma5-ul-1 navbar-nav">
                    <li class="ma5-li-1-0">
                        <div class="ma5-leave-bar">
                            <span class="ma5-btn-leave">

                            </span>
                            <a class="ma5-path-to-active ma5-btn-enter" href="#node1"><?= Yii::t('frontend', 'Back'); ?></a>
                        </div></li>
                    <li class="ma5-li-1-1">
                        <a title="<?php echo Yii::t('frontend', 'Venues'); ?>" href="<?= Url::toRoute(['browse/list','slug'=>'venues'], true); ?>">
                            <span class="venus_icon"></span>
                            <span> <?php echo Yii::t('frontend', 'Venues'); ?></span>
                        </a>
                    </li>

                    <li class="ma5-li-1-2">
                        <a title=" <?php echo Yii::t('frontend', 'Invitations'); ?>" href="<?= Url::toRoute(['browse/list', 'slug'=>'invitations'], true); ?>">
                            <span class="invit_icon"></span>
                            <span> <?php echo Yii::t('frontend', 'Invitations'); ?></span>
                        </a>
                    </li>
                    <li class="ma5-li-1-3">
                        <a title="<?php echo Yii::t('frontend', 'Food & Beverage'); ?>" href="<?= Url::toRoute(['browse/list', 'slug'=>'food-beverage'], true); ?>">
                            <span class="food_map"></span>
                            <span><?php echo Yii::t('frontend', 'Food & Beverage') ?></span>
                        </a>
                    </li>
                    <li class="ma5-li-1-4">
                        <a title="<?php echo Yii::t('frontend', 'Decor'); ?>"  href="<?= Url::toRoute(['browse/list', 'slug'=>'decor'], true); ?>">
                            <span class="decor"></span>
                            <span><?php echo Yii::t('frontend', 'Decor'); ?> </span>
                        </a>
                    </li>
                    <li class="ma5-li-1-5">
                        <a title="<?php echo Yii::t('frontend', 'Supplies'); ?>" href="<?= Url::toRoute(['browse/list', 'slug'=>'supplies'], true); ?>">
                            <span class="supplies"></span>
                            <span><?php echo Yii::t('frontend', 'Supplies'); ?> </span>
                        </a>
                    </li>

                    <li class="ma5-li-1-6">
                        <a title="<?php echo Yii::t('frontend', 'Entertainment'); ?>" href="<?= Url::toRoute(['browse/list', 'slug'=>'entertainment'], true); ?>">
                            <span class="entert"></span>
                            <span><?php echo Yii::t('frontend', 'Entertainment'); ?></span>
                        </a>
                    </li>
                    <li class="ma5-li-1-7">
                        <a title=" <?php echo Yii::t('frontend', 'Services'); ?>" href="<?= Url::toRoute(['browse/list', 'slug'=>'services'], true); ?>">
                            <span class="serv"></span>
                            <span> <?php echo Yii::t('frontend', 'Services'); ?></span>
                        </a>
                    </li>
                    <li class="ma5-li-1-8">
                        <a title="<?php echo Yii::t('frontend', 'Other'); ?>" href="<?= Url::toRoute(['browse/list', 'slug'=>'others'], true); ?>">
                            <span class="other"></span>
                            <span><?php echo Yii::t('frontend', 'Other'); ?></span>
                        </a>
                    </li>
                    <li class="ma5-li-1-9">
                        <a title="<?php echo Yii::t('frontend', 'Gift Favors'); ?>" href="<?= Url::toRoute(['browse/list', 'slug'=>'gift-favors'], true); ?>">
                            <span class="say_thank"></span>
                            <span><?php echo Yii::t('frontend', 'Gift Favors'); ?></span>
                        </a></li>
                </ul>
            </li>
            <li class="ma5-li-3"><a href="<?= Url::toRoute('site/experience', true); ?>" title="<?php echo Yii::t('frontend', 'Experience'); ?>"><?php echo Yii::t('frontend', 'Experience'); ?></a></li>
            <li class="ma5-li-3"><a href="<?= Url::toRoute('/themes/index', true); ?>" title="<?php echo Yii::t('frontend', 'Themes'); ?>"><?php echo Yii::t('frontend', 'Themes'); ?></a></li>
            <li class="ma5-li-3"><a href="<?= Url::toRoute('/directory/index', true); ?>" title="<?php echo Yii::t('frontend', 'Directory'); ?>"><?php echo Yii::t('frontend', 'Directory'); ?></a></li>


        <div class="logout_part" style="border:none;">
            <li class="<?php if ($action == "contact-us") { echo "active"; } ?>">
                <a href="<?= Url::toRoute('/contact-us', true); ?>" title="<?php echo Yii::t('frontend', 'About and Contact'); ?>"><?php echo Yii::t('frontend', 'About and Contact'); ?></a>
            </li>


        <?php if (Yii::$app->user->isGuest) { ?>

            <li class=""><a href="" data-toggle="modal"  onclick="show_login_modal('-2');" data-target="#myModal" title="<?php echo Yii::t('frontend', 'Sign in / Register'); ?>"><?php echo Yii::t('frontend', 'Sign in / Register'); ?></a>
            </li>

            <li class="">
                <a href="<?= Url::toRoute(['orders/view'],true);?>">
                    <?= Yii::t('frontend', 'Track order'); ?>
                </a>
            </li>
            
        <?php } else { ?>

        <li>
            <a href="<?php echo Url::to(['cart/index']); ?>">
                <?php echo Yii::t('frontend', 'Cart ({count})', ['count' => CustomerCart::item_count()]); ?>
            </a>
        </li>

         <li class="<?php if ($action == "account-settings") { echo "active"; } ?>">
            <a href="<?= Url::toRoute('/users/account_settings', true); ?>">
                <?php echo Yii::t('frontend', 'My Account'); ?>
            </a>
        </li>

        <li>
            <a href="<?php echo Url::to(['booking/pending']); ?>">
                <?php echo Yii::t('frontend', 'My Pending Bookings'); ?>
            </a>
        </li>

        <li>
            <a href="<?php echo Url::to(['things-i-like/index']); ?>">
                <?php echo Yii::t('frontend', 'Things I like'); ?>
            </a>
        </li>
        <li>
            <a href="<?php echo Url::toRoute('/users/address', true); ?>">
                <?php echo Yii::t('frontend', 'Address Book'); ?>
            </a>
        </li>

        <li><a href="<?= Url::toRoute('/users/logout', true); ?>" title="<?php echo Yii::t('frontend', 'Logout'); ?>"><?php echo Yii::t('frontend', 'Logout'); ?></a></li>
<?php } ?>

        <?php if(Yii::$app->language == "en"){ ?>
        <li class=""><a href="<?= Url::current(['language'=>'ar']) ?>">العربية</a></li>
        <?php }else{ ?>
        <li class=""><a href="<?= Url::current(['language'=>'en']) ?>">English</a></li>
        <?php } ?>

            </div>
        </ul>
    </div>
</nav>
<!--mobile menu navigation end-->

<script>
    jQuery(document).ready(function () {
        jQuery('#basket_list').hide();
        jQuery("#basket_menu").hover(
                function () {
                    jQuery('#basket_list').show();
                }),

        jQuery('#sear_button,#desk-search-label').click(function () {
            jQuery('#search_input_header').focus();
            if (jQuery("#search_input_header").css("display") == "none")
            {
                jQuery("#sear_button").hide();
                jQuery("#sear_button_submit").show();
            }
            else
            {
                jQuery("#sear_button").show();
                jQuery("#sear_button_submit").hide();
            }
            jQuery('#search_input_header').toggle('slide', {direction: 'right'}, 700, function () {
                jQuery('#search_input_header').focus();
            });
            if (jQuery('#search-close1').hasClass('visible'))
                jQuery('#search-close1').removeClass('visible');
            return false;
        });

        jQuery("html").click(function () {
            jQuery('#search-close1').removeClass('visible');
            jQuery('#search_input_header').hide('slide', {direction: 'right'}, 700, function () {
                jQuery('#search_input_header').val("");
                jQuery("#sear_button_submit").hide();
                jQuery("#sear_button").show();
            });
        });


        jQuery("#search-close1").click(function () {

            jQuery("#search_list_fail1").html('');
            jQuery(this).removeClass('visible');
            jQuery('#search_input_header').val("").focus();
            jQuery("#search_list").html('');
            //jQuery('#search_input_header').focus();
            return false;
        });
//        jQuery("#search_input_header").click(function () {
//            return false;
//        });
        jQuery('#desk-search-label').css({'position': 'absolute'});
    });
    /*jQuery('.container_eventslider').click(function(){
     jQuery('#search_input_header').toggle('slide', { direction: 'left' }, 900);
     });*/

</script>

<?php $this->registerCss("
    .cart-dropdown {
        border: 1px solid #999;
        overflow: hidden;
        -webkit-transition: max-height .8s;
        -moz-transition: max-height .8s;
        transition: max-height .8s;
        background: #fff;
        box-shadow: 0 0 3px 0 rgba(161,161,161,.5);
        position: absolute;
        width: 385px;
        z-index: 20;
        margin: 0 0 0 -126px;
        padding: 10px;
    }
    .cart-dropdown a:last-child {
        margin-right: 0;
    }
    .cart-dropdown ul li.col1 h5 {
        text-align: left;
        margin-bottom: 10px;
    }
    .cart-dropdown ul li.col1 span.row1 {
        float: left!important;
        color: #828282!important;
        width: 40px;
        text-align: left;
    }
    .cart-dropdown ul li.col1 span.row2 {
        float: left!important;
        color: #000!important;
        width: 65px;
        text-align: left;
    }
    .cart-dropdown ul li.col1 span.row2 md-select {
        padding: 0!important;
        margin-top: -9px;
    }
    md-select .md-select-value {
        border-bottom-color: rgba(0,0,0,0.12);
    }
    .md-select-value {
        display: -webkit-flex;
        display: -ms-flexbox;
        display: flex;
        -webkit-align-items: center;
        -ms-flex-align: center;
        align-items: center;
        padding: 2px 2px 1px;
        border-bottom-width: 1px;
        border-bottom-style: solid;
        background-color: transparent;
        position: relative;
        box-sizing: content-box;
        min-width: 64px;
        min-height: 26px;
        -webkit-flex-grow: 1;
        -ms-flex-positive: 1;
        flex-grow: 1;
    }
    .md-select-value :first-child {
        -webkit-flex: 1;
        -ms-flex: 1;
        flex: 1;
        text-overflow: ellipsis;
        white-space: nowrap;
        overflow: hidden;
        max-width: calc(100% - 2*8px);
        -webkit-transform: translate3d(0,2px,0);
        transform: translate3d(0,2px,0);
    }
    .cart-dropdown ul{padding:0; }
    div#style-3 {
        border-top: 1px solid #999;
    }
    div#style-3 li:first-child{padding:0}
    .cart-button button{height:35px;background:#000;color:#fff;}
    .cart-button button:hover{background:#333;color:#fff;}
    .plan_venues {  margin-bottom: 30px;  }
    #eventresult{color:red;}
    .eventErrorMsg{color:red;margin-bottom: 10px;}
    .event_loader{display:none;text-align:center;margin-bottom: 10px;}
    .ma5-path-to-active{color:#000;}
    .ma5-mobile-menu-container{z-index:9999;}

") ?>
