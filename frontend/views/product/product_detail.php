<?php 
use yii\helpers\Url;
use backend\models\Vendor;
use yii\helpers\Html;
use backend\models\Vendoritempricing;
use backend\models\Itemtype;
use backend\models\Category;
use yii\widgets\Breadcrumbs;
$this->title='Whitebook - '.$model['item_name'];?>

<!-- coniner start -->
<section id="inner_pages_white_back" class="product_details_com">
<div class="container paddng0">
<!-- Events slider start -->
<?php require(__DIR__ . '/../product/events_slider.php'); ?>
<!-- Events slider end -->
<div class="breadcrumb_common">
<div class="bs-example">

<?php 
$vendor_det=Vendor::vendorslug($model['vendor_id']);
$category_det=Category::category_slug($model['category_id']);
$this->params['breadcrumbs'][] = ['label' => ucfirst($category_det['category_name']), 'url' => Yii::$app->params['BASE_URL'].'/products/'.$category_det['slug']];
$this->params['breadcrumbs'][] =ucfirst($model['item_name']);
?>

<?= Breadcrumbs::widget([
'options' => ['class' => 'new breadcrumb'],
'homeLink' => [ 
'label' => Yii::t('yii', 'Home'),
'url' => Yii::$app->params['BASE_URL'],
],
'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
]); 
?>

</div>
</div>

<!-- Mobile start Here-->
<div class="product_detail_section responsive-detail-section"><!--product detail start-->
<div class="col-md-12 padding0">
<div class="select_items_select desktop-menu">
<div data-example-id="basic-forms" class="bs-example responsive_inner_top">
<form>
<div class="col-md-3 padding-right0 padding-right8">
<div class="form-group left_select_common">
<div class="bs-docs-example">
<select class="selectpicker" data-style="btn-primary" style="display: none;">
<option>Select Delivery Area</option>
<?php
    foreach ($vendor_area as $key => $value) { ?>
        <option><?= $value['location'];?></option>
<?php } ?>
</select>            
</div>
</div>
</div>
<div class="col-md-2 padding8">
<div class="form-group date-time">
<input class="form-control" type="text" id="delivery_date" placeholder="Delivery Date">
</div>
</div>

<div class="col-md-2 padding8">
<div class="form-group">
<div class="bs-docs-example">
<select class="selectpicker" data-style="btn-primary" style="display: none;">
<option>Select Delivery Time</option>
<?php
    /*foreach ($vendor_timeslot as $key => $value1) { ?>
        <option><?= $value1['timeslot_start_time'].' - '.$value1['timeslot_end_time'];?></option>
<?php } */ ?>
</select>            
</div>
</div>
</div>
<div class="col-md-1 padding8 select_buttons">
<button class="btn btn-default" type="submit" title="Select">Select</button>
</div>
<div class="col-md-4">
<!-- <em class="error_text">This item is not available at the selected time</em> -->
</div>
</form>
</div>
</div>

<div class="product_detials_common normal_tables">

<div class="col-md-6 paddig0 resp_hide">
<div class="left_descrip mobile-view">
<h2><?= $vendor_det['vendor_name'];?></h2>
<label><?= $model['item_name'];?></label>
<b><?php echo number_format($model['item_price_per_unit'],2)." ".Yii::$app->params['CURRENCY_CODE'];?></b>
</div>
<!-- Indicators responsive slider -->
<div class="responsive_slider_detials">

<!--23-10-2015 slider start-->
<div class="carousel-inner owl-carousel" id="mobile-slider">
<?php $sql='SELECT image_path FROM whitebook_image WHERE item_id='.$model['item_id'].' and module_type="vendor_item" order by vendorimage_sort_order'; 
$command = Yii::$app->DB->createCommand($sql);
$output = $command->queryAll();
$img_count=count($output);
foreach($output as $out){
if($out){
$imglink=Yii::getAlias('@vendor_image/').$out['image_path'];
$baselink=Yii::$app->params['BASE_URL'].Yii::getAlias('@vendor_image/').$out['image_path'];
}else { 
$imglink=Yii::getAlias('@vendor_image/').'no_image.jpg';
$baselink=Yii::$app->params['BASE_URL'].Yii::getAlias('@vendor_image/').'no_image.jpg';
}?>
<div class="item">   <img src="<?php echo $baselink;?>" alt="item detail image" style="width:530px;">
</div>
<?php }?>  
</div>
<!--23-10-2015 slider end-->

</div>
<!-- Indicators responsive slider end -->
</div>

<div id="main" role="main" class="col-md-6 padding-right0 product-left-width">
<div class="slider">
<div id="slider" class="flexslider display_none">
<ul class="slides">

<?php $sql='SELECT image_path FROM whitebook_image WHERE item_id='.$model['item_id'].' and module_type="vendor_item" order by vendorimage_sort_order'; 
$command = Yii::$app->DB->createCommand($sql);
$output = $command->queryAll();
$img_count=count($output);
foreach($output as $out){
if($out){
$imglink=Yii::getAlias('@vendor_image/').$out['image_path'];
$baselink=Yii::$app->params['BASE_URL'].Yii::getAlias('@vendor_image/').$out['image_path'];
}else { 
$imglink=Yii::getAlias('@vendor_image/').'no_image.jpg';
$baselink=Yii::$app->params['BASE_URL'].Yii::getAlias('@vendor_image/').'no_image.jpg';
}?>
<li>    <img src="<?php echo $baselink;?>" alt="item detail image" style="width:530px !important;">
</li>
<?php }?>           
</ul>
</div>
<?php if($img_count>1){?>
<div id="carousel" class="flexslider display_none_thumb">
<ul class="slides">

<?php $sql='SELECT image_path FROM whitebook_image WHERE item_id='.$model['item_id'].' and module_type="vendor_item" order by vendorimage_sort_order'; 
$command = Yii::$app->DB->createCommand($sql);
$output = $command->queryAll();
foreach($output as $out){
if($out){
$imglink=Yii::getAlias('@vendor_image/').$out['image_path'];
$baselink=Yii::$app->params['BASE_URL'].Yii::getAlias('@vendor_image/').$out['image_path'];
}else { 
$imglink=Yii::getAlias('@vendor_image/').'no_image.jpg';
$baselink=Yii::$app->params['BASE_URL'].Yii::getAlias('@vendor_image/').'no_image.jpg';
}?>
<li>    <img src="<?php echo $baselink;?>" alt="item detail image">
</li>
<?php }?>           
</ul>
</div>
<?php }?>
</div>
</div>
<div class="col-md-6 product-right-width paddng0">
<div class="right_descr_product">
<div class="product_name">
<div class="left_descrip desktop-view">
<a title="<?= $vendor_det['vendor_name'];?>" href="<?php echo Yii::$app->params['BASE_URL'];?>/experience/<?php echo $vendor_det['slug'];?>"><?php echo '<h2>'.$vendor_det['vendor_name'].'</h2>';?></a>
<label><?= $model['item_name'];?></label>
<b><?php echo number_format($model['item_price_per_unit'],2)." ".Yii::$app->params['CURRENCY_CODE'];?></b>
</div>
<div class="right_descrip">
<div class="responsive_width">
<!-- add to event start -->

<?php if(Yii::$app->params['CUSTOMER_ID']=='') { ?>
<a href="" data-toggle="modal" class="add_events" data-target="#myModal" title="Add to event"  onclick="add_event_login(<?php echo $model['item_id'];?>)"><span class="plus-icon-prod">Add to event</span></a>
<?php } else { ?>
<a  href="#" role="button" id="<?php echo $model['item_id'];?>" name="<?php echo $model['item_id'];?>" class="add_events"  data-target="#add_to_event<?php echo $model['item_id'];?>"   onclick="addevent('<?php echo $model['item_id']; ?>')" data-toggle="modal"  class="add_events" title="<?php echo Yii::t('frontend','ADD_EVENT');?>"><span class="plus-icon-prod"><?php echo Yii::t('frontend','ADD_EVENT');?></span></a>
<?php } ?>
<!-- add to event end here -->


<!-- Add to favourite start -->

<?php if(Yii::$app->params['CUSTOMER_ID']==''){?><a href="" class="faver_evnt_product" data-toggle="modal" data-target="#myModal" onclick="show_login_modal_wishlist(<?php echo $model['item_id'];?>);"  title="Add to Favourite"><span class="heart-product"></span></a><?php } else { $k=array();
foreach($customer_events_list as $l){
$k[]=$l['item_id'];
} 
 $result=array_search($model['item_id'],$k);
?> 
<a class="faver_evnt_product" href="javascript:;"  title="Add to Favourite" id="<?php echo $model['item_id']; ?>"><span class="<?php if (is_numeric ($result)) { echo "heart-product heart-product-hover";} else { echo "heart-product";}?>"></span></a>
<?php } ?>
<div id="loading_img" style='display:none'>
<?php $giflink=Yii::$app->params['BASE_URL'].Yii::getAlias('@gif_img');?>
<img id="loading-image" src="<?= $giflink;?>" alt="Loading..." />
</div>
<!-- Add to Event End here -->             
<?php if(Yii::$app->params['CUSTOMER_ID'] =='') {?>
<div class="buy_events"><a href="" data-toggle="modal" onclick="show_login_modal('-2');" data-target="#myModal">Buy </a></div>
<?php } else if(empty($avlbl_stock)) {?>
<div class="buy_events"><a href="#" id="<?php echo $model['item_id'];?>" class="stock" title="Buy">Out of stock</a></div>
<?php } else if($avlbl_stock > 0) {?>
<div class="buy_events"><a href="#" id="<?php echo $model['item_id'];?>" class="buy_item" data-slug="<?php echo $model['slug'];?>" title="Buy">Buy</a></div>
<?php } ?>
</div>
</div>
</div>

<div class="accad_menus">
<div class="panel-group" id="accordion">
<?php if(!empty($model['item_description'])){ ?>
<div class="panel panel-default">
<div class="panel-heading" role="tab" id="headingOne">
<h4 class="panel-title">
<a data-toggle="collapse" id="description_click" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
Product Description <span class="produ_type">( Product type: <?=Itemtype::itemtypename($model['type_id']);?> )</span>
<span class="glyphicon glyphicon-menu-down text-align pull-right"></span></a> 
</h4>
</div>
<div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
<div class="panel-body">
<p><?= strip_tags($model['item_description']);?></p>
<h1 class="space_height"></h1>
</div>
</div>
</div>
<?php } ?>
<?php if(!empty($model['item_additional_info'])){ ?>
<div class="panel panel-default">
<div class="panel-heading" role="tab" id="headingTwo">
<h4 class="panel-title">
<a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" id="additional_click" aria-controls="collapseTwo">
Additional Information
<span class="glyphicon glyphicon-menu-right text-align pull-right"></span></a> 
</h4>
</div>
<div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
<div class="panel-body">
<p><?= strip_tags($model['item_additional_info']);?></p>
<h1 class="space_height"></h1>
</div>
</div>
</div>

<?php }?>
<?php $vendordetail=Vendor::vendorcontactaddress($model['vendor_id']);
if(($vendordetail['vendor_contact_number'])||($vendordetail['vendor_contact_address'])){ ?>
<div class="panel panel-default">
<div class="panel-heading" role="tab" id="headingThree">
<h4 class="panel-title">
<a class="collapsed" data-toggle="collapse" data-parent="#accordion" id="contact_click" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
Contact Info  <span class="glyphicon glyphicon-menu-right text-align pull-right"></span></a> 
</h4>
</div>
<div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
<div class="panel-body">
<p><?php 
$num=explode(',',$vendordetail['vendor_contact_number']);
echo $vendordetail['vendor_contact_address'];?></p>
<p><?= $num[0];?></p>
<h1 class="space_height"></h1>
</div>
</div>
</div>
<?php } ?>
<?php if(Vendoritempricing::checkprice($model->item_id,$model->type_id, $model->item_price_per_unit)){?>
<div class="panel panel-default">
<div class="panel-heading" role="tab" id="headingFour">
<h4 class="panel-title">
<a class="collapsed" data-toggle="collapse" data-parent="#accordion" id="price_click" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
Price Cart  <span class="glyphicon glyphicon-menu-right text-align pull-right"></span></a> 
</h4>
</div>
<div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFour">
<div class="panel-body">
<p><?=Vendoritempricing::loadviewprice($model->item_id, $model->type_id, $model->item_price_per_unit);?></p>
<h1 class="space_height"></h1>
</div>
</div>
</div>
<?php }?>
<?php if(!empty($model['item_customization_description'])){ ?>
<div class="panel panel-default">
<div class="panel-heading" role="tab" id="headingFive">
<h4 class="panel-title">
<a class="collapsed" data-toggle="collapse" data-parent="#accordion" id="custom_click" href="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
Customization  <span class="glyphicon glyphicon-menu-right text-align pull-right"></span></a> 
</h4>
</div>
<div id="collapseFive" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFive">
<div class="panel-body">
<p><?= $model['item_customization_description'];?></p>
<h1 class="space_height"></h1>
</div>
</div>
<?php } ?>
</div>
</div>
</div>
<div class="social_share">
<?php 
$title='Whitebook Application'.ucfirst($vendor_det['vendor_name']);
$url=urlencode(Yii::$app->params['BASE_URL'].$_SERVER['REQUEST_URI']);
$summary= 'Whitebook Application'.ucfirst($vendor_det['vendor_name']).ucfirst($model['item_name']);
//$image='http://demositeapp.com/backend/web/uploads/vendor_images/445_blueberry_coffee_cake_61.jpg'; 
$image=$baselink; ?>
<h3>Share this</h3>
<ul>   
<li><a title="Facebook" onclick="window.open('http://www.facebook.com/sharer.php?s=100&amp;p[title]=<?php echo $title;?>&amp;p[summary]=<?php echo $summary;?>&amp;p[url]=<?php echo $url; ?>&amp;&p[images][0]=<?php echo $image;?>', 'sharer', 'toolbar=0,status=0,width=620,height=280');" href="javascript: void(0)"><span class="flaticon-facebook55"></span></a></li>

<li><a  title="Twitter" href="https://twitter.com/share" class="twitter" target="_blank" data-url="<?php echo $url; ?>" data-text="<?php echo $title; ?>" data-lang="en" data-size="medium" data-count="none"><span class="flaticon-twitter13"></span></a></li>

<li><a  title="Pinterest" target="_blank" href="//www.pinterest.com/pin/create/button/?url=<?php echo $url; ?>&media=<?php echo $image; ?>&description=<?php echo substr($summary,0,499); ?>" data-pin-do="buttonPin"><span class="flaticon-image87"></span></a></li>
<li><a target="_blank" href="https://plus.google.com/share?url=<?php echo $url; ?>" title="Google+"><span class="flaticon-google109"></span></a></li>
<li><a target="_blank" href="http://tumblr.com/share?s=&v=3&t=<?php echo $title;?>&u=<?php echo $url; ?>
" title="Tumblr"><span class="flaticon-tumblr14"></span></a></li>
<li><a href="mailto:<?= $social_vendor->vendor_contact_email;?>" title="<?= $social_vendor->vendor_contact_email;?>"><i class="flaticon-email5"></i> </a></li>
</ul>
</div>
</div>
</div>
</div>


<!-- Mobile end Here-->

<div class="similar_product_listing">
<div class="feature_product_title">
<h2>Similar products</h2>
</div>
<div class="feature_product_slider">
<div class="most_popular_slider">
<div class="slider_new_up">
<div class="flexslider4">
<div id="demo">
<div class="owl-carousel" id="similar-products-slider">   
<?php 

foreach($similiar_item as $s)
{
$sql='SELECT image_path FROM whitebook_image WHERE item_id='.$s['gid'].' and module_type="vendor_item" order by vendorimage_sort_order'; 
$command = Yii::$app->DB->createCommand($sql);
$out = $command->queryAll(); 
if($out){
$imglink=Yii::getAlias('@vendor_image/').$out[0]['image_path'];
$baselink=Yii::$app->params['BASE_URL'].Yii::getAlias('@vendor_image/').$out[0]['image_path'];
}else { 
$imglink=Yii::getAlias('@vendor_image/no_image.jpg');
$baselink=Yii::$app->params['BASE_URL'].Yii::getAlias('@vendor_image/no_image.jpg');
}?> 
<div class="item">
<div class="fetu_product_list">
<a href="<?php echo Yii::$app->params['BASE_URL'];?>/product/<?php echo $s['slug'];?>" title="Products" class="similar">
<img src="<?php echo $baselink;?>" alt="Slide show images" width="208" height="219">
<?php if (file_exists($imglink)) {?>
<img src="<?php echo $baselink;?>" alt="Slide show images" width="208" height="219">
<?php }  ?>
<div class="deals_listing_cont">
<?= $s['vname'];?>
<h3><?= $s['iname'];?></h3>
<p><?= $s['price'];?>KD</p>
</div>
</a>
</div>
</div>
<?php } ?>

</div>
</div>
</div>
</div>
</div>
</div> 

</div>                         
</div><!--product detail end-->
</div>
<!-- one end -->
</div>
</section>
<!-- continer end -->
<!-- end -->
<script type="text/javascript">
/* FeatureD Products script  */
/*jQuery(function () {
SyntaxHighlighter.all();
});*/
jQuery(window).load(function () {
/* client say slider start*/
jQuery('.flexslider2').flexslider({
animation: "slide",
controlNav: false,
animationLoop: true,
slideshow: true,
itemWidth: 223,
itemMargin: 0,
pauseOnHover: true,
slideshowSpeed: 3000,
move: 1,                    
minItems: 1,
maxItems: 5,
start: function (slider) {
jQuery('body').removeClass('loading');
}
});
/* client say slider end*/
});
/* FeatureD Products end*/
/*carousel slider*/
jQuery(window).load(function () {
window.curSlide;
jQuery('#carousel').flexslider({
	
    animation: "slide",
    controlNav: true,
    animationLoop: false,
    directionNav: true,
    slideshow: false,
    touch: true,
    itemWidth: 150,
    itemMargin: 10,
    asNavFor: '#slider',
	/*
animation: "slide",
controlNav: false,
animationLoop: false,
slideshow: true,
itemWidth: 150,
itemMargin: 10,
asNavFor: "#slider"*/

});

jQuery('#slider').flexslider({
	
    animation: "fade",
    slideshow: false,
    directionNav: true,
    controlNav: true,
    touch: true,
    useCSS: false,
    slideshowSpeed: 1000,
    animationSpeed: 100,
    sync: "#carousel",
/*animation: "slide",
controlNav: false,
animationLoop: false,
slideshow: false,
sync: "#carousel",*/
start: function (slider) {
jQuery('#carousel .slides li img').click(function (event) {
event.preventDefault();
sslider.flexAnimate(slider.currentSlide);
});
},
});
//jQuery(":file").filestyle({buttonText: "Find file"});
});
/*carousel slider end*/

jQuery('#description_click').click(function () {
jQuery("i", this).toggleClass("flaticon-up151 flaticon-downwards");

jQuery('#contact_click i').addClass("flaticon-downwards");
jQuery('#additional_click i').addClass("flaticon-downwards");
jQuery('#custom_click i').addClass("flaticon-downwards");
jQuery('#price_click i').addClass("flaticon-downwards");

jQuery('#contact_click i').removeClass("flaticon-up151");
jQuery('#additional_click i').removeClass("flaticon-up151");
jQuery('#custom_click i').removeClass("flaticon-up151");
jQuery('#price_click i').removeClass("flaticon-up151");
});
jQuery('#additional_click').click(function () {
jQuery("i", this).toggleClass("flaticon-up151");

jQuery('#contact_click i').addClass("flaticon-downwards");
jQuery('#price_click i').addClass("flaticon-downwards");
jQuery('#description_click i').addClass("flaticon-downwards");
jQuery('#custom_click i').addClass("flaticon-downwards");

jQuery('#contact_click i').removeClass("flaticon-up151");
jQuery('#description_click i').removeClass("flaticon-up151");
jQuery('#custom_click i').removeClass("flaticon-up151");
jQuery('#price_click i').removeClass("flaticon-up151");
});

jQuery('#contact_click').click(function () {
jQuery("i", this).toggleClass("flaticon-up151");

jQuery('#additional_click i').addClass("flaticon-downwards");
jQuery('#price_click i').addClass("flaticon-downwards");
jQuery('#description_click i').addClass("flaticon-downwards");
jQuery('#custom_click i').addClass("flaticon-downwards");

jQuery('#additional_click i').removeClass("flaticon-up151");
jQuery('#description_click i').removeClass("flaticon-up151");
jQuery('#custom_click i').removeClass("flaticon-up151");
jQuery('#price_click i').removeClass("flaticon-up151");
});

jQuery('#price_click').click(function () {
jQuery("i", this).toggleClass("flaticon-up151");

jQuery('#additional_click i').addClass("flaticon-downwards");
jQuery('#contact_click i').addClass("flaticon-downwards");
jQuery('#description_click i').addClass("flaticon-downwards");
jQuery('#custom_click i').addClass("flaticon-downwards");

jQuery('#additional_click i').removeClass("flaticon-up151");
jQuery('#description_click i').removeClass("flaticon-up151");
jQuery('#custom_click i').removeClass("flaticon-up151");
jQuery('#contact_click i').removeClass("flaticon-up151");
});

jQuery('#custom_click').click(function () {
jQuery("i", this).toggleClass("flaticon-up151");

jQuery('#additional_click i').addClass("flaticon-downwards");
jQuery('#price_click i').addClass("flaticon-downwards");
jQuery('#description_click i').addClass("flaticon-downwards");
jQuery('#contact_click i').addClass("flaticon-downwards");

jQuery('#additional_click i').removeClass("flaticon-up151");
jQuery('#description_click i').removeClass("flaticon-up151");
jQuery('#contact_click i').removeClass("flaticon-up151");
jQuery('#price_click i').removeClass("flaticon-up151");
});

function setupLabel() {
if (jQuery('.label_check input').length) {
jQuery('.label_check').each(function () {
jQuery(this).removeClass('c_on');
});
jQuery('.label_check input:checked').each(function () {
jQuery(this).parent('label').addClass('c_on');
});
}
;
if (jQuery('.label_radio input').length) {
jQuery('.label_radio').each(function () {
jQuery(this).removeClass('r_on');
});
jQuery('.label_radio input:checked').each(function () {
jQuery(this).parent('label').addClass('r_on');
});
}
;
}
;
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
/*end*/


jQuery('#sub_category_cakes').click(function () {
jQuery("span", this).toggleClass("minus_acc plus_acc");

jQuery('#sub_category_bakery span').removeClass("plus_acc");
jQuery('#sub_category_bakery span').removeClass("minus_acc");

jQuery('#sub_category_bakery span').addClass("plus_acc");
});

jQuery('#sub_category_bakery').click(function () {
jQuery("span", this).toggleClass("minus_acc plus_acc");

jQuery('#sub_category_cakes span').removeClass("minus_acc");
jQuery('#sub_category_cakes span').addClass("plus_acc");
});



jQuery('#open_search').click(function () {
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


</script>

<script type="text/javascript">
jQuery(document).ready(function () {
/*product mobile responsive carousel slider start*/
var owl = jQuery("#mobile-slider");

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
autoPlay:false,
pagination : true,
dots: true
});
/*product mobile responsive carousel slider end*/


var owl = jQuery("#owl-demo2");

owl.owlCarousel({
// Define custom and unlimited items depending from the width
// If this option is set, itemsDeskop, itemsDesktopSmall, itemsTablet, itemsMobile etc. are disabled
// For better preview, order the arrays by screen size, but it's not mandatory
// Don't forget to include the lowest available screen size, otherwise it will take the default one for screens lower than lowest available.
// In the example there is dimension with 0 with which cover screens between 0 and 450px

itemsCustom: [
[0, 1],
[450, 2],
[600, 2],
[700, 3],
[1000, 4],
[1200, 5],
[1400, 5],
[1600, 5]
],
navigation: true,
autoWidth: true,
loop: true
});


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
});

/* BEGIN ADD EVENT */

function addevent(item_id)
{	
jQuery.ajax({
type:'POST',
url:"<?php echo Yii::$app->urlManager->createAbsoluteUrl('product/addevent'); ?>",
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

/* BEGIN Buy Item */
jQuery('.buy_item').click(function(){    
    var item_id = (jQuery(this).attr('id'));    
    jQuery.ajax({
        type:'POST',
        url:"<?php echo Yii::$app->urlManager->createAbsoluteUrl('users/addtobasket'); ?>",       
        data:{'item_id':item_id, 'cust_id':<?= Yii::$app->params['CUSTOMER_ID']; ?>},
        success:function(data)
        {
           // alert(data);
        }
    });
});
/* END BUY Item */

</script>


