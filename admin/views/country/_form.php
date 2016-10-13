<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="col-md-8 col-sm-8 col-xs-8">

    <?php $form = ActiveForm::begin(); ?>  
    
    <?= $form->field($model, 'country_name')->textInput(['maxlength' => 100]) ?>
    
	<?= $form->field($model, 'iso_country_code')
            ->textInput(['maxlength' => 100])
            ->label('ISO Country Code', ['class'=> 'form-label-cap']) ?>
    
	<?= $form->field($model, 'currency_code')->textInput(['maxlength' => 100]) ?>
    
	<?= $form->field($model, 'currency_symbol')->textInput(['maxlength' => 100]) ?>
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('Back', ['index', ], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
