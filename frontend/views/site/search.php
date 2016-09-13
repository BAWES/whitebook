<?php

use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

$this->title = 'Search Result | Whitebook';
$get = Yii::$app->request->get();
$controller = get_class($this->context);
$event_status=Yii::$app->session->get('event_status');
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
			<div class="col-md-3 paddingleft0" id="left_side_cate">
				<div class="filter_content">
					<div class="filter_section">
						<div class="responsive-category-bottom search-list col-xs-12 teat-right pull-right">
							<span class="filter_butt title_filter color_yellow col-xs-12 text-right padding0 pull-right" data-toggle="offcanvas">Filter</span>
							<div class="filter_title">
								<span class="title_filter color_yellow"><?= Yii::t("frontend", "Filter by") ?></span>
							</div>
							<div class="filter_butt hamburger is-closed" data-toggle="offcanvas">
								<img width="32" height="35" src="<?php echo Url::to("@web/images/cross92.svg");?>" alt="click here">
							</div>
							<nav class="row-offcanvas row-offcanvas-left">
								<div class="listing_content_cat sidebar-offcanvas" id="sidebar" role="navigation" >
									<div id="accordion" class="panel-group">

										<?= $this->render('@frontend/views/plan/filter/theme',[
											'themes' => $themes]); ?>

										<?= $this->render('@frontend/views/plan/filter/vendor',[
											'vendor' => $vendor]); ?>

										<?= $this->render('@frontend/views/plan/filter/price',[
											'imageData' => $imageData]); ?>
											
									</div>
								</div>
							</nav>
						</div>
					</div>
				</div>
			</div>

		<div class="col-md-9 paddingright0">
		<div class="banner_section_plan">
			<h3>Search Result for:<?= $search?> (<?= count($imageData);?>)</h3>
		</div>
		<!-- BEGIN Item lists -->
		<div class="listing_right">
		<div class="events_listing">
            <ul>
            <?php
                if(!empty($imageData))  {
                    foreach ($imageData as $key => $value) {
                        echo $this->render('@frontend/views/plan/item',[
                            'value' => $value,
                            'customer_events_list' => $customer_events_list
                        ]);
                    }
                } else {
                    echo Yii::t('frontend', "No records found");
                }
            ?>
            </ul>
            <div id="planloader"><img src="<?=Url::to("@web/images/ajax-loader.gif");?>" title="Loader" style="margin-top: 15%;"></div>
		</div>
		<?php /*
		<div class="add_more_commons">
		<?php
		if((!empty($imageData)) && (count($imageData) > 20)) { ?>
		<div class="lode_more_buttons">
		<button title="Load More" data-element="button" id="loadmore" class="btn btn-danger loadmore" type="button">Load More</button>
		</div>
		<?php } ?>
		</div>
		</div> */ ?>
		<!-- END Item lists -->
		</div>
		</div>

	</div>
</section>

<!-- continer end -->
<link href="<?= Url::to("@web/css/owl.carousel.css") ?>" rel="stylesheet">
<link href="<?= Url::to("@web/css/bootstrap-select.min.css") ?>" rel="stylesheet">
<link href="<?= Url::to("@web/css/jquery.mCustomScrollbar.css") ?>" rel="stylesheet">

<?php
$this->registerJs("

    var slug                = '".$search."',
        search              = '".$search."',
        path                = '".Yii::$app->urlManager->createAbsoluteUrl('product-filter-result/searching-page-filter')."',
        giflink             = '".Url::to("@web/images/ajax-loader.gif")."',
        wishlist_url        = '".Url::to(['/users/add_to_wishlist'])."',
        addevent            = '".Url::to(['/product/addevent'])."',
        load_more           = '".Yii::$app->urlManager->createAbsoluteUrl('plan/loadmoreitems')."'
    ", \yii\web\View::POS_END, 'searching-options');

echo $this->registerJsFile('js/jquery.mCustomScrollbar.concat.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
echo $this->registerJsFile('js/pages/search.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

//$this->registerCssFile("http://example.com/css/themes/black-and-white.css", [
//'depends' => [BootstrapAsset::className()],
//'media' => 'print',
//], 'css-print-theme');

?>