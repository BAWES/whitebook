<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Alert;
use backend\models\Siteinfo;
use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;

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
<link href="<?php echo Yii::$app->themeURL->createAbsoluteUrl(''); ?>plugins/pace/pace-theme-flash.css" rel="stylesheet" type="text/css" media="screen"/>
<link href="<?php echo Yii::$app->themeURL->createAbsoluteUrl(''); ?>plugins/boostrapv3/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo Yii::$app->themeURL->createAbsoluteUrl(''); ?>plugins/boostrapv3/css/bootstrap-theme.min.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo Yii::$app->themeURL->createAbsoluteUrl(''); ?>plugins/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo Yii::$app->themeURL->createAbsoluteUrl(''); ?>css/animate.min.css" rel="stylesheet" type="text/css"/>
<!-- END CORE CSS FRAMEWORK -->
<!-- BEGIN CSS TEMPLATE -->
<link href="<?php echo Yii::$app->themeURL->createAbsoluteUrl(''); ?>css/style.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo Yii::$app->themeURL->createAbsoluteUrl(''); ?>css/responsive.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo Yii::$app->themeURL->createAbsoluteUrl(''); ?>css/custom-icon-set.css" rel="stylesheet" type="text/css"/>
<!-- END CSS TEMPLATE -->
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="error-body no-top">
<div class="container">
   <div class="row login-container">
	   <div class="margin text-center" style="margin-top: 50px;  margin-bottom: 25px;">
			<a href="#" target="_blank"><img src="/backend/web/uploads/app_img/logo_login.png" class="logo" alt="" data-src="/backend/web/uploads/app_img/logo_login.png" data-src-retina="/backend/web/uploads/app_img/logo_login.png" width="" height=""/></a>
			</div>  
       <div class="col-md-5 col-md-offset-4" style="padding: 1% 8% 3% 8%;  background: white;  width: 40%; border-radius:20px; margin-left:30%;">			
			<h2>Vendor Sign In</h2> <br>    
			<?php $form = ActiveForm::begin(['id' => 'login-form']); ?>   
			 <div class="row">
			 <div class="form-group col-md-12" id="login">         
             <?= $form->field($model, 'vendor_contact_email')->textInput(array('class'=> 'form-control top', 'placeholder'=>'Email')); ?>
             <?= $form->field($model, 'vendor_password')->passwordInput(array('class'=> 'form-control bottom', 'placeholder'=>'password')); ?>           
             </div>
             </div>
		     <div class="row">
             <div class="control-group  col-md-5" style="width: 100%;">
             <div class="checkbox checkbox check-success">
			 <?= Html::a('Forgot Password', ['/vendor/default/recoverypassword'], ['class'=>'link-title']) ?>&nbsp;&nbsp; 
             <?= $form->field($model, 'rememberMe')->checkbox(array('label'=>'rememberMe')); ?>
             </div>

            <?php if($flash = Yii::$app->session->getFlash('danger'))
          echo Alert::widget(['options' => ['class' => 'alert-danger'], 'body' => $flash,'closeButton'=>['label'=>'']]);?>
			 </div>
			<div class="col-md-5" style="clear: both;  text-align: center;  float: right;">		             
             <?= Html::submitButton('Login', ['class' => 'btn btn-primary btn-cons pull-right', 'name' => 'login-button']) ?> 
             <?php ActiveForm::end(); ?>                   
            </div>
         </div>          
       </form>
     </div>   
   </div>
</div>
<!-- END CONTAINER -->
<!-- BEGIN CORE JS FRAMEWORK-->
<script src="<?php echo Yii::$app->themeURL->createAbsoluteUrl(''); ?>plugins/jquery-1.8.3.min.js" type="text/javascript"></script>
<script src="<?php echo Yii::$app->themeURL->createAbsoluteUrl(''); ?>plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="<?php echo Yii::$app->themeURL->createAbsoluteUrl(''); ?>plugins/pace/pace.min.js" type="text/javascript"></script>
<script src="<?php echo Yii::$app->themeURL->createAbsoluteUrl(''); ?>plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
<script src="<?php echo Yii::$app->themeURL->createAbsoluteUrl(''); ?>js/login.js" type="text/javascript"></script>
<!-- BEGIN CORE TEMPLATE JS -->
<!-- END CORE TEMPLATE JS -->
</body>
</html>
