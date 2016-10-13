<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\ckeditor\CKEditor;

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
    
    <?= $form->field($model, 'page_name')->textInput(['maxlength' => 100]) ?>
	
	<?= $form->field($model, 'page_name_ar')->textInput(['maxlength' => 100]) ?>
	
	<?= $form->field($model, 'cms_meta_title')->textArea(['maxlength' => 250])?>
	
	<?= $form->field($model, 'cms_meta_keywords')->textArea(['maxlength' => 250])?>
	
	<?= $form->field($model, 'cms_meta_description')->textArea(['maxlength' => 250])?>
	
	<?= $form->field($model, 'page_content')->textArea(['id'=>'text-editor']) ?>
	
	<?= $form->field($model, 'page_content_ar')->textArea(['id'=>'text-editor-ar']) ?>
	
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('Back', ['index', ], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<?php 

$this->registerJsFile("@web/themes/default/plugins/ckeditor/ckeditor.js", ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerJs("
	$(function() {
		CKEDITOR.replace('text-editor');
		CKEDITOR.replace('text-editor-ar');
	});
");
