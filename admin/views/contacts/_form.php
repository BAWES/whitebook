<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="col-md-8 col-sm-8 col-xs-8">

    <?php $form = ActiveForm::begin(); ?>
    
    <?= $form->field($model, 'contact_name')->textInput(['maxlength' => 100]) ?>
    
	<?= $form->field($model, 'contact_email')->textInput(['maxlength' => 100]) ?>
    
	<?= $form->field($model, 'contact_phone')->textInput(['maxlength' => 100]) ?>
    
	<?= $form->field($model, 'subject')->textInput(['maxlength' => 100]) ?>
    
	<?= $form->field($model, 'message')->textarea() ?>
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
		<?= Html::a('Back', ['index', ], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
