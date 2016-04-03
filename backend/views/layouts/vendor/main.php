<?php
use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use yii\bootstrap\Alert;
use common\models\Vendor;
AppAsset::register($this);
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
<!-- BEGIN CORE CSS FRAMEWORK -->
<link href="<?php echo Yii::$app->themeURL->createAbsoluteUrl(''); ?>plugins/pace/pace-theme-flash.css" rel="stylesheet" type="text/css" media="screen"/>
<link href="<?php echo Yii::$app->themeURL->createAbsoluteUrl(''); ?>plugins/boostrapv3/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo Yii::$app->themeURL->createAbsoluteUrl(''); ?>plugins/boostrapv3/css/bootstrap-theme.min.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo Yii::$app->themeURL->createAbsoluteUrl(''); ?>plugins/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo Yii::$app->themeURL->createAbsoluteUrl(''); ?>css/animate.min.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo Yii::$app->themeURL->createAbsoluteUrl(''); ?>plugins/jquery-scrollbar/jquery.scrollbar.css" rel="stylesheet" type="text/css"/>
<!-- END CORE CSS FRAMEWORK -->
<!-- BEGIN CSS TEMPLATE -->
<link href="<?php echo Yii::$app->themeURL->createAbsoluteUrl(''); ?>css/style.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo Yii::$app->themeURL->createAbsoluteUrl(''); ?>css/responsive.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo Yii::$app->themeURL->createAbsoluteUrl(''); ?>css/custom-icon-set.css" rel="stylesheet" type="text/css"/>
<!-- END CSS TEMPLATE -->
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="">
<!-- BEGIN CORE JS FRAMEWORK-->
<script src="<?php echo Yii::$app->themeURL->createAbsoluteUrl(''); ?>plugins/jquery-1.8.3.min.js" type="text/javascript"></script>
<script src="<?php echo Yii::$app->themeURL->createAbsoluteUrl(''); ?>plugins/jquery-ui/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>
<script src="<?php echo Yii::$app->themeURL->createAbsoluteUrl(''); ?>plugins/boostrapv3/js/bootstrap.min.js" type="text/javascript"></script>
<script src="<?php echo Yii::$app->themeURL->createAbsoluteUrl(''); ?>plugins/breakpoints.js" type="text/javascript"></script>
<script src="<?php echo Yii::$app->themeURL->createAbsoluteUrl(''); ?>plugins/jquery-unveil/jquery.unveil.min.js" type="text/javascript"></script>

<!-- END CORE JS FRAMEWORK -->
<script src="<?php echo Yii::$app->themeURL->createAbsoluteUrl(''); ?>plugins/jquery-scrollbar/jquery.scrollbar.min.js" type="text/javascript"></script>
<script src="<?php echo Yii::$app->themeURL->createAbsoluteUrl(''); ?>plugins/jquery-numberAnimate/jquery.animateNumbers.js" type="text/javascript"></script>
<!-- END CORE PLUGINS -->

<?php $this->beginContent('@app/views/layouts/vendor/header.php'); ?>
<!-- You may need to put some content here -->
	<?php $this->endContent(); ?>
<!-- BEGIN CONTENT -->
<div class="page-container row-fluid">
<?php $this->beginContent('@app/views/layouts/vendor/sidebar.php'); ?>
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
<?php $this->beginContent('@app/views/layouts/vendor/footer.php'); ?>
    <!-- You may need to put some content here -->
	<?php $this->endContent(); ?>
	<?php $this->endBody() ?>
	<script type="text/javascript">
		$(".alert-success,.alert-danger");
	 </script>
</body>
</html>
<?php $this->endPage() ?>
