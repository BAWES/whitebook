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

<div class="row">
    <div class="col-md-12 full-width">
        <div class="panel panel-default no-shadow" data-widget="{&quot;draggable&quot;: &quot;false&quot;}" data-widget-static="" style="visibility: visible; opacity: 1; display: block; transform: translateY(0px);">
            <div class="panel-body">
               <div class="pb-md">
                    <h4 class="mb-n" style="width: 100%;">
                        Booking Statistics
                        <select id="graph_type" style="max-width: 100px;float: right;position: relative;top: -8px;">
                            <option value="day">Day</option>
                            <option value="month">Month</option>
                            <option value="year">Year</option>
                        </select>
                    </h4>
                </div>
                <div class="chart-holder" id="morris-chart-1" style="height: 220px!important; min-height:220px;"></div>
            </div>
        </div>
    </div>
</div>

<!-- END DASHBOARD TILES -->

<?php

$this->registerCssFile('@web/themes/default/plugins/jquery-morris-chart/css/morris.min.css?v=1.0', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('@web/themes/default/plugins/jquery-morris-chart/js/raphael-min.js?v=1.0', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('@web/themes/default/plugins/jquery-morris-chart/js/morris.min.js?v=1.0', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('@web/themes/default/js/dashboard.js?v=1.0', ['depends' => [\yii\web\JqueryAsset::className()]]);


