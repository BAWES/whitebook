<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<!-- BEGIN HEADER -->
<div class="header navbar navbar-inverse">
	<!-- BEGIN TOP NAVIGATION BAR -->
	<div class="navbar-inner">
		<!-- BEGIN NAVIGATION HEADER -->
		<div class="header-seperation">
			<!-- BEGIN MOBILE HEADER -->
			<ul class="nav pull-left notifcation-center" id="main-menu-toggle-wrapper" style="display:none">
				<li class="dropdown">
					<a id="main-menu-toggle" href="#main-menu" class="">
						<div class="iconset top-menu-toggle-white"></div>
					</a>
				</li>
			</ul>
			<!-- END MOBILE HEADER -->
			<!-- BEGIN LOGO -->
			<a href="<?php echo Url::to(['site/index']); ?>">
				<?= Html::img('@web/uploads/app_img/logo1_434.png',['class'=>"logo",'width'=>"200"]) ?>
			</a>
			<!-- END LOGO -->

			<!-- 
			<ul class="nav pull-right notifcation-center">
				<li class="dropdown" id="header_task_bar"> <a href="<?= Url::to(['site/index']); ?>" class="dropdown-toggle active" data-toggle=""> <div class="iconset top-home"></div> </a> </li>
			</ul>
			END LOGO NAV BUTTONS -->
		</div>
		<!-- END NAVIGATION HEADER -->
		<!-- BEGIN CONTENT HEADER -->
		<div class="header-quick-nav">
			<!-- BEGIN HEADER LEFT SIDE SECTION -->
			<div class="pull-left">
				<!-- BEGIN SLIM NAVIGATION TOGGLE -->
				<ul class="nav quick-section">
					<li class="quicklinks">
						<a href="#" class="" id="layout-condensed-toggle">
							<div class="iconset top-menu-toggle-dark"></div>
						</a>
					</li>
				</ul>
			</div>
			<!-- END HEADER LEFT SIDE SECTION -->
			<!-- BEGIN HEADER RIGHT SIDE SECTION -->
			<div class="pull-right">
				<div class="chat-toggler">
					<!-- BEGIN NOTIFICATION CENTER -->
					<a href="#" data-placement="bottom" data-content="" data-toggle="dropdown" data-original-title="Notifications">
						<div class="user-details">
							<div class="username">
							</div>
						</div>
					</a>
					<div id="notification-list" style="display:none">
						<div style="width:300px">
						</div>
					</div>

				</div>
				<!-- BEGIN HEADER NAV BUTTONS -->
				<ul class="nav quick-section pull-right">
					<!-- BEGIN SETTINGS -->
					<li class="quicklinks">
						<a data-toggle="" class=" pull-right" href="#" id="user-options">
							<div class="iconset top-settings-dark"></div>
						</a>
						<ul class="dropdown-menu  pull-right" role="menu" aria-labelledby="user-options">
							<li><a href="<?= Url::toRoute('site/profile'); ?>"> My Account</a>
					        </li>
					        <li><a href="<?= Url::toRoute('/site/changepassword'); ?>">Change password</a>
					        </li>
					        <li class="divider"></li>
					        <li><a href="<?= Url::toRoute('/site/logout'); ?>"><i class="fa fa-power-off"></i>&nbsp;&nbsp;Log Out</a></li>
						</ul>
					</li>
					<!-- END SETTINGS -->
					<!-- END CHAT SIDEBAR TOGGLE -->
				</ul>
				<!-- END HEADER NAV BUTTONS -->
			</div>
			<!-- END HEADER RIGHT SIDE SECTION -->
		</div>
		<!-- END CONTENT HEADER -->
	</div>
	<!-- END TOP NAVIGATION BAR -->
</div>
<!-- END HEADER -->
<div class="processing_image" style="display: none;">
	<p style="color:#f78f1e; font:normal 26px kreonbold;">
		<?= Html::img(Yii::getAlias('@web/themes/default/img/loading.gif'), ['class'=>'','width'=>'64px','height'=>'64px','id'=>'loading','alt'=>'loading']);?>
	</p>
</div>
