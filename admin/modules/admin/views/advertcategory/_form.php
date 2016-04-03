<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
/* @var $this yii\web\View */
/* @var $model common\models\Advertcategory */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="loadingmessage" style="display: none;">
<p>
<?= Html::img(Yii::getAlias('@web/themes/default/img/loading.gif'), ['class'=>'','width'=>'64px','height'=>'64px','id'=>'loading','alt'=>'loading']);?> 
</p>
</div>
<?= Html::csrfMetaTags() ?>
<div class="col-md-8 col-sm-8 col-xs-8">	
    <?php $form = ActiveForm::begin(['options'=>['enctype'=>'multipart/form-data','id' => 'myform','name' => 'myform']]); ?>
    <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
    
    <div class="form-group">   
	<?= $form->field($model, 'category_id',['template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->listbox($category,['multiple'=>true,'size' => 7]); ?>
	</div>
	<div class="form-group">
	<?= $form->field($model, 'advert_position',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->textInput(['maxlength' => 128,'value'=>'Top', 'readonly' => 'true']) ?>
	</div>
	<div class="form-group"  id="advert_script">   
	<?= $form->field($model, 'advert_code',['template' => "{label}<div class='controls1'>{input}</div> {hint} {error}" 
	])->textArea(['rows' => 6]) ?> 
	</div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-primary']) ?>
        <?= Html::a('Back', ['index', ], ['class' => 'btn btn-default']) ?>
    </div>
 
<?php ActiveForm::end(); ?>
</div>

<link href="<?php echo Yii::$app->themeURL->createAbsoluteUrl(''); ?>plugins/bootstrap-select2/select2.css" rel="stylesheet" type="text/css" />
<script src="<?php echo Yii::$app->themeURL->createAbsoluteUrl(''); ?>plugins/bootstrap-select2/select2.min.js" type="text/javascript"></script>
<script type="text/javascript">

$("#advertcategory-category_id").select2({
    placeholder: "Choose category..",
});
	$('#position').hide();
</script>

