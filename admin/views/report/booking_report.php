<?php 

use yii\helpers\Url;
use common\models\Booking;

$status = Booking::statusList();

$total_orders = 0; 

foreach ($orders_by_payment_methods as $key => $value) 
{ 
	$total_orders += $value['total'];
} 

$total_sale = 0; 

foreach ($orders_by_payment_methods as $key => $value) 
{ 
	$total_sale += $value['total_sale'];
}

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
		<?php 

		$commission = 0;

		foreach ($bookings as $key => $value) { 
			
			$commission += $value['commission_total']; ?>
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

		<div class="summary-box nobreak">

			<h4>Summary </h4>

			<b>Total number of orders:</b> <?= $total_orders ?><br />
			<b>Total Sales:</b> KD <?= $total_sale ?> <br />
			<b>Total Whitebook charge:</b> KD <?= $commission ?>

			<div class="clearfix"></div>

			<hr />

			<b>Net:</b> KD <?= $total_sale - $commission ?>
		</div>
	</div>
</div>
