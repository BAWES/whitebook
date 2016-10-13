<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\web\View;

?>

<div class="customer-form">
<div class="col-md-8 col-sm-8 col-xs-8">
 
    <?php $form = ActiveForm::begin(); ?>

	<?= $form->field($model, 'customer_name')->textInput(['maxlength' => 128])?>

	<?= $form->field($model, 'customer_email')->textInput(['maxlength' => 128])?>

    <?php if($model->isNewRecord) { ?>

    	<?= $form->field($model, 'customer_password')->passwordInput(['maxlength' => 128])?>
    
    	<?= $form->field($model, 'customer_dateofbirth')->textInput(['maxlength' => 128])?>
    
    <?php } else { ?>

    	<?= $form->field($model, 'customer_dateofbirth')
                ->textInput([
                    'maxlenght' => 255, 
                    'value' => date( 'd-m-Y', strtotime( $model->customer_dateofbirth))
                ]); ?>
    
    <?php } ?>

	<?= $form->field($model, 'customer_gender')
            ->dropDownList([ 'Male' => 'Male', 'Female' => 'Female', ], ['prompt' => 'Select Gender']) ?>

	<?= $form->field($model, 'customer_mobile')->textInput(['maxlength' => 128])?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('Back', ['index', ], ['class' => 'btn btn-defauult']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php 

$this->registerJsFile('@web/themes/default/plugins/jquery-mask/jquery.mask.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerJs("
    $(function (){
        $('#customeraddress-country_id').change(function (){
            var csrfToken = $('meta[name=\"csrf-token\"]').attr('content');
            var country_id = $('#customeraddress-country_id').val();
            var path = '".Url::to(['/location/city'])."';
            
            $.ajax({
                type: 'POST',
                url: path, //url to be called
                data: { country_id: country_id ,_csrf : csrfToken}, //data to be send
                success: function( data ) {
                     $('#customeraddress-city_id').html(data);
                }
            });
        });
    
        $('#customeraddress-city_id').change(function (){
            var csrfToken = $('meta[name=\"csrf-token\"]').attr('content');
            var city_id = $('#customeraddress-city_id').val();
            var path = '".Url::to(['/location/area'])."';
            
            $.ajax({
                type: 'POST',
                url: path, //url to be called
                data: { city_id: city_id ,_csrf : csrfToken}, //data to be send
                success: function( data ) {
                     $('#customeraddress-area_id').html(data);
                }
            });
         });
    });

    $('#customer-customer_dateofbirth').mask('00-00-0000', { placeholder: 'dd-mm-yyyy' });
");

    

