<?php

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use yii\bootstrap\Alert;
use common\models\Vendor;

$this->beginPage();
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
	<meta charset="utf-8" />
	<title><?php echo ($this->title)?$this->title:'Whitebook Application'; ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<meta content="" name="description" />
	<meta content="" name="author" />
	<?php $this->head();
	AppAsset::register($this); ?>
	<link rel="shortcut icon" href="/favicon.png" type="image/x-icon" />
</head>
<body class="">
	<?php $this->beginBody() ?>
	<?php $this->beginContent('@app/views/layouts/header.php'); ?>
	<!-- You may need to put some content here -->
	<?php $this->endContent(); ?>
	<!-- BEGIN CONTENT -->
	<div class="page-container row-fluid">
		<?php $this->beginContent('@app/views/layouts/sidebar.php'); ?>
		<!-- You may need to put some content here -->
		<?php $this->endContent(); ?>
		<!-- BEGIN PAGE CONTAINER-->
		<div class="page-content">
			<div class="content">
				<?php
				if($flash = Yii::$app->session->getFlash('success'))
				echo Alert::widget(['options' => ['class' => 'alert-success'], 'body' => $flash,'closeButton'=>['label'=>'']]);
				if($flash = Yii::$app->session->getFlash('danger'))
				echo Alert::widget(['options' => ['class' => 'alert-danger'], 'body' => $flash,'closeButton'=>['label'=>'']]);
				?>
				<ul class="breadcrumb">
					<?=
					Breadcrumbs::widget([
						'homeLink' => [ 'label' => 'Dashboard','url' =>['default/index'],],
						'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
						'activeItemTemplate'=>'<li class=\"active\"><b>{link}</b></li>'
					])
					?>
				</ul>
				<div class="page-title"> <i class="icon-custom-left"></i>
					<h3><span class="semi-bold"><?= Html::encode($this->title) ?></span></h3>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="grid simple">
							<div class="grid-body no-border"> <br>
								<div class="row">
									<?= $content ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- END PAGE CONTAINER -->
		<?php $this->beginContent('@app/views/layouts/footer.php'); ?>
		<!-- You may need to put some content here -->
		<?php $this->endContent(); ?>
		<?php $this->endBody() ?>
	</body>
	</html>
	<?php $this->endPage() ?>
