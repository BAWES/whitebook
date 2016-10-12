<?php 

use yii\helpers\Html;
use yii\helpers\Url;
use common\components\CFormatter;

//if item for sale 
if($value['item_for_sale'] == 'Yes'){
	$item_url = Url::to(["shop/product", 'slug' => $value['slug']]);
}else{
	$item_url = Url::to(["product/product", 'slug' => $value['slug']]);
}

?>
<li>
<div class="events_items">
	<div class="events_images">
		<div class="hover_events">
			<div class="pluse_cont">
				<?php if(Yii::$app->user->isGuest) { ?>
					<a href="" role="button" class="" data-toggle="modal"  onclick="show_login_modal(<?php echo $value['item_id'];?>);" data-target="#myModal" title="<?php echo Yii::t('frontend','Add to Event');?>"></a>
				<?php } else { ?>
					<a href="#" role="button" id="<?php echo $value['item_id'];?>" name="<?php echo $value['item_id'];?>" class="" data-toggle="modal" data-target="#add_to_event<?php echo $value['item_id'];?>" onclick="addevent('<?php echo $value['item_id']; ?>')" title="<?php echo Yii::t('frontend','Add to Event');?>"></a>
				<?php } ?>
			</div>

			<?php if(Yii::$app->user->isGuest) { ?>
			<div class="faver_icons">
				<a href="" role="button" class="" data-toggle="modal" id="<?php echo $value['item_id']; ?>" onclick="show_login_modal_wishlist(<?php echo $value['item_id'];?>);" data-target="#myModal" title="<?php echo Yii::t('frontend','Add to Things I Like');?>"></a>
			</div>
			<?php } else {

			$k = array();
			$result  = '';
			if (count($customer_events_list) > 0) {
				foreach ((array)$customer_events_list as $l) {
					$k[] = $l['item_id'];
				}
				$result = array_search($value['item_id'], $k);
			}

			if (is_numeric ($result)) { ?>
			<div class="faver_icons faverited_icons">
			<?php } else { ?>
					<div class="faver_icons">
			<?php }?>
					<a href="javascript:;" role="button" id="<?php echo $value['item_id']; ?>"  class="add_to_favourite" name="add_to_favourite" title="<?php echo Yii::t('frontend','Add to Things I Like');?>"></a></div>
			<?php } ?>
			</div>

			<a href="<?= $item_url ?>">
				<?php 

				if(isset($value['image_path'])) {
					$path = Yii::getAlias("@s3/vendor_item_images_210/").$value['image_path'];
				} else {
					$path = 'https://placeholdit.imgix.net/~text?txtsize=20&txt=No%20Image&w=210&h=208';
				}

				echo Html::img($path,['class'=>'item-img', 'style'=>'width:210px; height:208px;']);
				
				?>
			</a>
		</div>
		<div class="events_descrip">			

			<?php if(Yii::$app->language == 'en') { ?>
				<a href="<?= $item_url ?>">
					<?= $value['vendor_name'] ?>
				</a>
				<h3><?= $value['item_name']  ?></h3>
			<?php } else { ?>
				<a href="<?= $item_url ?>">
					<?= $value['vendor_name_ar'] ?>
				</a>
				<h3><?= $value['item_name_ar']  ?></h3>
			<?php } ?>
			
			<p>
				<?= CFormatter::format($value['item_price_per_unit'])  ?>				
			</p>
		</a>
		</div>
	</div>
</li>