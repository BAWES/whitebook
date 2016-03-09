<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = $name;
?>



<link href="<?php echo Yii::$app->params['CSS_PATH'];?>bootstrap-select.min.css" rel="stylesheet">
<section id="inner_page_detials">
<div class="top_sections_titles">
<div class="container">
<div class="site-error event_middle_tab">
<div class="col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2 col-xs-12" style="min-height:200px;">
<div style="font-size:75px; text-align:center;margin-top:50px; color:#D46046;">
<b>404</b>
</div>
<div class="alert alert-danger" style="text-align:center">
Oops, Your requested page doesn't exist..!
</div>     

</div>
</div>
</div>
</div>
</section>

<script>
// Window load event used just in case window height is dependant upon images
if (jQuery(window).width() > 991) {
$(window).bind("load", function() { 

var footerHeight = 0,
footerTop = 0,
$footer = $("#footer_sections");

positionFooter();

function positionFooter() {

footerHeight = $footer.height();
footerTop = ($(window).scrollTop()+$(window).height()-footerHeight)+"px";

if ( ($(document.body).height()+footerHeight) < $(window).height()) {
$footer.css({
position: "absolute"
})
} else {
$footer.css({
position: "static"
}).animate({
bottom: footerTop
})
}

}

$(window)
.scroll(positionFooter)
.resize(positionFooter)

});

}
// mobile hover menu start
$(".mobile-menu .dropdown").click(function () {                            
$(this).toggleClass('open');
}, 
function () {
$(this).toggleClass('close');
} 
);
// mobile hover menu end 
</script>
<!--end footer sticky-->


