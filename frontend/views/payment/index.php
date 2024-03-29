<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\view;
use common\models\Image;
use common\components\CFormatter;
use common\components\LangFormat;
use common\models\SuborderItemMenu;
use yii\helpers\ArrayHelper;

$this->title = Yii::t('frontend', 'Pay Now | Whitebook');

?>

<section id="inner_pages_white_back">
    <div class="container paddng0">

        <div class="title_main">
			<h1><?= Yii::t('frontend', 'Pay Now'); ?></h1>
		</div>

		<br />
		<br />

        <table class="table table-bordered cart-table">
	        <thead>
	        	<tr>
	        		<td align="center"><?= Yii::t('frontend', 'Image') ?></th>
	        		<td align="left"><?= Yii::t('frontend', 'Item') ?></th>
	        		<td align="left"><?= Yii::t('frontend', 'Delivery') ?></th>
	        		<td align="right" class="visible-md visible-lg"><?= Yii::t('frontend', 'Total') ?></th>
	        	</tr>
	        </thead>
	        <tbody>
	        	<?php foreach ($items as $item) { ?>
	        	<tr>
	        		<td align="center">
	        			<?php
                        $vendorData = \common\models\CustomerCart::getItemVendor($item['item_id']);
                        $image_row = Image::find()->select(['image_path'])
                                ->where(['item_id' => $item['item_id']])
                                ->orderby(['vendorimage_sort_order' => SORT_ASC])
                                ->asArray()
                                ->one();

                        if ($image_row) {
                            $imglink = Yii::getAlias("@s3/vendor_item_images_210/")
                                . $image_row['image_path'];
                        } else {
                            $imglink = Url::to("@web/images/item-default.png");
                        }

                        echo Html::img($imglink, ['style'=>'width:50px; height:50px;']);
                        ?>
	        		</td>
	        		<td>
	        			<?php if($item->item) { ?>
	        			<a href="<?= Url::to(["browse/detail", 'slug' => $item->item->slug]) ?>" target="_blank">
	        				<?= LangFormat::format($item['item_name'], $item['item_name_ar']); ?>
	        			</a>
	        			<?php } else {  ?>
	        				<?= LangFormat::format($item['item_name'], $item['item_name_ar']); ?>
	        			<?php } ?>

                        <br />
                        <!-- Quantity -->
                        <i><small><?=Yii::t('frontend','Quantity').': '. $item['quantity']?></small></i>
                        <!-- Quantity -->
                        <br/>


                        <i><small><strong><?=Yii::t('frontend','Vendor Name').':</strong> '. $vendorData->vendor_name?></small></i>
                        <!-- Quantity -->
                        <br/>

	        			<?php

	        			foreach ($item->bookingItemMenus as $key => $menu_item) {
	        				if(Yii::$app->language == 'en') {
	        					echo '<i class="cart_menu_item">'.$menu_item['menu_item_name'].' x '.$menu_item['quantity'];
	        				}else{
	        					echo '<i class="cart_menu_item">'.$menu_item['menu_item_name_ar'].' x '.$menu_item['quantity'];
	        				}

	        				$menu_item_total = $menu_item['quantity'] * $menu_item['price'];

	        				if($menu_item_total) {
	        					echo ' = '.CFormatter::format($menu_item_total);
	        				}

	        				echo '</i>';
	        			}

                        if($item['female_service']) {
                            echo '<i class="cart_menu_item">'.Yii::t('frontend', 'Female service').'</i>';
                        }

                        if($item['special_request']) {
                            echo '<i class="cart_menu_item">'.$item['special_request'].'</i>';
                        }

	        			?>

	        			<?php if($item->bookingItemMenus) { ?>
		        			<div class="visible-xs visible-sm">
		        				 = <?= CFormatter::format($item['total']); ?>
		        			</div>
	        			<?php } else { ?>
		        			<div class="visible-xs visible-sm">
		        				x <?= $item['quantity'] ?> = <?= CFormatter::format($item['total']); ?>
		        			</div>
	        			<?php } ?>

	        		</td>
					<?php
					$color = '';
					$msg = '';

					/*if (strtotime($item['delivery_date']) < strtotime(date('Y-m-d'))) {
						$color = '#f2dede';
						$msg = '<small>'.Yii::t('frontend','Past delivery date').'</small>';
					} else if (
						(strtotime($item['delivery_date']) == strtotime(date('Y-m-d'))) &&
						(strtotime($item['working_end_time']) < strtotime(date('H:i:s')))
					) {
						$color = '#f2dede';
						$msg = '<small>'.Yii::t('frontend','Past delivery time slot date').'</small>';
					}*/

					?>
	        		<td class="position-relative " style="background-color:<?=$color?> ">

	        			<?= $item['delivery_address'] ?><br />

                        <?= $item['delivery_date'] ?><br />

						<?= $item['timeslot']; ?><br />

						<?= $msg; ?>
	        		</td>
	        		<td align="right" class="visible-md visible-lg">
	        			<?= CFormatter::format($item['total'])  ?>
	        		</td>
	        	</tr>
	        	<?php } ?>

                <tr>
                    <td colspan="3" class="text-right"><strong><?= Yii::t('frontend', 'Sub-Total') ?></strong></td>
                    <td class="text-right"><?= CFormatter::format($booking['total_without_delivery']) ?></td>
                </tr>
                <tr>
                    <td colspan="3" class="text-right"><strong><?= Yii::t('frontend', 'Delivery Charge') ?></strong></td>
                    <td class="text-right"><?= CFormatter::format($booking['total_delivery_charge']) ?></td>
                </tr>
                <tr>
                    <td colspan="3"  class="text-right"><strong><?= Yii::t('frontend', 'Total') ?></strong></td>
                    <td class="text-right"><?= CFormatter::format($booking['total_with_delivery']) ?></td>
                </tr>
	        </tbody>
        </table>

        <div class="row">
        	<?php if($cod) { ?>
        	<div class="col-sm-12">
        		<a href="<?= Url::to(['payment/cod']) ?>" class="btn btn-lg btn-primary btn-payment pull-right">
		        	<?= Yii::t('frontend', 'Pay By Cash on Delivery') ?>
		        </a>
        	</div>
        	<?php } ?>
        	<?php if($tap) {
        	    $position = (Yii::$app->language =='en') ? 'float:right' : 'float:left';
        	    ?>
        	<div class="col-sm-12">
        		<a href="<?= Url::to(['payment/tap']) ?>" class="btn btn-lg btn-primary btn-payment " style="<?=$position; ?>">
		        	<?= Yii::t('frontend', 'Click here to pay') ?>
		        </a>
        	</div>
        	<?php } ?>
        </div>

        </form>

        <br />
        <br />
        <br />
        <br />
        <br />

    </div>
</section>
