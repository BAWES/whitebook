<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

$get = Yii::$app->request->get();
?>

<section id="inner_pages_white_back" class="<?=Yii::$app->controller->id;?>">
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

			<span class="filter_butt visible-xs visible-sm">
                <i class="fa fa-filter"></i>
            </span>

			<div class="col-md-3 paddingleft0 hidden-xs hidden-sm" id="left_side_cate">
				<div class="filter_content">
					<div class="filter_section">
						<?=$this->render('@frontend/views/common/filter/top_cat.php',['path'=>'themes/detail','theme'=>$theme->slug]); ?>
						<div class="responsive-category-bottom">
							<nav class="row-offcanvas row-offcanvas-left">
								<div class="listing_content_cat sidebar-offcanvas" id="sidebar" role="navigation" >
									<div id="accordion" class="panel-group sub-category-wrapper"">
										<?=$this->render('@frontend/views/common/filter/category.php',['slug'=>$slug]); ?>
										<!-- END FILTER  -->
									</div><!-- END -->
								</div>
							</nav>

							<div class="filter_title">
								<span class="title_filter color_yellow"><?= Yii::t("frontend", "Filter by") ?></span>
							</div>

							<nav class="row-offcanvas row-offcanvas-left">
								<div class="listing_content_cat sidebar-offcanvas" id="sidebar" role="navigation" >
									<div id="accordion" class="panel-group">
										<?=$this->render('@frontend/views/common/filter/vendor.php',['vendor'=>$vendor]); ?>
										<?=$this->render('@frontend/views/common/filter/price.php');  ?>
										<!-- END FILTER  -->
									</div><!-- END -->
								</div>
							</nav>
						</div>
					</div><!-- END .filter_section -->

					<button class="btn btn-close-filter visible-sm visible-xs"><?=Yii::t('frontend','Close filter')?></button>

				</div><!-- END .filter_content -->
			</div><!-- END .left_side_cate -->
			<div class="col-md-9 paddingright0 right-side">
				<div class="listing_right">
					<?=$this->render('@frontend/views/common/items',['items' => $provider, 'customer_events_list' => $customer_events_list]); ?>
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
<?php $this->registerJs("
	var theme =  '".$theme->slug."';
	var giflink = '".Url::to("@web/images/ajax-loader.gif")."';
	var load_more_items = '".Url::to(['browse/load-more-items'])."';
	var load_items = '".Url::to(['/themes'],true)."';
	var product_slug = '".$get['slug']."';
	var current_page = 'theme';
	", \yii\web\View::POS_BEGIN);

$this->registerJs("

	$(document).delegate('.category_listing_nav a', 'click', function(e) {
	    product_slug = $(this).attr('data-slug');
	    
	    //load child categories 
	    $.get('browse/sub-category-filter?slug=' + product_slug, function(html) {
	        $('.sub-category-wrapper').html(html);
	        $('.left-main-cat').val(product_slug).change();
	        
	        //load items from selected cat 
	        //filter();
	    });
	    e.preventDefault();
	});

", \yii\web\View::POS_END);

?>