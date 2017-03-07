<?php

use yii\helpers\Url;
use common\models\Order;
use common\models\Vendor;
use common\models\OrderStatus;
use common\models\SuborderItemMenu;
use common\models\SuborderItemPurchase;
use common\components\CFormatter;

?>
<tr>
    <td width="20"></td>
    <td style=" font:normal 14px/21px arial; color:#333333;">
        Hi <?= $user; ?>,
    </td>
    <td width="20"></td>
</tr>
<tr height="5"></tr>
<tr>
    <td width="20"></td>
    <td style=" font:normal 15px arial; color:#333333;">
        New booking request registered.

        <a href="<?= Url::to(['booking/view-pending', 'booking_token' => $booking->booking_token], true); ?>" style="background-color:#EB7035;border:1px solid #EB7035;border-radius:3px;color:#ffffff;display:inline-block;font-family:sans-serif;font-size:16px;line-height:44px;text-align:center;text-decoration:none;width:150px;">Track booking request &rarr;</a>
        
    </td>
    <td width="20"></td>
</tr>
<tr>
    <td width="20"></td>
    <td style=" font:normal 15px arial; color:#333333;">

    	<br />

        <table class="table table-bordered" style="width:100%;">
			<thead>
				<tr>
					<td colspan="2" style="border-top: 1px solid #ddd; border-bottom: 1px solid #ddd;">
						<?= Yii::t('frontend', 'Booking Request Details') ?>
					</td>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td style="border-right: 1px solid #ddd; border-bottom: 1px solid #ddd;">
						<b><?= Yii::t('frontend', 'Bookng ID') ?>:</b> <?= $booking->booking_id ?> <br />
						<b><?= Yii::t('frontend', 'Bookng Token') ?>:</b> <?= $booking->booking_token ?> <br />
						<b><?= Yii::t('frontend', 'Date Added') ?>:</b> <?= date('d/m/Y', strtotime($booking->created_datetime)) ?>
					</td>
				</tr>
			</tbody>
		</table>

		<br />

		<table class="table table-bordered" style="width:100%;">
			<thead>
				<tr>
					<td colspan="2" style="border-top: 1px solid #ddd; border-bottom: 1px solid #DDDDDD;">
					<?php if(Yii::$app->language == 'en') { 
							echo $vendor->vendor_name;
						  } else { 
						  	echo $vendor->vendor_name_ar;
						  } ?>
					</td>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td style="border-right: 1px solid #ddd;"">
						<?= Yii::t('frontend', 'Status') ?>: 
						<?= $booking->statusName ?>	
						<br />
					</td>
					<td>
						<?= Yii::t('frontend', 'Contact Email') ?>: <?= $vendor->vendor_public_email ?> 
					</td>
				</tr>
			</tbody>
		</table>

		<br />

		<table class="table table-bordered" style="width:100%;">
			<thead>
				<tr>
					<td align="left" style="border-top: 1px solid #ddd; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD;">
						<b><?= Yii::t('frontend', 'Item Name') ?></b>
					</th>
		    		<td align="left" style="border-top: 1px solid #ddd; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD;">
		    			<b><?= Yii::t('frontend', 'Delivery') ?></b>
		    		</th>
		    		<td align="right" style="border-top: 1px solid #ddd; border-bottom: 1px solid #DDDDDD;">
		    			<b><?= Yii::t('frontend', 'Total') ?></b>
		    		</th>	
				</tr>
			</thead>	
			<tbody>
			<?php foreach ($booking->bookingItems as $item) { ?>
				<tr>
					<td align="left" style="border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD;">
						<?php if(Yii::$app->language == 'en') {
        					echo $item->item_name;
        				} else {
        					echo $item->item_name_ar; 
        				} 

	                    foreach ($item->bookingItemMenus as $key => $menu_item) { 
	                        echo '<div class="clearfix"></div> - <i class="cart_menu_item">'.$menu_item['menu_item_name'].' x '.$menu_item['quantity'];
	                        
	                        $menu_item_total = $menu_item['quantity'] * $menu_item['price'];

                            if($menu_item_total) {
                                echo ' = '.CFormatter::format($menu_item_total);    
                            }
                            
                            echo '</i>';
	                    } 

                        if($item['female_service']) {
                            echo '<div class="clearfix"></div> - <i class="cart_menu_item">'.Yii::t('frontend', 'Female service').'</i>';
                        }

                        if($item['special_request']) {
                            echo '<div class="clearfix"></div> - <i class="cart_menu_item">'.$item['special_request'].'</i>';
                        }

	                    ?>
        				<br />
        				x <?= $item->quantity ?>
		        	</td>
		    		<td aligh="left" style="border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD;">
		    			<?= $item->delivery_address ?> 
		    			<br />
		    			
		    			<?= date('d/m/Y', strtotime($item->delivery_date)) ?>
		    			
		    			<br />

		    			<?= $item->timeslot ?>
		    			
		    		</td>
		    		<td align="right" style="border-bottom: 1px solid #DDDDDD;">
		    			<?= $item->total ?> KD</th>	
				</tr>
				<?php } ?>
				<tr>
					<td align="right" colspan="2" style="border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD;">
						<b>Sub Total</b></td>
					<td align="right" style="border-bottom: 1px solid #DDDDDD;">
						<?= $booking->total_without_delivery ?> KD</td>
				</tr>
				<tr>
					<td align="right" colspan="2" style="border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD;">
						<b>Delivery Charge</b></td>
					<td align="right" style="border-bottom: 1px solid #DDDDDD;">
						<?= $booking->total_delivery_charge ?> KD</td>
				</tr>
				<tr>
					<td align="right" colspan="2" style="border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD;">
						<b>Total</b></td>
					<td align="right" style="border-bottom: 1px solid #DDDDDD;">
						<?= $booking->total_with_delivery ?> KD</td>
				</tr>
			</tbody>
		</table>
    </td>
    <td width="20"></td>
</tr>

