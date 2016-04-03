<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;
?>

	<div class="col-md-8 col-sm-8 col-xs-8">    

	<?= Html::csrfMetaTags() ?>
    <?php $form = ActiveForm::begin(); ?>
    
    <?= $form->field($model, 'category_id')->dropDownList($category, ['prompt'=>'Select...']); ?>
	<?php if($model->isNewRecord){ $subcategory=array();?>
    <?= $form->field($model, 'subcategory_id')->dropDownList($subcategory, ['prompt'=>'Select...']); ?>
     <?php } else {?> 
    <?= $form->field($model, 'subcategory_id')->dropDownList($subcategory, ['prompt'=>'Select...']); ?>
    <?php } ?>
     <?php if($model->isNewRecord){$vendoritem=array();?>
    <div class="form-group"> 
	    <?= $form->field($model, 'item_id',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->listbox($vendoritem,['id'=>'item_id','multiple'=>true]); ?> 
	</div>
    <?php } else {
		if($model->item_id)
		{$i=(explode(",",$model->item_id));
		$model->item_id = $i;}
		else {
			$model->item_id = 0;
			}?>
      <div class="form-group"> 
    <?= $form->field($model, 'item_id',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->listbox($priorityitem,['id'=>'item_id','multiple'=>true]);?>
	</div>
    <?php }?>

    <?= $form->field($model, 'priority_level')->dropDownList(['Normal' => 'Normal', 'Super' => 'Super', ], ['prompt' => 'Select...']) ?>

		<?php if($model->isNewRecord){?>
    <?= $form->field($model, 'priority_start_date')->textInput() ?>
		<?php }else { ?>
	<?= $form->field($model, 'priority_start_date')->textInput(['maxlenght' => 255, 'value' => date( 'd-m-Y', strtotime( $model->priority_start_date ) )]) ?>
		<?php } ?>
		
	<?php if($model->isNewRecord){?>
    <?= $form->field($model, 'priority_end_date')->textInput() ?>
		<?php }else { ?>
	<?= $form->field($model, 'priority_end_date')->textInput(['maxlenght' => 255, 'value' => date( 'd-m-Y', strtotime( $model->priority_end_date ) )]) ?>
		<?php }?>
	  <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('Back', ['index', ], ['class' => 'btn btn-defauult']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<script type="text/javascript">
$(function (){ 
    $("#priorityitem-vendor_id").change(function (){
		var csrfToken = $('meta[name="csrf-token"]').attr("content");
        var id = $('#priorityitem-vendor_id').val(); 
           
        var path = "<?php echo Url::to(['/admin/priorityitem/loadcategory']); ?> ";
        $.ajax({  
        type: 'POST',      
        url: path,
        data: { id: id ,_csrf : csrfToken}, 
        success: function( data ) {
             $('#priorityitem-category_id').html(data);
         }
        })

     });
 });
</script>

<script type="text/javascript">
$(function (){ 
    $("#priorityitem-category_id").change(function (){
		var csrfToken = $('meta[name="csrf-token"]').attr("content");
        var id = $('#priorityitem-category_id').val();
        var path = "<?php echo Url::to(['/vendor/priorityitem/loadsubcategory']); ?> ";
        $.ajax({  
        type: 'POST',      
        url: path, 
        data: { id: id ,_csrf : csrfToken}, 
        success: function( data ) {			
             $('#priorityitem-subcategory_id').html(data);
         }
        })

     });
 });
</script>

<script type="text/javascript">
$(function (){ 
    $("#priorityitem-subcategory_id").change(function (){
		var csrfToken = $('meta[name="csrf-token"]').attr("content");
        var category_id = $('#priorityitem-category_id').val();
        var subcategory_id = $('#priorityitem-subcategory_id').val();
        var path = "<?php echo Url::to(['/vendor/priorityitem/loaditems']); ?> ";
        $.ajax({  
        type: 'POST',      
        url: path, 
        data: {  category_id: category_id ,subcategory_id: subcategory_id ,_csrf : csrfToken}, //data to be send
        success: function( data ) {		
             $('#item_id').html(data);
         }
        })

     });
 });
</script>



<!-- BEGIN PLUGIN CSS -->
<link href="<?php echo Yii::$app->themeURL->createAbsoluteUrl(''); ?>plugins/bootstrap-datepicker/css/datepicker.css" rel="stylesheet" type="text/css" />
<link href="<?php echo Yii::$app->themeURL->createAbsoluteUrl(''); ?>plugins/bootstrap-select2/select2.css" rel="stylesheet" type="text/css" />
<!-- END PLUGIN CSS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="<?php echo Yii::$app->themeURL->createAbsoluteUrl(''); ?>plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
<script src="<?php echo Yii::$app->themeURL->createAbsoluteUrl(''); ?>plugins/bootstrap-select2/select2.min.js" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->

<script>
$('#priorityitem-priority_start_date').datepicker({  format: 'dd-mm-yyyy',});
$("#item_id").select2({
    placeholder: "Select Item.."
});

$('#priorityitem-priority_end_date').datepicker({  format: 'dd-mm-yyyy',});
</script>
