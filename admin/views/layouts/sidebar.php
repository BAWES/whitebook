<?php

use yii\helpers\Html;
use yii\widgets\Menu;
use admin\models\VendorItem;
use admin\models\AccessControlList;

$item_pending_count = VendorItem::item_pending_count();

?>
<div class="page-sidebar" id="main-menu">
	  <div class="page-sidebar-wrapper scrollbar-dynamic" id="main-menu-wrapper">

	<!-- BEGIN MINI-PROFILE -->
	<?php if(!Yii::$app->user->isGuest) { ?>
	<div class="user-info-wrapper">
		<div class="user-info">
			<div class="greeting">Welcome, <?php echo Yii::$app->user->identity->admin_name; ?>! </div>
			<div class="username"> <span class="semi-bold"> </span></div>
		</div>
	</div>
	<?php } ?>

	<p class="menu-title">NAVIGATION</p>

	<?=\admin\widgets\MenuExtended::widget([
	    'items' => [
	        [	
	        	'label' => '<i class="icon-custom-home"></i><span class="title">Dashboard</span>', 
	        	'url' => ['site/index']
	        ],
	        [	
	        	'label' => '<i class="glyphicon glyphicon-send"></i><span class="title">Item Pending</span><span class="item_pending_count">'.$item_pending_count.'</span>', 
	        	'url' => ['vendor-item-pending/index'],
	        ],
	        [	
	        	'label' => '<i class="icon-custom-extra"></i><span class="title">Order</span>', 
	        	'url' => ['order/index'],
	        ],
	        [
	        	'label' => '<i class="fa fa-university"></i><span class="title">General Settings</span>',
				'options' => ['class'=>'dropdown'],
				'template' => '<a href="javascript:;">{label}<span class="arrow"></span></a>',
				'items' => [
					['label' => 'Site Info', 'url' => ['site-info/update']],
					['label' => 'Contact Enquiries', 'url' => ['contacts/index']],
					['label' => 'Social media', 'url' => ['social-info/index']],
					['label' => 'Manage Banner Slides', 'url' => ['slide/index']],
					['label' => 'Manage Admin', 'url' => ['admin/index']],
					['label' => 'Manage Role ', 'url' => ['role/index']],
					['label' => 'Manage FAQ Group', 'url' => ['faq-group/index']],
					['label' => 'Manage FAQ', 'url' => ['faq/index']],
					['label' => 'Home Ads', 'url' => ['advert-home/index']],
					['label' => 'Order Status', 'url' => ['order-status/index']],
					['label' => 'Payment Gateway', 'url' => ['payment-gateway/index']]
				]
			],
	        [
	        	'label' => '<i class="fa fa-anchor"></i><span class="title">Manage Address</span>',
				'options'=>['class'=>'dropdown'],
				'template' => '<a href="javascript:;">{label}<span class="arrow"></span></a>',
				'items' => [
					['label' => 'Manage Country', 'url' => ['country/index']],
					['label' => 'Manage Governorate', 'url' => ['city/index']],
					['label' => 'Manage Area', 'url' => ['location/index']],
					['label' => 'Manage Address Type', 'url' => ['address-type/index']],
					['label' => 'Manage Address Question', 'url' => ['address-question/index']]
				]
			],
			[
	        	'label' => '<i class="fa fa-bell"></i><span class="title">Manage Category</span>',
				'options'=> ['class'=>'dropdown'],
				'template' => '<a href="javascript:;">{label}<span class="arrow"></span></a>',
				'items' => [
					['label' => 'Level I', 'url' => ['category/index']],
					['label' => 'Level II', 'url' => ['category/manage_subcategory']],
					['label' => 'Level III', 'url' => ['category/child_category_index']],
				]
			],
			[
	        	'label' => '<i class="fa fa-arrows"></i><span class="title">Manage Vendor</span>',
				'options'=>['class'=>'dropdown'],
				'template' => '<a href="javascript:;">{label}<span class="arrow"></span></a>',
				'items' => [
					['label' => 'Manage Package', 'url' => ['package/index']],
					['label' => 'Manage Vendor', 'url' => ['vendor/index']],
					['label' => 'Manage Vendor Item', 'url' => ['vendor-item/index']],
				]
			],
			[
	        	'label' => '<i class="fa fa-certificate"></i><span class="title">Manage Item</span>',
				'options'=>['class'=>'dropdown'],
				'template' => '<a href="javascript:;">{label}<span class="arrow"></span></a>',
				'items' => [
					['label' => 'Manage Group', 'url' => ['feature-group/index']],
					['label' => 'Manage Themes Days', 'url' => ['themes/index']],
					['label' => 'Manage Item Type', 'url' => ['item-type/index']],
					['label' => 'Manage Priority Item', 'url' => ['priority-item/index']],
					['label' => 'Manage Event Type', 'url' => ['event-type/index']],
				]
			],
			[
	        	'label' => '<i class="glyphicon glyphicon-user"></i><span class="title">Manage Customer</span>',
				'options'=>['class'=>'dropdown'],
				'template' => '<a href="javascript:;">{label}<span class="arrow"></span></a>',
				'items' => [
					['label' => 'Customer', 'url' => ['customer/index']],
					['label' => 'Customer Events', 'url' => ['events/index']],
				]
			],
			[
	        	'label' => '<i class="fa fa-bullseye"></i><span class="title">Reports</span>',
				'options'=>['class'=>'dropdown'],
				'template' => '<a href="javascript:;">{label}<span class="arrow"></span></a>',
				'items' => [
					['label' => 'Vendor packages', 'url' => ['report/package']],
					['label' => 'Vendor commission', 'url' => ['report/commission']]
				]
			],
			[	
	        	'label' => '<i class="fa fa-tasks"></i><span class="title">Static Pages</span>', 
	        	'url' => ['cms/index']
	        ],
	    ],
	    'encodeLabels' => false,
		'submenuTemplate' => "\n<ul class='sub-menu' role='menu'>\n{items}\n</ul>\n",
	]);

	?>	 
	
	<div class="clearfix"></div>
	
</div>
</div>

<!-- SCROLL UP HOVER -->
<a href="#" class="scrollup">Scroll</a>

<?php 

$this->registerJs("

	//chcek if sub-menu item open menu 
	$(document).ready(function () {

		var dropdown = $('.page-sidebar-wrapper .active').parents('.dropdown');

		if(dropdown.length > 0) {
			dropdown.addClass('open');
		}
    });
");
