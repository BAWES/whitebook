<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Socialinfo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="col-md-8 col-sm-8 col-xs-8">

    <?php $form = ActiveForm::begin(); ?>  
    
    <div class="form-group">   
	<?= $form->field($model, 'store_facebook_share',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->textarea(['maxlength' => 100]) ?>
    </div>
    
    <div class="form-group">   
	<?= $form->field($model, 'store_twitter_share',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->textarea(['maxlength' => 100]) ?>
    </div>
    
    <div class="form-group">   
	<?= $form->field($model, 'store_google_share',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->textarea(['maxlength' => 100]) ?>
    </div>
    
    <div class="form-group">   
	<?= $form->field($model, 'store_linkedin_share',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->textarea(['maxlength' => 100]) ?>
    </div>

	<div class="form-group">   
	<?= $form->field($model, 'google_analytics',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->textarea() ?>
    </div>
    
    <div class="form-group">   
	<?= $form->field($model, 'live_script',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->textarea() ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('Back', ['site/index', ], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
