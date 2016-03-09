<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use backend\models\Category;
use backend\models\SubCategory;
use backend\models\CategorySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model backend\models\Category */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="category-form">
	<div class="col-md-8 col-sm-8 col-xs-8">    
    
	<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
	
    
<div class="form-group"><?= $form->field($model, 'parent_category_id',['template' => "{label}<div class='controls'>{input}</div>{hint}
{error}"])->dropDownList($subcategory, ['prompt'=>'Select...']) ?></div>

<div class="form-group">
	<?= $form->field($model, 'category_name',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"])->textInput(['maxlength' => 128])?>
</div> 

<div class="form-group">
	<?= $form->field($model, 'category_meta_title',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"])->textArea(['maxlength' => 250])?>
</div> 
    <div class="form-group">
	<?= $form->field($model, 'category_meta_keywords',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"])->textArea(['maxlength' => 250])?>
</div> 
    <div class="form-group">
	<?= $form->field($model, 'category_meta_description',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"])->textArea(['maxlength' => 250])?>
</div>
	
	<div class="form-group">   
	<?= $form->field($model, 'category_allow_sale',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->checkbox(['yes' => 'yes']) ?>
    </div>    
    
    <div class="form-group">
    <?= $form->field($model, 'subcategory_icon',['template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 	
    	])->fileInput()->hint('Icon Size 240 * 50') ?>
    </div> 

    <?php if(@$model->category_id) { 
		if(file_exists($_SERVER['DOCUMENT_ROOT'].'/backend/web/uploads/subcategory_icon/sub_category_'.$model->category_id.'.png')) { 
		?>
<?= Html::img(Yii::getAlias('@web/uploads/subcategory_icon/').'sub_category_'.$model->category_id.'.png', ['alt'=>'some', 'class'=>'thing','width'=>'100px','height'=>'100px']); } } ?> 
    
    <div class="form-group"><br>
		<?= $form->field($model, 'top_ad',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"])->textArea(['maxlength' => 250])?>
	</div>

    <div class="form-group">
		<?= $form->field($model, 'bottom_ad',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"])->textArea(['maxlength' => 250])?>
	</div>

    <div class="form-group" style="margin-top:10px;">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('Back', ['manage_subcategory', ], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script>
	<?php if($model->isNewRecord){ ?>
	$('#subcategory-category_allow_sale').prop('checked', true);
	<?php }
	else
	{ if($model->category_allow_sale=='yes'){?>
	$('#subcategory-category_allow_sale').prop('checked', true);	
		<?php }	else { ?>
	$('#subcategory-category_allow_sale').prop('checked', false);		
			<?php } ?>
	<?php } ?>
	
</script>
