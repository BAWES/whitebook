<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\VendorWorkingTiming */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="vendor-working-timing-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'working_day')->dropDownList([ 'Sunday' => 'Sunday', 'Monday' => 'Monday', 'Tuesday' => 'Tuesday', 'Wednesday' => 'Wednesday', 'Thursday' => 'Thursday', 'Friday' => 'Friday', 'Saturday' => 'Saturday', ], ['prompt' => '']) ?>
        </div>
        <div class="col-md-4 working_hours_wrapper">
            <?= $form->field($model, 'working_start_time')->textInput() ?>
        </div>
        <div class="col-md-4 working_hours_wrapper2">
            <?= $form->field($model, 'working_end_time')->textInput() ?>
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Back'), ['vendor-working-timing/index'],['class'=>'btn btn-default']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<?php
$this->registerCssFile('@web/themes/default/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css');
$this->registerJsFile("@web/themes/default/plugins/bootstrap-datetimepicker/js/moment.js", ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile("@web/themes/default/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js", ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJs("
        $('.working_hours_wrapper input').datetimepicker({
	        format: 'LT',
	        useCurrent: false,
	        defaultDate: '12/12/13 08:00:00'
        });
        
        $('.working_hours_wrapper2 input').datetimepicker({
	        format: 'LT',
	        useCurrent: false,
	        defaultDate: '12/12/13 20:00:00'
        });
    ", \yii\web\View::POS_READY);

