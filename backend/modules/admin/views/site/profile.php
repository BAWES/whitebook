<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Alert;

/* @var $this yii\web\View */
/* @var $model app\models\Users */
/* @var $form yii\widgets\ActiveForm */	
$this->title = 'My Profile';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-md-8 col-sm-8 col-xs-8">  
    <?php $form = ActiveForm::begin(); ?>
<div class="form-group"> 
    <?= $form->field($model, 'admin_name',['template' => "{label}<div class='controls'>{input}</div> {hint} {error}"])->textInput(['maxlength' => 50]) ?>
   </div>
    <?= $form->field($model, 'admin_email',['template' => "{label}<div class='controls'>{input}</div> {hint} {error}"])->textInput(['maxlength' => 50]) ?>    
    
    <?= $form->field($model, 'address',['template' => "{label}<div class='controls'>{input}</div> {hint} {error}"])->textInput(['maxlength' => 50]) ?>

    <?= $form->field($model, 'phone',['template' => "{label}<div class='controls'>{input}</div> {hint} {error}"])->textInput(['maxlength' => 50]) ?>    


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('Back', ['index', ], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
