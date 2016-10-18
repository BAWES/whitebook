<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="vendoritemquestionguide-form">
	<div class="col-md-8 col-sm-8 col-xs-8">	
    
    <?php $form = ActiveForm::begin(['options'=>['enctype'=>'multipart/form-data']]); ?>
    
    <?= $form->field($model, 'question_id')->dropDownList($questions, ['prompt'=>'Select...']); ?>
	
	<?= $form->field($model, 'guide_image_id')->fileInput() ?>  
	
    <?= $form->field($model, 'guide_caption')->textarea() ?>
	  
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

	</div>
</div>
