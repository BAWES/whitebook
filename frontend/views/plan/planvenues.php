<?php

use common\models\ChildCategory;
use common\models\SubCategory;
use frontend\models\Category;
use common\models\Vendor;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use yii\web\view;

$get = Yii::$app->request->get();

?>

<!-- coniner start -->
<section id="inner_pages_white_back">

	<div id="event_slider_wrapper">
		<div class="container paddng0">
			<?php require(__DIR__ . '/../product/events_slider.php'); ?>
		</div>
	</div>

	<div class="container paddng0">

		<div class="breadcrumb_common">
			<div class="bs-example">

			<?php

			$category_det=Category::category_value($slug);

			$this->params['breadcrumbs'][] = ['label' => ucfirst($category_det['category_name']), 'url' => Url::to(["plan/plan", 'slug' => $slug])];
			?>

			<?= Breadcrumbs::widget([
				'options' => ['class' => 'new breadcrumb'],
				'homeLink' => [
					'label' => Yii::t('yii', 'Home'),
					'url' => Yii::$app->homeUrl,
				],
				'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
			]); ?>
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
					<span class="title_filter"><?= Yii::t('frontend', 'Categories') ?></span>

					<select class="selectpicker" style="display: none;" id="main-category">

						<option data-icon="venues-category" value="venues" <?= $category_id == Category::VENUES?'selected="selected"':''; ?> name="category" >
							Venues
						</option>

						<option data-icon="invitation-category" value="invitations" <?php  if($category_id == Category::INVITATIONS) { ?> selected="selected"<?php } ?> name="category">Invitations</option>

						<option data-icon="food-category" name="category" value="food-beverage" <?php  if($category_id == Category::FOOD_BEVERAGES) { ?> selected="selected"<?php } ?> value="<?= Url::toRoute(['plan/plan', 'slug'=>'food-beverage']) ?>">Food & Beverage</option>

						<option data-icon="decor-category" name="category" value="decor" <?php  if($category_id ==  Category::DECOR) { ?> selected="selected"<?php } ?> value="<?= Url::toRoute(['plan/plan', 'slug'=>'decor']) ?>">Decor</option>

						<option data-icon="supply-category" value="supplies" <?php  if($category_id ==  Category::SUPPLIES) { ?> selected="selected"<?php } ?> value="<?= Url::toRoute(['plan/plan', 'slug'=>'supplies']) ?>">Supplies</option>

						<option data-icon="enter-category" value="entertainment" <?php  if($category_id ==  Category::ENTERTAINMENT) { ?> selected="selected"<?php } ?> value="<?= Url::toRoute(['plan/plan', 'slug'=>'entertainment']) ?>">Entertainment</option>

						<option data-icon="service-category" value="services" <?php  if($category_id ==  Category::SERVICES) { ?> selected="selected"<?php } ?> value="<?= Url::toRoute(['plan/plan', 'slug'=>'services']) ?>">Services</a></option>

						<option data-icon="others-category" <?php  if($category_id ==  Category::OTHERS) { ?> selected="selected" <?php } ?> name="category" value="<?= Url::toRoute(['plan/plan', 'slug'=>'others']) ?>">Others</option>

						<option data-icon="saythankyou-category" <?php  if($category_id == Category::GIFT_FAVORS) { ?> selected="selected"<?php } ?> name="category" value="<?= Url::toRoute(['plan/plan', 'slug'=>'gift-favors']) ?>">Gift Favor</option>

					</select>
				</div><!-- END .listing_sub_cat1 -->
			</div><!-- END .responsive-category-top -->

			<div class="responsive-category-bottom">
				<span class="filter_butt title_filter color_yellow col-xs-12 text-right padding0" data-toggle="offcanvas"><?= Yii::t('frontend', 'Filter') ?></span>
				<div class="filter_title">
					<span class="title_filter color_yellow"><?= Yii::t('frontend', 'Filter by') ?></span>
				</div>
				<div class="filter_butt hamburger is-closed" data-toggle="offcanvas">
					<img width="32" height="35" src="<?php echo Url::to("@web/images/cross92.svg");?>" alt="click here">
				</div>
				<nav class="row-offcanvas row-offcanvas-left">
					<div class="listing_content_cat sidebar-offcanvas" id="sidebar" role="navigation" >
					<div id="accordion" class="panel-group">
			<!-- BEGIN CATEGORY FILTER  -->
			<?php

			/* Get slug name to find category */
			$subcategory = SubCategory::loadsubcat($slug);

			$col=1;
			foreach ($subcategory as $key => $value) {
				$t = $in ='';
				if($col==1){
					$s_class='minus_acc';$t='area-expanded="true"';$in='in';
				}else{
					$s_class='plus_acc';
				}
				?>
				<div class="panel panel-default" >
					<div class="panel-heading">
						<div class="clear_left"><p><?= $value['category_name']; ?> <a href="javascript:void(0)" class="filter-clear" id="filter-clear" title="Clear">- Clear</a></p></div>
						<div class="clear_right">
							<a href="#<?= $value['category_id']; ?>" id="category" data-parent="#accordion" data-toggle="collapse" class="collapsed">
								<h4 class="panel-title">
									<span class="<?= $s_class;?>"></span>
								</h4>
							</a>
						</div>
					</div>
					<div id="<?= $value['category_id']; ?>" <?= $t; ?> class="panel-collapse collapse <?= $in; ?>"  >
						<div class="panel-body">
							<div class="table">
								<?php $childcategory = ChildCategory::loadchildcategoryslug($value['category_id']);
								/* Display scroll for more than three li */
								if(count($childcategory) > 3 ) { $class = "test_scroll"; } else { $class = "";}
								/* Display scroll for more than three li */
								?>
								<ul class="<?= $class; ?>">
									<?php

									foreach ($childcategory as $key => $value) {

										if(isset($get['category']) && $get['category'] !="")
										{
											$val = explode(' ',$get['category']);

											if(in_array($value['slug'],$val))
											{
												$checked = 'checked=checked';
											}
											else
											{
												$checked = '';
											}
										}
										/* END check category checbox values */
										?>
										<li>
											<label class="label_check" for="checkbox-<?= $value['category_name'] ?>">
												<input name="items" data-element="input" class="items" id="checkbox-<?= $value['category_name'] ?>"
												value="<?= $value['slug'] ?>" step="<?= $value['category_id'] ?>"
												type="checkbox" <?php echo (isset($checked) && $checked !="") ?  $checked : ''; ?> >
												<?= ucfirst(strtolower($value['category_name'])); ?></label>
											</li>
											<?php }  ?>
										</ul>
									</div>
								</div>
							</div>
						</div>
						<?php $col++; } ?>
						<!--  END CATEGORY FILTER-->
						<!--  BEGIN THEME FILTER-->
						<div class="panel panel-default" >
							<div class="panel-heading">
								<div class="clear_left"><p><?= Yii::t('frontend', 'Themes') ?> <a href="javascript:void(0)" class="filter-clear" id="filter-clear" title="Clear">- <?= Yii::t('frontend', 'Clear') ?></a></p></div>
								<div class="clear_right">
									<a href="#themes" id="category" data-parent="#accordion" data-toggle="collapse" class="collapsed">
										<h4 class="panel-title">
											<span class="plus_acc"></span>
										</h4>
									</a>
								</div>
							</div>
							<div id="themes" class="panel-collapse collapse" aria-expanded="false">
								<div class="panel-body">
									<div class="table">
										<?php
										/* BEGIN Display scroll for more than three li */
										if(count($themes) > 3 ) { $class = "test_scroll"; } else { $class = "";}
										/* END Display scroll for more than three li */
										?>
										<ul class="<?= $class; ?>">
											<?php foreach ($themes as $key => $value) {
												if(isset($get['themes']) && $get['themes'] !="")
												{
													$val = explode(' ',$get['themes']);

													if(in_array($value['slug'],$val))
													{
														$checked1 = 'checked=checked';
													}
													else
													{
														$checked1 = '';
													}
												}

												if(Yii::$app->language == "en"){
												  	$theme_name = ucfirst(strtolower($value['theme_name'])); 
												}else{ 
													$theme_name = ucfirst(strtolower($value['theme_name_ar'])); 
												}
												
												?>
												<li>
													<label class="label_check" for="checkbox-<?= $value['theme_name'] ?>">

													<input name="themes" data-element="input" class="items" id="checkbox-<?= $value['theme_name'] ?>" step="<?= $value['theme_id'] ?>" value="<?= $value['slug'] ?>" type="checkbox" <?php echo (isset($checked1) && $checked1 !="") ?  $checked1 : ''; ?> ><?= $theme_name ?></label>
												</li>
												<?php } ?>
											</ul>
										</div>
									</div>
								</div>
							</div>
							<!--  END THEME FILTER -->

							<!--  BEGIN VENDOR FILTER -->
							<div class="panel panel-default" >
							<div class="panel-heading">
							<div class="clear_left"><p><?= Yii::t('frontend', 'Vendor') ?> <a href="javascript:void(0)" class="filter-clear" id="filter-clear" title="Clear">- <?= Yii::t('frontend', 'Clear') ?></a></p></div>
							<div class="clear_right">
							<a href="#vendor" id="category" data-parent="#accordion" data-toggle="collapse" class="collapsed">
							<h4 class="panel-title">
							<span class="plus_acc"></span>
							</h4>
							</a>
							</div>
							</div>
							<div id="vendor" class="panel-collapse collapse" area-expanded="false" >
							<div class="panel-body">
							<div class="table">
							<?php

							/* BEGIN Display scroll for more than three li */
							if(count($vendor) > 3 ) { $class = "test_scroll"; } else { $class = "";}
							/* END Display scroll for more than three li */
							?>
							<ul class="<?= $class; ?>">
							<?php foreach ($vendor as $key => $value) {

							if(isset($get['vendor']) && $get['vendor'] !="")
							{

							$val = explode(' ',$get['vendor']);

							if(in_array($value['slug'],$val))
							{
							$checked2 = 'checked=checked';
							}
							else
							{
							$checked2 = '';
							}
							}

							if(Yii::$app->language == "en"){
							  	$vendor_name = ucfirst(strtolower($value['vendor_name'])); 
							}else{ 
								$vendor_name = ucfirst(strtolower($value['vendor_name_ar'])); 
							}
							
							?>
							<li>
							<label class="label_check" for="checkbox-<?= $value['vendor_name'] ?>">
							<input name="vendor" data-element="input" class="items" id="checkbox-<?= $value['vendor_name'] ?>" step="<?= $value['vendor_name'] ?>" value="<?= $value['slug'] ?>" type="checkbox" <?php echo (isset($checked2) && $checked2 !="") ?  $checked2 : ''; ?> ><?= $vendor_name; ?></label>
							</li>
							<?php }?>

							</ul>
							</div><!-- END table -->
							</div>
							</div>
							</div>
							<!--  END VENDOR FILTER-->
							<!--  BEGIN PRICE FILTER -->
<div class="panel panel-default" >
<div class="panel-heading">
<div class="clear_left">
	<p>
		<?= Yii::t('frontend', 'Price'); ?>
		<a href="javascript:void(0)" class="filter-clear" id="filter-clear" title="Clear">- <?= Yii::t('frontend', 'Clear') ?>	
		</a>
	</p>
</div>
<div class="clear_right">
	<a href="#price" data-parent="#accordion" data-toggle="collapse" class="collapsed" id="sub_category_price">
		<h4 class="panel-title">
		<span class="plus_acc">
		</span>
		</h4>
	</a>
</div>
</div>
<div class="panel-collapse collapse" style="height: 0px;" id="price" area-expanded="true" aria-expanded="true">
<div class="panel-body">
<div class="table">
<ul class="test_scroll">
<?php

/* Get max price_per_unit in item table */
$min_price = Yii::$app->db->createCommand('SELECT MIN(item_price_per_unit) as price FROM `whitebook_vendor_item` WHERE trash="Default" and item_approved="Yes"  and item_status="Active" and item_for_sale="Yes"')->queryAll();

$max_price = Yii::$app->db->createCommand('SELECT MAX(item_price_per_unit) as price FROM `whitebook_vendor_item` WHERE trash="Default" and item_approved="Yes"  and item_status="Active" and item_for_sale="Yes"')->queryAll();

$max = $max_price[0]['price'];

$divide = round($max / 5);

$i = 0;

for ($x = $min_price[0]['price'] ; $x <= 1000 ; $x+=$divide) {

//$item_price = $imageData[$i]['item_price_per_unit'];
$min_kd = round($x-$divide);

//if($min_kd > 0 && $item_price >= $min_kd && $item_price <= $x)
if($min_kd > 0 )
{

foreach ($imageData as $key => $value) {
/* Check checkbox based on URL */
if(isset($get['price']) && $get['price'] !="")
{

$val = explode(' ',$get['price']);

if(in_array($value['slug'],$val))
{
	$checked3 = 'checked=checked';
}
else
{
	$checked3 = '';
}
}
/* Check checkbox based on URL */

# code...
$item_price = $value['item_price_per_unit'];

$check_range = ($item_price >= $min_kd && $item_price <= $x) ? 1 : 0;

if($check_range ==1)	{
?>
<li>
<label class="label_check" for="checkbox-<?php echo $x;?>">
<input name="price" id="checkbox-<?php echo $x;?>" value="<?php echo $min_kd = floor($min_kd / 100) * 100;  $min_kd; ?>-<?php echo $x = ceil($x / 100) * 100;?>" type="checkbox">
<?php echo $min_kd = floor($min_kd / 100) * 100;  $min_kd; ?> KD  -  <?php echo $x = ceil($x / 100) * 100;?> KD</label>
</li>
<?php
break;
}
$i++; }
}
}

?>
</ul>
</div>
</div>
</div>
</div>
<!--  END PRICE FILTER-->
<!-- END FILTER  -->
</div>
</nav>
</div>
</div>
</div>
</div>
<div class="col-md-9 paddingright0">
<div class="banner_section_plan">
<?= Html::img("@web/images/banner_plan.png") ?>
</div>
<!-- BEGIN Item lists -->
<div class="listing_right">
<div class="events_listing">
<ul>
<?php
if(!empty($imageData))
{
foreach ($imageData as $key => $value) {

// echo $value['image_path'];die;
if($value['image_path'] !="")  {
?>
<li>
<div class="events_items">
<div class="events_images">
<div class="hover_events">
<div class="pluse_cont">

<?php if(Yii::$app->user->isGuest) { ?>
<a href=""  role="button" class=""  data-toggle="modal"  onclick="show_login_modal(<?php echo $value['item_id'];?>);" data-target="#myModal" title="<?php echo Yii::t('frontend','Add to Event');?>"></a>
<?php } else { ?>
<a  href="#" role="button" id="<?php echo $value['item_id'];?>" name="<?php echo $value['item_id'];?>" class=""   data-toggle="modal" data-target="#add_to_event<?php echo $value['item_id'];?>" onclick="addevent('<?php echo $value['item_id']; ?>')" title="<?php echo Yii::t('frontend','Add to Event');?>"></a>
<?php } ?></div>

<?php if(Yii::$app->user->isGuest) { ?>
<div class="faver_icons">
<a href=""  role="button" class=""  data-toggle="modal" id="<?php echo $value['item_id']; ?>" onclick="show_login_modal_wishlist(<?php echo $value['item_id'];?>);" data-target="#myModal" title="<?php echo Yii::t('frontend','Add to Things I Like');?>"></a>
</div>
<?php } else {
$k=array();
foreach((array)$customer_events_list as $l){
$k[]=$l['item_id'];
}
//print_r($k);die;
$result=array_search($value['item_id'],$k);

if (is_numeric ($result)) { ?>  <div class="faver_icons faverited_icons"> <?php } else { ?>
<div class="faver_icons">
<?php }?>
<a  href="javascript:;" role="button" id="<?php echo $value['item_id']; ?>"  class="add_to_favourite" name="add_to_favourite" title="<?php echo Yii::t('frontend','Add to Things I Like');?>"></a></div>
	<?php } ?>
</div>

<a href="<?= Url::to(["product/product", 'slug' => $value['slug']]) ?>" title="" ><?= Html::img(Yii::getAlias("@s3/vendor_item_images_210/").$value['image_path'],['class'=>'item-img', 'style'=>'width:210px; height:208px;']); ?></a>
</div>
<div class="events_descrip">
<?= Html::a($value['vendor_name'],Url::toRoute(['/product/product/','slug'=>$value['slug']])) ?>
<h3><?= $value['item_name']  ?></h3>
<p><?php if($value['item_price_per_unit'] !='') {echo $value['item_price_per_unit'].'.00 KD'; }else echo '-';?></p></a>
</div>
</div>
</li>
<?php } }  } else {
	echo Yii::t('frontend', "No records found");
}
?>
</ul>
<div id="planloader">
<img src="<?php echo Url::to("@web/images/ajax-loader.gif");?>" title="Loader" style="margin-top: 15%;">
</div>
</div>
<div class="add_more_commons">
	<?php if(count($imageData) > 12) { ?>
	<div class="lode_more_buttons">
		<button title="Load More" data-element="button" id="loadmore" class="btn btn-danger loadmore" type="button">
			 Yii::t('frontend', 'Load More') ?>
		</button>
	</div>
	<?php } ?>
	<div class="banner_section_plan">
	<?= Html::img("@web/images/banner_plan.png") ?>
	</div>
</div><!-- END .add_more_commons -->
</div>
<!-- END Item lists -->

</div>
</div>

</div>
</section>

<?php 

$giflink = Url::to("@web/images/ajax-loader.gif");

$this->registerCssFile("@web/css/owl.carousel.css");
$this->registerCssFile("@web/css/jquery.mCustomScrollbar.css");
$this->registerCssFile("@web/css/bootstrap-select.min.css");
$this->registerJsFile("@web/js/jquery.mCustomScrollbar.concat.min.js", ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerJs("
	function setupLabel() {

		if(jQuery('.label_check input').length ) {

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
	}//end of setupLabel
", VIEW::POS_HEAD);

//megamenu script
//plan last:child script -->
$this->registerJs("
	jQuery('.listing_right .events_listing ul li:nth-child(4n)').addClass('margin-rightnone');
	jQuery('.thing_items li:nth-child(8n)').addClass('margin-rightnone');

	jQuery('.dropdown').hover(
		function () {
			jQuery('.dropdown-menu', this).stop(true, true).slideDown('fast');
			jQuery(this).toggleClass('open');
		},
		function () {
			jQuery('.dropdown-menu', this).stop(true, true).slideUp('fast');
			jQuery(this).toggleClass('open');
		}
	);

	var owl = jQuery('#owl-demo');

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

	jQuery('.label_check, .label_radio').click(function () {
		setupLabel();
	});

	setupLabel();

	jQuery('.custom-select').change(function () {
		var selectedOption = jQuery(this).find(':selected').text();
		jQuery(this).next('.holder').text(selectedOption);
	}).trigger('change');

	//nav content js
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

	jQuery('.collapse').on('shown.bs.collapse', function(){
		jQuery(this).parent().find('.plus_acc').removeClass('plus_acc').addClass('minus_acc');
	}).on('hidden.bs.collapse', function(){
		jQuery(this).parent().find('.minus_acc').removeClass('minus_acc').addClass('plus_acc');
	});

	$('#open_search').click(function () {
		jQuery('#open_search').toggleClass('active');
	});

	/* Mega menu */
	jQuery('.dropdown').hover(
		function() {
			jQuery('.dropdown-menu', this).stop( true, true ).slideDown('fast');
			jQuery(this).toggleClass('open');
		},
		function() {
			jQuery('.dropdown-menu', this).stop( true, true ).slideUp('fast');
			jQuery(this).toggleClass('open');
		}
	);

", View::POS_READY);

$this->registerJs("

	/* Mega menu ends */
	(function(jQuery){
		jQuery(window).load(function(){
			jQuery('.test_scroll').mCustomScrollbar({
				theme: 'rounded-dark',
				mouseWheelPixels: 50,
				scrollInertia: 0
			});
		});
	})(jQuery);

	/* BEGIN filter item list */
	var csrfToken = jQuery('meta[name=\"csrf-token\"]').attr('content');
	var url = window.location.href;     // Returns full URL
	setupLabel();
	
	jQuery('.label_check input').on('change',function() {
		filter();
	});

	/* BEGIN GET SLUG FROM URL */
	var url = window.location.href;
	var newUrl = url.substring(0, url.indexOf('?'));
	var slug;
	
	if(newUrl !='')
	{
		 slug = newUrl.substring(newUrl.lastIndexOf('/') + 1);
	}
	else
	{
		slug = url.substring(url.lastIndexOf('/') + 1);
	}
	/* END GET SLUG FROM URL */

	var limit = 1;

	jQuery('button#loadmore').click(function(event) {
		jQuery('#planloader').show();
	
		setupLabel();
		limit = limit+4;
		var path = '".Yii::$app->urlManager->createAbsoluteUrl('plan/loadmoreitems')."';
	
		jQuery.ajax({
			type:'POST',
			url:path,
			data:{ limit:limit, slug:slug, _csrf : csrfToken},
			success:function(data){
				jQuery('.events_listing ul li:last-child').after(data);
				// Every fourth li change margin
				jQuery('.listing_right .events_listing ul li:nth-child(4n)').addClass('margin-rightnone');
				jQuery('#planloader').hide();
			}
		});
	});


	/* BEGIN load category and reload the page */
	jQuery('#main-category').change(function(){
		var s = jQuery('#main-category :selected').val();
		var hostname = window.location.href;
		var newUrl1 = url.substring(0, url.indexOf('plan'));
		window.location.href = jQuery(this).val();
	});
	/* END load category and reload the page */

	var loadmore = 0;

	function filter(){
		jQuery('#planloader').show();
		jQuery('.events_listing').css({'opacity' : '0.5', 'position' : 'relative'});
		
		var category_name = jQuery('input[name=items]:checked').map(function() {
			return this.value;
		}).get().join('+');

		var theme_name = jQuery('input[name=themes]:checked').map(function() {
			return this.value;
		}).get().join('+');

		var vendor_name = jQuery('input[name=vendor]:checked').map(function() {
			return this.value;
		}).get().join('+');

		var price_val = jQuery('input[name=price]:checked').map(function() {
			return this.value;
		}).get().join('+');
		/* URL format */

		var url_path;
		/* BEGIN GET SLUG FROM URL */
		var url = window.location.href;
		slug = '".$get['slug']."';
		/* END GET SLUG FROM URL */

		/* if all checkbox uncheck load items based on category */
		if(category_name == '' && theme_name == '' && vendor_name == '') {
			window.history.pushState('test', 'Title', newUrl);
		}

		if(category_name != '' || theme_name != '' || vendor_name != '' || price_val != '') {
			url_path = '?slug=' + slug + '&category=' + category_name + '&themes=' + theme_name + '&vendor=' + vendor_name + '&price=' + price_val;
		}

		var path = '".Url::to(['plan/loaditems'])."';

		jQuery.ajax({
			type:'POST',
			url:path,
			data:{
				item_ids: category_name, 
				themes : theme_name,
				vendor : vendor_name,
				price : price_val,
				slug: slug, 
				_csrf : csrfToken
			},
			success:function(data){
				window.history.pushState('test', 'Title', url_path);
				jQuery('.events_listing ul').html(data);
				// Every fourth li change margin
				jQuery('.listing_right .events_listing ul li:nth-child(4n)').addClass('margin-rightnone');
				jQuery('#planloader').hide();
				jQuery('.events_listing').css({'opacity' : '1.0', 'position' : 'relative'});
			}
		}).done(function(){
			jQuery('.add_to_favourite').click(function(){

				jQuery('#loading_img_list').show();
				jQuery('#loading_img_list').html('<img id=\"loading-image\" src=\"".$giflink."\" alt=\"Loading...\" />');

				item_id = jQuery(this).attr('id');
				jQueryelement = jQuery(this);
				jQuery(jQueryelement).parent().toggleClass('faverited_icons');
				var _csrf = jQuery('#_csrf').val();
				jQuery.ajax({
					url : '".Url::toRoute('/users/add_to_wishlist')."',
					type : 'post',
					data : 'item_id=' + item_id + '&_csrf='+_csrf,
					success:function(data) {
						jQuery('#heart_fave').html(data);
						jQuery('#loading_img_list').hide();
					}
				});
			});
		});
	}//end of function 

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
	});

	jQuery('[data-toggle=\"offcanvas\"]').click(function () {
		jQuery('#wrapper').toggleClass('toggled');
	});

	jQuery('#left_side_cate nav').removeClass('navbar navbar-fixed-top');
	jQuery('#left_side_cate ul').removeClass('nav sidebar-nav');
	jQuery('#left_side_cate nav').removeAttr('id')

	if (jQuery(window).width() < 991) {
		jQuery('#left_side_cate nav').addClass ('navbar navbar-fixed-top');
		jQuery('#left_side_cate ul').addClass ('nav sidebar-nav');
		jQuery('#left_side_cate nav').attr ('id','sidebar-wrapper')
	}

	/* END RESPONSIVE FILTER NAVIGATION */
	jQuery('.listing_right .events_listing ul li:nth-child(4n)').addClass('margin-rightnone');

");
