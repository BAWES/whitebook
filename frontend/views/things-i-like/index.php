<?php
use yii\helpers\Url;
use yii\helpers\Html;
use frontend\models\Users;
use common\models\Image;
use common\components\CFormatter;

$this->title = 'Events/Wishlist | Whitebook';
?>
<section id="inner_pages_sections">
	<div class="container">
		<div class="title_main">
			<h1><?php echo Yii::t('frontend','Things I Like'); ?></h1>
		</div>
		<div class="account_setings_sections">
			
			<?= $this->render('/users/_sidebar_menu'); ?>

			<div class="col-md-10">

				<div class="wishlist_category_wrapper">
					<ul>
						<li>
							<a data-href="<?= Url::to(['things-i-like/index']) ?>" class="active">
								<span class="icon icon-all"></span>
								<span class="category_name">
									<?= Yii::t('frontend', 'All') ?>
								</span>
							</a>
						</li>		
						<?php foreach ($categories as $key => $value) { ?>
						<li>
							<a data-href="<?= Url::to(['things-i-like/index', 'category_id' => $value->category_id]) ?>">
								<span class="icon icon-<?= $value->slug ?>"></span>
								<span class="category_name">
									<?php if(Yii::$app->language == 'en') { ?>
										<?= $value->category_name ?>
									<?php } else { ?>
										<?= $value->category_name_ar ?>
									<?php } ?>
								</span>
							</a>
						</li>						
						<?php } ?>
					</ul>
				</div>

				<div class="wishlist_item_wrapper">
					<?= $this->render('_items', [
	                    'items' => $customer_wishlist, 
	                ]); ?>
	            </div>
			</div>
		</div>
	</div>
</section>

<?php

$this->registerCss("
	.search_data{padding: 10px;}
	.loader1{display:none;text-align:center;margin-bottom: 10px;}
	.item-img{
		width: 100%; 
	}
	.eventErrorMsg{color:red;margin-bottom: 10px;}
	.event_loader{display:none;text-align:center;margin-bottom: 10px;}
	.msg-success{margin-top: 5px; width: 320px; float: left; text-align: left;}
	table{    font-size: 12px;}
	.header-updated{padding-bottom:0; margin-bottom: 0;}
	.body-updated{background: white; margin-top: 0;}
	#inner_pages_sections .container{background:#fff; margin-top:12px;}
	.border-left{border-left: 1px solid #e2e2e2;}
	.hidde_res  {
		margin-right: 0;
	}
");

$this->registerJsFile('@web/js/thing_i_like.js?v=1.2', ['depends' => [\yii\web\JqueryAsset::className()]]);
