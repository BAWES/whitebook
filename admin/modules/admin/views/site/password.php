<?php
use backend\assets\AppAsset;
use yii\bootstrap\ActiveForm; 
use yii\helpers\Html;
/* @var $this \yii\web\View */
/* @var $content string */

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;

AppAsset::register($this);
?>
<?= Html::csrfMetaTags() ?> 
<?php $this->beginPage() ?>
 <div class="form-signin">
      <div class="text-center">
        <img src="<?php echo Yii::$app->urlManagerBackEnd->createAbsoluteUrl(''); ?>theme/master/dist/assets/img/logo.png" alt="Metis Logo">
      </div>
      <hr>
		<div id="forgot" class="tab-pane">
            <?php $form = ActiveForm::begin(['id' => 'login-form', 'action'=>'/backend/web/site/password']); ?>
            <p class="text-muted text-center">Enter your Old Password</p>
            <?= $form->field($model, 'email')->textInput(array('class'=> 'form-control top', 'placeholder'=>'Password')); ?>
            <br>  
            <?= Html::submitButton('Submit', ['class' => 'btn btn-lg btn-primary btn-block', 'name' => 'login-button']) ?> 
            <?php ActiveForm::end(); ?>      
        </div>
        <div class="text-center">
        <ul class="list-inline">
          <li> <?= Html::a('Login', ['/site/login'], ['class'=>'link-title']) ?>  </li>
        </ul>
      </div>
 </div>
