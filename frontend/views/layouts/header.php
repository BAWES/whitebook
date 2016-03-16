<?php 
use yii\helpers\Html;
use yii\helpers\Url;
use frontend\models\Website;
//define('ACTION',Yii::$app->controller->action->id);
$action = Yii::$app->controller->action->id;

if(isset(Yii::$app->params['header1']))
{
?>
<!-- END plan page header -->
<?php } else {   
  ?>
<!-- header main start  -->
<header id="top_header" class="ma5-page">
<!-- home top header login det -->
<div class="border-top-yellow" role="navigation">

<div class="container">
<div class="mobile-menu" id="mobile-sid">
<div class="logo-sec">
<a href="#0" class="ma5-toggle-menu">
<span class="demo"><img src="<?php echo Url::toRoute('/images/responsive-button.png',true);?>" alt="click here">
 </span></a></div>
<!--div class="col-xs-4 responsive-hid"></div-->
<div class="logo_header col-xs-10 text-center padding-right0">
<a href="<?= Url::toRoute('/home',true);?>" title="THEWHITEBOOK"><img src="<?php echo Url::toRoute('/images/mobile_logo.svg',true);?>" alt="Whitebook" title="THEWHITEBOOK" /></a>
<div class="search_header col-xs-3">
<div class="input-group">
<div id="navigation-bar">
<form id="search" method="post" action="#" onsubmit="return false;">
<div id="input1" class="left_slider">
<input type="text" name="search-terms" id="search-terms2" onkeyup="show_close()" placeholder="SEARCH FOR... " class="search-box" autocomplete="off" >
<button class="js-search-cancel"> Cancel </button>
<button id="search-close" class="search-clear icon-search_clear" type="reset" >Clear</button>
</div>
<div id="label1">
<div id="search-labl" class="search_for"></div>
<div id="search_list2"></div>
<label for="search-terms" id="search-label" class="search-lbl-mobile">
</label></div>
</form>
</div>
</div><!-- /input-group -->
</div>

</div>
<div id="mobile_search_list" class="mobile-search-term"></div>
<div id="mobile_search_fail"></div>
<div id="desktop_search_fail"></div>
</div>
<div class="mobile-logo-text col-xs-12 text-center padding0">
<?php if(Yii::$app->params['CUSTOMER_ID']=='') { ?>

<a href="" data-toggle="modal" onclick="show_login_modal('-2');" data-target="#myModal"  title="THEWHITEBOOK"><img src="<?php echo Url::toRoute('/images/mywhitebook_vector.svg',true);?>" alt="Whitebook" title="THEWHITEBOOK" /></a>
<?php } else { ?>
<a href="<?= Url::toRoute('/events',true);?>" title="THEWHITEBOOK"><img src="<?php echo Url::toRoute('/images/mywhitebook_vector.svg',true);?>" alt="Whitebook" title="THEWHITEBOOK" /></a>
<?php } ?>
</div>

<div class="desktop-menu">
<div class="col-md-4 col-xs-12 col-sm-4 respo_hidde"></div>
<div class="col-md-4 col-xs-12 col-sm-6">
<div class="navbar-header">
<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
<span class="sr-only">Toggle navigation</span>
<span class="icon-bar"></span>
<span class="icon-bar"></span>
<span class="icon-bar"></span>
</button>
</div>
<div class="logo_header">
<a href="<?= Url::toRoute('/home',true);?>"  title="THEWHITEBOOK"><img src="<?php echo Url::toRoute('/images/mywhitebook_vector.svg',true);?>" alt="THEWHITEBOOK" title="THEWHITEBOOK" /></a>
</div>

<div class="navbar-collapse collapse">
<ul class="nav navbar-nav">
<li class="dropdown mega-dropdown"><a href="<?= Url::toRoute('/plan',true);?>" title="Home">Plan</a>
<div class="dropdown-menu mega-dropdown-menu category_listing_nav">
<ul class="nav-list list-inline">
<li>
<a title="<?php echo Yii::t('frontend','VENUES');?>  " href="<?= Url::toRoute('products/venues',true);?>">
<span class="venus_icon"></span>
<span> <?php echo Yii::t('frontend','VENUES');?></span>
</a>
</li>
<li>
<a title=" <?php echo Yii::t('frontend','INVITATIONS');?>" href="<?= Url::toRoute('/products/invitations',true);?>">
<span class="invit_icon"></span>
<span> <?php echo Yii::t('frontend','INVITATIONS');?></span>
</a>
</li>
<li>
<a title="<?php echo Yii::t('frontend','FOOD_BEVERAGE');?>" href="<?= Url::toRoute('/products/food-beverage',true);?>">
<span class="food_map"></span>
<span><?php echo Yii::t('frontend','FOOD_BEVERAGE')?></span>
</a>
</li>
<li>
<a title="<?php echo Yii::t('frontend','DECOR_SUPPLIES');?>" href="<?= Url::toRoute('/products/decor',true);?>">
<span class="decor"></span>
<span><?php echo Yii::t('frontend','DECOR_SUPPLIES');?> </span>
</a>
</li>
<li>
<a title="<?php echo Yii::t('frontend','SUPPLIES');?>" href="<?= Url::toRoute('/products/supplies',true);?>"> 
<span class="supplies"></span>
<span><?php echo Yii::t('frontend','SUPPLIES');?> </span>
</a>
</li>


<li>
<a title="<?php echo Yii::t('frontend','ENTERTAINMENT');?>" href="<?= Url::toRoute('/products/entertainment',true);?>">
<span class="entert"></span>
<span><?php echo Yii::t('frontend','ENTERTAINMENT');?></span>
</a>
</li>
<li>
<a title=" <?php echo Yii::t('frontend','SERVICES');?>" href="<?= Url::toRoute('/products/services',true);?>">
<span class="serv"></span>
<span> <?php echo Yii::t('frontend','SERVICES');?></span>
</a>
</li>
<li>
<a title="<?php echo Yii::t('frontend','OTHERS');?>" href="<?= Url::toRoute('/products/others',true);?>">
<span class="other"></span>
<span><?php echo Yii::t('frontend','OTHERS');?></span>
</a>
</li>
<li><a title="<?php echo Yii::t('frontend','SAY_THANK_YOU');?>" href="<?= Url::toRoute('/products/say-thank-you',true);?>">
<span class="say_thank"></span>
<span><?php echo Yii::t('frontend','SAY_THANK_YOU');?></span>
</a></li>
</ul>
</div>
</li>

<li class=""><a href="<?= Url::toRoute('/shop',true);?>" title="<?php echo Yii::t('frontend','SHOP');?>"><?php echo Yii::t('frontend','SHOP');?></a></li>
<li  class="<?php if($action=="experience") { echo "active"; } ?>"><a href="<?= Url::toRoute('/experience',true);?>" title="<?php echo Yii::t('frontend','EXPERIENCE');?>"><?php echo Yii::t('frontend','EXPERIENCE');?></a></li>
<li class="<?php if($action=="directory") { echo "active"; } ?>"><a href="<?= Url::toRoute('/directory',true);?>" title="<?php echo Yii::t('frontend','DIRECTORY');?>"><?php echo Yii::t('frontend','DIRECTORY');?></a></li>                                
</ul>
</div> 
</div>
<div class="col-md-4 col-xs-12 col-sm-6 padding-left0 <?php if(Yii::$app->params['CUSTOMER_ID']!='') {echo 'new_user_name'; }?>">
<?php if(Yii::$app->params['CUSTOMER_ID']!='') { ?>
<div class="user_name_cont">
<p><?= 'Hi '.Yii::$app->params['CUSTOMER_NAME'].',';?></p>
</div>
<?php }?>
<ul class="logout_part">
<?php if(Yii::$app->params['CUSTOMER_ID']=='') { ?>
<li class="<?php if($action=="about-us") { echo "active"; } ?>"><a href="<?= Url::toRoute('/about-us',true);?>" title="<?php echo Yii::t('frontend','ABOUT_US');?>"><?php echo Yii::t('frontend','ABOUT_US');?></a></li>
<li class=""><a href="" data-toggle="modal"  onclick="show_login_modal('-2');" data-target="#myModal" title="<?php echo Yii::t('frontend','SIGN_IN_REGISTER');?>"><?php echo Yii::t('frontend','SIGN_IN_REGISTER');?></a></li>
<?php } else { ?>
<li class="<?php if($action=="account-settings") { echo "active"; } ?>"><a href="<?php echo Url::toRoute('/account-settings',true);?>" title="<?php echo Yii::t('frontend','MY_ACCOUNT');?>"><?php echo Yii::t('frontend','MY_ACCOUNT');?></a></li>
<li><a href="<?php echo Url::toRoute('/events',true);?>" title="<?php echo Yii::t('frontend','MY_EVENTS');?>"><?php echo Yii::t('frontend','MY_EVENTS');?></a></li>
<li><a href="<?php echo Url::toRoute('/logout',true);?>" title="<?php echo Yii::t('frontend','LOGOUT');?>"><?php echo Yii::t('frontend','LOGOUT');?></a></li>
<?php } ?>

</ul>

<div class="search_header">
<div class="input-group">
<div id="navigation-bar">
<form id="search"  method="post" onsubmit="return false;">
<!--
<div id="input" class="left_slider">

<form id="search"  method="post" onsubmit="return false;">
<div id="input" class="left_slider">

<input type="search" name="search-terms" id="search-input" onkeyup="show_close3()" placeholder="search" autocomplete="off">
<button id="search-close11" class="search-clear icon-search_clear" type="reset" >Clear</button>
<div id="search_list"></div>    
<div id="search_list_fail1"></div>
</div>
<div id="label">
<div id="search-labl" class="search_for">search</div>
<label for="search-terms" id="search-label"></label></div> -->
<?php if(Yii::$app->params['CUSTOMER_ID'] !="") {
   $search_div ='<div class="form-group has-feedback" style="margin-bottom:0px">';
 } else {
      $search_div ='<div class="form-group has-feedback">';
}
 echo $search_div; 
  ?>
                    <label for="search" class="sr-only">Search</label>
                    <input type="search" class="form-control sear_ip_head" onkeyup="show_close3()" autofocus name="search_input_desk" id="search_input_header" autocomplete="off" title="search" placeholder="&nbsp;&nbsp;SEARCH FOR...">
                    <button id="search-close1" class="search-clear icon-search_clear" type="reset" >Clear</button>
                    <a id="sear_button" href="#" class=" "></a>
                    <input type="submit" id="sear_button_submit"/>
                    <div id="desk-search-label" class="search_for">Search</div>
                    <div id="search_list"></div>    
<div id="search_list_fail1"></div>
                </div>    
</form>
</div>

</div><!-- /input-group -->
</div>
</div>
</div>
</div>
</div>
</header>
<!-- header main end  --> 
<?php } ?>

<!-- BEGIN Create event Modal Box -->
<div class="modal fade" id="EventModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"data-backdrop="static" data-keyboard="false">
<div class="modal-dialog" id="eventModal">
<div class="modal-content  modal_member_login signup_poupu row">
<div class="modal-header">
<button type="button" class="close" id="boxclose" name="boxclose" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
<div class="text-center">
<span class="yellow_top"></span>
</div>
<h4 class="modal-title text-center" id="myModalLabel"><?php echo Yii::t('frontend','CREATE_NEW_EVENT');?></h4>
</div>
<div class="modal-body">
<div class="row">
<div class="col-xs-8 col-xs-offset-2">
<div class="product_popup_signup_box">
<div class="product_popup_signup_log">
<form name="create_event" id="create_event">
<input type="hidden" id="_csrf" name="_csrf" value="<?= Yii::$app->request->csrfToken; ?>" />
<div class="form-group">
<input type="text" name="event_name" class="form-control required" id="event_name" placeholder="<?php echo Yii::t('frontend','enter_event_name');?>" title="<?php echo Yii::t('frontend','enter_event_name');?>">
</div>
<div class="form-group">
<input type="hidden" name="item_id" class="form-control required" id="item_id" value="0">
</div>
<div class="form-group top_calie_new">
                                    
                                    
            <div data-date-format="dd-mm-yyyy" data-date="12-02-2012" id="dp3" class="input-append date">
               <input type="text"  name="event_date" id="event_date" readonly size="16" class="form-control required datetimepicker date1" placeholder="<?php echo Yii::t('frontend','choose_event_date');?>" title="<?php echo Yii::t('frontend','choose_event_date');?>">
               <span class="add-on position_news"> <i class="flaticon-calendar189"></i></span>
             </div>
             <label for="event_date" class="error"></label>
              </div>
<?/*<div class="form-group">
<div data-date-format="dd-mm-yyyy" data-date="12-02-2012" id="datetimepicker2" class="input-append date">             
<!--<div id="datetimepicker2" class="input-group date">-->
<input type="text" name="event_date" id="event_date" class="form-control required datetimepicker date1" placeholder="<?php echo Yii::t('frontend','choose_event_date');?>" title="<?php echo Yii::t('frontend','choose_event_date');?>">
<span class="input-group-addon">
<i class="flaticon-calendar189 date1" id="oops"></i>
</span>
</div>
<label for="event_date" class="error"></label>
</div>*/?>
<div class="form-group new_popup_common">
<div class="bs-docs-example"><select class="selectpicker required trigger" name="event_type" data""-style="btn-primary" id="event_type" >
<option value="">Select event type</option>
<?php                                                 

$event_type=Website::get_event_types();
foreach($event_type as $e) { ?>
<option value="<?php echo $e['type_name'];?>"><?php echo $e['type_name'];?></option>
<?php } ?>
</select>

<div class="error" id="type_error"></div>
</div>
</div>
<div id="eventresult" style="color:red"></div>
<div class="eventErrorMsg error" style="color:red;margin-bottom: 10px;"></div>
<div class="event_loader" style="display:none;text-align:center;margin-bottom: 10px;"><img src="<?php echo Url::toRoute('/backend/web/uploads/ajax-loader.gif',true);?>" title="Loader"></div>
<div class="buttons">
<div class="creat_evn_sig">
<button type="button" id="create_event_button" name="create_event_button" class="btn btn-default" title="<?php echo Yii::t('frontend','CREATE_EVENT');?>"><?php echo Yii::t('frontend','CREATE_EVENT');?></button>
</div>
<div class="cancel_sig">
<input class="btn btn-default" data-dismiss="modal"  id="cancel_button" name="cancel_button" type="button" value="<?php echo Yii::t('frontend','CANCEL');?>" title="<?php echo Yii::t('frontend','CANCEL');?>">
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
<!-- END Create event Modal Box -->

<!-- BEGIN EDIT EVENT MODAL BOX -->
<div class="modal fade" id="EditeventModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"data-backdrop="static" data-keyboard="false">
<div class="modal-dialog" id="editeventModal">
</div>
</div>
<!-- END EDIT EVENT MODAL BOX -->

<!--navigation mobile menu start-->
<nav class="ma5-menu-mobile mobile-menu">
<div class="navbar-collapse">
<ul class="nav navbar-nav ma5-ul" style=" border:none;">      
<li class="ma5-li-1"> <a class="ma5-path-to-active ma5-btn-enter" href="#node1">Plan</a>

<ul class="ma5-ul-1 navbar-nav">
<li class="ma5-li-1-0">
<div class="ma5-leave-bar">
<span class="ma5-btn-leave">

</span>
<a style="color:#000;" class="ma5-path-to-active ma5-btn-enter" href="#node1">Back</a>
</div></li>
<li class="ma5-li-1-1">                    
<a title="<?php echo Yii::t('frontend','VENUES');?>" href="<?= Url::toRoute('/products/venues',true);?>">
<span class="venus_icon"></span>
<span> <?php echo Yii::t('frontend','VENUES');?></span>
</a>
</li>

<li class="ma5-li-1-2">
<a title=" <?php echo Yii::t('frontend','INVITATIONS');?>" href="<?= Url::toRoute('/products/invitations',true);?>">
<span class="invit_icon"></span>
<span> <?php echo Yii::t('frontend','INVITATIONS');?></span>
</a>                   
</li>
<li class="ma5-li-1-3">
<a title="<?php echo Yii::t('frontend','FOOD_BEVERAGE');?>" href="<?= Url::toRoute('/products/food-beverage',true);?>">
<span class="food_map"></span>
<span><?php echo Yii::t('frontend','FOOD_BEVERAGE')?></span>
</a>
</li>
<li class="ma5-li-1-4">
<a title="<?php echo Yii::t('frontend','DECOR_SUPPLIES');?>"  href="<?= Url::toRoute('/products/decor',true);?>">
<span class="decor"></span>
<span><?php echo Yii::t('frontend','DECOR_SUPPLIES');?> </span>
</a>
</li>
<li class="ma5-li-1-5">
<a title="<?php echo Yii::t('frontend','SUPPLIES');?>" href="<?= Url::toRoute('/products/supplies',true);?>">
<span class="supplies"></span>
<span><?php echo Yii::t('frontend','SUPPLIES');?> </span>
</a>
</li>

<li class="ma5-li-1-6">
<a title="<?php echo Yii::t('frontend','ENTERTAINMENT');?>" href="<?= Url::toRoute('/products/entertainment',true);?>">
<span class="entert"></span>
<span><?php echo Yii::t('frontend','ENTERTAINMENT');?></span>
</a>
</li>
<li class="ma5-li-1-7">
<a title=" <?php echo Yii::t('frontend','SERVICES');?>" href="<?= Url::toRoute('/products/services',true); ?>">
<span class="serv"></span>
<span> <?php echo Yii::t('frontend','SERVICES');?></span>
</a>
</li>
<li class="ma5-li-1-8">
<a title="<?php echo Yii::t('frontend','OTHERS');?>" href="<?= Url::toRoute('/products/others',true); ?>">
<span class="other"></span>
<span><?php echo Yii::t('frontend','OTHERS');?></span>
</a>
</li>
<li class="ma5-li-1-9">
<a title="<?php echo Yii::t('frontend','SAY_THANK_YOU');?>" href="<?= Url::toRoute('/products/say-thank-you',true);?>">
<span class="say_thank"></span>
<span><?php echo Yii::t('frontend','SAY_THANK_YOU');?></span>
</a></li>

</ul>
</li>
<li class="ma5-li-2"> <a class="ma5-path-to-active ma5-btn-enter" href="<?= Url::toRoute('/shop',true); ?>">Shop</a>
</li>
<li class="ma5-li-3"><a href="<?= Url::toRoute('/experience',true);?>" title="<?php echo Yii::t('frontend','EXPERIENCE');?>"><?php echo Yii::t('frontend','EXPERIENCE');?></a></li>
<li class="ma5-li-3"><a href="<?= Url::toRoute('/directory',true);?>" title="<?php echo Yii::t('frontend','DIRECTORY');?>"><?php echo Yii::t('frontend','DIRECTORY');?></a></li>


<div class="logout_part" style="border:none;">
<?php if(Yii::$app->params['CUSTOMER_ID']=='') { ?>
<li class="<?php if($action=="about-us") { echo "active"; } ?>"><a href="<?= Url::toRoute('/about-us',true);?>" title="<?php echo Yii::t('frontend','ABOUT_US');?>"><?php echo Yii::t('frontend','ABOUT_US');?></a></li>
<li class=""><a href="" data-toggle="modal"  onclick="show_login_modal('-2');" data-target="#myModal" title="<?php echo Yii::t('frontend','SIGN_IN_REGISTER');?>"><?php echo Yii::t('frontend','SIGN_IN_REGISTER');?></a></li>
<?php } else { ?>
<li class="<?php if($action=="account-settings") { echo "active"; } ?>"><a href="<?= Url::toRoute('/account-settings',true);?>" title="<?php echo Yii::t('frontend','MY_ACCOUNT');?>"><?php echo Yii::t('frontend','MY_ACCOUNT');?></a></li>
<li><a href="<?= Url::toRoute('/events',true);?>" title="<?php echo Yii::t('frontend','MY_EVENTS');?>"><?php echo Yii::t('frontend','MY_EVENTS');?></a></li>
<li><a href="<?= Url::toRoute('/logout',true);?>" title="<?php echo Yii::t('frontend','LOGOUT');?>"><?php echo Yii::t('frontend','LOGOUT');?></a></li>
<?php } ?> 

</div>
</ul>
</div>
</nav> 
<!--mobile menu navigation end-->