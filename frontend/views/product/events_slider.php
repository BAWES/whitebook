<?php
use frontend\models\Website;
use frontend\models\Users;
use yii\helpers\Url;
use yii\helpers\Html;
if(Yii::$app->params['CUSTOMER_ID']!='') {
$wishlist = Users::loadcustomerwishlist(Yii::$app->params['CUSTOMER_ID']);
$customer_events=Website::get_customer_events(Yii::$app->params['CUSTOMER_ID']);
?>
<?php if(count($customer_events) == 0) {  ?>
<div class="container_eventslider">
<span class="first_events"><img src="<?php echo Url::toRoute('/images/my_book_desk.svg') ?>" alt="My White Book"/></span>
<div class="creatfirst_events">
<p data-example-id="active-anchor-btns" class="bs-example">
<a  href="javascript:" role="button" class="btn btn-default"  data-toggle="modal" data-target="#EventModal" title="<?php echo Yii::t('frontend','CREATE_YOUR_FIRST_EVENT');?>"><?php echo Yii::t('frontend','CREATE_YOUR_FIRST_EVENT');?></a>
</p>
</div>
</div>
<?php } else { ?>
<div class="directory_slider" id="oner">
<div class="event_slider_top">
<div class="col-md-3">
<?= Html::img('@web/images/my_book_desk.svg', ['alt' => 'My White Book','class'=>'top_whit']) ?>
</div>
<div class="col-md-8">
<div class="inner_slider_event">

<div id="demo">
<div id="owl-demo" class="owl-carousel">
<div class="item" style=" background: transparent;">
<?php if(!empty($customer_events)) {?>
<a href="<?= Url::toRoute('/users/events'); ?>" class="thing_cont" title="Things I like"><span class="heart_fave" id="heart_fave"><?= count($wishlist); ?></span>
Things I like</a>
<?php } else {?>
<a href="javascript:" role="button" class="btn btn-default" data-toggle="modal" data-target="#EventModal" title="CREATE YOUR FIRST EVENT" style="   float: left;    margin-left: 225px;    margin-top: 45px;    min-height: 30px;">CREATE YOUR FIRST EVENT</a>
<?php } ?>
</div>                                    
<?php                                                                 
foreach ($customer_events as $key => $value) { ?>
<a href="<?=  Url::toRoute('/users/eventdetails'); ?><?= '/'.$value['slug']; ?>">
<div class="item">
<h4><?php if(strlen($value['event_name'])>12){echo substr($value['event_name'], 0, 12).' ...';}else{ echo$value['event_name'];} ?></h4>
<p><?= $value['event_date']; ?></p>
<p><?= $value['event_type']; ?><br/>
</p>
</div>
</a>
<?php }  ?>                       
</div>
</div>
</div>
</div>
<?php if(!empty($customer_events)) {?>
<div class="col-md-1">
<span class="plus_icons"><a href="#" role="button" data-toggle="modal" data-target="#EventModal" title=""> &nbsp;</a></span>
</div>
<?php } ?>
</div>
</div>
<!-- END load user events -->
<?php } }else {
?>
<div class="container_eventslider">
<span class="first_events"><img src="<?php echo Url::toRoute('/images/my_book_desk.svg') ?>" alt="My White Book"/></span>
<div class="creatfirst_events">
<p data-example-id="active-anchor-btns" class="bs-example">
<?php if(Yii::$app->params['CUSTOMER_ID']=='') { ?>
<a href="javascript:"  role="button" class="btn btn-default"  data-toggle="modal"  onclick="show_login_modal(-1);" data-target="#myModal" title="<?php echo Yii::t('frontend','CREATE_YOUR_EVENT');?>"><?php echo Yii::t('frontend','CREATE_YOUR_EVENT');?></a>
<?php } else { 
if(count($customer_events) > 0) {?>
<a  href="javascript:" role="button" class="btn btn-default"  data-toggle="modal" data-target="#EventModal" title="<?php echo Yii::t('frontend','CREATE_YOUR_EVENT');?>"><?php echo Yii::t('frontend','CREATE_YOUR_EVENT');?></a>
<?php }else {?>
<a  href="javascript:" role="button" class="btn btn-default"  data-toggle="modal" data-target="#EventModal" title="<?php echo Yii::t('frontend','CREATE_YOUR_FIRST_EVENT');?>"><?php echo Yii::t('frontend','CREATE_YOUR_FIRST_EVENT');?></a>
<?php } }?>
</p>
</div>
</div>
<!-- END load user events -->
<?php } ?>
<script type="text/javascript">
jQuery(document).ready(function () {
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
if(jQuery("div.owl-item").length == 2){
jQuery('.directory_slider > .col-md-8').css("width", "28%");
}
else if(jQuery("div.owl-item").length == 3){
jQuery('.directory_slider > .col-md-8').css("width", "41%");
}
else if(jQuery("div.owl-item").length == 4){
jQuery('.directory_slider > .col-md-8').css("width", "53%");
}
else if(jQuery("div.owl-item").length == 5){
jQuery('.directory_slider > .col-md-8').css("width", "66%");
}

});
</script>


