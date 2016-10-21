<?php 

use common\models\Order;
use common\models\Vendor;
use common\models\OrderStatus;
use common\models\SuborderItemPurchase;

$this->title = Yii::t('frontend', 'View Order | Whitebook'); 

?>

<section id="inner_pages_white_back">
    <div class="container paddng0">
       
        <div class="title_main">
			<h1><?= Yii::t('frontend', 'View Order'); ?></h1>
		</div>

		<br />
		<br />
		<br />

		<table class="table table-bordered">
			<thead>
				<tr>
					<td colspan="2"><?= Yii::t('frontend', 'Order Details') ?></td>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
						<b><?= Yii::t('frontend', 'Order ID') ?>:</b> #<?= $order->order_id ?> <br />
						<b><?= Yii::t('frontend', 'Date Added') ?>:</b> <?= date('d/m/Y', strtotime($order->created_datetime)) ?>
					</td>
					<td>
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

		<table class="table table-bordered">
			<thead>
				<tr>
					<td colspan="2">
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
					<td>
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


		<table class="table table-bordered">
			<thead>
				<tr>
					<td align="left"><?= Yii::t('frontend', 'Item Name') ?></th>
		    		<td align="left"><?= Yii::t('frontend', 'Delivery Datetime') ?></th>
		    		<td align="left"><?= Yii::t('frontend', 'Delivery Address') ?></th>
		    		<td aligh="left"><?= Yii::t('frontend', 'Quantity') ?></th>
		    		<td align="right"><?= Yii::t('frontend', 'Unit Price') ?></th>
		    		<td align="right"><?= Yii::t('frontend', 'Total') ?></th>	
				</tr>
			</thead>	
			<tbody>
			<?php foreach (Order::subOrderItems($row->suborder_id) as $item) { ?>
				<tr>
					<td align="left">
						<?php if(Yii::$app->language == 'en') {
        					echo $item->vendoritem->item_name;
        				} else {
        					echo $item->vendoritem->item_name_ar; 
        				} ?>
		        	</th>
		    		<td align="left">
		    			<?= date('d/m/Y', strtotime($item->purchase_delivery_date)) ?>

		    			<br />

		    			<?= date('h:m A', strtotime($item->timeslot->timeslot_start_time)) ?> - 
		    			<?=	date('h:m A', strtotime($item->timeslot->timeslot_end_time)); ?>
		    		</th>
		    		<td aligh="left"><?= $item->purchase_delivery_address ?></th>
		    		<td aligh="left"><?= $item->purchase_quantity ?></th>
		    		<td align="right"><?= Yii::$app->params['Currency']; ?> <?= $item->purchase_price_per_unit ?></th>
		    		<td align="right"><?= Yii::$app->params['Currency']; ?> <?= $item->purchase_total_price ?></th>	
				</tr>
				<?php } ?>
				<tr>
					<td align="right" colspan="5">Sub Total</td>
					<td align="right">
						<?= Yii::$app->params['Currency']; ?>
						<?= $row->suborder_total_without_delivery ?>
					</td>
				</tr>
				<tr>
					<td align="right" colspan="5">Delivery Charge</td>
					<td align="right">
						<?= Yii::$app->params['Currency']; ?>
						<?= $row->suborder_delivery_charge ?></td>
				</tr>
				<tr>
					<td align="right" colspan="5">Total</td>
					<td align="right">
						<?= Yii::$app->params['Currency']; ?>
						<?= $row->suborder_total_with_delivery ?>
					</td>
				</tr>
			</tbody>
		</table>
		<?php } ?>