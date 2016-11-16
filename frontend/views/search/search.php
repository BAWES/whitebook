<?php

use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

$this->title = 'Search Result | Whitebook';
$get = Yii::$app->request->get();
$controller = get_class($this->context);
$event_status=Yii::$app->session->get('event_status');
$search = ($search != '') ? $search : 'All';
if($event_status==-1) { ?>

	<script type="text/javascript">
	function display_event_modal() {
		jQuery('#EventModal').modal('show');
	}
	window.onload=display_event_modal;
</script>
<?php
}

if($event_status>0){
?>
	<script type="text/javascript">
	/* BEGIN ADD EVENT */
	 function addevent1(item_id) {
		jQuery.ajax({
			type:'POST',
			url:"<?= Url::toRoute('/product/addevent'); ?>",
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
	var x='<?= $event_status;?>';
	window.onload=addevent1(x);
   </script>
<?php } ?>
<!-- coniner start -->
<section id="inner_pages_white_back">
	<div id="event_slider_wrapper">
		<div class="container paddng0">
			<?=$this->render('/product/events_slider.php'); ?>
		</div>
	</div>
	<div class="container paddng0">
		<div class="breadcrumb_common">
			<div class="bs-example">
			<!-- <ul class="breadcrumb"> -->
			<?php
				$this->params['breadcrumbs'][] = ['label' => ucfirst($slug), 'url' => Url::to(['/plan/plan/'.$slug])];
			?>
			<?= Breadcrumbs::widget([
				'options' => ['class' => 'new breadcrumb'],
				'homeLink' => [
					'label' => Yii::t('yii', 'Home'),
					'url' => Yii::$app->homeUrl,
				],
				'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
				]);
			?>
		<!-- </ul> -->
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
				<div class="responsive-category-bottom search-list col-xs-12 teat-right pull-right">

					<div class="filter_title">
						<span class="title_filter color_yellow"><?= Yii::t("frontend", "Filter by") ?></span>
					</div>
					
					<nav class="row-offcanvas row-offcanvas-left">
						<div class="listing_content_cat sidebar-offcanvas" id="sidebar" role="navigation" >
							<div id="accordion" class="panel-group">
								<?= $this->render('@frontend/views/common/filter/theme',['themes' => $themes]); ?>
								<?= $this->render('@frontend/views/common/filter/vendor',['vendor' => $vendor]); ?>
								<?= $this->render('@frontend/views/common/filter/price',['items' => $items]); ?>
							</div>
						</div>
					</nav>
				</div>

				<button class="btn btn-close-filter visible-sm visible-xs"><?=Yii::t('frontend','Close filter')?></button>
			</div>
			</div>
		</div>
		<div class="col-md-9 paddingright0">
			<div class="banner_section_plan">
				<h3>
					<?= Yii::t('frontend', 'Search Result for: {search} ({count})', [
							'search' => $search,
							'count' => $count
					]) ?>
				</h3>
			</div>
			<div class="listing_right">
				<div class="events_listing">
						<?=$this->render('@frontend/views/common/items',['items' => $items, 'customer_events_list' => $customer_events_list]); ?>
					<div id="planloader"><img src="<?=Url::to("@web/images/ajax-loader.gif");?>" title="Loader" class="margin-top-15"></div>
				</div>
				<?php /*
				<div class="add_more_commons">
				<?php
				if((!empty($items)) && (count($items) > 20)) { ?>
				<div class="lode_more_buttons">
				<button title="Load More" data-element="button" id="loadmore" class="btn btn-danger loadmore" type="button">Load More</button>
				</div>
				<?php } ?>
				</div>
				</div> */ ?>
			</div>
		</div>
	</div>
</section>

<?php
$this->registerCss("
.margin-top-15{margin-top: 15%}
");

$this->registerJs("
	var page = 'search',
        search_keyword      = '".$search."',
        load_items          = '".Url::toRoute(['/search/index'],true)."',
        load_more_items		= '".Url::toRoute(['plan/loadmoreitems'],true)."'
	", \yii\web\View::POS_HEAD, 'searching-options'
);

$this->registerCssFile("@web/css/owl.carousel.css");
$this->registerCssFile("@web/css/bootstrap-select.min.css");
$this->registerCssFile("@web/css/jquery.mCustomScrollbar.css");
$this->registerJsFile('@web/js/jquery.mCustomScrollbar.concat.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
//$this->registerJsFile('@web/js/pages/search.js?V=1.1', ['depends' => [\yii\web\JqueryAsset::className()]]);
?>