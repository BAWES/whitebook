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
	<h1><?= Yii::t("frontend", "Themes") ?></h1>
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
$ltr = strtoupper(substr($d['theme_name'],0,1));
if($ltr==$f)
{?>
<li><?= Html::a(strtoupper($d['theme_name']),Url::toRoute(['/site/vendor_profile/','slug'=>$d['slug']])) ?></li>
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
<div class="col-md-4 resposive-clearfix">
<?php $i=0;foreach($first_letter as $f)
{ ?>
<?php if($i<$first){?>
<div class="direct_list">
<h2><?php echo $f;?></h2>
<ul>
<?php foreach($directory as $d) {
$first_letter = strtoupper(substr($d['theme_name'],0,1));
if($first_letter==$f)
{if($i<$first){ ?>
<li><?= Html::a(strtoupper($d['theme_name']),Url::toRoute(['/site/themesearch/','slug'=>$d['slug']])) ?></li>
<?php }  } }?>
</ul>
</div>
<?php }$i++; }?>
</div>
<!-- first section end here-->
<!-- second section start here-->
<div class="col-md-4">
<?php $i=0;foreach($k as $f)
{  ?>
<?php if(($i>=$first)&&($i<$second)){?>
<div class="direct_list">
<h2><?php echo $f;?></h2>
<ul><?php
foreach($directory as $d) {
$first_letter = strtoupper(substr($d['theme_name'],0,1));
if($first_letter==$f)
{ ?>
<li><?= Html::a(strtoupper($d['theme_name']),Url::toRoute(['/site/themesearch/','slug'=>$d['slug']])) ?></li>
<?php  } }?>

</ul>
</div>
<?php }$i++;}?>
</div>
<!-- second section end here-->
<!-- Third section start here-->
<div class="col-md-4 paddingright0">

<?php $i=0;foreach($l as $f)
{  ?>
<?php if(($i>=$second)&&($i<$third)){?>
<div class="direct_list">
<h2><?php echo $f;?></h2>
<ul>
<?php
foreach($directory as $d) {
$first_letter = strtoupper(substr($d['theme_name'],0,1));
if($first_letter==$f)
{?>
<li><?= Html::a(strtoupper($d['theme_name']),Url::toRoute(['/site/themesearch/','slug'=>$d['slug']])) ?></li>
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

var path = "<?= Url::to('site/searchdirectory'); ?> ";
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
