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
						<?=$this->render('@frontend/views/common/filter/top_cat.php',['path'=>'themes/detail','theme'=>$theme->slug]); ?>
						<div class="responsive-category-bottom">
							<nav class="row-offcanvas row-offcanvas-left">
								<div class="listing_content_cat sidebar-offcanvas" id="sidebar" role="navigation" >
									<div id="accordion" class="panel-group">
										<?=$this->render('@frontend/views/common/filter/category.php',['slug'=>$slug]); ?>
										<!-- END FILTER  -->
									</div><!-- END -->
								</div>
							</nav>

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
										<?=$this->render('@frontend/views/common/filter/vendor.php',['vendor'=>$vendor]); ?>
										<?=$this->render('@frontend/views/common/filter/price.php');  ?>
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
				<!-- BEGIN Item lists -->
				<div class="listing_right">
					<?=$this->render('@frontend/views/common/items',['items' => $provider, 'customer_events_list' => $customer_events_list]); ?>
				</div>
				<div class="banner_section_plan">
					<?= Html::img("@web/images/banner_plan.png") ?>
				</div>
				<!-- END Item lists -->
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
	var load_more_items = '".Url::to(['shop/load-more-items'])."';
	var load_items = '".Url::to(['themes/detail'])."';
	var product_slug = '".$get['slug']."';
	", \yii\web\View::POS_BEGIN);
?>