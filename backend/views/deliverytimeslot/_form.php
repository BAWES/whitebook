<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;

/* @var $model common\models\Deliverytimeslot */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="deliverytimeslot-form">
	<div class="col-md-8 col-sm-8 col-xs-8">

<?php
    
$form = ActiveForm::begin();

if(!$model->isNewRecord) {

    $model->start_hr  = date("h", strtotime($model->timeslot_start_time));
    $model->start_min  = date("i", strtotime($model->timeslot_start_time));
    $model->end_min  = date("i", strtotime($model->timeslot_end_time));
    $model->end_hr  = date("h", strtotime($model->timeslot_end_time));

    date("a", strtotime($model->timeslot_start_time))=='pm'?$model->start_med='PM':$model->start_med='AM';

    date("a", strtotime($model->timeslot_end_time))=='pm'?$model->end_med='PM':$model->end_med='AM';

} ?>

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
        <?= Html::button($model->isNewRecord ? 'Create' : 'Update', ['id'=> 'submit1','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?=  Html::a('Back', ['index', ], ['class' => 'btn btn-default']) ?>
    </div>
</div>
    <?php ActiveForm::end(); ?>

</div>

<?php 

if ($model->isNewRecord) {
    $this->registerJs("
        var check_time_url = '".Url::to(['/deliverytimeslot/checktime'])."';
        var update_value = '0';
    ", View::POS_HEAD);
} else {
    $this->registerJs("
        var check_time_url = '".Url::to(['/deliverytimeslot/checktime'])."';
        var update_value = '".$model->exception_id."';
    ", View::POS_HEAD);
}

$this->registerCssFile("@web/themes/default/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css");

$this->registerJsFile("@web/themes/default/plugins/bootstrap-timepicker/js/bootstrap-timepicker.js", ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerJsFile("@web/themes/default/js/delivery_timeslot.js", ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerCss("
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
");
