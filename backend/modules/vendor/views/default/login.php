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

?>

<div class="row login-container">
    <div class="margin text-center" style="margin-top: 50px;  margin-bottom: 25px;">
            <?= Html::img("@web/uploads/app_img/logo_login.png") ?>
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

            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
