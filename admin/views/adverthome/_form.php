<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="col-md-8 col-sm-8 col-xs-8">	
    <?php $form = ActiveForm::begin(['options'=>['enctype'=>'multipart/form-data','id' => 'myform','name' => 'myform','onsubmit'=>'return check_validation();']]); ?>
  
	<?= $form->field($model, 'advert_code', [
			'options' => ['id' => 'advert_script']
		])
		->textArea([
			'rows' => 6
		]); ?>
  
    <div class="form-group">
     	<?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-primary']) ?>
		<?= Html::a('Back', ['site/index'], ['class' => 'btn btn-default']) ?>
    </div>
	
	<?php ActiveForm::end(); ?>

</div>

