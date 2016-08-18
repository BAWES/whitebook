<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;

/* @var $model common\models\addressquestion */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="address-question-form">
    <div class="col-md-8 col-sm-8 col-xs-8">    
        
        <?php $form = ActiveForm::begin(); ?>
        
        <div class="form-group">
            <?= $form->field($model, 'address_type_id',[
                'template' => "{label}<div class='controls'>{input}</div>{hint}{error}"]
                )->dropDownList($addresstype, ['prompt'=>'Select...']) ?>
        </div>

        <?php if($model->isNewRecord){?>

        <div class="question-box">       
            <div class="form-group">    
                <?= $form->field($model, 'question[]',['template' => "{label}<div >{input}</div>{hint}{error}"]
                    )->textInput(['multiple' => 'multiple']) ?> 
            </div>

            <?= $form->field($model, 'question_ar[]',[
                    'template' => "{label}<div >{input}</div>{hint}{error}"]
                )->textInput([
                    'value' => '',
                    'multiple' => 'multiple'
                ])->label('Question - Arabic');
            ?>
        </div>

        <div class="form-group">
            <input type="button" name="add_item" value="Add More" onclick="addAddress(0,this);" />
        </div>
        
        <?php } else { ?>
        <?php

        $i=0;

        foreach ($addressquestion as $ques){  ?>

        <div class="question-box">    

            <?= $form->field($model, 'question[]',[
                    'template' => "{label}<div >{input}</div>{hint}{error}"]
                )->textInput([
                    'value' => $ques['question'],
                    'multiple' => 'multiple'
                ]);
            ?>

            <?= $form->field($model, 'question_ar[]',[
                    'template' => "{label}<div >{input}</div>{hint}{error}"]
                )->textInput([
                    'value' => $ques['question_ar'],
                    'multiple' => 'multiple'
                ])->label('Question - Arabic');
            ?>

            <input type="hidden" name="addressquestion[quesid][]" value="<?php echo $ques['ques_id']; ?>" multiple="multiple" />

        </div>
        <?php $i++;} ?> 

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

<?php 

$this->registerCss("
    .question-box {
        background: wheat;
        padding: 10px;
        margin-bottom: 10px;
        color: black;
    }
");

$this->registerJs("
    var j=0;
    var html = '';

    function addAddress(r, tis)
    { 
        html  = '<div class=\"question-box append_address'+j+'\">';
        
        html += '<div class=\"form-group\">';
        html += 'Question <span style=\"color:red\"> *</span>';
        html += '<input type=\"text\" id=\"addressquestion-question'+j+'\" class=\"form-control required\" name=\"AddressQuestion[question][]\" multiple = \"multiple\"/>';
        html += '</div>';

        html += '<div class=\"form-group\">';
        html += 'Question - Arabic<span style=\"color:red\"> *</span>';
        html += '<input type=\"text\" id=\"addressquestion-question'+j+'\" class=\"form-control required\" name=\"AddressQuestion[question_ar][]\" multiple = \"multiple\"/>';
        html += '</div>';

        html += '<label class=\"form-label\" style=\"margin-top: 10px;\"><input type=\"button\" class=\"delete_'+j+'\" onclick=deleteAddress(\"'+j+'\") value=Remove></label>';
        html += '</div>'

        $(tis).parent().before(html);
        j++;    
    }

    function deleteAddress(d) {
        $('.append_address' + d).remove();
    }

", View::POS_HEAD);



