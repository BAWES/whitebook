<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Vendor */
/* @var $form yii\widgets\ActiveForm */
?>
 <div class="col-md-8 col-sm-8 col-xs-8">
<?php $form = ActiveForm::begin();?>	

	<div class="form-group">
	<?= $form->field($model, 'vendor_contact_no',['template' => "{label}<div class='controls append_text'>{input}</div> {hint} {error}" 
	])->textInput(['maxlength' => 128])?>
	<div class="form-group">
	<?= $form->field($model, 'area_id',['template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->dropDownlist($area,['prompt'=>'Select Area'])?>
	</div>
	<div class="form-group">
	<?= $form->field($model, 'address_text',['template' => "{label}<div class='controls append_address'>{input}</div> {hint} {error}" 
	])->textArea()?>
	</div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('Back', ['index', ], ['class' => 'btn btn-default']) ?>
    </div>
    
    <?php ActiveForm::end(); ?>
</div>
