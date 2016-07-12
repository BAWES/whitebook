<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model common\models\Featuregroupitem */
/* @var $form yii\widgets\ActiveForm */
?>
<?= Html::csrfMetaTags() ?>

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
	<?php if($model->isNewRecord){ $subcategory=array(); ?>
  	<div class="form-group">
	    <?= $form->field($model, 'subcategory_id',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}"
		])->dropDownList($subcategory, ['prompt'=>'Select...']); ?>
    </div>
    <?php } else {?>
    <div class="form-group">
	    <?= $form->field($model, 'subcategory_id',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}"
		])->dropDownList($subcategory, ['prompt'=>'Select...']); ?>
    </div>
    <?php } ?>
    
    <?php if($model->isNewRecord){$vendoritem=array();?>
    <div class="form-group">
	    <?= $form->field($model, 'item_id',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}"
	])->listbox($vendoritem,['id'=>'item_id','multiple'=>true]); ?>
	</div>
    <?php } else {
		if($model->item_id)
		{
			$i=(explode(",",$model->item_id));
			$model->item_id = $i;
		} else {
			$model->item_id = 0;
		}
	?>    
    <div class="form-group">
	    <?= $form->field($model, 'item_id',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}"
		])->listbox($featuregroupitem,['id'=>'item_id','multiple'=>true]);?>
	</div>
    <?php } ?>
		
	<?php if($model->isNewRecord){ ?>
    <div class="form-group">
		<?= $form->field($model, 'featured_start_date',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}"
	])->textInput() ?>
	</div>
	<?php } else { ?>
	<div class="form-group">
		<?= $form->field($model, 'featured_start_date',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}"
		])->textInput(['maxlenght' => 255, 'value' => date( 'd-m-Y', strtotime( $model->featured_start_date ) )]) ?>
	</div>
	<?php } ?>

	<?php if($model->isNewRecord){?>
    <div class="form-group">
		<?= $form->field($model, 'featured_end_date',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}"
	])->textInput() ?>
	</div>
	<?php } else { ?>
	<div class="form-group">
		<?= $form->field($model, 'featured_end_date',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}"
		])->textInput(['maxlenght' => 255, 'value' => date( 'd-m-Y', strtotime( $model->featured_end_date ) )]) ?>
	</div>
	<?php }?>
	
	<div class="form-group">
	    <?= $form->field($model, 'featured_sort',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}"
		])->textInput() ?>
	</div>
	  
	<div class="form-group">
	    <?= $form->field($model, 'group_item_status',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}"
		])->dropDownList([ 'Active' => 'Active', 'Deactive' => 'Deactive', ], ['prompt' => 'Selet']) ?>
	</div>

	<?php if($model->isNewRecord){?>
	<div class="form-group">
		<?= $form->field($model, 'themelist',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}"
		])->listbox($themelist,['id'=>'themelist','multiple' => true]); ?>
	</div>
	<?php } else {
		$themeid = array('0'=>$themeid);
		$i = (explode(",",$themeid[0]));
		$model->themelist=$i;?>
	<div class="form-group">
		<?= $form->field($model, 'themelist',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}"
	])->listbox($themelist,['id'=>'themelist','multiple'=>true]);?>
	</div>
	<?php } ?>
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('Back', ['index', ], ['class' => 'btn btn-defauult']) ?>
	</div>

    <?php ActiveForm::end(); ?>
</div>

<?php 

$this->registerCss('@web/themes/default/plugins/bootstrap-datepicker/css/datepicker.css');

$this->registerCss('@web/themes/default/plugins/bootstrap-select2/select2.css');

$this->registerJs("
    $('#featuregroupitem-category_id').change(function (){
		var csrfToken = $('meta[name=\"csrf-token\"]').attr('content');
        var id = $('#featuregroupitem-category_id').val();
        var path = '".."';
        
        $.ajax({
	        type: 'POST',
	        url: path,
	        data: { id: id ,_csrf : csrfToken},
	        success: function( data ) {
	             $('#featuregroupitem-subcategory_id').html(data);
	        }
        });
    });

    $('#featuregroupitem-subcategory_id').change(function (){
		var csrfToken = $('meta[name=\"csrf-token\"]').attr('content');
        var category_id = $('#featuregroupitem-category_id').val();
        var subcategory_id = $('#featuregroupitem-subcategory_id').val();
        var path = '".Url::to(['featuregroupitem/loaditems'])."';
        
        $.ajax({
	        type: 'POST',
	        url: path, //url to be called
	        data: { category_id: category_id ,subcategory_id: subcategory_id ,_csrf : csrfToken},
	        success: function( data ) {
	             $('#item_id').html(data);
	        }
        });
     });
");

$this->registerJsFile('@web/themes/default/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerJsFile('@web/themes/default/plugins/bootstrap-select2/select2.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerJs("

	$('#featuregroupitem-featured_start_date').datepicker({ format: 'dd-mm-yyyy' });
	$('#featuregroupitem-featured_end_date').datepicker({ format: 'dd-mm-yyyy' });

	$('#item_id').select2({
	    placeholder: 'Select Item..'
	});

	$('#themelist').select2({
	    placeholder: 'Select Themes..'
	});

");