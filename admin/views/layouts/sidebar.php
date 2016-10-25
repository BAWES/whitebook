<?php
	use yii\helpers\Html;
	use admin\models\VendorItem;
	$item_pending_count = VendorItem::item_pending_count();
?>
<!-- BEGIN SIDEBAR -->
	<!-- BEGIN MENU -->
	<div class="page-sidebar" id="main-menu">
		  <div class="page-sidebar-wrapper scrollbar-dynamic" id="main-menu-wrapper">
		<!-- BEGIN MINI-PROFILE -->
		<div class="user-info-wrapper">
			<div class="user-info">
				<div class="greeting">Welcome, <?php echo Yii::$app->user->identity->admin_name; ?>! </div>
				<div class="username"> <span class="semi-bold"> </span></div>
			</div>
		</div>
		<!-- END MINI-PROFILE -->
		<!-- BEGIN SIDEBAR MENU -->
		<?php

		$cntrl = Yii::$app->controller->id;
		$action = Yii::$app->controller->action->id;
		$controller = get_class($this->context);
		$action = $this->context->action->id;
		$menu = explode('\\',$controller);
		$menu_act = $menu[2];
		$first_menu = ['siteinfo','contacts','socialinfo', 'slide','role','admin','accesscontrol','faq','faqgroup','adverthome', 'order-status', 'payment-gateway'];
		$second_menu = ['country','city','location','addresstype','addressquestion'];
		$third_menu = ['category'];
		$fourth_menu = ['featuregroup','eventtype','themes','itemtype','priorityitem'];
		$fifth_menu = ['package','vendor','vendoritem','prioritylog'];
		$seventh_menu = ['categoryads','adverthome'];
		$eighth_menu = ['customer'];
		$nineth_menu = ['report'];
		?>
		 <p class="menu-title">NAVIGATION</p>
		<ul>
			<li class="<?=($cntrl == 'site') ? "active" : "noactive"; ?>"><?= Html::a('<i class="icon-custom-home"></i><span class="title">Dashboard</span>', ['site/index'], ['class'=>'link-title']) ?></li>
			<li class="<?=($cntrl == 'vendor-item-pending')  ? "active" : "noactive"; ?>"><?= Html::a('<i class="glyphicon glyphicon-send"></i><span class="title">Item Pending</span><span class="item_pending_count">'.$item_pending_count.'</span>', ['vendor-item-pending/index'], ['class'=>'link-title']) ?></li>
			<li class="<?=($cntrl == 'order')  ? "active" : "noactive"; ?>"><?= Html::a('<i class="icon-custom-extra"></i><span class="title">Order</span>', ['order/index'], ['class'=>'link-title']) ?></li>

			<li class="<?=(in_array($cntrl,$first_menu)) ? "open" : "noactive"?>">
				<a href="javascript:;">
					<i class="fa fa-university"></i>
					<span class="title">General Settings</span>
					<span class="<?=(in_array($cntrl,$first_menu))? "arrow open" : "arrow"?>"></span>
				</a>
				<ul class="sub-menu">
					<li class="<?=($cntrl == 'siteinfo')  ? "active" : "noactive"; ?>"><?= Html::a('Site Info', ['siteinfo/index'], ['class'=>'link-title']) ?></li>
					<li class="<?=($cntrl == 'contacts')  ? "active" : "noactive"; ?>"><?= Html::a('Contact Enquiries', ['contacts/index'], ['class'=>'link-title']) ?></li>
					<li class="<?=($cntrl == 'socialinfo')  ? "active" : "noactive"; ?>"><?= Html::a('Social media', ['socialinfo/index'], ['class'=>'link-title']) ?></li>
					<li class="<?=($cntrl == 'slide')  ? "active" : "noactive"; ?>"><?= Html::a('Manage Banner Slides ', ['slide/index'], ['class'=>'link-title']) ?></li>
					<li class="<?=($cntrl == 'admin')  ? "active" : "noactive"; ?>"><?= Html::a('Manage Admin', ['admin/index'], ['class'=>'link-title']) ?></li>
					<li class="<?=($cntrl == 'role')  ? "active" : "noactive"; ?>"><?= Html::a('Manage Role ', ['role/index'], ['class'=>'link-title']) ?></li>
					<li class="<?=($cntrl == 'accesscontrol')  ? "active" : "noactive"; ?>"><?= Html::a('Manage Access control ', ['accesscontrol/index'], ['class'=>'link-title']) ?></li>
					<li class="<?=($cntrl == 'faqgroup')  ? "active" : "noactive"; ?>"><?= Html::a('Manage FAQ Group', ['faqgroup/index'], ['class'=>'link-title']) ?></li>
					<li class="<?=($cntrl == 'faq')  ? "active" : "noactive"; ?>"><?= Html::a('Manage FAQ', ['faq/index'], ['class'=>'link-title']) ?></li>
					<li class="<?=($cntrl == 'adverthome')  ? "active" : "noactive"; ?>"><?= Html::a('Home Ads', ['adverthome/index'], ['class'=>'link-title']) ?></li>
					<li class="<?=($cntrl == 'order-status')  ? "active" : "noactive"; ?>"><?= Html::a('Order Status', ['order-status/index'], ['class'=>'link-title']) ?></li>
					<li class="<?=($cntrl == 'payment-gateway')  ? "active" : "noactive"; ?>"><?= Html::a('Payment Gateway', ['payment-gateway/index'], ['class'=>'link-title']) ?></li>
				</ul>
			</li>


			<!-- Manage Address Start-->
			<li class="<?=(in_array($cntrl,$second_menu))?"open" : "noactive"?>">
				<a href="javascript:;">
					<i class="fa fa-anchor"></i>
					<span class="title">Manage Address</span>
					<span class="<?=(in_array($cntrl,$second_menu))? "arrow open" : "arrow"?>"></span>
				</a>
				<ul class="sub-menu">
					<li class="<?=($cntrl == 'country')  ? "active" : "noactive"; ?>"><?= Html::a('Manage Country', ['country/index']) ?></li>
					<li class="<?=($cntrl == 'ciy')  ? "active" : "noactive"; ?>"><?= Html::a('Manage Governorate', ['city/index'], ['class'=>'link-title']) ?></li>
					<li class="<?=($cntrl == 'location')  ? "active" : "noactive"; ?>"><?= Html::a('Manage Area', ['location/index'], ['class'=>'link-title']) ?></li>
					<li class="<?=($cntrl == 'addresstype')  ? "active" : "noactive"; ?>"><?= Html::a('Manage Address Type', ['addresstype/index'], ['class'=>'link-title']) ?></li>
					<li class="<?=($cntrl == 'addressquestion')  ? "active" : "noactive"; ?>"><?= Html::a('Manage Address Question', ['addressquestion/index'], ['class'=>'link-title']) ?></li>
				</ul>
			</li>

			<!-- Manage Address End-->
			<!-- Manage Category Start-->
			<li class="<?=(in_array($cntrl,$third_menu)) ?"open" : "noactive"?>">
				<a href="javascript:;">
					<i class="fa fa-bell"></i>
					<span class="title">Manage Category</span>
					<span class="<?=(in_array($cntrl,$third_menu)) ? "arrow open" : "arrow" ?>"></span>
				  </a>
					<ul class="sub-menu">
					 <li class="<?=(in_array($cntrl,$third_menu) && $action == 'index') ? "active" : "noactive"?>">
						<?= Html::a('Level I', ['category/index'], ['class'=>'link-title']) ?>
					 </li>
					 <li class="<?=(in_array($cntrl,$third_menu) && $action == 'manage_subcategory') ? "active" : "noactive"?>">
						<?= Html::a('Level II', ['category/manage_subcategory'], ['class'=>'link-title']) ?>
					 </li>
					 <li class="<?=(in_array($cntrl,$third_menu) && $action == 'child_category_index')  ? "active" : "noactive"?>">
						<?= Html::a('Level III', ['category/child_category_index'], ['class'=>'link-title']) ?>
					 </li>
				</ul>
			</li>
			<!-- Manage Category End-->


			<li class="<?=(in_array($cntrl,$fifth_menu))? "open" : "noactive" ?>">
				<a href="javascript:;">
					<i class="fa fa-arrows"></i>
					<span class="title">Manage Vendor</span>
					<span class="<?=(in_array($cntrl,$fifth_menu)) ? "arrow open" : "arrow"; ?>"></span>
				</a>
				<ul class="sub-menu">
					<li class="<?=($cntrl == 'package')  ? "active" : "noactive"; ?>"><?= Html::a('Manage Package', ['package/index'], ['class'=>'link-title']) ?></li>
					<li class="<?=($cntrl == 'vendor')  ? "active" : "noactive"; ?>"><?= Html::a('Manage Vendor', ['vendor/index'], ['class'=>'link-title']) ?></li>
					<li class="<?=($cntrl == 'vendoritem')  ? "active" : "noactive"; ?>"><?= Html::a('Manage Vendor Item', ['vendoritem/index'], ['class'=>'link-title']) ?></li>
				</ul>
			</li>

			<!-- Manage Category Start-->
			<li class="<?=(in_array($cntrl,$fourth_menu)) ? "open" : "noactive";?>">
				<a href="javascript:;">
					<i class="fa fa-certificate"></i>
					<span class="title">Manage Item</span>
					<span class="<?=(in_array($cntrl,$fourth_menu)) ? "arrow open" : "arrow";?>"></span>
				  </a>
				<ul class="sub-menu">
					<li class="<?=($cntrl == 'featuregroup')  ? "active" : "noactive"; ?>"><?= Html::a('Manage Group', ['featuregroup/index'], ['class'=>'link-title']) ?></li>
					<li class="<?=($cntrl == 'themes')  ? "active" : "noactive"; ?>"><?= Html::a('Manage Themes Days', ['themes/index'], ['class'=>'link-title']) ?></li>
					<li class="<?=($cntrl == 'itemtype')  ? "active" : "noactive"; ?>"><?= Html::a('Manage Item Type', ['itemtype/index'], ['class'=>'link-title']) ?></li>
					<li class="<?=($cntrl == 'priorityitem')  ? "active" : "noactive"; ?>"><?= Html::a('Manage Priority Item', ['priorityitem/index'], ['class'=>'link-title']) ?></li>
					<li class="<?=($cntrl == 'eventtype')  ? "active" : "noactive"; ?>"><?= Html::a('Manage Event Type', ['eventtype/index'], ['class'=>'link-title']) ?></li>
				</ul>
			</li>

			<li class="<?=(in_array($cntrl,$eighth_menu)) ? "open" : "noactive";?>">
				<a href="javascript:;">
					<i class="glyphicon glyphicon-user"></i>
					<span class="title">Manage Customer</span>
					<span class="<?=(in_array($menu_act,$eighth_menu)) ? "arrow open" : "arrow";?>"></span>
			  	</a>
				<ul class="sub-menu">
					<li class="<?=($cntrl == 'customer')  ? "active" : "noactive"; ?>"><?= Html::a('Customer', ['customer/index'], ['class'=>'link-title']) ?></li>
				</ul>
			</li>

			<li class="<?=(in_array($cntrl, $nineth_menu)) ? "open" : "noactive"; ?>">
				<a href="javascript:;">
					<i class="fa fa-bullseye"></i>
					<span class="title">Reports</span>
					<span class="<?=(in_array($cntrl,$nineth_menu)) ? "arrow open" : "arrow";?>"></span>
				</a>
				<ul class="sub-menu">
					<li class="<?=($cntrl == 'report')  ? "active" : "noactive"; ?>"><?= Html::a('Vendor packages', ['report/package'], ['class'=>'link-title']) ?></li>
					<li class="<?=($cntrl == 'report' && $action == 'commission')  ? "active" : "noactive"; ?>"><?= Html::a('Vendor commission', ['report/commission'], ['class'=>'link-title']) ?></li>
				</ul>
			</li>
			<li class="<?=($cntrl == 'cms')  ? "active" : "noactive"; ?>"><?= Html::a('<i class="fa fa-tasks"></i><span class="title">Static Pages</span>', ['cms/index'], ['class'=>'link-title']) ?></li>
		</ul>
		<div class="clearfix"></div>
		<!-- END SIDEBAR WIDGETS -->
	</div>
	</div>
	<!-- BEGIN SCROLL UP HOVER -->
	<a href="#" class="scrollup">Scroll</a>

	<?php 

	$this->registerJs("
		$(document).ready(function () {
	        $('.header .nav li').removeClass('active');//this will remove the active class from
	   		//previously active menu item
	        $('.open').addClass('active');
	    });
	");
	
	
