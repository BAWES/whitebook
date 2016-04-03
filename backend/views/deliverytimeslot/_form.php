<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Deliverytimeslot */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="deliverytimeslot-form">
	<div class="col-md-8 col-sm-8 col-xs-8">
    <?php
    $form = ActiveForm::begin();?>


<?php if(!$model->isNewRecord){?>

       <?php   $model->start_hr  = date("h", strtotime($model->timeslot_start_time));
          $model->start_min  = date("i", strtotime($model->timeslot_start_time));
          $model->end_min  = date("i", strtotime($model->timeslot_end_time));
          $model->end_hr  = date("h", strtotime($model->timeslot_end_time));
       date("a", strtotime($model->timeslot_start_time))=='pm'?$model->start_med='PM':$model->start_med='AM'
     ?>
     <?php
date("a", strtotime($model->timeslot_end_time))=='pm'?$model->end_med='PM':$model->end_med='AM'
     ?>
     <?php } ?>
<div id="result"></div>
<div class="form-group">
 <?= $form->field($model, 'timeslot_day',['template' => "{label}<div class='controls'>{input}</div>
{hint}{error}"])->dropDownList($days, ['prompt'=>'Select...']); ?>
</div>

<div class="form-group">
 <?= $form->field($model, 'timeslot_maximum_orders',['template' => "{label}<div class='controls'>{input}</div>
{hint}{error}"])->textInput() ?>
</div>


<div class="form-group timeslotshms">
<div class="delivery_start"> Start time <span style="color:red">*</span></div>
    <div class="col-sm-4">
        <?= $form->field($model, 'start_hr')->dropDownList(['01'=>'01','02'=>'02','03'=>'03','04'=>'04','05'=>'05','06'=>'06','07'=>'07','08'=>'08','09'=>'09','10'=>'10','11'=>'11','12'=>'12'])->label(false); ?>
    </div>
    <div class="col-sm-4">
        <?= $form->field($model, 'start_min')->dropDownList(['00'=>'00','01'=>'01','02'=>'02','03'=>'03','04'=>'04','05'=>'05','06'=>'06','07'=>'07','08'=>'08','09'=>'09','10'=>'10','11'=>'11','12'=>'12','13'=>'13','14'=>'14','15'=>'15','16'=>'16','17'=>'17','18'=>'18','19'=>'19','20'=>'20','21'=>'21','22'=>'22','23'=>'23','24'=>'24','25'=>'25','26'=>'26','27'=>'27','28'=>'28','29'=>'29','30'=>'30','31'=>'31','32'=>'32','33'=>'33','34'=>'34','35'=>'35','36'=>'36','37'=>'37','38'=>'38','39'=>'39','40'=>'40','41'=>'41','42'=>'42','43'=>'43','44'=>'44','45'=>'45','46'=>'46','47'=>'47','48'=>'48','49'=>'49','50'=>'50','51'=>'51','52'=>'52','53'=>'53','54'=>'54','55'=>'55','56'=>'56','57'=>'57','58'=>'58','59'=>'59'])->label(false); ?>
    </div>
    <div class="col-sm-4">
        <?= $form->field($model, 'start_med')->dropDownList(['AM' => 'AM', 'PM' => 'PM'])->label(false); ?>
    </div>
    </div>
<div class="form-group timeslotshms">
<div class="delivery_end">End time <span style="color:red">*</span></div>
    <div class="col-sm-4">
        <?= $form->field($model, 'end_hr')->dropDownList(['01'=>'01','02'=>'02','03'=>'03','04'=>'04','05'=>'05','06'=>'06','07'=>'07','08'=>'08','09'=>'09','10'=>'10','11'=>'11','12'=>'12'])->label(false); ?>
    </div>
    <div class="col-sm-4">
        <?= $form->field($model, 'end_min')->dropDownList(['00'=>'00','01'=>'01','02'=>'02','03'=>'03','04'=>'04','05'=>'05','06'=>'06','07'=>'07','08'=>'08','09'=>'09','10'=>'10','11'=>'11','12'=>'12','13'=>'13','14'=>'14','15'=>'15','16'=>'16','17'=>'17','18'=>'18','19'=>'19','20'=>'20','21'=>'21','22'=>'22','23'=>'23','24'=>'24','25'=>'25','26'=>'26','27'=>'27','28'=>'28','29'=>'29','30'=>'30','31'=>'31','32'=>'32','33'=>'33','34'=>'34','35'=>'35','36'=>'36','37'=>'37','38'=>'38','39'=>'39','40'=>'40','41'=>'41','42'=>'42','43'=>'43','44'=>'44','45'=>'45','46'=>'46','47'=>'47','48'=>'48','49'=>'49','50'=>'50','51'=>'51','52'=>'52','53'=>'53','54'=>'54','55'=>'55','56'=>'56','57'=>'57','58'=>'58','59'=>'59'])->label(false); ?>
    </div>
    <div class="col-sm-4">
        <?= $form->field($model, 'end_med')->dropDownList(['AM' => 'AM', 'PM' => 'PM'])->label(false); ?>
    </div>
</div>

<div class="form-group" style="display:none">
    <?= $form->field($model, 'timeslot_start_time')->textInput(['value'=>'']); ?>
</div>
<div class="form-group" style="display:none">
    <?= $form->field($model, 'default',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"])->textInput(['value'=>'']) ?>
</div>
<div class="form-group" style="display:none">
    <?= $form->field($model, 'timeslot_end_time',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"])->textInput(['value'=>'']) ?>
</div>
	<div class="col-sm-offset-2 col-sm-10">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['id'=> 'submit1','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?=  Html::a('Back', ['index', ], ['class' => 'btn btn-default']) ?>
    </div>
	</div>
    <?php ActiveForm::end(); ?>

</div>

<link href="<?php echo Yii::$app->themeURL->createAbsoluteUrl(''); ?>plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="<?php echo Yii::$app->themeURL->createAbsoluteUrl(''); ?>plugins/bootstrap-timepicker/js/bootstrap-timepicker.js"></script>

<script>
	$('#deliverytimeslot-timeslot_start_time,#deliverytimeslot-timeslot_end_time').timepicker();
</script>

<script>
$('#submit1').click(function()
{
        var day = $('#deliverytimeslot-timeslot_day').val();
        var start_hr = $('#deliverytimeslot-start_hr').val();
        var start_min = $('#deliverytimeslot-start_min').val();
        var start_med = $('#deliverytimeslot-start_med').val();
        var colon=':';
        var end_hr = $('#deliverytimeslot-end_hr').val();
        var end_min = $('#deliverytimeslot-end_min').val();
        var end_med = $('#deliverytimeslot-end_med').val();

        var slot = $('#deliverytimeslot-timeslot_maximum_orders').val();
        var path = "<?php echo Url::to(['deliverytimeslot/checktime']); ?> ";
        var update = "<?php if($model->isNewRecord){echo '0';}else{echo $model->timeslot_id;} ?> ";
if(start_hr!='' && start_min!=''  && start_med!='' && end_hr!='' && end_min!=''&& end_med!='' && day!='' && slot!=''){
    var sta=start_hr.concat(colon);
    var res1 = sta.concat(start_min);
    var start_time=res1.concat(start_med);

    var en=end_hr.concat(colon);
    var res2 = en.concat(end_min);
    var end_time=res2.concat(end_med);
$.ajax({
        type: 'POST',
        async:false,
        url: path, //url to be called
        data: { day: day ,start: start_time ,end: end_time,update: update}, //data to be send
        success: function( data ) {

            if(data==1)
            {
            $('#deliverytimeslot-default').val('');
            $("#result").html('<div class="alert alert-failure"><button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>Time period is not a valid one!</div>');
            return false;
            }else if(data==2){
            $("#result").html('<div class="alert alert-failure"><button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>Day already exists in same time period!</div>');
            $('#deliverytimeslot-default').val('');
            return false;
        }else{
          $('#deliverytimeslot-default').val('1');
          $('#deliverytimeslot-timeslot_start_time').val(start_time);
          $('#deliverytimeslot-timeslot_end_time').val(end_time);
          return false;
        }
        }
        })
    }
 });
</script>
<style>
.timeslotshms{
  height:75px;
}
.delivery_start{
      float: left;
    width: 100%;
    height: 30px;
  }
.delivery_end{
    float: left;
    width: 100%;
    height: 30px;
  }
</style>
