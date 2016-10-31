<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="vendor item question-form">
	<div class="col-md-8 col-sm-8 col-xs-8">	
    
    <?php $form = ActiveForm::begin(); ?>
   
    <?= $form->field($model, 'vendor_id')->dropDownList($vendorname, ['prompt'=>'Select...']); ?>

    <?= $form->field($model, 'category_id')->dropDownList($category, ['prompt'=>'Select...']); ?>
	
    <?= $form->field($model, 'subcategory_id')->dropDownList($subcategory, ['prompt'=>'Select...']); ?>
	
	<?= $form->field($model, 'item_id[]')->checkboxList($vendoritem); ?> 
	
    <?= $form->field($model, 'answer_id')->textInput(['maxlength' => 11]) ?>

    <?= $form->field($model, 'question_text')->textInput([]) ?>

    <?= $form->field($model, 'question_answer_type')
            ->dropDownList(
                ['text' => 'Text', 'image' => 'Image', 'selection' => 'Selection'], 
                ['prompt' => '']
        ) ?>

	<?= $form->field($model, 'question_max_characters')->textInput() ?>

    <?= $form->field($model, 'question_sort_order')->textInput() ?>

    <?= $form->field($model, 'question_archived')->dropDownList(
            ['yes' => 'Yes', 'no' => 'No', ], ['prompt' => '']); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?=  Html::a('Back', ['index', ], ['class' => 'btn btn-default']) ?>
    </div>

	<?php ActiveForm::end(); ?>

    </div>
    
</div>

<script type="text/javascript">
	var csrfToken = $('meta[name="csrf-token"]').attr("content");
$(function (){ 	
    $("#vendoritemquestion-vendor_id").change(function (){
		
        var id = $('#vendoritemquestion-vendor_id').val();
        var path = "<?php echo Url::to(['/feature-group-item/loadcategory']); ?> ";
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
        var path = "<?php echo Url::to(['/feature-group-item/loadsubcategory']); ?> ";
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
        var path = "<?php echo Url::to(['/feature-group-item/loadvendoritems']); ?> ";
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
        var path = "<?php echo Url::to(['/feature-group-item/loadsubcategory']); ?>";
        
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
