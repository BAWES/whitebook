 <?php use yii\helpers\Html; ?>
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
		<?php $controller = get_class($this->context);
		$action = $this->context->action->id;
		$menu = explode('\\',$controller);
		$menu_act = $menu[2];
		$first_menu = array('SiteinfoController','ContactsController','SocialinfoController',
				   'SlideController','RoleController','AdminController','AccesscontrolController','FaqController','AdverthomeController', 'OrderStatusController', 'PaymentGatewayController');
		$second_menu = array('CountryController','CityController','LocationController','AddresstypeController','AddressquestionController');
		$third_menu = array('CategoryController');
		$fourth_menu = array('FeaturegroupController','EventtypeController','ThemesController','ItemtypeController','PriorityitemController',);
		$fifth_menu = array('PackageController','VendorController','VendoritemController','PrioritylogController');
		$seventh_menu = array('CategoryadsController','AdverthomeController');
		$eighth_menu = array('CustomerController');
		$nineth_menu = array('ReportController');
		?>
		 <p class="menu-title">NAVIGATION</p>
		<ul>
			<li class="<?php if ($menu_act == 'SiteController') {echo "active"; } else  {echo "noactive";}?>">
				<?= Html::a('<i class="icon-custom-home"></i><span class="title">Dashboard</span>', ['site/index'], ['class'=>'link-title']) ?>
			</li>
			<li class="<?php if ($menu_act == 'OrderController') {echo "active"; } else  {echo "noactive";}?>">
				<?= Html::a('<i class="icon-custom-extra"></i><span class="title">Order</span>', ['order/index'], ['class'=>'link-title']) ?>
			</li>
			<li class="<?php if (in_array($menu_act,$first_menu)) {echo "open"; } else  {echo "noactive";}?>">
				<a href="javascript:;">
					<i class="fa fa-university"></i>
					<span class="title">General Settings</span>
					<span class="<?php if (in_array($menu_act,$first_menu)) {echo "arrow open"; } else  {echo "arrow";}?>"></span>
				</a>
				<ul class="sub-menu">
					<li class="<?php if ($menu_act == 'SiteinfoController') {echo "active"; } else  {echo "noactive";}?>">
						<?= Html::a('Site Info', ['siteinfo/index'], ['class'=>'link-title']) ?>
					</li>
					<li class="<?php if ($menu_act == 'ContactsController') {echo "active"; } else  {echo "noactive";}?>">
						<?= Html::a('Contact Enquiries', ['contacts/index'], ['class'=>'link-title']) ?>
					</li>
					<li class="<?php if ($menu_act == 'SocialinfoController') {echo "active"; } else  {echo "noactive";}?>">
						<?= Html::a('Social media', ['socialinfo/index'], ['class'=>'link-title']) ?>
				 </li>
					<li class="<?php if ($menu_act == 'SlideController') {echo "active"; } else  {echo "noactive";}?>">
						<?= Html::a('Manage Banner Slides ', ['slide/index'], ['class'=>'link-title']) ?>
				  </li>
					<li class="<?php if ($menu_act == 'AdminController') {echo "active"; } else  {echo "noactive";}?>">
						<?= Html::a('Manage Admin', ['admin/index'], ['class'=>'link-title']) ?>
					</li>
					<li class="<?php if ($menu_act == 'RoleController') {echo "active"; } else  {echo "noactive";}?>">
						<?= Html::a('Manage Role ', ['role/index'], ['class'=>'link-title']) ?>
				  	</li>
					<li class="<?php if ($menu_act == 'AccesscontrolController') {echo "active"; } else  {echo "noactive";}?>">
						<?= Html::a('Manage Access control ', ['accesscontrol/index'], ['class'=>'link-title']) ?>
				  	</li>
					<li class="<?php if ($menu_act == 'FaqgroupController') {echo "active"; } else  {echo "noactive";}?>">
						<?= Html::a('Manage FAQ Group', ['faqgroup/index'], ['class'=>'link-title']) ?>
					</li>
					<li class="<?php if ($menu_act == 'FaqController') {echo "active"; } else  {echo "noactive";}?>">
						<?= Html::a('Manage FAQ', ['faq/index'], ['class'=>'link-title']) ?>
					</li>
					<li class="<?php if ($menu_act == 'AdverthomeController') {echo "active"; } else  {echo "noactive";}?>">
						<?= Html::a('Home Ads', ['adverthome/index'], ['class'=>'link-title']) ?>
					</li>
					<li class="<?php if ($menu_act == 'OrderStatusController') {echo "active"; } else  {echo "noactive";}?>">
						<?= Html::a('Order Status', ['order-status/index'], ['class'=>'link-title']) ?>
					</li>
					<li class="<?php if ($menu_act == 'PaymentGatewayController') {echo "active"; } else  {echo "noactive";}?>">
						<?= Html::a('Payment Gateway', ['payment-gateway/index'], ['class'=>'link-title']) ?>
					</li>
				</ul>
			</li>


			<!-- Manage Address Start-->
			<li class="<?php if (in_array($menu_act,$second_menu)) {echo "open"; } else  {echo "noactive";}?>">
				<a href="javascript:;">
					<i class="fa fa-anchor"></i>
					<span class="title">Manage Address</span>
					<span class="<?php if (in_array($menu_act,$second_menu)) {echo "arrow open"; } else  {echo "arrow";}?>"></span>
				</a>
				<ul class="sub-menu">
					<li class="<?php if ($menu_act == 'CountryController') {echo "active"; } else  {echo "noactive";}?>">
						<?= Html::a('Manage Country', ['country/index']) ?>
					</li>
					<li class="<?php if ($menu_act == 'CityController') {echo "active"; } else  {echo "noactive";}?>">
						<?= Html::a('Manage Governorate', ['city/index'], ['class'=>'link-title']) ?>
					</li>
					<li class="<?php if ($menu_act == 'LocationController') {echo "active"; } else  {echo "noactive";}?>">
						<?= Html::a('Manage Area', ['location/index'], ['class'=>'link-title']) ?>
					</li>
					<li class="<?php if ($menu_act == 'AddresstypeController') {echo "active"; } else  {echo "noactive";}?>">
						<?= Html::a('Manage Address Type', ['addresstype/index'], ['class'=>'link-title']) ?>
					</li>
					<li class="<?php if ($menu_act == 'AddressquestionController') {echo "active"; } else  {echo "noactive";}?>">
						<?= Html::a('Manage Address Question', ['addressquestion/index'], ['class'=>'link-title']) ?>
					</li>
				</ul>
			</li>

			<!-- Manage Address End-->
			<!-- Manage Category Start-->
			<li class="<?php if (in_array($menu_act,$third_menu)) {echo "open"; } else  {echo "noactive";}?>">
				<a href="javascript:;">
					<i class="fa fa-bell"></i>
					<span class="title">Manage Category</span>
					<span class="<?php if (in_array($menu_act,$third_menu)) {echo "arrow open"; } else  {echo "arrow";}?>"></span>
				  </a>
					<ul class="sub-menu">
					 <li class="<?php if (in_array($menu_act,$third_menu) && $action == 'index') {echo "active"; } else  {echo "noactive";}?>">
						<?= Html::a('Level I', ['category/index'], ['class'=>'link-title']) ?>
					 </li>
					 <li class="<?php if (in_array($menu_act,$third_menu) && $action == 'manage_subcategory') {echo "active"; } else  {echo "noactive";}?>">
						<?= Html::a('Level II', ['category/manage_subcategory'], ['class'=>'link-title']) ?>
					 </li>
					 <li class="<?php if (in_array($menu_act,$third_menu) && $action == 'child_category_index') {echo "active"; } else  {echo "noactive";}?>">
						<?= Html::a('Level III', ['category/child_category_index'], ['class'=>'link-title']) ?>
					 </li>
				</ul>
			</li>
			<!-- Manage Category End-->


			<li class="<?php if (in_array($menu_act,$fifth_menu)) {echo "open"; } else  {echo "noactive";}?>">
				<a href="javascript:;">
					<i class="fa fa-arrows"></i>
					<span class="title">Manage Vendor</span>
					<span class="<?php if (in_array($menu_act,$fifth_menu)) {echo "arrow open"; } else  {echo "arrow";}?>"></span>
				  </a>
					<ul class="sub-menu">
					<li class="<?php if ($menu_act == 'PackageController') {echo "active"; } else  {echo "noactive";}?>">
						<?= Html::a('Manage Package', ['package/index'], ['class'=>'link-title']) ?>
					<li class="<?php if ($menu_act == 'VendorController') {echo "active"; } else  {echo "noactive";}?>">
						<?= Html::a('Manage Vendor', ['vendor/index'], ['class'=>'link-title']) ?>
					</li>
					<li class="<?php if ($menu_act == 'VendoritemController') {echo "active"; } else  {echo "noactive";}?>">
						<?= Html::a('Manage Vendor Item', ['vendoritem/index'], ['class'=>'link-title']) ?>
					</li>
				</ul>
			</li>

			<!-- Manage Category Start-->
			<li class="<?php if (in_array($menu_act,$fourth_menu)) {echo "open"; } else  {echo "noactive";}?>">
				<a href="javascript:;">
					<i class="fa fa-certificate"></i>
					<span class="title">Manage Item</span>
					<span class="<?php if (in_array($menu_act,$fourth_menu)) {echo "arrow open"; } else  {echo "arrow";}?>"></span>
				  </a>
					<ul class="sub-menu">
					</li>
					<li class="<?php if ($menu_act == 'FeaturegroupController') {echo "active"; } else  {echo "noactive";}?>">
						<?= Html::a('Manage Group', ['featuregroup/index'], ['class'=>'link-title']) ?>
					</li>
					<li class="<?php if ($menu_act == 'ThemesController') {echo "active"; } else  {echo "noactive";}?>">
						<?= Html::a('Manage Themes Days', ['themes/index'], ['class'=>'link-title']) ?>
					</li>
					<li class="<?php if ($menu_act == 'ItemtypeController') {echo "active"; } else  {echo "noactive";}?>">
						<?= Html::a('Manage Item Type', ['itemtype/index'], ['class'=>'link-title']) ?>
					</li>
					<li class="<?php if ($menu_act == 'PriorityitemController') {echo "active"; } else  {echo "noactive";}?>">
						<?= Html::a('Manage Priority Item', ['priorityitem/index'], ['class'=>'link-title']) ?>
					</li>
					<li class="<?php if ($menu_act == 'EventtypeController') {echo "active"; } else  {echo "noactive";}?>">
						<?= Html::a('Manage Event Type', ['eventtype/index'], ['class'=>'link-title']) ?>
					</li>
				</ul>
			</li>

			<li class="<?php if (in_array($menu_act,$eighth_menu)) {echo "open"; } else  {echo "noactive";}?>">
				<a href="javascript:;">
					<i class="fa fa-bullseye"></i>
					<span class="title">Manage Customer</span>
					<span class="<?php if (in_array($menu_act,$eighth_menu)) {echo "arrow open"; } else  {echo "arrow";}?>"></span>
				  </a>
					<ul class="sub-menu">
					<li class="<?php if(($menu_act=='CustomerController') && ($action == 'index')){echo "active"; } else  {echo "noactive";}?>">
						<?= Html::a('Customer', ['customer/index'], ['class'=>'link-title']) ?>
					</li>
				</ul>
			</li>

			<li class="<?php if (in_array($menu_act, $nineth_menu)) { echo "open"; } else { echo "noactive";} ?>">
				<a href="javascript:;">
					<i class="fa fa-bullseye"></i>
					<span class="title">Reports</span>
					<span class="<?php if (in_array($menu_act,$nineth_menu)) {echo "arrow open"; } else { echo "arrow";}?>"></span>
				</a>
				<ul class="sub-menu">
					<li class="<?php if(($menu_act=='ReportController') && ($action == 'package')){echo "active"; } else  {echo "noactive";}?>">
						<?= Html::a('Vendor packages', ['report/package'], ['class'=>'link-title']) ?>
					</li>
					<li class="<?php if(($menu_act=='ReportController') && ($action == 'commission')){echo "active"; } else  {echo "noactive";}?>">
						<?= Html::a('Vendor commission', ['report/commission'], ['class'=>'link-title']) ?>
					</li>
				</ul>
			</li>

			<li class="<?php if ($menu_act == 'CmsController') {echo "active"; } else  {echo "noactive";}?>">
				<?= Html::a('<i class="fa fa-tasks"></i><span class="title">Static Pages</span>', ['cms/index'], ['class'=>'link-title']) ?>
			</li>
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
	
	
