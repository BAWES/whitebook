<?php use yii\helpers\Url;
use yii\helpers\Html;?>
<!-- coniner start -->
<section id="inner_pages_white_back">
<div class="container paddng0">
<!-- Events slider start -->
<?php require(__DIR__ . '/../product/events_slider.php'); ?>
<!-- Events slider end -->

<div class="directory_listing">
<div class="title_main">
<h1>Directory</h1>
</div>
<div class="col-md-3 paddingleft0">
<div class="filter_content">
<div class="filter_section">
<span class="title_filter">Categories</span>
<div class="listing_sub_cat">
<input type="hidden" id="ajaxdata" name="ajaxdata">
<select class="selectpicker" style="display: none;" id="filter_category" name="filter_category">
<option value="All">All</option>
<option data-icon="venues-category" value="venues">Venues</option>
<option data-icon="invitation-category" value="invitations">Invitations</option>
<option data-icon="food-category" value="food-beverage">Food & Beverage</option>
<option data-icon="decor-category" value="decor" >Decor</option>
<option data-icon="supply-category" value="supplies">Supplies</option>
<option data-icon="enter-category" value="entertainment">Entertainment</option>
<option data-icon="service-category" value="services">Services</option>
<option data-icon="others-category" value="others">Others</option>
<option data-icon="saythankyou-category" value="say-thank-you">Say "Thank You"</option>
<?php /*<option data-icon="venue-category">Venues</option>
<option data-icon="invitation-category">Invitations</option>
<option data-icon="food-category">Food &Beverage</option>
<option data-icon="decor-category">Decor</option>
<option data-icon="supply-category">Supplies</option>
<option data-icon="enter-category">Entertainment</option>
<option data-icon="service-category">Services</option>
<option data-icon="other-category">Others</option>
<option data-icon="say-category">Say Thank you</option> */?>
</select>
</div>
</div>
</div>
</div>

<div id="mobile_respon">
<div class="mobile-view col-xs-12 padding0 directory-responsive">
<?php $fl=$first_letter;?>
<div class="tabContainer">
<ul id="demoOne" class="demo">
<?php foreach($fl as $f)
{ ?>
<li><h2><?php echo $f;?></h2></li>
<?php foreach($directory as $d) {
$ltr = strtoupper(substr($d['vname'],0,1));
if($ltr==$f)
{?>
<li><?= Html::a(strtoupper($d['vname']),Url::toRoute(['/site/experience/','slug'=>$d['slug']])) ?></li>
<?php } }?>
<?php }?>
</ul>
</div>
</div>
</div>

<div id="filter">
<?php $total=count($directory);
if($total>1){
$first=$total/3;
$second=$first+$first;
$third=$second+$first;
$k=$first_letter;
$l=$first_letter;?>

<!-- first section start here-->
<div class="resposive-clearfix">
<div class="col-md-3 resposive-clearfix">
<?php $i=0;foreach($first_letter as $f)
{ ?>
<?php if($i<$first){?>
<div class="direct_list">
<h2><?php echo $f;?></h2>
<ul>
<?php foreach($directory as $d) {
$first_letter = strtoupper(substr($d['vname'],0,1));
if($first_letter==$f)
{if($i<$first){ ?>
<li><?= Html::a(strtoupper($d['vname']),Url::to(['site/vendor_profile','slug'=>$d['slug']])) ?></li>
<?php }  } }?>
</ul>
</div>
<?php }$i++; }?>
</div>
<!-- first section end here-->
<!-- second section start here-->
<div class="col-md-3">
<?php $i=0;foreach($k as $f)
{  ?>
<?php if(($i>=$first)&&($i<$second)){?>
<div class="direct_list">
<h2><?php echo $f;?></h2>
<ul><?php
foreach($directory as $d) {
$first_letter = strtoupper(substr($d['vname'],0,1));
if($first_letter==$f)
{ ?>
<li><?= Html::a(strtoupper($d['vname']), Url::to(["site/vendor_profile", 'slug' => $d['slug']])) ?></li>
<?php  } }?>

</ul>
</div>
<?php }$i++;}?>
</div>
<!-- second section end here-->
<!-- Third section start here-->
<div class="col-md-3 paddingright0">

<?php $i=0;foreach($l as $f)
{  ?>
<?php if(($i>=$second)&&($i<$third)){?>
<div class="direct_list">
<h2><?php echo $f;?></h2>
<ul>
<?php
foreach($directory as $d) {
$first_letter = strtoupper(substr($d['vname'],0,1));
if($first_letter==$f)
{?>
<li><?= Html::a(strtoupper($d['vname']),Url::to(['site/vendor_profile','slug'=>$d['slug']])) ?></li>
<?php  } }?>

</ul>
</div>
<?php }$i++;}?>
</div>
<!-- Third section end here-->
<?php } ?>
</div>
</div>

</div>
</div>
</section>
<!-- continer end -->
<script src="<?= Url::to("@web/js/jquery-listnav.js") ?>"></script>
<link href="<?= Url::to("@web/css/listnav.css") ?>" rel="stylesheet">
<script>


/*directorypage nav tabs*/
$(function(){
jQuery('#demoOne').listnav();

jQuery('.demo a').click(function(e) {
e.preventDefault();
});
});

jQuery('#filter_category').change(function(){


var x= jQuery('#filter_category').val();
var ajaxdata= jQuery('#ajaxdata').val();

var path = "<?php echo Yii::$app->urlManager->createAbsoluteUrl('site/searchdirectory'); ?> ";
jQuery.ajax({
type:'POST',
url:path,
data:{slug:x,ajaxdata:ajaxdata},
success:function(data){
if(ajaxdata=='1'){
	jQuery('#mobile_respon').html(data);}
	else
{jQuery('#filter').html(data);}
}
}).done(function() {
if(ajaxdata=='1'){
jQuery('#demoOne').listnav();

jQuery('.demo a').click(function(e) {
e.preventDefault();
});
}


});
});


jQuery('#demoOne a').click(function(){
var directive_link=(jQuery(this).attr("href"));
window.location.href=directive_link;
});
</script>
<script type="text/javascript">
if (jQuery(window).width() < 991) {
	jQuery('#ajaxdata').val('1');
}else{
		jQuery('#ajaxdata').val('0');
		}
</script>
