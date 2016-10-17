<?php

use frontend\models\Category;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
$get = Yii::$app->request->get();
?>

<section id="inner_pages_white_back">
	<div id="event_slider_wrapper">
		<div class="container paddng0">
			<?=$this->render('/product/events_slider.php'); ?>
		</div>
	</div>

	<div class="container paddng0">
		<div class="breadcrumb_common">
			<div class="bs-example">
			<?php

			$category_det = Category::category_value($slug);
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
						<?php
						foreach($top_categories as $category) {
							$selected = ($category_id == $category['category_id']) ? 'selected="selected"' : '' ;
							$category_name = (Yii::$app->language == "en") ? $category['category_name'] : $category['category_name_ar'];
						?>
							<option
								data-icon="<?= $category['icon'] ?>"
								value="<?= Url::toRoute(['plan/products', 'slug'=> $category['slug']]) ?>"
								name="category" <?= $selected ?>>
								<?= $category_name ?>
							</option>
						<?php } ?>

					</select>
				</div><!-- END .listing_sub_cat1 -->
			</div><!-- END .responsive-category-top -->

			<div class="responsive-category-bottom">
				<nav class="row-offcanvas row-offcanvas-left">
					<div class="listing_content_cat sidebar-offcanvas" id="sidebar" role="navigation" >
						<div id="accordion" class="panel-group">
							<?=$this->render('@frontend/views/common/filter/category',['slug' => $slug]);?>
							<!--  END PRICE FILTER-->
							<!-- END FILTER  -->
						</div>
				</nav>
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
						<?=$this->render('@frontend/views/common/filter/price');?>
						<?=$this->render('@frontend/views/common/filter/theme',['themes' => $themes]); ?>
						<?=$this->render('@frontend/views/common/filter/vendor',['vendor' => $vendor]); ?>
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
	<div class="listing_right">
		<?=$this->render('@frontend/views/common/items',['items' => $provider, 'customer_events_list' => $customer_events_list]); ?>
	</div>
	<div class="banner_section_plan">
		<?= Html::img("@web/images/banner_plan.png") ?>
	</div>
</div>
</div>
</div>
</section>

<?php
$this->registerCssFile("@web/css/owl.carousel.css");
$this->registerCssFile("@web/css/jquery.mCustomScrollbar.css");
$this->registerCssFile("@web/css/bootstrap-select.min.css");
$this->registerJsFile("@web/js/jquery.mCustomScrollbar.concat.min.js", ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJS("
var giflink = '".Url::to("@web/images/ajax-loader.gif")."';
var load_items = '".Url::to(['plan/products/'])."';
var product_slug = '".$get['slug']."';
", yii\web\View::POS_BEGIN);