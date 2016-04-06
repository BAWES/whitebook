<?php
use yii\bootstrap\ActiveForm;

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use yii\bootstrap\Alert;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
AppAsset::register($this);
?>
<?= Html::csrfMetaTags() ?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<meta charset="utf-8" />
<title>Whitebook</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta content="" name="description" />
<meta content="" name="author" />
<!-- BEGIN CORE CSS FRAMEWORK -->
<link href="<?= Yii::getAlias('@themes'.'/plugins/pace/pace-theme-flash.css') ?>" rel="stylesheet" type="text/css" media="screen"/>
<link href="<?= Yii::getAlias('@themes'.'/plugins/boostrapv3/css/bootstrap.min.css') ?>" rel="stylesheet" type="text/css"/>
<link href="<?= Yii::getAlias('@themes'.'/plugins/boostrapv3/css/bootstrap-theme.min.css') ?>" rel="stylesheet" type="text/css"/>
<link href="<?= Yii::getAlias('@themes'.'/css/animate.min.css'); ?>" rel="stylesheet" type="text/css"/>
<!-- END CORE CSS FRAMEWORK -->
<!-- BEGIN CSS TEMPLATE -->
<link href="<?= Yii::getAlias('@themes'.'/css/style.css'); ?>" rel="stylesheet" type="text/css"/>
<link href="<?= Yii::getAlias('@themes'.'/css/responsive.css'); ?>" rel="stylesheet" type="text/css"/>
<link href="<?= Yii::getAlias('@themes'.'/css/custom-icon-set.css'); ?>" rel="stylesheet" type="text/css"/>
<!-- END CSS TEMPLATE -->
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="error-body no-top">
<div class="container">
  <div class="row login-container">
           <div class="margin text-center" style="margin-top: 50px;  margin-bottom: 25px;">
			<a href="#" target="_blank"><?= Html::img("@web/uploads/app_img/logo_login.png") ?></a>
			</div>

       <div class="col-md-5 col-md-offset-4" style="padding: 1% 8% 3% 8%;  background: white;  width: auto; border-radius:20px; margin-left:30%;">
	   <h2>Recovery Password</h2>  <br>
				<?php
				 if($flash = Yii::$app->session->getFlash('success')){
				echo Alert::widget(['options' => ['class' => 'alert-success'], 'body' => $flash,'closeButton'=>['label'=>'']]);
			}
			  if($flash = Yii::$app->session->getFlash('danger')){
				echo Alert::widget(['options' => ['class' => 'alert-danger'], 'body' => $flash,'closeButton'=>['label'=>'']]);
			} ?>

			<?php $form = ActiveForm::begin(['id' => 'login-forms']); ?> 		

		<div class="row">
		<div class="form-group col-md-12">
		<?= $form->field($model, 'vendor_contact_email', [
		'template' => "{label}<div class='controls'><div class='input-with-icon  right'><i class=''></i></div></div>{input}\n{hint}\n{error}"
		])->textInput(array('placeholder' => 'Enter your email id','class'=> 'form-control','id' => 'txtemail'));  ?>
        </div>
        <div class="col-md-5">
			<?= Html::submitButton('Recovery password',['class' => 'btn btn-lg btn-success btn-block', 'name' => 'login-button']) ?>
		</div>
		 <div class="col-md-5">
		 <?= Html::a('Cancel', ['site/login', ], ['class' => 'btn btn-danger']) ?>
		 </div>
        <?php ActiveForm::end(); ?>

 </div>
</div>
<!-- END CONTAINER -->
</body>
</html>
