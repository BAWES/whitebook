<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Vendoritemcapacityexception */
/* @var $form yii\widgets\ActiveForm */
?>
<?php //  echo $exist_dates;die; ?>
<div class="vendoritemcapacityexception-form">
	<div class="col-md-8 col-sm-8 col-xs-8">	
    <?php $form = ActiveForm::begin(); ?>

<?= Html::csrfMetaTags() ?>

<div class="form-group"><?= $form->field($model, 'item_id',['template' => "{label}<div class='controls'><div class='input-group transparent col-md-12'>{input}</div></div>{hint}
{error}"])->dropDownList(backend\models\Vendoritem::loaditems() , ['multiple'=>'multiple']) ?>
<div id="date_error" calss="help-block" style="color:#a94442"></div>
</div>
<div class="form-group"> 
<?php if(!$model->isNewRecord){?>
   <?= $form->field($model, 'exception_date',['template' => "{label}<div class='controls'><div class='input-group col-md-12'>{input}</div></div>{hint}{error}"])->textInput(['maxlenght' => 255, 'value' => date( 'd-m-Y', strtotime( $model->exception_date ) )]) ?>
   <?php }else{ ?>
    <?= $form->field($model, 'exception_date',['template' => "{label}<div class='controls'><div class='input-group col-md-12'>{input}</div></div>{hint}{error}"])->textInput(['maxlenght' => 255]) ?>
   <?php } ?>
</div>
<div class="form-group">  
  <?= $form->field($model, 'exception_capacity',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"])->textInput() ?>
</div>


<div class="form-group" style="Display:none">  
    <?= $form->field($model, 'default',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"])->textInput(['value'=>'1']) ?>  
</div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['id'=> 'submit1','class' => $model->isNewRecord ? 'btn btn-success complete' : 'btn btn-primary complete']) ?>
        <?=  Html::a('Back', ['index', ], ['class' => 'btn btn-default ']) ?>
    </div>
	</div>
    <?php ActiveForm::end(); ?>
</div>

<!-- BEGIN PLUGIN CSS -->
<link href="<?php echo Yii::$app->themeURL->createAbsoluteUrl(''); ?>plugins/bootstrap-datepicker/css/datepicker.css" rel="stylesheet" type="text/css" />
<link href="<?php echo Yii::$app->themeURL->createAbsoluteUrl(''); ?>plugins/bootstrap-select2/select2.css" rel="stylesheet" type="text/css" />
<!-- END PLUGIN CSS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="<?php echo Yii::$app->themeURL->createAbsoluteUrl(''); ?>plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
<script src="<?php echo Yii::$app->themeURL->createAbsoluteUrl(''); ?>plugins/bootstrap-select2/select2.min.js" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->


<!-- multi select begin -->
<script type="text/javascript" src="<?php echo Yii::$app->themeURL->createAbsoluteUrl(''); ?>plugins/bootstrap-multiselect/dist/js/bootstrap-multiselect.js"></script>
<link href="<?php echo Yii::$app->themeURL->createAbsoluteUrl(''); ?>plugins/bootstrap-multiselect/dist/css/bootstrap-multiselect.css" rel="stylesheet" type="text/css" />
<!-- multi select end -->

<script>
	
var forbidden = ["<?php //echo $exist_dates; ?>"];
$('#vendoritemcapacityexception-exception_date').datepicker({
	startDate: 'today',
    autoclose:true,
	format: 'dd-mm-yyyy',
	   beforeShowDay:function(Date){
        //
        var curr_day = Date.getDate();
        var curr_month = Date.getMonth()+1;
        var curr_year = Date.getFullYear();        
        var curr_date=curr_month+'/'+curr_day+'/'+curr_year;        

        if (forbidden.indexOf(curr_date)>-1) return false;        
    }
});


    $(function(){
     $('#vendoritemcapacityexception-item_id').multiselect({
            'enableFiltering': true,
            'buttonWidth': '660px',
            'filterPlaceholder': 'Select Item...'
            });       
    });
</script>

<script>
$('#submit1').click(function()
{       
        //var csrfToken = $('meta[name="csrf-token"]').attr("content");
        var item_id = $('#vendoritemcapacityexception-item_id').val();
        var exception_date = $('#vendoritemcapacityexception-exception_date').val();
        var exception_date = exception_date.split("-").reverse().join("-");   
        var path = "<?php echo Url::to(['/vendor/vendoritemcapacityexception/checkitems']); ?> ";
        var update = "<?php if($model->isNewRecord){echo '0';}else{echo $model->exception_id;} ?> ";
$.ajax({  
        type: 'POST',
        async:false,      
        url: path, 
        data: { item_id: item_id ,exception_date: exception_date ,update: update}, //data to be send
        success: function( data ) {
            if(data==2){
            $(".field-vendoritemcapacityexception-exception_date").addClass('has-error');
            $(".field-vendoritemcapacityexception-item_id").find('.help-block').html('Item already exists in same date!');
            $(".field-vendoritemcapacityexception-exception_capacity").find('.help-block').html('Item already exists in same date!');
            $('#date_error').html('Item already exists in same date!').animate({ color: "#a94442" }).show();
            $('#vendoritemcapacityexception-default').val('');
            return false;
        }else{
          $('#vendoritemcapacityexception-default').val('1');  
        }
        return false;
         }
        })
 });
</script>
