<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<?= Html::csrfMetaTags() ?>

<div class="addresstype-form">
    <div class="col-md-8 col-sm-8 col-xs-8">    

    <?php $form = ActiveForm::begin(); ?>

	<?= $form->field($model, 'type_name')->textInput(['maxlength' => 128]); ?>

	<?= $form->field($model, 'status')->checkbox(['label'=>'Address type status','Active' => 'Active']) ?>
	
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?=  Html::a('Back', ['index', ], ['class' => 'btn btn-default']) ?>
    </div>
    
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php 

if($model->isNewRecord){ 
	
	$this->registerJs("
		$('#AddressType-status').prop('checked', true);
	");

} elseif ($model->status=='Active'){ 
			
	$this->registerJs("
		$('#AddressType-status').prop('checked', true);
	");

} else { 
	
	$this->registerJs("	
		$('#AddressType-status').prop('checked', false);
	");
} 

