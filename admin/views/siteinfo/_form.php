<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use admin\models\Admin;

?>

<div class="col-md-8 col-sm-8 col-xs-8">	
	 
	<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
	
	<?= $form->field($model, 'home_slider_alias')->textInput([
			'placeholder' => 'Enter slider alias',
			'class'=> 'form-control'
		]); ?>
	
	<?= $form->field($model, 'super_admin_role_id')->dropDownList(Admin::roles()); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    	<?= Html::a('Back', ['site/index'], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
