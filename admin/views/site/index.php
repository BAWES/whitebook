<?php
/* @var $this yii\web\View */
use yii\web\JsExpression;
use common\models\Customer;
use common\models\VendorItem;
use yii\helpers\Url;
use miloschuman\highcharts\SeriesDataHelper;
use yii\bootstrap\Alert;
use  yii\web\Session;
use common\models\Package;
use yii\helpers\Html;
use miloschuman\highcharts\Highcharts;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use common\models\Log;
use yii\log\Logger;

$this->title = 'Dashboard';
?>
<!-- BEGIN DASHBOARD TILES -->
<div class="row stats">
	<div class="col-md-3 stat_box">
		<div class="color_1 stat_data">
			<i class="fa fa-info"></i>
			<div class="details">
				<span class="big"><?php echo $bookingPending; ?></span>
				<span>Pending Booking</span>
			</div>
		</div>
	</div>
	<div class="col-md-3 stat_box">
		<div class="color_2 stat_data">
			<i class="fa fa-check"></i>
			<div class="details">
				<span class="big"><?php echo $bookingAccepted; ?></span>
				<span>Accepted Booking</span>
			</div>
		</div>
	</div>
	<div class="col-md-3 stat_box">
		<div class="color_3 stat_data">
			<i class="fa fa-times-circle"></i>
			<div class="details">
				<span class="big"><?php echo $bookingRejected; ?></span>
				<span>Rejected Booking</span>
			</div>
		</div>
	</div>
	<div class="col-md-3 stat_box">
		<div class="color_4 stat_data">
			<i class="fa fa-hourglass-o"></i>
			<div class="details">
				<span class="big"><?php echo $bookingExpired; ?></span>
				<span>Expired Booking</span>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-4 col-vlg-3 col-sm-6">
		<div class="tiles green m-b-10">
			<div class="tiles-body">
				<div class="controller"> <a href="javascript:;" ></a>  </div>
				<div class="tiles-title text-black">OVERALL PRODUCT </div>
				<div class="widget-stats">
					<div class="wrapper transparent">
						<span class="item-title">Overall items</span> <span class="item-count animate-number semi-bold" data-value="<?php echo $vendoritemcnt;?>" data-animation-duration="700">0</span>
					</div>
				</div>
				<div class="widget-stats">
					<div class="wrapper transparent">
						<span class="item-title">Month  item</span> <span class="item-count animate-number semi-bold" data-value="<?php echo $monthitemcnt;?>" data-animation-duration="700">0</span>
					</div>
				</div>
				<div class="widget-stats">
					<div class="wrapper transparent">
						<span class="item-title">Today  item</span> <span class="item-count animate-number semi-bold" data-value="<?php echo $dateitemcnt;?>" data-animation-duration="700">0</span>
					</div>
				</div>
				<div class="progress transparent progress-small no-radius m-t-20" style="width:90%">
					<div class="progress-bar progress-bar-white animate-progress-bar" data-percentage="64.8%" ></div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-4 col-vlg-3 col-sm-6">
		<div class="tiles blue m-b-10">
			<div class="tiles-body">
				<div class="controller"> <a href="javascript:;" ></a>  </div>
				<div class="tiles-title text-black">OVERALL VENDOR </div>
				<div class="widget-stats">
					<div class="wrapper transparent">
						<span class="item-title">Overall Vendor</span> <span class="item-count animate-number semi-bold" data-value="<?php echo $vendorcnt;?>" data-     animation-duration="700">0</span>
					</div>
				</div>
				<div class="widget-stats ">
					<div class="wrapper transparent">
						<span class="item-title">Month</span> <span class="item-count animate-number semi-bold" data-value="<?php echo $vendormonth;?>" data-animation-duration="700">0</span>
					</div>
				</div>
				<div class="widget-stats">
					<div class="wrapper transparent">
						<span class="item-title">Today's</span> <span class="item-count animate-number semi-bold" data-value="<?php echo $vendorday;?>" data-animation-duration="700">0</span>
					</div>
				</div>
				<div class="progress transparent progress-small no-radius m-t-20" style="width:90%">
					<div class="progress-bar progress-bar-white animate-progress-bar" data-percentage="54%" ></div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-4 col-vlg-3 col-sm-6">
		<div class="tiles purple m-b-10">
			<div class="tiles-body">
				<div class="controller"> <a href="javascript:;" ></a>  </div>
				<div class="tiles-title text-black">CUSTOMER </div>
				<div class="widget-stats">
					<div class="wrapper transparent">
						<span class="item-title">Overall Customer</span> <span class="item-count animate-number semi-bold" data-value="<?php echo $customercnt;?>" data-animation-duration="700">0</span>
					</div>
				</div>
				<div class="widget-stats">
					<div class="wrapper transparent">
						<span class="item-title">Month</span> <span class="item-count animate-number semi-bold" data-value="<?php echo $customermonth;?>" data-animation-duration="700">0</span>
					</div>
				</div>
				<div class="widget-stats">
					<div class="wrapper transparent">
						<span class="item-title">Today's</span> <span class="item-count animate-number semi-bold" data-value="<?php echo $customerday;?>" data-animation-duration="700">0</span>
					</div>
				</div>
				<div class="progress transparent progress-small no-radius m-t-20" style="width:90%">
					<div class="progress-bar progress-bar-white animate-progress-bar" data-percentage="90%" ></div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-4 col-vlg-3 visible-xlg visible-sm col-sm-6">
		<div class="tiles red m-b-10">
			<div class="tiles-body">
				<div class="controller"> <a href="javascript:;"></a>  </div>
				<div class="tiles-title text-black">OVERALL SALES </div>
				<div class="widget-stats">
					<div class="wrapper transparent">
						<span class="item-title">Overall Sales</span> <span class="item-count animate-number semi-bold" data-value="5669" data-animation-duration="700">0</span>
					</div>
				</div>
				<div class="widget-stats">
					<div class="wrapper transparent">
						<span class="item-title">Today's</span> <span class="item-count animate-number semi-bold" data-value="751" data-animation-duration="700">0</span>
					</div>
				</div>
				<div class="widget-stats ">
					<div class="wrapper last">
						<span class="item-title">Month</span> <span class="item-count animate-number semi-bold" data-value="1547" data-animation-duration="700">0</span>
					</div>
				</div>
				<div class="progress transparent progress-small no-radius m-t-20" style="width:90%">
					<div class="progress-bar progress-bar-white animate-progress-bar" data-percentage="64.8%" ></div>
				</div>
			</div>
		</div>
	</div>
</div>


<div class="col-md-4 col-vlg-3 visible-xlg visible-sm col-sm-6">
	<div class="tiles red m-b-10">
		<div class="tiles-body">
			<div class="controller"> <a href="javascript:;"></a>  </div>
			<div class="tiles-title text-black">OVERALL SALES </div>
			<div class="widget-stats">
				<div class="wrapper transparent">
					<span class="item-title">Overall Sales</span> <span class="item-count animate-number semi-bold" data-value="5669" data-animation-duration="700">0</span>
				</div>
			</div>
			<div class="widget-stats">
				<div class="wrapper transparent">
					<span class="item-title">Today's</span> <span class="item-count animate-number semi-bold" data-value="751" data-animation-duration="700">0</span>
				</div>
			</div>
			<div class="widget-stats ">
				<div class="wrapper last">
					<span class="item-title">Month</span> <span class="item-count animate-number semi-bold" data-value="1547" data-animation-duration="700">0</span>
				</div>
			</div>
			<div class="progress transparent progress-small no-radius m-t-20" style="width:90%">
				<div class="progress-bar progress-bar-white animate-progress-bar" data-percentage="64.8%" ></div>
			</div>
		</div>
	</div>
</div>
</div>

<!-- Start -->
<div class="col-md-12 col-vlg-12m-b-10 ">
	<div class="tiles white">
		<div class="row">
			<h4 class="semi-bold m-t-30 m-l-30">Customer report</h4>
			<?php
			for ($x = 0; $x < 5; $x++) {
				$year=date('Y');
				$previousyear = $year -$x;
				$previousyears[] = $year -$x;
				$h[]=  Customer::find()
				->select(['created_datetime'])
				->where(['YEAR(created_datetime)' => $previousyear])
				->andwhere(['customer_status' => 'Active'])
				->count();
			}
			$data=array_map('intVal', $h);

			echo Highcharts::widget([
				'options' => [
					'credits' => ['enabled' => false],
					'chart' => [
						'type' => 'areaspline'
					],
					'title' => ['text' => 'Customer report'],
					'xAxis' => [
						'categories' =>$previousyears,
					],
					'yAxis' => [
						'title' => ['text' => 'Customer count'],
					],
					'series' => [
						['name' => 'Year', 'data' => $data],
					]
				]
			]);

			?>
		</div>
	</div>
</div>
<!-- End -->

<!-- Start -->
<div class="col-md-12 col-vlg-12m-b-10 ">
	<div class="tiles white">
		<div class="row">
			<h4 class="semi-bold m-t-30 m-l-30">Product report</h4>
			<?php
			for ($x = 1; $x <=12; $x++) {
				$year=date('Y');
				$active[]=  VendorItem::find()
				->select(['created_datetime'])
				->where(['YEAR(created_datetime)' => $year])
				->andwhere(['MONTH(created_datetime)' => $x])
				->andwhere(['item_status' => 'Active'])
				->count();
			}
			$active=array_map('intVal', $active);
			for ($x = 1; $x <=12; $x++) {
				$year=date('Y');
				$deactive[]=  VendorItem::find()
				->select(['created_datetime'])
				->where(['YEAR(created_datetime)' => $year])
				->andwhere(['MONTH(created_datetime)' => $x])
				->andwhere(['item_status' => 'Deactive'])
				->count();
			}
			$deactive=array_map('intVal', $deactive);

			echo  Highcharts::widget([
				'options' => [
					'credits' => ['enabled' => false],
					'chart' => [
						'type' => 'column'
					],
					'title' => [
						'text' => 'Product report'
					],
					'yAxis' => [
						'title' => [
							'text' => 'Item count'
						]
					],
					'xAxis' => [
						'categories' => ['Jan','Feb','Mar','Apr','May','June','July','Aug','Sep','Oct','Nov','Dec']
					],
					'series' => [
						['name' => 'Approved', 'data' => $active],
						['name' => 'Blocked', 'data' => $deactive]
					]
				]
			]);
			?>
		</div>
	</div>
</div>
<!-- End -->
<!-- END DASHBOARD TILES -->
