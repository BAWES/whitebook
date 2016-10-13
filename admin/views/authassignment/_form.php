<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Authassignment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="authassignment-form">
	<div class="col-md-8 col-sm-8 col-xs-8">	
    
    	<?php $form = ActiveForm::begin(); ?>

		<?= $form->field($model, 'item_name')->textInput(['maxlength' => 64]) ?>
	
		<?= $form->field($model, 'user_id')->textInput(['maxlength' => 64]) ?>
	
		<?= $form->field($model, 'created_datetime')->textInput() ?>
		
		<?= $form->field($model, 'modified_datetime')->textInput() ?>
		
		<div class="form-group">
		    <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>

		    <?=  Html::a('Back', ['index', ], ['class' => 'btn btn-default']) ?>
		</div>

    	<?php ActiveForm::end(); ?>

	</div>

</div>
