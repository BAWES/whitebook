<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Change Password';
$this->params['breadcrumbs'][] = ['label' => 'Manage vendor', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="role-create">

    <div class="col-md-8 col-sm-8 col-xs-8">	

	    <?php $form = ActiveForm::begin(); ?>
	    
	    <div class="form-group">   
			<?= $form->field($model, 'vendor_name')->textInput(['readonly' => 'true']); ?>   
		</div>

		<div class="form-group">   
			<?= $form->field($model, 'vendor_password'); ?>   
		</div>    

	    <div class="form-group">
	        <?= Html::submitButton('Update', ['class' => 'btn btn-primary']) ?>
	        <?= Html::a('Back', ['index'], ['class' => 'btn btn-default']) ?>
	    </div>

	    <?php ActiveForm::end(); ?>

	</div>

</div>
