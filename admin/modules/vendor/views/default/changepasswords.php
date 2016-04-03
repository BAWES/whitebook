<?php
use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm; 
use yii\bootstrap\Alert;

/* @var $this \yii\web\View */
/* @var $content string */

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = 'Change password';
$this->params['breadcrumbs'][] = $this->title;

AppAsset::register($this);
?>
<?= Html::csrfMetaTags() ?>
<?php $this->beginPage() ?>
<div class="col-md-8 col-sm-8 col-xs-8">    
 <div class="form-signin">       
      <div id="forgot" class="tab-pane">
            <?php $form = ActiveForm::begin(['id' => 'login-forms', 'action' => '/backend/web/vendor/default/changepassword']); ?>            
          
            <?= $form->field($model, 'old_password')->passwordInput(array('class'=> 'form-control bottom',)); ?>           
           
            <?= $form->field($model, 'new_password')->passwordInput(array('class'=> 'form-control bottom',));?>
            
            <?= $form->field($model, 'confirm_password')->passwordInput(array('class'=> 'form-control bottom',)); ?>
          
            <?= Html::submitButton('Submit', ['class' => 'btn btn-lg btn-danger ', 'name' => 'login-buttons']) ?>
            <?php ActiveForm::end(); ?>
        </div>		   
 </div>
