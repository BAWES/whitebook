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
<div class="row">
	<div class="col-md-12" style="margin-bottom:20px;">
		<h1>Recent Activity</h1>

		<?php
		$logDataProvider = new ActiveDataProvider([
			'query' => Log::find()->where("category != 'application'")->orderBy("log_time DESC"),
			'pagination' => [
				'pageSize' => 10,
			]
		]);
		?>
		<?= GridView::widget([
			'dataProvider' => $logDataProvider,
			'columns' => [
				[
					'attribute' => 'Time',
					'format' => 'raw',
					'value' => function ($model) {
						return Yii::$app->formatter->asDatetime(explode('.', $model->log_time)[0]);
					},
				],
				[
					'attribute' => 'Message',
					'format' => 'raw',
					'value' => function ($model) {
						return $model->message;
					},
				],
				[
					'attribute' => 'Level',
					'format' => 'raw',
					'value' => function ($model) {
						switch($model->level){
							case Logger::LEVEL_INFO:
							return "<div style='text-align:center; background:green; color:white; font-weight:bold;'>Info</div>";
							break;
							case Logger::LEVEL_ERROR:
							return "<div style='text-align:center; background:red; font-weight:bold;'>Error</span></div>";
							break;
							case Logger::LEVEL_WARNING:
							return "<div style='text-align:center; background:yellow; font-weight:bold;'>Warning</div>";
							break;
						}
					},
				],

				['class' => 'yii\grid\ActionColumn', 'controller' => 'log', 'template' => '{view}'],
			],
		]);
		?>
	</div>
</div>
<!-- BEGIN DASHBOARD TILES -->
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

<!-- Start  -->
<div class="col-md-12 col-vlg-12m-b-10 ">
	<div class="tiles white">
		<div class="row">
			<h4 class="semi-bold m-t-30 m-l-30">Package expiry in 15 days</h4>
			<table class="table table-striped table-bordered">
				<thead>
					<tr>
						<th style="width:20%">Package name</th>
						<th style="width:25%">Vendor name</th>
						<th style="width:25%">Email</th>
						<th style="width:15%">Expiry date</th>
						<th style="width:25%">Action </th>
					</tr>
				</thead>
				<tbody>
					<?php $j=0; 
	 if(!empty($vendorperiod)){
	foreach ($vendorperiod as $i){ if($j<5){
						$p= Package::PackageData($i['package_id']);
						if((($i['package_id'])>0)&&($p)) {?>
							<tr>
								<td class="v-align-middle bold text-success"> <?= Package::PackageData($i['package_id']);?></td>
								<td class="v-align-middle"><span class="muted"><?= $i['vendor_name']?></span> </td>
								<td class="v-align-middle"><span class="muted"><?= $i['vendor_contact_email']?></span> </td>
								<td class="v-align-middle bold text-success"><?= date( 'd-M-Y', strtotime($i['package_end_date'] ) );?></td>
								<td class="v-align-middle bold text-success"><?php $url = Yii::$app->urlManagerBackEnd->createAbsoluteUrl('/admin/vendor/view?id='.$i['vendor_id']);
								echo  Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
									'title' => Yii::t('app', 'View items'),'data-pjax'=>"0",
								]);?></td>

							</tr><?php $j++; }}}} ?>
							<?php if(count($vendorperiod)>5){ ?>
								<tr>
									<td class="v-align-middle">&nbsp;</td>
									<td>&nbsp; </td>
									<td>&nbsp; </td>
									<td>&nbsp; </td>
									<td class="v-align-middle bold text-success"><?php if(count($vendorperiod)>5){ $url = Url::to(['/vendor/index']);
										echo Html::a('<span>View more >></span>', $url, [
											'title' => Yii::t('app', 'View more')]);}?></td>

										</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						</div>
						<!-- ENd -->


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
