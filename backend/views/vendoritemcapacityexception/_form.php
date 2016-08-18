<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Vendoritemcapacityexception */
/* @var $form yii\widgets\ActiveForm */
?>
<?php //  echo $exist_dates;die; ?>
<div class="vendoritemcapacityexception-form">
	<div class="col-md-8 col-sm-8 col-xs-8">
    <?php $form = ActiveForm::begin(); ?>

<?= Html::csrfMetaTags() ?>

<div class="form-group"><?= $form->field($model, 'item_id',['template' => "{label}<div class='controls'><div class='input-group transparent col-md-12'>{input}</div></div>{hint}
{error}"])->dropDownList(common\models\Vendoritem::loaditems() , ['multiple'=>'multiple']) ?>

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

<?php 

$this->registerCssFile("@web/themes/default/plugins/bootstrap-datepicker/css/datepicker.css");
$this->registerCssFile("@web/themes/default/plugins/bootstrap-select2/select2.css");
$this->registerCssFile("@web/themes/default/plugins/bootstrap-multiselect/dist/css/bootstrap-multiselect.css");

$this->registerJs("
    var check_item_url = '".Url::to(['/vendor/vendoritemcapacityexception/checkitems'])."';
    var update_value = '".$model->isNewRecord?'0':$model->exception_id."';
");

$this->registerJsFile('@web/themes/default/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerJsFile('@web/themes/default/plugins/bootstrap-select2/select2.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerJsFile('@web/themes/default/plugins/bootstrap-multiselect/dist/js/bootstrap-multiselect.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerJsFile('@web/themes/default/js/vendor_item_capacity.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerCss("
    .multiselect-container>li{
        height: auto !important;
    }
");

