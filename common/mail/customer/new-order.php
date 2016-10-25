<?php

use yii\helpers\Url;
use common\models\Order;
use common\models\Vendor;
use common\models\OrderStatus;
use common\models\SuborderItemPurchase;

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
        New order placed.
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
						<?= Yii::t('frontend', 'Order Details') ?>
					</td>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td style="border-right: 1px solid #ddd; border-bottom: 1px solid #ddd;">
						<b><?= Yii::t('frontend', 'Order ID') ?>:</b> #<?= $order->order_id ?> <br />
						<b><?= Yii::t('frontend', 'Date Added') ?>:</b> <?= date('d/m/Y', strtotime($order->created_datetime)) ?>
					</td>
					<td style="border-bottom: 1px solid #ddd;">
						<b><?= Yii::t('frontend', 'Payment Method') ?>:</b> <?= $order->order_payment_method ?><br />
						<b><?= Yii::t('frontend', 'Transaction ID') ?>:</b> <?= $order->order_transaction_id ?>
					</td>
				</tr>
			</tbody>
		</table>

		<?php 

		foreach($suborder as $row){

			$vendor = Vendor::findOne($row->vendor_id);

		?>

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
						<?= Yii::t('frontend', 'Order status') ?>: 
						<?php if(Yii::$app->language == 'en') { 
								echo OrderStatus::findOne($row->status_id)->name;
							  } else {
								echo OrderStatus::findOne($row->status_id)->name_ar;
							  } ?>	
						<br />
					</td>
					<td>
						<?= Yii::t('frontend', 'Contact Email') ?>: <?= $vendor->vendor_public_email ?> <br />
						<?= Yii::t('frontend', 'Contact Number') ?>: <?= $vendor->vendor_public_phone ?>
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
			<?php foreach (Order::subOrderItems($row->suborder_id) as $item) { ?>
				<tr>
					<td align="left" style="border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD;">
						<?php if(Yii::$app->language == 'en') {
        					echo $item->vendoritem->item_name;
        				} else {
        					echo $item->vendoritem->item_name_ar; 
        				} ?>
        				<br />
        				x <?= $item->purchase_quantity ?>
		        	</th>
		    		<td aligh="left" style="border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD;">
		    			<?= $item->purchase_delivery_address ?> 
		    			<br />
		    			
		    			<?= date('d/m/Y', strtotime($item->purchase_delivery_date)) ?>
		    			
		    			<br />

		    			<?= date('h:m A', strtotime($item->timeslot->timeslot_start_time)) ?> - 
		    			<?=	date('h:m A', strtotime($item->timeslot->timeslot_end_time)); ?>

		    		</td>
		    		<td align="right" style="border-bottom: 1px solid #DDDDDD;">
		    			<?= $item->purchase_total_price ?> KD</th>	
				</tr>
				<?php } ?>
				<tr>
					<td align="right" colspan="2" style="border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD;">
						<b>Sub Total</b></td>
					<td align="right" style="border-bottom: 1px solid #DDDDDD;">
						<?= $row->suborder_total_without_delivery ?> KD</td>
				</tr>
				<tr>
					<td align="right" colspan="2" style="border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD;">
						<b>Delivery Charge</b></td>
					<td align="right" style="border-bottom: 1px solid #DDDDDD;">
						<?= $row->suborder_delivery_charge ?> KD</td>
				</tr>
				<tr>
					<td align="right" colspan="2" style="border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD;">
						<b>Total</b></td>
					<td align="right" style="border-bottom: 1px solid #DDDDDD;">
						<?= $row->suborder_total_with_delivery ?> KD</td>
				</tr>
			</tbody>
		</table>
		<?php } ?>
    </td>
    <td width="20"></td>
</tr>

