<?php

//use common\models\ChildCategory;
//use common\models\SubCategory;
use frontend\models\Category;
//use common\models\Vendor;
use frontend\models\Themes;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

$get = Yii::$app->request->get();
?>

<section id="inner_pages_white_back">
	<div class="container paddng0">
		<?php $this->render('/product/events_slider.php'); ?>

		<div class="breadcrumb_common">
			<div class="bs-example">
				<?php
				$this->params['breadcrumbs'][] = ['label' => 'Themes >  '.ucfirst($theme->theme_name), 'url' => Url::to(["/themes/detail", 'slug' => $theme->slug])];
				echo Breadcrumbs::widget([
					'options' => ['class' => 'new breadcrumb'],
					'homeLink' => [
						'label' => Yii::t('yii', 'Home'),
						'url' => Yii::$app->homeUrl,
					],
					'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
				]);
				?>
			</div>
		</div>
		<div class="plan_venues" id="wrapper">
			<div class="overlay"></div>
			<div class="overlay_filter"></div>
			<div class="col-md-3 paddingleft0" id="left_side_cate">
				<div class="filter_content">
					<div class="filter_section">
						<div class="responsive-category-top">
							<div class="listing_sub_cat1">
								<span class="title_filter">Categories</span>
								<select class="selectpicker" style="display: none;" id="main-categories" title="Category">
									<option data-icon="venues-category" selected="selected" name="category"><?= Yii::t("frontend", "All"); ?></option>
									<option data-icon="venues-category" value="venues" <?php  if($category_id == Category::VENUES) { ?> selected="selected" <?php } ?> ><?= Yii::t("frontend", "Venues") ?></option>
									<option data-icon="invitation-category" value="invitations" <?php  if($category_id == Category::INVITATIONS) { ?> selected="selected"<?php } ?> name="category"><?= Yii::t("frontend", "Invitations") ?></option>
									<option data-icon="food-category"  value="food-beverage" <?php  if($category_id == Category::FOOD_BEVERAGES) { ?> selected="selected"<?php } ?> value="<?= Url::toRoute(['plan/plan', 'slug'=>'food-beverage']) ?>"><?= Yii::t("frontend", "Food & Beverage") ?></option>
									<option data-icon="decor-category" value="decor" <?php  if($category_id ==  Category::DECOR) { ?> selected="selected"<?php } ?> value="<?= Url::toRoute(['plan/plan', 'slug'=>'decor']) ?>"><?= Yii::t("frontend", "Decor") ?></option>
									<option data-icon="supply-category" value="supplies" <?php  if($category_id ==  Category::SUPPLIES) { ?> selected="selected"<?php } ?> value="<?= Url::toRoute(['plan/plan', 'slug'=>'supplies']) ?>"><?= Yii::t("frontend", "Supplies") ?></option>
									<option data-icon="enter-category" value="entertainment" <?php  if($category_id ==  Category::ENTERTAINMENT) { ?> selected="selected"<?php } ?> value="<?= Url::toRoute(['plan/plan', 'slug'=>'entertainment']) ?>"><?= Yii::t("frontend", "Entertainment") ?></option>
									<option data-icon="service-category" value="services" <?php  if($category_id ==  Category::SERVICES) { ?> selected="selected"<?php } ?> value="<?= Url::toRoute(['plan/plan', 'slug'=>'services']) ?>"><?= Yii::t("frontend", "Services") ?></a></option>
									<option data-icon="others-category" value="others" <?php  if($category_id ==  Category::OTHERS) { ?> selected="selected" <?php } ?> name="category" value="<?= Url::toRoute(['plan/plan', 'slug'=>'others']) ?>"><?= Yii::t("frontend", "Others") ?></option>
									<option data-icon="saythankyou-category" value="gift-favors" <?php  if($category_id == Category::GIFT_FAVORS) { ?> selected="selected"<?php } ?> name="category" value="<?= Url::toRoute(['plan/plan', 'slug'=>'gift-favors']) ?>"><?= Yii::t("frontend", "Gift Favors") ?></option>
								</select>
							</div>
						</div>
						<div class="responsive-category-bottom">
							<span class="filter_butt title_filter color_yellow col-xs-12 text-right padding0" data-toggle="offcanvas">
								<?= Yii::t("frontend", "Filter") ?>
							</span>
							<div class="filter_title">
								<span class="title_filter color_yellow"><?= Yii::t("frontend", "Filter by") ?></span>
							</div>
							<div class="filter_butt hamburger is-closed" data-toggle="offcanvas">
								<img width="32" height="35" src="<?php echo Url::to("@web/images/cross92.svg");?>" alt="click here">
							</div>
							<nav class="row-offcanvas row-offcanvas-left">
								<div class="listing_content_cat sidebar-offcanvas" id="sidebar" role="navigation" >
									<div id="accordion" class="panel-group">
										<?php
											require '_filter/category.php';
											require '_filter/vendor.php';
											require '_filter/price.php';
										?>
										<!-- END FILTER  -->
									</div><!-- END -->
								</div>
							</nav>
						</div>
					</div><!-- END .filter_section -->
				</div><!-- END .filter_content -->
			</div><!-- END .left_side_cate -->
			<div class="col-md-9 paddingright0">
				<div class="banner_section_plan">
					<?= Html::img("@web/images/banner_plan.png") ?>
				</div>
				<div class="listing_right">
					<div class="events_listing">
						<ul>
						<?php

						if(!empty($items)) {
							foreach ($items as $key => $value) {
								echo $this->render('item', [ 
									'value' => $value,
									'customer_events_list' => $customer_events_list
								]); 
							}
						} else {
							echo "No records found";
						}
						?>
						</ul>
					<div id="planloader">
						<img src="<?php echo Url::to("@web/images/ajax-loader.gif");?>" title="Loader" style="margin-top: 15%;">
					</div>
					</div>

					<!--
					<div class="add_more_commons">
						<?php if(count($items) > 12) { ?>
							<div class="lode_more_buttons">
								<button title="Load More" data-element="button" id="loadmore" class="btn btn-danger loadmore" type="button">Load More</button>
							</div>
						<?php } ?>
						<div class="banner_section_plan">
							<?= Html::img("@web/images/banner_plan.png") ?>
						</div>
					</div>
					-->
				</div>
			</div>
		</div>
	</div>
</section>

<!-- continer end -->
<link href="<?= Url::to("@web/css/owl.carousel.css") ?>" rel="stylesheet">
<link href="<?= Url::to("@web/css/bootstrap-select.min.css") ?>" rel="stylesheet">
<link href="<?= Url::to("@web/css/jquery.mCustomScrollbar.css") ?>" rel="stylesheet">
<script src="<?= Url::to("@web/js/jquery.mCustomScrollbar.concat.min.js") ?>"></script>

<!-- megamenu script -->
<!-- plan last:child script -->
<script type="text/javascript">
$(document).ready(function () {
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
	});

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


function setupLabel() {
	if (jQuery('.label_check input').length ) {
		jQuery('.label_check').each(function () {
		jQuery(this).removeClass('c_on');
			if(jQuery(this).parents('.panel-body').find('label.c_on').length == 0){
				jQuery(this).parents('.panel-default').find('a.filter-clear').css('display','none');
			}else{
				jQuery(this).parents('.panel-default').find('a.filter-clear').css('display','inline-block');
			}
		});
		jQuery('.label_check input:checked').each(function () {
			jQuery(this).parent('label').addClass('c_on');
			if(jQuery(this).parents('.panel-body').find('label.c_on').length == 0){
				jQuery(this).parents('.panel-default').find('a.filter-clear').css('display','none');
			}else{
				jQuery(this).parents('.panel-default').find('a.filter-clear').css('display','inline-block');
			}
		});
	}

	if (jQuery('.label_radio input').length) {
		jQuery('.label_radio').each(function () {
			jQuery(this).removeClass('r_on');
		});
		jQuery('.label_radio input:checked').each(function () {
			jQuery(this).parent('label').addClass('r_on');
		});
	}
}

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


/*jQuery('#accordion a').click(function () {
jQuery("span", this).toggleClass("minus_acc plus_acc");
});*/
jQuery('.collapse').on('shown.bs.collapse', function(){
	jQuery(this).parent().find(".plus_acc").removeClass("plus_acc").addClass("minus_acc");
}).on('hidden.bs.collapse', function(){
	jQuery(this).parent().find(".minus_acc").removeClass("minus_acc").addClass("plus_acc");
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
	});
});
/*
function toggl(val)
{
//   alert(val);
jQuery(".panel-heading #bakery").remove();
var cat = 'category_'+val;
alert(cat);
//alert($("#"+cat).parent().parent().attr("class"));
$("#"+cat).parent().parent().find('#bakery').hide();
*/
/*end*/

$('#open_search').click(function () {
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
(function(jQuery){
	jQuery(window).load(function(){
		jQuery(".test_scroll").mCustomScrollbar({
			theme:"rounded-dark" ,
			mouseWheelPixels: 50,
			scrollInertia: 0
		});
	});
})(jQuery);


/* BEGIN filter item list */
var csrfToken = jQuery('meta[name="csrf-token"]').attr("content");
var url = window.location.href;     // Returns full URL
setupLabel();
jQuery('.label_check input').on('change',function()
{
	filter();
});

/* BEGIN GET SLUG FROM URL */
var url = window.location.href;
var newUrl = url.substring(0, url.indexOf('?'));
var slug;
if (newUrl !='') {
	 slug = newUrl.substring(newUrl.lastIndexOf('/') + 1);
} else {
	slug = url.substring(url.lastIndexOf('/') + 1);
}
/* END GET SLUG FROM URL */

var limit = 1;
jQuery('button#loadmore').click(function(event)
{
	jQuery("#planloader").show();
	setupLabel();
	limit = limit+4;
	var path = "<?php echo Yii::$app->urlManager->createAbsoluteUrl('plan/loadmoreitems'); ?> ";
	jQuery.ajax({
		type:'POST',
		url:path,
		data:{limit:limit, slug:slug, _csrf : csrfToken},
		success:function(data){
			jQuery('.events_listing ul li:last-child').after(data);
			// Every fourth li change margin
			jQuery('.listing_right .events_listing ul li:nth-child(4n)').addClass("margin-rightnone");
			jQuery("#planloader").hide();
		}
	})
});

/* BEGIN load category and reload the page */
//jQuery('#main-categories').change(function(){
//	//alert(jQuery('option[name=category]').val());
//	var s = jQuery('#main-categories :selected').val();
//	var hostname = window.location.href;
//	var newUrl1 = url.substring(0, url.indexOf('plan'));
//	window.location.href = jQuery(this).val();
//});
/* END load category and reload the page */
var loadmore=0;
function filter() {
	var main_categories = $('#main-categories').val();
	jQuery("#planloader").show();
	jQuery(".events_listing").css({"opacity":"0.5","position":"relative"});
	var category_name = jQuery("input[name=items]:checked").map(function() {
		return this.value;
	}).get().join('+');

	var theme_name = jQuery("input[name=themes]:checked").map(function() {
		return this.value;
	}).get().join('+');

	var vendor_name = jQuery("input[name=vendor]:checked").map(function() {
		return this.value;
	}).get().join('+');

	var price_val = jQuery("input[name=price]:checked").map(function() {
		return this.value;
	}).get().join('+');
	/* URL format */

	var url_path;
	/* BEGIN GET SLUG FROM URL */
	var url = window.location.href;
	slug = <?= '"'.$get['slug'].'"';?>;
	var newUrl = url.substring(0, url.indexOf('?'));
	/* END GET SLUG FROM URL */

	/* if all checkbox uncheck load items based on category */
	if (category_name =="" && theme_name =="" && vendor_name =="") {
		window.history.pushState("test", "Title", newUrl);
	}

	if (category_name !="" || theme_name !="" || vendor_name !="" || price_val !="") {
		url_path = 'subcategory='+category_name+'&vendor='+vendor_name+'&price='+price_val+'&category='+main_categories;
		window.history.pushState("test", "Title", '?slug='+slug+'&'+url_path);
	} else {
		url_path = '?slug='+slug+'&subcategory='+category_name+'&vendor='+vendor_name+'&price='+price_val+'&category='+main_categories;
		window.history.pushState("test", "Title", url_path);
	}

	var path = window.location.href;//"<?= Url::to(['/themes/detail']); ?> ";
	<?php $giflink= Url::to("@web/images/ajax-loader.gif");?>
	jQuery.ajax({
		type:'POST',
		url:path,
		data:{item_ids: category_name, themes : theme_name,vendor : vendor_name,price : price_val,slug: slug,category:main_categories, _csrf : csrfToken},
		success:function(data){
			jQuery('.events_listing ul').html(data);
			// Every fourth li change margin
			jQuery('.listing_right .events_listing ul li:nth-child(4n)').addClass("margin-rightnone");
			jQuery("#planloader").hide();
			jQuery(".events_listing").css({"opacity":"1.0","position":"relative"});
		}
		}).done(function(){
			jQuery(".add_to_favourite").click(function(){
				jQuery('#loading_img_list').show();
				jQuery('#loading_img_list').html('<img id="loading-image" src="<?= $giflink;?>" alt="Loading..." />');

				item_id=(jQuery(this).attr('id'));
				jQueryelement = jQuery(this)
				jQuery(jQueryelement).parent().toggleClass("faverited_icons");
				var _csrf=jQuery('#_csrf').val();
				jQuery.ajax({
					url:"<?= Url::toRoute('/users/add_to_wishlist'); ?>",
					type:"post",
					data:"item_id="+item_id+"&_csrf="+_csrf,
					//async: false,
					success:function(data)
					{
						jQuery('#heart_fave').html(data);
						jQuery('#loading_img_list').hide();
					}
				});
			});
	});
}

/* BEGIN CLEAR FILTER */
jQuery('a#filter-clear').on('click',function(){
	jQuery(this).parents('.panel-default').find('label.label_check').removeClass('c_on');
	jQuery(this).parents('.panel-default').find('label.label_check input').prop('checked', false);
	jQuery(this).hide();
	filter();
})
/* END CLEAR FILTER */

/* BEGIN RESPONSIVE FILTER NAVIGATION */
var trigger = jQuery('.filter_butt,.search_header'),
overlay = jQuery('.overlay'),
isClosed = false;

trigger.click(function () {
	filter_butt();   /* FUNCTION REFERENCE main.js */
});

jQuery('.search_header').click(function(){
	if (isClosed == true) {
		overlay.hide();
		trigger.removeClass('ses_act');
		trigger.addClass('ses_dct');
		isClosed = false;
	} else {
		overlay.show();
		trigger.removeClass('ses_act');
		trigger.addClass('ses_dct');
		isClosed = true;
	}
})

jQuery('[data-toggle="offcanvas"]').click(function () {
	jQuery('#wrapper').toggleClass('toggled');
});


jQuery("#left_side_cate nav").removeClass ("navbar navbar-fixed-top ");
jQuery("#left_side_cate ul").removeClass ("nav sidebar-nav ");
jQuery("#left_side_cate nav").removeAttr ("id")
if (jQuery(window).width() < 991) {
	jQuery("#left_side_cate nav").addClass ("navbar navbar-fixed-top ");
	jQuery("#left_side_cate ul").addClass ("nav sidebar-nav ");
	jQuery("#left_side_cate nav").attr ('id','sidebar-wrapper')
}

/* END RESPONSIVE FILTER NAVIGATION */
jQuery('.listing_right .events_listing ul li:nth-child(4n)').addClass("margin-rightnone");

/* BEGIN load category and reload the page */
jQuery('#main-categories').change(function(){
	var category = jQuery('#main-categories :selected').val();
	var hostname = '<?=$url?>';
	window.location.href = hostname+'&category='+category;
});
/* END load category and reload the page */
</script>
