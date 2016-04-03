<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Vendoritemquestionguide */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="vendoritemquestionguide-form">
	<div class="col-md-8 col-sm-8 col-xs-8">	
    <?php $form = ActiveForm::begin(['options'=>['enctype'=>'multipart/form-data']]); ?>
    
    <div class="form-group"> 
    <?= $form->field($model, 'question_id',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->dropDownList($questions, ['prompt'=>'Select...']); ?>
	</div>
	
	<div class="form-group">
	<?= $form->field($model, 'guide_image_id',['template' => "{label}<div class='controls append_address'>{input}</div> {hint} {error}" 
	])->fileInput() ?>  
	</div>
	
	<div class="form-group"> 
   <?= $form->field($model, 'guide_caption',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"
	])->textarea() ?>
	</div>
      
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
</div>
