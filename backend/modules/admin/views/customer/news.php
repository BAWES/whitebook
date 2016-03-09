<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
/* @var $this yii\web\View */
/* @var $model backend\models\Customer */
/* @var $form yii\widgets\ActiveForm */


$this->title = 'Newsletter';
$this->params['breadcrumbs'][] = ['label' => 'Customers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="customer-create">

<div class="customer-form">
<div class="col-md-8 col-sm-8 col-xs-8">    
    <?php $form = ActiveForm::begin();?>
    <?php //print_r ($customer_email);die; ?>
    <div class="form-group">   
	<?= $form->field($model, 'newsmail',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->dropDownList($customer_email, ['id'=>'customer_email','multiple'=>true,'placeholder'=>'Select user']) ?>
    </div>    
    

<div class="form-group">
	<?= $form->field($model, 'content',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"])->textarea(['maxlength' => 128])?>
</div> 
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Send' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('Back', ['index', ], ['class' => 'btn btn-defauult']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

</div>

<script type="text/javascript">
$(function (){ 
    $("#customeraddress-country_id").change(function (){ 
		var csrfToken = $('meta[name="csrf-token"]').attr("content");
        var country_id = $('#customeraddress-country_id').val();       
        var path = "<?php echo Url::to(['/admin/location/city']); ?> ";
        $.ajax({  
        type: 'POST',      
        url: path, //url to be called
        data: { country_id: country_id ,_csrf : csrfToken}, //data to be send
        success: function( data ) {			
             $('#customeraddress-city_id').html(data);
         }
        })
     });
 });
</script>

<script type="text/javascript">
$(function (){ 
    $("#customeraddress-city_id").change(function (){  
		var csrfToken = $('meta[name="csrf-token"]').attr("content");
        var city_id = $('#customeraddress-city_id').val();    
        var path = "<?php echo Url::to(['/admin/location/area']); ?> ";
        $.ajax({  
        type: 'POST',      
        url: path, //url to be called
        data: { city_id: city_id ,_csrf : csrfToken}, //data to be send
        success: function( data ) {			
             $('#customeraddress-area_id').html(data);
         }
        })

     });
 });
</script>
<!-- BEGIN PLUGIN CSS -->
<link href="<?php echo Yii::$app->themeURL->createAbsoluteUrl(''); ?>plugins/bootstrap-datepicker/css/datepicker.css" rel="stylesheet" type="text/css" />
<link href="<?php echo Yii::$app->themeURL->createAbsoluteUrl(''); ?>plugins/bootstrap-select2/select2.css" rel="stylesheet" type="text/css" />
<!-- END PLUGIN CSS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="<?php echo Yii::$app->themeURL->createAbsoluteUrl(''); ?>plugins/bootstrap-select2/select2.min.js" type="text/javascript"></script>
<script src="<?php echo Yii::$app->themeURL->createAbsoluteUrl(''); ?>plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
<script src="<?php echo Yii::$app->themeURL->createAbsoluteUrl(''); ?>plugins/ckeditor/ckeditor.js" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->
<script>

$("#customer_email").select2({
    placeholder: "Select category.."
});
	
$('#customer-customer_dateofbirth').datepicker({  format: 'dd-mm-yyyy',});

$(function()
{
	CKEDITOR.replace('Customer[content]');
});
</script>

