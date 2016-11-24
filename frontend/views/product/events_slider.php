<?php

use yii\web\View;
use yii\helpers\Url;
use yii\helpers\Html;
use frontend\models\Users;
use frontend\models\Website;

if(!Yii::$app->user->isGuest) {
	
$wishlist = Users::loadCustomerWishlist(Yii::$app->user->identity->customer_id);
$customer_events = Website::getCustomerEvents(Yii::$app->user->identity->customer_id);

if(count($customer_events) == 0) {  ?>
	<div class="container_eventslider">
		<span class="first_events">
			<?= Yii::t('frontend', 'My Events'); ?>
		</span>
		<div class="creatfirst_events">
			<p data-example-id="active-anchor-btns" class="bs-example">
				<a  href="javascript:" role="button" class="btn btn-default"  data-toggle="modal" data-target="#EventModal" title="<?php echo Yii::t('frontend','Create Your First Event');?>">
					<?php echo Yii::t('frontend','Create Your First Event');?></a>
			</p>
		</div>
	</div>
<?php } else { ?>
	<div class="directory_slider" id="oner">
		<div class="event_slider_top">
			<div class="col-md-3">
				<span class="first_events">
					<?= Yii::t('frontend','My Events'); ?>
				</span>
				<div class="creatfirst_events">
				    <p data-example-id="active-anchor-btns" class="bs-example">
					    <a href="javascript:" role="button" class="btn btn-default" data-toggle="modal" data-target="#EventModal">
					        <?php echo Yii::t('frontend','Add an Event');?>
					    </a>
					</p>
				</div>
			</div>
			<div class="col-md-8">
				<div class="inner_slider_event">
					<div id="demo">
						<div id="owl-demo" class="owl-carousel">
							<div class="item border-none-class">
								<?php if(!empty($customer_events)) {?>
									<a href="<?= Url::toRoute(['/things-i-like/index']); ?>" class="thing_cont" title="Things I like"><span class="heart_fave" id="heart_fave"><?= count($wishlist); ?></span>
										<?php echo Yii::t('frontend','Things I like'); ?>
									</a>
								<?php } else {?>
									<a href="javascript:" role="button" class="btn btn-default first-event-btn" data-toggle="modal" data-target="#EventModal" title="Create Your First Event">
										<?= Yii::t('frontend','Create Your First Event'); ?>
									</a>
								<?php } ?>
							</div>
							<?php
							foreach ($customer_events as $key => $value) { ?>
								<a href="<?=  Url::toRoute(['/events/detail','slug'=>$value['slug']]); ?>">
									<div class="item">
										<h4><?php if(strlen($value['event_name'])>12){echo substr($value['event_name'], 0, 12).' ...';}else{ echo$value['event_name'];} ?></h4>
										<p><?= $value['event_date']; ?></p>
										<p><?= $value['event_type']; ?><br/>
										</p>
									</div>
								</a>
								<?php }  ?>
							</div>
						</div>
					</div>
				</div>
		</div>
	</div><!-- END load user events -->
<?php } 

} else {

?>
<div class="container_eventslider">
	<span class="first_events">
		<?php echo Yii::t('frontend','My Events'); ?>
	</span>
	<div class="creatfirst_events">
		<p data-example-id="active-anchor-btns" class="bs-example">
		<a href="javascript:"  role="button" class="btn btn-default"  data-toggle="modal"  onclick="show_login_modal(-1);" data-target="#myModal" title="<?= Yii::t('frontend', 'Create Your First Event');?>"><?php echo Yii::t('frontend','Create Your First Event');?></a>
		</p>
	</div>
</div>
<?php } ?>

<?php 

$this->registerCss("
	.container_eventslider,.first_events{text-transform:uppercase;}
	.border-none-class{background: transparent!important; border: none!important;}
	.first-event-btn{float: left;    margin-left: 225px;    margin-top: 45px;   min-height: 30px;}
"); 

$this->registerJs("
	jQuery('.listing_right .events_listing ul li:nth-child(4n)').addClass('margin-rightnone');
	jQuery('.thing_items li:nth-child(8n)').addClass('margin-rightnone');

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

	if(jQuery('div.owl-item').length == 2){
		jQuery('.directory_slider > .col-md-8').css('width', '28%');
	} 
	else if(jQuery('div.owl-item').length == 3){
		jQuery('.directory_slider > .col-md-8').css('width', '41%');
	} 
	else if(jQuery('div.owl-item').length == 4)
	{
		jQuery('.directory_slider > .col-md-8').css('width', '53%');
	}
	else if(jQuery('div.owl-item').length == 5)
	{
		jQuery('.directory_slider > .col-md-8').css('width', '66%');
	}

", View::POS_READY);
