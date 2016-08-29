  <?php
 use yii\helpers\Html;
 use common\models\Vendor;
 use common\models\Vendoritem;
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

		/* BEGIN Check vendor have any one item. !imporatnt for menus */
		$checkone = Vendoritem::find()->where(['vendor_id'=>Yii::$app->user->getId(),'item_approved'=>'Yes','item_for_sale'=>'Yes'])->count();
		/* END Check vendor have any one item. !imporatntfor menus */
		?>
		<!-- END MINI-PROFILE -->
		<!-- BEGIN SIDEBAR MENU -->
		<p class="menu-title">NAVIGATION</p>

			  <?php
			  $menuItems = [
				  ['label' => '<i class="icon-custom-home"></i><span class="title">Dashboard</span>', 'url' => ['/site/index']],
				  ['label' => '<i class="fa fa-certificate"></i><span class="title">Manage Item</span>', 'url' => ['/vendoritem/index']],
				  ['label' => '<i class="fa fa-arrows"></i><span class="title">Manage Area</span>', 'url' => ['/vendorlocation/edit']],

			  ];
			  if($checkone > 0) {
				  $menuItems[] = ['label' => '<i class="fa fa-clock-o"></i><span class="title">Delivery Time slot</span>', 'url' => ['/deliverytimeslot/index']];
				  $menuItems[] = ['label' => '<i class="fa fa-calendar-o"></i><span class="title">Exception Dates</span>', 'url' => ['/vendor-item-capacity-exception/index']];
				  $menuItems[] = ['label' => '<i class="fa fa-anchor"></i><span class="title">Weekly Off</span>', 'url' => ['/blockeddate/createweek']];
				  $menuItems[] = ['label' => '<i class="fa fa-film"></i><span class="title">Block Date</span>', 'url' => ['/blockeddate/index']];
			  }

			  $menuItems[] = ['label' => '<i class="fa fa-book"></i><span class="title">My Package</span>', 'url' => ['/vendorpackages/index']];
			  $menuItems[] = ['label' => '<i class="fa fa-university"></i><span class="title">My Profile</span>', 'url' => ['/site/profile']];
			  echo Nav::widget([
				  'options' => ['class' => 'left-side-bar'],
				  'activateParents'=>true,
				  'encodeLabels' => false,
				  'items' => $menuItems,
			  ]);

			  ?>
			<div class="clearfix"></div>

		<ul>
			<li class="<?php if ($menu_act == 'SiteController'  && $action == 'index') {echo "active"; } else  {echo "noactive";}?>">
				<?= Html::a('<i class="icon-custom-home"></i><span class="title">Dashboard</span>', ['site/index'], ['class'=>'link-title']) ?>
			</li>

            <li class="<?php if (in_array($menu_act,$first_menu)) {echo "open"; } else  {echo "noactive";}?>">
				<a href="javascript:;">
					<i class="fa fa-archive"></i>
					<span class="title">Items</span>
					<span class="<?php if (in_array($menu_act,$first_menu)) {echo "arrow open"; } else  {echo "arrow";}?>"></span>
				</a>
				<ul class="sub-menu" style='<?= in_array($menu_act,$first_menu)?"display:block":"" ?>'>
                    <li class="<?php if($menu_act == 'VendoritemController') {echo "active"; } else  {echo "noactive";}?>">
        				<?= Html::a('<i class="fa fa-certificate"></i><span class="title">Manage Item</span>', ['vendoritem/index'], ['class'=>'link-title']) ?>
        			</li>
                    <li class="<?php if ($menu_act == 'VendoritemcapacityexceptionController'  && $action == 'index') {echo "active"; } else  {echo "noactive";}?>">
						<?= Html::a('<i class="fa fa-calendar-o"></i><span class="title">Exception Dates</span>', ['vendoritemcapacityexception/index'], ['class'=>'link-title']) ?>
        			</li>
                </ul>
            </li>

            <li class="<?php if (in_array($menu_act,$deliveryMenu)) {echo "open"; } else  {echo "noactive";}?>">
				<a href="javascript:;">
					<i class="fa fa-car"></i>
					<span class="title">Delivery</span>
					<span class="<?php if (in_array($menu_act,$deliveryMenu)) {echo "arrow open"; } else  {echo "arrow";}?>"></span>
				</a>
				<ul class="sub-menu" style='<?= in_array($menu_act,$deliveryMenu)?"display:block":"" ?>'>
                    <li class="<?php if ($menu_act == 'VendorlocationController') {echo "active"; } else  {echo "noactive";}?>">
        				<?= Html::a('<i class="fa fa-arrows"></i><span class="title">Manage Area</span>', ['vendorlocation/edit'], ['class'=>'link-title']) ?>
        			</li>
        			<!-- Manage Address End-->
        			<li class="<?php if ($menu_act == 'DeliverytimeslotController') {echo "active"; } else  {echo "noactive";}?>">
        				<?= Html::a('<i class="fa fa-clock-o"></i><span class="title">Delivery Time slot</span>', ['deliverytimeslot/index'], ['class'=>'link-title']) ?>
        			</li>
        			<!-- Block date management-->
        			<li class="<?php if ($menu_act == 'BlockeddateController' && $action == 'createweek') {echo "active"; } else  {echo "noactive";}?>">
        				<?= Html::a('<i class="fa fa-anchor"></i><span class="title">Weekly Off</span>', ['blockeddate/createweek'], ['class'=>'link-title']) ?>
        			</li>
        			<li class="<?php if ($menu_act == 'BlockeddateController' && $action == 'index') {echo "active"; } else  {echo "noactive";}?>">
        				<?= Html::a('<i class="fa fa-film"></i><span class="title">Block Date</span>', ['blockeddate/index'], ['class'=>'link-title']) ?>
        			</li>
                </ul>
            </li>

			<!-- block date management-->
			<li class="<?php if ($menu_act == 'VendorpackagesController') {echo "active"; } else  {echo "noactive";}?>">
				<?= Html::a('<i class="fa fa-book"></i><span class="title">My Package</span>', ['vendorpackages/index'], ['class'=>'link-title']) ?>
			</li>
			<li class="<?php if ($menu_act == 'SiteController'  && $action == 'profile') {echo "active"; } else  {echo "noactive";}?>">
				<?= Html::a('<i class="fa fa-user"></i><span class="title">My Profile</span>', ['site/profile'], ['class'=>'link-title']) ?>
			</li>
		</ul>
			<div class="clearfix"></div>
		<!-- END SIDEBAR WIDGETS -->
	</div>
	</div>
	<a href="#" class="scrollup">Scroll</a>
