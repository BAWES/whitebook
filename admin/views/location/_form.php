<?php

use yii\web\View;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>

<?= Html::csrfMetaTags() ?>

<div class="col-md-8 col-sm-8 col-xs-8">	

    <?php $form = ActiveForm::begin(); ?>
    
    <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />

    <?= $form->field($model, 'country_id')->dropDownList($country, ['prompt'=>'Select...']) ?>
	
    <?php if($model->isNewRecord) { $city = array(); } ?>

	<?= $form->field($model, 'city_id')->dropDownList($city, ['prompt'=>'Select...']); ?>
		
	<?= $form->field($model, 'location')->textInput(['maxlength' => 50])  ?>
	
    <?= $form->field($model, 'location_ar')->textInput(['maxlength' => 50])  ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('Back', ['index', ], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php 

$this->registerJs("
    $(function (){ 
        $('#location-country_id').change(function (){  
            var csrfToken = $('meta[name=\"csrf-token\"]').attr('content');
            var country_id = $('#location-country_id').val();       
            var path = '".Url::to(['/location/city'])."';
            
            $.ajax({  
                type: 'POST',      
                url: path, //url to be called
                data: { country_id: country_id ,_csrf : csrfToken}, //data to be send
                success: function( data ) {         
                     $('#location-city_id').html(data);
                }
            });

         });
    });

");



