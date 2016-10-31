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

	<?php

	$acc_report_comm = AccessControlList::can('report', 'commission');
	$acc_report_package = AccessControlList::can('report', 'package');

	$acc_siteinfo = AccessControlList::can('site-info', 'index');
	$acc_contact = AccessControlList::can('contacts', 'index');
	$acc_social = AccessControlList::can('social-info', 'index');
	$acc_slide = AccessControlList::can('slide', 'index');
	$acc_admin = AccessControlList::can('admin', 'index');
	$acc_role = AccessControlList::can('role', 'index');
	$acc_faqgroup = AccessControlList::can('faq-group', 'index');
	$acc_faq = AccessControlList::can('faq', 'index');
	$acc_adverthome = AccessControlList::can('advert-home', 'index');
	$acc_order_status = AccessControlList::can('order-status', 'index');
	$acc_pg = AccessControlList::can('payment-gateway', 'index');

	$acc_country = AccessControlList::can('country', 'index');
	$acc_governorate = AccessControlList::can('city', 'index');
	$acc_location = AccessControlList::can('location', 'index');
	$acc_addresstype = AccessControlList::can('address-type', 'index');
	$acc_addressquestion = AccessControlList::can('address-question', 'index');

	$acc_category = AccessControlList::can('category', 'index');
	$acc_manage_subcategory = AccessControlList::can('category', 'manage_subcategory'); 
	$acc_child_category_index = AccessControlList::can('category', 'child_category_index');

	$acc_package =  AccessControlList::can('package', 'index');
	$acc_vendor = 	AccessControlList::can('vendor', 'index');
	$acc_vendoritem = AccessControlList::can('vendor-item', 'index');

	$acc_featuregroup = AccessControlList::can('feature-group', 'index');
	$acc_themes = AccessControlList::can('themes', 'index');
	$acc_itemtype = AccessControlList::can('item-type', 'index');
	$acc_priorityitem = AccessControlList::can('priority-item', 'index');
	$acc_eventtype = AccessControlList::can('event-type', 'index');

	$acc_customer = AccessControlList::can('customer', 'index');
	$acc_events = AccessControlList::can('events', 'index');

	echo Menu::widget([
	    'items' => [
	        [	
	        	'label' => '<i class="icon-custom-home"></i><span class="title">Dashboard</span>', 
	        	'url' => ['site/index']
	        ],
	        [	
	        	'label' => '<i class="glyphicon glyphicon-send"></i><span class="title">Item Pending</span><span class="item_pending_count">'.$item_pending_count.'</span>', 
	        	'url' => ['vendor-item-pending/index'],
	        	'visible' => AccessControlList::can('vendor-item-pending', 'index')
	        ],
	        [	
	        	'label' => '<i class="icon-custom-extra"></i><span class="title">Order</span>', 
	        	'url' => ['order/index'],
	        	'visible' => AccessControlList::can('order', 'index')
	        ],
	        [
	        	'label' => '<i class="fa fa-university"></i><span class="title">General Settings</span>',
				'options' => ['class'=>'dropdown'],
				'template' => '<a href="javascript:;">{label}<span class="arrow"></span></a>',
				'visible' => ($acc_siteinfo || $acc_contact || $acc_social || $acc_slide || $acc_admin || $acc_role ||
					$acc_faqgroup || $acc_faq || $acc_adverthome || $acc_order_status || $acc_pg) ? true : false,
				'items' => [
					['label' => 'Site Info', 'url' => ['site-info/index'], 'visible' => $acc_siteinfo],
					['label' => 'Contact Enquiries', 'url' => ['contacts/index'], 'visible' => $acc_contact],
					['label' => 'Social media', 'url' => ['social-info/index'], 'visible' => $acc_social],
					['label' => 'Manage Banner Slides', 'url' => ['slide/index'], 'visible' => $acc_slide],
					['label' => 'Manage Admin', 'url' => ['admin/index'], 'visible' => $acc_admin],
					['label' => 'Manage Role ', 'url' => ['role/index'], 'visible' => $acc_role],
					['label' => 'Manage FAQ Group', 'url' => ['faq-group/index'], 'visible' => $acc_faqgroup],
					['label' => 'Manage FAQ', 'url' => ['faq/index'], 'visible' => $acc_faq],
					['label' => 'Home Ads', 'url' => ['advert-home/index'], 'visible' => $acc_adverthome],
					['label' => 'Order Status', 'url' => ['order-status/index'], 'visible' => $acc_order_status],
					['label' => 'Payment Gateway', 'url' => ['payment-gateway/index'], 'visible' => $acc_pg]
				]
			],
	        [
	        	'label' => '<i class="fa fa-anchor"></i><span class="title">Manage Address</span>',
				'options'=>['class'=>'dropdown'],
				'template' => '<a href="javascript:;">{label}<span class="arrow"></span></a>',
				'visible' => ($acc_country || $acc_governorate || $acc_location || $acc_addresstype || $acc_addressquestion) ? true : false,
				'items' => [
					['label' => 'Manage Country', 'url' => ['country/index'], 'visible' => $acc_country],
					['label' => 'Manage Governorate', 'url' => ['city/index'], 'visible' => $acc_governorate],
					['label' => 'Manage Area', 'url' => ['location/index'], 'visible' => $acc_location],
					['label' => 'Manage Address Type', 'url' => ['address-type/index'], 'visible' => $acc_addresstype],
					['label' => 'Manage Address Question', 'url' => ['address-question/index'], 'visible' => $acc_addressquestion]
				]
			],
			[
	        	'label' => '<i class="fa fa-bell"></i><span class="title">Manage Category</span>',
				'options'=> ['class'=>'dropdown'],
				'template' => '<a href="javascript:;">{label}<span class="arrow"></span></a>',
				'visible' => ($acc_category || $acc_manage_subcategory || $acc_child_category_index) ? true : false,
				'items' => [					
					['label' => 'Level I', 'url' => ['category/index'], 'visible' => $acc_category],
					['label' => 'Level II', 'url' => ['category/manage_subcategory'], 'visible' => $acc_manage_subcategory],
					['label' => 'Level III', 'url' => ['category/child_category_index'], 'visible' => $acc_child_category_index],
				]
			],
			[
	        	'label' => '<i class="fa fa-arrows"></i><span class="title">Manage Vendor</span>',
				'options'=>['class'=>'dropdown'],
				'template' => '<a href="javascript:;">{label}<span class="arrow"></span></a>',
				'visible' => ($acc_package || $acc_vendor || $acc_vendoritem) ? true : false,
				'items' => [
					['label' => 'Manage Package', 'url' => ['package/index'], 'visible' => $acc_package],
					['label' => 'Manage Vendor', 'url' => ['vendor/index'], 'visible' => $acc_vendor],
					['label' => 'Manage Vendor Item', 'url' => ['vendor-item/index'], 'visible' => $acc_vendoritem],
				]
			],
			[
	        	'label' => '<i class="fa fa-certificate"></i><span class="title">Manage Item</span>',
				'options'=>['class'=>'dropdown'],
				'template' => '<a href="javascript:;">{label}<span class="arrow"></span></a>',
				'visible' => ($acc_featuregroup || $acc_themes || $acc_itemtype || $acc_priorityitem || $acc_eventtype) ? true:false, 
				'items' => [				
					['label' => 'Manage Group', 'url' => ['feature-group/index'], 'visible' => $acc_featuregroup],
					['label' => 'Manage Themes Days', 'url' => ['themes/index'], 'visible' => $acc_themes],
					['label' => 'Manage Item Type', 'url' => ['item-type/index'], 'visible' => $acc_itemtype],
					['label' => 'Manage Priority Item', 'url' => ['priority-item/index'], 'visible' => $acc_priorityitem],
					['label' => 'Manage Event Type', 'url' => ['event-type/index'], 'visible' => $acc_eventtype],
				]
			],
			[
	        	'label' => '<i class="glyphicon glyphicon-user"></i><span class="title">Manage Customer</span>',
				'options'=>['class'=>'dropdown'],
				'template' => '<a href="javascript:;">{label}<span class="arrow"></span></a>',
				'visible' => ($acc_customer || $acc_events)? true: false,
				'items' => [
					['label' => 'Customer', 'url' => ['customer/index'], 'visible' => $acc_customer],
					['label' => 'Customer Events', 'url' => ['events/index'], 'visible' => $acc_events],
				]
			],
			[
	        	'label' => '<i class="fa fa-bullseye"></i><span class="title">Reports</span>',
				'options'=>['class'=>'dropdown'],
				'template' => '<a href="javascript:;">{label}<span class="arrow"></span></a>',
				'visible' => ($acc_report_package || $acc_report_comm) ? true : false,
				'items' => [
					[
						'label' => 'Vendor packages', 
						'url' => ['report/package'],
						'visible' => $acc_report_package
					],
					[
						'label' => 'Vendor commission', 
						'url' => ['report/commission'],
						'visible' => $acc_report_comm
					]
				]
			],
			[	
	        	'label' => '<i class="fa fa-tasks"></i><span class="title">Static Pages</span>', 
	        	'url' => ['cms/index'],
	        	'visible' => AccessControlList::can('cms/index')
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
