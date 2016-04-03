<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Featuregroup */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="featuregroup-form">

	<div class="col-md-8 col-sm-8 col-xs-8">    
    <?php $form = ActiveForm::begin(); ?>
<div class="form-group">
	<?= $form->field($model, 'group_name',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"])->textInput(['maxlength' => 128])?>
</div> 
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('Back', ['index', ], ['class' => 'btn btn-defauult']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
