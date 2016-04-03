<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Vendoritemquestion */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="vendor item question-form">
	<div class="col-md-8 col-sm-8 col-xs-8">	
    <?php $form = ActiveForm::begin(); ?>
   
    <div class="form-group"> 
    <?= $form->field($model, 'vendor_id',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->dropDownList($vendorname, ['prompt'=>'Select...']); ?>
	</div>
	
    <div class="form-group"> 
    <?= $form->field($model, 'category_id',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->dropDownList($category, ['prompt'=>'Select...']); ?>
	</div>
	
	<div class="form-group"> 
    <?= $form->field($model, 'subcategory_id',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->dropDownList($subcategory, ['prompt'=>'Select...']); ?>
	</div>

	
    <div class="form-group"> 
	    <?= $form->field($model, 'item_id[]',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->checkboxList($vendoritem); ?> 
	</div>  

<div class="form-group"> 
   <?= $form->field($model, 'answer_id',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"
					])->textInput(['maxlength' => 11]) ?>

</div>

<div class="form-group"> 
   <?= $form->field($model, 'question_text',[
		  'template' => "{label}<div class='controls'>{input}</div>{hint}{error}"
					])->textInput([]) ?>
</div>

<div class="form-group"> 
   <?= $form->field($model, 'question_answer_type',['template' => "{label}<div class='append_address'>{input}</div>{hint}{error}"
					])->dropDownList([ 'text' => 'Text', 'image' => 'Image', 'selection' => 'Selection', ], ['prompt' => '']) ?>

</div>

<div class="form-group"> 
	<?= $form->field($model, 'question_max_characters',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"
					])->textInput() ?>
</div>

<div class="form-group"> 
   <?= $form->field($model, 'question_sort_order',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"
					])->textInput() ?>
</div>

<div class="form-group"> 
   <?= $form->field($model, 'question_archived',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"
					])->dropDownList([ 'yes' => 'Yes', 'no' => 'No', ], ['prompt' => '']) ?>
</div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?=  Html::a('Back', ['index', ], ['class' => 'btn btn-default']) ?>
    </div>
	</div>
    <?php ActiveForm::end(); ?>

</div>

<script type="text/javascript">
	var csrfToken = $('meta[name="csrf-token"]').attr("content");
$(function (){ 	
    $("#vendoritemquestion-vendor_id").change(function (){
		
        var id = $('#vendoritemquestion-vendor_id').val();
        var path = "<?php echo Url::to(['/admin/featuregroupitem/loadcategory']); ?> ";
        $.ajax({
        type: 'POST',      
        url: path, //url to be called
        data: { id: id ,_csrf : csrfToken}, //data to be send
        success: function( data ) {			
             $('#vendoritemquestion-category_id').html(data);
         }
        })
     });
 });
 
$(function (){ 	
    $("#vendoritemquestion-category_id").change(function (){
		
        var id = $('#vendoritemquestion-category_id').val();
        var path = "<?php echo Url::to(['/admin/featuregroupitem/loadsubcategory']); ?> ";
        $.ajax({
        type: 'POST',      
        url: path, //url to be called
        data: { id: id ,_csrf : csrfToken}, //data to be send
        success: function( data ) {			
             $('#vendoritemquestion-subcategory_id').html(data);
         }
        })
     });
 });

$(function (){ 
    $("#vendoritemquestion-subcategory_id").change(function (){
		var csrfToken = $('meta[name="csrf-token"]').attr("content");
        var id2 = $('#vendoritemquestion-category_id').val();
        var id3 = $('#vendoritemquestion-subcategory_id').val();
        var path = "<?php echo Url::to(['/admin/featuregroupitem/loadvendoritems']); ?> ";
        $.ajax({  
        type: 'POST',      
        url: path, //url to be called
        data: { id2: id2 ,id3: id3 ,_csrf : csrfToken}, //data to be send
        success: function( data ) {
             $('#vendoritemquestion-item_id').html(data);
         }
        })
     });
 });
</script>


<script type="text/javascript">
$(function (){ 	
    $("#vendoritemquestion-question_answer_type").change(function (){
        var id = $('#vendoritemquestion-question_answer_type').val();
        if(id=='selection'){
			addAddress();
        var path = "<?php echo Url::to(['/admin/featuregroupitem/loadsubcategory']); ?>";
        
     }
     });
 });

</script>


<script type="text/javascript">
	var j=0;
function addAddress()
{
	$(".append_address").append('<textarea id="vendoritemquestion-question'+j+'" class="form-control delete_'+j+'" name="vendoritemquestion[question][]"></textarea><input type="button" name="add_item" class="delete_'+j+'" value="Add More" onClick="addAddress();" /><input type="button" class="delete_'+j+'" onclick=deleteAddress("delete_'+j+'") value=Delete>');
	j++;	
}
function deleteAddress(d) {
	$("."+d).remove();
}

</script>
