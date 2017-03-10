<?php
use yii\helpers\Html;
use common\models\Vendor;

$order_request_count =
    \common\models\Booking::find()
    ->where(['booking_status' => '0','vendor_id'=>Yii::$app->user->getId()])
    ->count();

?>
 	<!-- BEGIN SIDEBAR -->
	<!-- BEGIN MENU -->
	<div class="page-sidebar" id="main-menu">
		  <div class="page-sidebar-wrapper scrollbar-dynamic" id="main-menu-wrapper">
		<!-- BEGIN MINI-PROFILE -->
		<div class="user-info-wrapper">
			<div class="user-info">
				<div class="greeting">Hi, <?php echo Vendor::getVendor('vendor_name'); ?>!</div>
				<div class="username"> <span class="semi-bold">  </span></div>
			</div>
		</div>

		<?php
		$action = Yii::$app->controller->action->id;
		$cntrl = Yii::$app->controller->id;

		$itemTab = ['vendor-item', 'vendor-item-capacity-exception'];
        $DeliveryTab = array('vendor-location','blocked-date');

		?>
		<!-- END MINI-PROFILE -->
		<!-- BEGIN SIDEBAR MENU -->
		<p class="menu-title">NAVIGATION</p>
		<ul>			
			<li class="<?=($cntrl == 'site'  && $action == 'index') ? "active" : "noactive"?>">
				<?= Html::a('<i class="icon-custom-home"></i><span class="title">Dashboard</span>', ['site/index'], ['class'=>'link-title']) ?>
			</li>

			<li class="<?=($cntrl == 'booking' && ($action == 'pending' || $action == 'view-pending')) ? "active" : "noactive" ?>">
				<?= Html::a('<i class="glyphicon glyphicon-send"></i><span class="title">Booking Requests</span>  <span class="badge badge-danger">'.$order_request_count.'</span>', ['booking/pending'], ['class'=>'link-title']) ?>
			</li>
			
			<li class="<?=($cntrl == 'booking' && ($action == 'index' || $action == 'view')) ? "active" : "noactive" ?>">
				<?= Html::a('<i class="icon-custom-extra"></i><span class="title">All Booking</span>', ['booking/index'], ['class'=>'link-title']) ?>
			</li>

			<li class="<?=($cntrl == 'payments') ? "active" : "noactive" ?>">
				<?= Html::a('<i class="fa fa-money"></i><span class="title">Payments</span>', ['payments/index'], ['class'=>'link-title']) ?>
			</li>

            <li class="<?=(in_array($cntrl,$itemTab)) ? "open" : "noactive" ?>">
				<a href="javascript:;">
					<i class="fa fa-archive"></i>
					<span class="title">Items</span>
					<span class="<?=(in_array($cntrl,$itemTab)) ? "arrow open" : "arrow" ?>"></span>
				</a>
				<ul class="sub-menu" style='<?= in_array($cntrl,$itemTab)?"display:block":"" ?>'>
                    <li class="<?=($cntrl == 'vendor-item' && $action == 'index') ? "active"  : "noactive";?>">
        				<?= Html::a('<i class="fa fa-certificate"></i><span class="title">Manage Item</span>', ['vendor-item/index'], ['class'=>'link-title']) ?>
        			</li>
                    <li class="<?=($cntrl == 'vendor-item-capacity-exception' && $action == 'index') ? "active"  : "noactive"; ?>">
						<?= Html::a('<i class="fa fa-calendar-o"></i><span class="title">Exception Dates</span>', ['vendor-item-capacity-exception/index'], ['class'=>'link-title']) ?>
        			</li>
                    <li class="<?=($cntrl == 'vendor-item'  && $action == 'item-inventory') ? "active"  : "noactive"; ?>">
                        <?= Html::a('<i class="fa fa-calculator"></i><span class="title">Inventory</span>', ['vendor-item/item-inventory'], ['class'=>'link-title']) ?>
                    </li>
                </ul>
            </li>

            <li class="<?=(in_array($cntrl,$DeliveryTab)) ? "open" : "noactive"; ?>">
				<a href="javascript:;">
					<i class="fa fa-car"></i>
					<span class="title">Delivery</span>
					<span class="<?=(in_array($cntrl,$DeliveryTab))  ? "arrow open" : "arrow"; ?>"></span>
				</a>
				<ul class="sub-menu" style='<?= in_array($cntrl,$DeliveryTab)?"display:block":"" ?>'>
                    <li class="<?=($cntrl == 'vendor-location') ? "active" : "noactive" ?>">
        				<?= Html::a('<i class="fa fa-arrows"></i><span class="title">Manage Area</span>', ['vendor-location/index'], ['class'=>'link-title']) ?>
        			</li>
        			<!-- Block date management-->
        			<li class="<?=($cntrl == 'blocked-date' && $action == 'createweek') ? "active" : "noactive" ?>">
        				<?= Html::a('<i class="fa fa-anchor"></i><span class="title">Weekly Off</span>', ['blocked-date/createweek'], ['class'=>'link-title']) ?>
        			</li>
        			<li class="<?=($cntrl == 'blocked-date' && $action == 'index') ? "active" : "noactive" ?>">
        				<?= Html::a('<i class="fa fa-film"></i><span class="title">Block Date</span>', ['blocked-date/index'], ['class'=>'link-title']) ?>
        			</li>
                </ul>
            </li>

			<li class="<?=($cntrl == 'site'  && $action == 'profile') ? "active" : "noactive" ?>">
				<?= Html::a('<i class="fa fa-user"></i><span class="title">My Profile</span>', ['site/profile'], ['class'=>'link-title']) ?>
			</li>

            <li class="<?=($cntrl == 'vendor-working-timing') ? "active" : "noactive" ?>">
				<?= Html::a('<i class="fa fa-clock-o"></i><span class="title">Working Days & Timing</span>', ['vendor-working-timing/index'], ['class'=>'link-title']) ?>
			</li>
		</ul>
			<div class="clearfix"></div>
		<!-- END SIDEBAR WIDGETS -->
	</div>
	</div>
	<a href="#" class="scrollup">Scroll</a>
