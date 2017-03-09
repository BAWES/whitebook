<?php

use yii\web\Session;
use yii\bootstrap\Alert;
use common\models\Vendor; 

$this->title = 'Whitebook Application';
 
?>
<div class="row">	 
	<div class="col-md-12">
		<ul class="stats">
			<li class="lime">
				<i class="fa fa-archive"></i>
				<div class="details">
					<span class="big"><?php echo $vendoritemcnt; ?></span>
					<span>Overall items</span>
				</div>
			</li>
			<li class="green">
				<i class="fa fa-archive"></i>
				<div class="details">
					<span class="big"><?php echo $monthitemcnt;?></span>
					<span>Month item</span>
				</div>
			</li>
			<li class="blue">
				<i class="fa fa-archive"></i>
				<div class="details">
					<span class="big"><?php echo $dateitemcnt; ?></span>
					<span>Today item</span>
				</div>
			</li>
			<li class="orange">
				<i class="fa fa-rocket"></i>
				<div class="details">
					<span class="big"><?= $earning_total ?></span>
					<span>Earning total</span>
				</div>
			</li>
			<li class="satblue">
				<i class="fa fa-money"></i>
				<div class="details">
					<span class="big"><?= $vendor_payable ?></span>
					<span>Account Receivable</span>
				</div>
			</li>
		</ul>
	</div>
</div>
<!-- END DASHBOARD TILES -->


