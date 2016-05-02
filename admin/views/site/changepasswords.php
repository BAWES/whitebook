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

$this->title = 'Change Password';
$this->params['breadcrumbs'][] = $this->title;

AppAsset::register($this);
?>
<?= Html::csrfMetaTags() ?>
<?php $this->beginPage() ?>
 <div class="form-signin">          
      
<div class="col-md-8 col-sm-8 col-xs-8"> 
      <div id="forgot" class="tab-pane">
            <?php $form = ActiveForm::begin(['id' => 'login-forms']); ?>            
          
            <?= $form->field($model, 'old_password')->passwordInput(array('class'=> 'form-control bottom',)); ?>           
           
            <?= $form->field($model, 'new_password')->passwordInput(array('class'=> 'form-control bottom',));?>
            
            <?= $form->field($model, 'confirm_password')->passwordInput(array('class'=> 'form-control bottom',)); ?>
          
            <?= Html::submitButton('Submit', ['class' => 'btn btn-lg btn-danger ', 'name' => 'login-buttons']) ?>
            <?= Html::a('Back', ['index', ], ['class' => 'btn btn-default']) ?>
            <?php ActiveForm::end(); ?>
        </div>		   
 </div>
