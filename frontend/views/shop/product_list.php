<?php
    use common\models\ChildCategory;
    use common\models\SubCategory;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\Breadcrumbs;
    use yii\web\view;
    $this->params['breadcrumbs'][] = ['label' => ucfirst($Category->category_name), 'url' => Url::to(["shop/products", 'slug' => $slug])];
    $get = Yii::$app->request->get();
?>
    <!-- coniner start -->
    <section id="inner_pages_white_back">
        <div id="event_slider_wrapper">
            <div class="container paddng0">
                <?php echo $this->render('/product/events_slider.php');  ?>
            </div>
        </div>
        <div class="container paddng0">
            <div class="breadcrumb_common">
                <div class="bs-example">
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
                                    <select class="selectpicker" id="main-category">
                                        <?php

                                        foreach ($TopCategories as $category) {

                                            if ($Category->category_id == $category['category_id']) {
                                                $selected = 'selected="selected"';
                                            } else {
                                                $selected = '';
                                            }

                                            if(Yii::$app->language == "en"){
                                                $category_name = $category['category_name'];
                                            }else{
                                                $category_name = $category['category_name_ar'];
                                            }
                                            ?>
                                            <option
                                                data-icon="<?= $category['icon'] ?>"
                                                value="<?= Url::toRoute(['shop/products', 'slug'=> $category['slug']]) ?>"
                                                name="category" <?= $selected ?>>
                                                <?= $category_name ?>
                                            </option>
                                        <?php } ?>
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
                                            <?php echo $this->render('filter/date.php');  ?>
                                            <?php echo $this->render('filter/locations.php');  ?>
                                            <?php echo $this->render('filter/price.php');  ?>
                                            <?php echo $this->render('filter/category.php',['slug'=>$slug]); ?>
                                            <?php echo $this->render('filter/theme.php',['themes'=>$themes]); ?>
                                            <?php echo $this->render('filter/vendor.php',['vendor'=>$vendor]); ?>
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
                                    require 'product_list_ajax.php';
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
                                        <?php Yii::t('frontend', 'Load More') ?>
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
		var path = '".Yii::$app->urlManager->createAbsoluteUrl('shop/load-more-items')."';

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

		var price_val = jQuery('.price_slider').val().replace(',', '-');
		/* URL format */

        var areas = jQuery('input[name=location]:checked').map(function() {
			return this.value;
		}).get().join('+');

		var date = jQuery('#delivery_date_2').val()
		var price_val = jQuery('.price_slider').val().replace(',', '-');


		var url_path;
		slug = '".$get['slug']."';
		var url = window.location.href+'?slug='+slug;


		/* if all checkbox uncheck load items based on category */
		if(category_name == '' && theme_name == '' && vendor_name == '') {

			window.history.pushState('test', 'Title', newUrl+'?slug='+slug);
		}

		if(category_name != '' || theme_name != '' || vendor_name != '' || price_val != '') {
			url_path = '?slug=' + slug + '&category=' + category_name + '&themes=' + theme_name + '&vendor=' + vendor_name + '&price=' + price_val+ '&date=' + date+ '&location=' + areas;
		}

		var path = '".Url::to(['shop/load-items'])."';

		jQuery.ajax({
			type:'POST',
			url:path,
			data:{
				item_ids: category_name,
				themes : theme_name,
				vendor : vendor_name,
				price : price_val,
				slug: slug,
                date : date,
				location: areas,
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

", View::POS_END);
