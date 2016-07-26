<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model common\models\Featuregroupitem */
/* @var $form yii\widgets\ActiveForm */
?>
	<div class="col-md-8 col-sm-8 col-xs-8">
    <?php $form = ActiveForm::begin(); ?>
 <div class="form-group">
    <?= $form->field($model, 'group_id', [  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}"
	])->dropDownList($group, ['prompt'=>'Select...']); ?>
   </div>
    <div class="form-group">
    <?= $form->field($model, 'category_id',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}"
	])->dropDownList($category, ['prompt'=>'Select...']); ?>
	</div>

  <div class="form-group">
      <?php if($model->isNewRecord){?>
    <?= $form->field($model, 'subcategory_id',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}"
	])->dropDownList($subcategory, ['prompt'=>'Select...',]); ?>
	     <?php } else {

    <?= $form->field($model, 'subcategory_id',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}"
	])->dropDownList($subcategory_id, ['prompt'=>'Select...']); ?>
		<?php }?>
    </div>

    <?php if($model->isNewRecord){?>
    <div class="form-group">
	    <?= $form->field($model, 'item_id',['template' => "{label}<div class='controls'>{input}</div> {hint} {error}"
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
    <?= $form->field($model, 'item_id',[ 'template' => "{label}<div class='controls'>{input}</div> {hint} {error}"
	])->checkboxList($vendoritem);?>
	</div>
    <?php }?>
		<?php if($model->isNewRecord){?>
      <div class="form-group">
		<?= $form->field($model, 'featured_start_date',[ 'template' => "{label}<div class='controls'>{input}</div> {hint} {error}"
	])->textInput([['maxlenght' => 255,]]) ?>
		</div>
		<?php }else { ?>
		  <div class="form-group">
	<?= $form->field($model, 'featured_start_date',['template' => "{label}<div class='controls'>{input}</div> {hint} {error}"
	])->textInput(['maxlenght' => 255, 'value' => date( 'd-m-Y', strtotime( $model->featured_start_date ) )]) ?>
	</div>
		<?php }?>

	<?php if($model->isNewRecord){?>
      <div class="form-group">
		<?= $form->field($model, 'featured_end_date',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}"
	])->textInput() ?>
		</div>
		<?php }else { ?>
		  <div class="form-group">
	<?= $form->field($model, 'featured_end_date',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}"
	])->textInput(['maxlenght' => 255, 'value' => date( 'd-m-Y', strtotime( $model->featured_end_date ) )]) ?>
	</div>
		<?php }?>
  <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('Back', ['index', ], ['class' => 'btn btn-defauult']) ?>
		</div>
    <?php ActiveForm::end(); ?>
</div>

<?php 

$this->registerJs("

  var csrfToken = $('meta[name=\"csrf-token\"]').attr('content');

  $(function (){
   
    if($('input[class=check]:checked').length > 2)
    {
      alert('You can not able to select more than 20 categories');
      return false;
    }

    $('#featuregroupitem-category_id').change(function (){
        var id = $('#featuregroupitem-category_id').val();
        var path = '".Url::to(['/featuregroupitem/loadsubcategory'])."';
        
        $.ajax({
          type: 'POST',
          url: path, //url to be called
          data: { id: id ,_csrf : csrfToken}, //data to be send
          success: function( data ) {
               $('#featuregroupitem-subcategory_id').html(data);
          }
        });
    });
 
    $('#featuregroupitem-subcategory_id').change(function (){
        var id2 = $('#featuregroupitem-category_id').val();
        var id3 = $('#featuregroupitem-subcategory_id').val();
        var path = '".Url::to(['/featuregroupitem/loaditems'])."';

        $.ajax({
          type: 'POST',
          url: path, //url to be called
          data: { id2: id2 ,id3: id3 ,_csrf : csrfToken}, //data to be send
          success: function( data ) {
               $('#featuregroupitem-item_id').html(data);
          }
        });
    });
  });
");
	
$this->registerCssFile('@web/themes/default/plugins/bootstrap-datepicker/css/datepicker.css');

$this->registerJsFile("@web/themes/default/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js");

$this->registerJs("
  
  $('#featuregroupitem-featured_start_date').datepicker({  format: 'dd-mm-yyyy', startDate: 'today',});
  $('#featuregroupitem-featured_end_date').datepicker({  format: 'dd-mm-yyyy', startDate: 'today',});

  var countChecked = function() {
    var n = $('input:checked').length;
    
    if(n>20){
      alert('The limit is 20 for feature group item');
      return false;
    }
  };

  countChecked();

  $('input[type=checkbox]').live('click', countChecked);

");
