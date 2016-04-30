<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use dosamigos\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $model common\models\Cms */
/* @var $form yii\widgets\ActiveForm */
?>

<?php

$this->registerJs(
   '$("document").ready(function(){
        $("w0-filters").on("pjax:end", function() {
            $.pjax.reload({container:"#countries"});  //Reload GridView
        });
    });'
);
?>
 <div class="col-md-8 col-sm-8 col-xs-8">
    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true ]]); ?>
    <div class="form-group">
	<?= $form->field($model, 'page_name',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}"
	])->textInput(['maxlength' => 100]) ?>
	</div>

	<div class="form-group">
	<?= $form->field($model, 'cms_meta_title',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"])->textArea(['maxlength' => 250])?>
	</div>

    <div class="form-group">
	<?= $form->field($model, 'cms_meta_keywords',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"])->textArea(['maxlength' => 250])?>
	</div>

    <div class="form-group">
	<?= $form->field($model, 'cms_meta_description',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"])->textArea(['maxlength' => 250])?>
	</div>

	<div class="form-group">
	<?= $form->field($model, 'page_content',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}"
	])->textArea(['id'=>'text-editor']) ?>
	</div>


     <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('Back', ['index', ], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="<?= Url::to("@web/themes/default/plugins/ckeditor/ckeditor.js") ?>" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->
<script type="text/javascript">
$(function()
{
	CKEDITOR.replace('text-editor');
});
</script>
