<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Emailtemplate */
/* @var $form yii\widgets\ActiveForm */
?>

 <div class="col-md-8 col-sm-8 col-xs-8">	

    <?php $form = ActiveForm::begin(); ?>
    
    <div class="form-group">   
	<?= $form->field($model, 'email_title',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->textInput(['maxlength' => 100]) ?>
	</div>
	
	<div class="form-group">   
	<?= $form->field($model, 'email_subject',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->textInput(['maxlength' => 100]) ?>
	</div>
	
	<div class="form-group">   
	<?= $form->field($model, 'email_content',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->textArea(['id'=>'text-editor','maxlength' => 100]) ?>
	</div> 

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
		<?= Html::a('Back', ['index', ], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="<?php echo Yii::$app->themeURL->createAbsoluteUrl(''); ?>plugins/ckeditor/ckeditor.js" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->
<script type="text/javascript">
$(function()
{
	CKEDITOR.replace('text-editor');
});
</script>
