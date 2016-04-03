<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Addresstype */
/* @var $form yii\widgets\ActiveForm */
?>

<?= Html::csrfMetaTags() ?>
<div class="addresstype-form">
    <div class="col-md-8 col-sm-8 col-xs-8">    
    <?php $form = ActiveForm::begin(); ?>

<div class="form-group">
	<?= $form->field($model, 'type_name',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"])->textInput(['maxlength' => 128])?>
</div>    

<div class="form-group">   
	<?= $form->field($model, 'status',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->checkbox(['label'=>'Address type status','Active' => 'Active']) ?>
    </div>
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?=  Html::a('Back', ['index', ], ['class' => 'btn btn-default']) ?>
    </div>
    
    
    </div>
    <?php ActiveForm::end(); ?>

</div>

<script>
	<?php if($model->isNewRecord){ ?>
	$('#addresstype-status').prop('checked', true);
	<?php }
	else
	{ if($model->status=='Active'){?>
	$('#addresstype-status').prop('checked', true);	
		<?php }	else { ?>
	$('#addresstype-status').prop('checked', false);		
			<?php } ?>
	<?php } ?>
	
</script>

