<?php 
use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model backend\models\Featuregroupitem */
/* @var $form yii\widgets\ActiveForm */
?>
	<div class="col-md-8 col-sm-8 col-xs-8">    
    <?php $form = ActiveForm::begin(); ?>
	<?php if($model->isNewRecord){?>
	  <div class="form-group"> 
	<?= $form->field($model, 'theme_id',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->dropDownList($themelist,['prompt'=>'Select...']); ?>
	</div>
	<?php } else {?>
		  <div class="form-group"> 
		<?= $form->field($model, 'theme_id',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->dropDownList($themelist,['prompt'=>'Select...']);?>
	</div>
		<?php } ?>
      <div class="form-group"> 
    <?= $form->field($model, 'category_id',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->dropDownList($category_id, ['prompt'=>'Select...']); ?>
	</div>

  <div class="form-group"> 
      <?php if($model->isNewRecord){?>
    <?= $form->field($model, 'subcategory_id',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->dropDownList($subcategory_id, ['prompt'=>'Select...']); ?>
	     <?php } else { ?>
	    
    <?= $form->field($model, 'subcategory_id',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->dropDownList($subcategory_id, ['prompt'=>'Select...']); ?>
		<?php }?>
    </div> 

    <?php if($model->isNewRecord){?>
    <div class="form-group"> 
	    <?= $form->field($model, 'item_id[]',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->checkboxList($vendoritem); ?> 
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
	])->checkboxList($vendoritem);?>
	</div>
    <?php }?>
		<?php if($model->isNewRecord){?>
      <div class="form-group"> 
		<?= $form->field($model, 'theme_start_date',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->textInput() ?>
		</div>
		<?php }else { ?>
		  <div class="form-group"> 
	<?= $form->field($model, 'theme_start_date',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->textInput(['maxlenght' => 255, 'value' => date( 'd-m-Y', strtotime( $model->theme_start_date ) )]) ?>
	</div>
		<?php }?>
		
	<?php if($model->isNewRecord){?>
      <div class="form-group"> 
		<?= $form->field($model, 'theme_end_date',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->textInput() ?>
		</div>
		<?php }else { ?>
		  <div class="form-group"> 
	<?= $form->field($model, 'theme_end_date',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->textInput(['maxlenght' => 255, 'value' => date( 'd-m-Y', strtotime( $model->theme_end_date ) )]) ?>
	</div>
		<?php }?>
      <div class="form-group"> 
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('Back', ['index', ], ['class' => 'btn btn-defauult']) ?>
		</div>
    <?php ActiveForm::end(); ?>
</div>

<script type="text/javascript">
$(function (){ 
    $("#vendoritemthemes-category_id").change(function (){
		var csrfToken = $('meta[name="csrf-token"]').attr("content");
        var id = $('#vendoritemthemes-category_id').val();
        var path = "<?php echo Url::to(['/admin/featuregroupitem/loadsubcategory']); ?> ";
        $.ajax({
        type: 'POST',      
        url: path, //url to be called
        data: { id: id ,_csrf : csrfToken}, //data to be send
        success: function( data ) {			
             $('#vendoritemthemes-subcategory_id').html(data);
         }
        })
     });
 });
</script>

<script type="text/javascript">
$(function (){ 
    $("#vendoritemthemes-subcategory_id").change(function (){
		var csrfToken = $('meta[name="csrf-token"]').attr("content");
        var id2 = $('#vendoritemthemes-category_id').val();
        var id3 = $('#vendoritemthemes-subcategory_id').val();
        var path = "<?php echo Url::to(['/admin/themeitem/loaditems']); ?> ";
        $.ajax({  
        type: 'POST',      
        url: path, //url to be called
        data: { id2: id2 ,id3: id3 ,_csrf : csrfToken}, //data to be send
        success: function( data ) {
             $('#vendoritemthemes-item_id').html(data);
         }
        })
     });
 });
</script>

<!-- BEGIN PLUGIN CSS -->
<link href="<?php echo Yii::$app->themeURL->createAbsoluteUrl(''); ?>plugins/bootstrap-datepicker/css/datepicker.css" rel="stylesheet" type="text/css" />
<!-- END PLUGIN CSS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="<?php echo Yii::$app->themeURL->createAbsoluteUrl(''); ?>plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->
<script>
$('#vendoritemthemes-theme_start_date').datepicker({  format: 'dd-mm-yyyy', startDate: 'today',});
$('#vendoritemthemes-theme_end_date').datepicker({  format: 'dd-mm-yyyy', startDate: 'today',});
</script>

<script>
var countChecked = function() {
  var n = $( "input:checked" ).length;
  if(n>20){
  alert('The limit is 20 for feature group item');
  return false;}
};
countChecked();
$( "input[type=checkbox]" ).live( "click", countChecked );
</script>
