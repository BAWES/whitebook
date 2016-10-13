<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

 <div class="col-md-8 col-sm-8 col-xs-8">	

    <?php $form = ActiveForm::begin(); ?>
    
    <?= $form->field($model, 'role_id')->dropDownList($role,['prompt'=>'Select...']); ?>

	<?= $form->field($model, 'admin_name')->textInput(['maxlength' => 100]) ?>
	
	<?= $form->field($model, 'admin_email')->textInput(['maxlength' => 100]) ?>
	
	<?php if($model->isNewRecord) {?>
		 
		<?= $form->field($model, 'admin_password')->PasswordInput(['maxlength' => 100]) ?>
	
	<?php } ?>
		
	<?= $form->field($model, 'address')->textArea(['maxlength' => 100]) ?>

	<?= $form->field($model, 'phone')->textInput(['maxlength' => 100]) ?>

	<?= $form->field($model, 'admin_status')->dropDownList(['Active' => 'Active', 'Deactive' => 'Deactive']) ?>
	
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('Back', ['index', ], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
