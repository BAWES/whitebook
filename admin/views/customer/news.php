<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\web\View;

$this->title = 'Newsletter';
$this->params['breadcrumbs'][] = ['label' => 'Customers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="customer-create">

<div class="customer-form">
    <div class="col-md-8 col-sm-8 col-xs-8">
        
        <?php $form = ActiveForm::begin();?>
        
    	<?= $form->field($model, 'newsmail')
                ->dropDownList(
                    $customer_email, 
                    [
                        'id' => 'customer_email',
                        'multiple' => true,
                        'placeholder' => 'Select user'
                    ]
                ); ?>
    
    	<?= $form->field($model, 'content')->textarea(['maxlength' => 128]); ?>
    
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Send' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            <?= Html::a('Back', ['index', ], ['class' => 'btn btn-defauult']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>

<?php 

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
");

$this->registerCssFile("@web/themes/default/plugins/bootstrap-datepicker/css/datepicker.css");

$this->registerCssFile("@web/themes/default/plugins/bootstrap-select2/select2.css");

$this->registerJsFile("@web/themes/default/plugins/bootstrap-select2/select2.min.js", ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerJsFile("@web/themes/default/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js", ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerJsFile("@web/themes/default/plugins/ckeditor/ckeditor.js", ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerJs("
    $('#customer_email').select2({
        placeholder: 'Select category..'
    });

    $('#customer-customer_dateofbirth').datepicker({  format: 'dd-mm-yyyy',});

    $(function()
    {
        CKEDITOR.replace('Customer[content]');
    });
");
