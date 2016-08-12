<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model common\models\City */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="col-md-8 col-sm-8 col-xs-8">

    <?php $form = ActiveForm::begin(); ?>  
    
    <div class="form-group">   
	<?= $form->field($model, 'country_id',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->dropDownList($country, ['prompt'=>'Select...']); ?>
    </div>
    
    <div class="form-group">   
	<?= $form->field($model, 'city_name',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->textInput(['maxlength' => 100]) ?>
    </div>    
    
    <div class="form-group">   
    <?= $form->field($model, 'city_name_ar',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
    ])->textInput(['maxlength' => 100]) ?>
    </div>   

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('Back', ['index', ], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
