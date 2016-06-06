<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Category;
use common\models\SubCategory;
use common\models\CategorySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model common\models\Category */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="category-form">
	<div class="col-md-8 col-sm-8 col-xs-8">    
	<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
<div class="form-group">

<?php if($model->isNewRecord){?>
<?= $form->field($model, 'parent_category_id',['template' => "{label}<div class='controls'>{input}</div>{hint}
{error}"])->dropDownList($category, ['prompt'=>'Select...']) ?></div>

<?php } else  { $model->parent_category_id=$parentid; ?>
<?= $form->field($model, 'parent_category_id',['template' => "{label}<div class='controls'>{input}</div>{hint}
{error}"])->dropDownList($parentcategory, ['prompt'=>'Select...']) ?></div>
<?php } ?>

<?php if($model->isNewRecord){?>
<div class="form-group"><?= $form->field($model, 'subcategory_id',['template' => "{label}<div class='controls'>{input}</div>{hint}
{error}"])->dropDownList(['prompt'=>'Select...']) ?></div>
<?php } else if(!$model->isNewRecord) { $model->subcategory_id=$subcategory_id;?>

<div class="form-group"><?= $form->field($model, 'subcategory_id',['template' => "{label}<div class='controls'>{input}</div>{hint}
{error}"])->dropDownList($subcategory,['prompt'=>'Select...']) ?></div>
<?php } ?>



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
    
    <div class="form-group"><br/>
		<?= $form->field($model, 'top_ad',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"])->textArea(['maxlength' => 250])?>
	</div>

    <div class="form-group">
		<?= $form->field($model, 'bottom_ad',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"])->textArea(['maxlength' => 250])?>
	</div>

    

   <div class="form-group" style="margin-top:10px;">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('Back', ['child_category_index', ], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


<script type="text/javascript">
$(function (){ 
    $("#childcategory-parent_category_id").change(function (){
		var csrfToken = $('meta[name="csrf-token"]').attr("content");
        var id = $('#childcategory-parent_category_id').val();
        var path = "<?php echo Url::to(['/category/loadsubcategory']); ?> ";
        $.ajax({
        type: 'POST',      
        url: path, //url to be called
        data: { id: id ,_csrf : csrfToken}, //data to be send
        success: function( data ) {			
             $('#childcategory-subcategory_id').html(data);
         }
        })

     });
 });
</script>

<script>
	<?php if($model->isNewRecord){ ?>
	$('#childcategory-category_allow_sale').prop('checked', true);
	<?php }
	else
	{ if($model->category_allow_sale=='yes'){?>
	$('#childcategory-category_allow_sale').prop('checked', true);	
		<?php }	else { ?>
	$('#childcategory-category_allow_sale').prop('checked', false);		
			<?php } ?>
	<?php } ?>
	
</script>
