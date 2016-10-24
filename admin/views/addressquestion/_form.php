<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;

?>

<div class="address-question-form">
    <div class="col-md-8 col-sm-8 col-xs-8">    
        
        <?php $form = ActiveForm::begin(); ?>
        
        <?= $form->field($model, 'address_type_id')->dropDownList($addresstype) ?>
        
        <?= $form->field($model, 'question'); ?> 
    
        <?= $form->field($model, 'question_ar')->label('Question - Arabic'); ?>

        <?= $form->field($model, 'status')->dropDownList(['Active' => 'Active', 'Deactive' => 'Deactive']); ?>

        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>

            <?=  Html::a('Back', ['index', ], ['class' => 'btn btn-default']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>    
</div>


