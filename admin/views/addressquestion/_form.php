<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\addressquestion */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="address-question-form">
<div class="col-md-8 col-sm-8 col-xs-8">    
<?php $form = ActiveForm::begin(); ?>
<div class="form-group"><?= $form->field($model, 'address_type_id',['template' => "{label}<div class='controls'>{input}</div>{hint}
{error}"])->dropDownList($addresstype, ['prompt'=>'Select...']) ?></div>

<?php if($model->isNewRecord){?>
<div class="form-group">    <?=  $form->field($model, 'question[]',['template' => "{label}<div >{input}</div>
{hint}
{error}"])->textInput(['multiple' => 'multiple']) ?> 
</div>
<div class="form-group">
<input type="button" name="add_item" value="Add More" onclick="addAddress(0,this);" />
</div>
<?php }else { ?>
<?php
$i=0;
foreach ($addressquestion as $ques){ $value=$ques['question'];?>
<div class="form-group">    <?=  $form->field($model, 'question[]',['template' => "{label}<div >{input}</div>
{hint}
{error}"])->textInput(['value'=>$value,'multiple' => 'multiple']) ?>

<input type="hidden" name="addressquestion[quesid][]" value="<?php echo $ques['ques_id']; ?>" multiple="multiple" />
</div><?php $i++;}?> 
<div class="form-group">
<input type="button" name="add_item" value="Add More" onclick="addAddress(<?php echo $i;?>,this);" />
</div>
<?php } ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?=  Html::a('Back', ['index', ], ['class' => 'btn btn-default']) ?>
    </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>


<script type="text/javascript">
	var j=0;
function addAddress(r,tis)
{ 
	$(tis).parent().before('<div class="form-group append_address'+j+'">Question <span style="color:red"> *</span><input type="text" id="addressquestion-question'+j+'" class="form-control required" name="AddressQuestion[question][]" multiple = "multiple"/><label class="form-label" style="margin-top: 10px;"><input type="button" class="delete_'+j+'" onclick=deleteAddress("'+j+'") value=Remove></label></div>');
	j++;	
}
function deleteAddress(d) {
	$(".append_address"+d).remove();
}
</script>

