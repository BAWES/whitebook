<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Siteinfo */
/* @var $form yii\widgets\ActiveForm */
?>
 <div class="col-md-8 col-sm-8 col-xs-8">	
	 
	<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
	
	<div class="form-group">    
	<?= $form->field($model, 'home_slider_alias',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->textInput(['placeholder' => 'Enter slider alias','class'=> 'form-control']) ?>
	</div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
     <?= Html::a('Back', ['site/index'], ['class' => 'btn btn-default']) ?>
     </div>

    <?php ActiveForm::end(); ?>

</div>
