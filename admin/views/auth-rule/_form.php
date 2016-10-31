<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\AuthRule */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="authrule-form">
	<div class="col-md-8 col-sm-8 col-xs-8">	

    <?php $form = ActiveForm::begin(); ?>

		<div class="form-group">   
			<?= $form->field($model, 'name',[
					'template' => "{label}<div class='controls'>{input}</div>{hint}{error}"
				])->textInput(['maxlength' => 64]) ?>
		</div>

		<div class="form-group">    
			<?= $form->field($model, 'data',[
					'template' => "{label}<div class='controls'>{input}</div>{hint}{error}"
				])->textarea(['rows' => 6,'placeholder' => 'Enter data','class'=> 'form-control']) ?>
		</div>	

	    <div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	        <?=  Html::a('Back', ['index', ], ['class' => 'btn btn-default']) ?>
	    </div>

    <?php ActiveForm::end(); ?>
    
	</div>

</div>
