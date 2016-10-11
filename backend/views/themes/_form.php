<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Themes */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="themes-form">

	<div class="col-md-8 col-sm-8 col-xs-8">    
    
    <?php $form = ActiveForm::begin(); ?>
    
    <?= $form->field($model, 'theme_name')
            ->textInput(['maxlength' => 128]); ?>

	<?= $form->field($model, 'theme_status')
            ->dropDownList(['Active' => 'Active', 'Deactive' => 'Deactive'], ['prompt' => 'Select']); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
