<?php 

use yii\helpers\Url;
use common\models\Booking;

$status = Booking::statusList();

?>

<div class="row" style="margin-top: 0px; margin-bottom: 10px;">
	<div class="col-print-4">
		<img src="<?= Url::to('@web/uploads/app_img/logo_login.png', true) ?>" style="width:200px; margin-left: 10px;" />
	</div>
	<div class="col-print-4 text-center">
		<h3 style="margin-right: -25px;">Sales Report (Detail)</h3>
	</div>
	<div class="col-print-4 text-right">
		Date from : <?= $date_start ?> <br />
		Date to : <?= $date_end ?>
	</div>
</div>

<table class="table table-hover">
	<thead>
		<tr>
			<th>Date/Time</th>
			<th>Booking ID</th> 
			<th>Customer</th> 
			<th>Mobile</th> 
			<th>Payment</th>
			<th>Status</th>
			<th>Amount</th> 
			<th>Whitebook Charges </th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($bookings as $key => $value) { ?>
		<tr>
			<td><?= date('d/m/y H:i:s', strtotime($value['created_datetime'])) ?></td>
			<td><?= $value['booking_id'] ?></td>
			<td><?= $value['customer_name'].' '.$value['customer_lastname'] ?></td>
			<td><?= $value['customer_mobile'] ?></td>
			<td><?= $value['payment_method'] ?></td>
			<td><?= $status[$value['booking_status']] ?></td>
			<td><?= $value['total_with_delivery'] ?></td>
			<td><?= $value['commission_total'] ?></td>
		</tr>
		<?php } ?>
	</tbody>
</table>

<div class="row">
	<div class="col-print-6">

		<div class="summary-box">

			<h4>Order Detail </h4>

			<?php 

			$total_orders = 0; 
			
			foreach ($orders_by_payment_methods as $key => $value) { 

				$total_orders += $value['total'];

				?>
				<b>Total <?= $value['payment_method'] ?> Orders :</b> <?= $value['total'] ?> 

				<div class="clearfix"></div>
			<?php } ?>

			<hr />

			<b>Total Orders :</b> <?= $total_orders ?>

		</div>
	</div>

	<div class="col-print-6">

		<div class="summary-box">

			<h4>Sales Detail </h4>

			<?php 

			$total_sale = 0; 
			
			foreach ($orders_by_payment_methods as $key => $value) { 

				$total_sale += $value['total_sale'];

				?>
				<b>Total <?= $value['payment_method'] ?> Sale :</b> <?= $value['total_sale'] ?>

				<div class="clearfix"></div>
			<?php } ?>

			<hr />

			<b>Total Sale :</b> <?= $total_sale ?>
		</div>
	</div>
</div>
