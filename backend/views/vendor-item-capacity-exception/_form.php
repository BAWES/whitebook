<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\VendorItemCapacityException*/
/* @var $form yii\widgets\ActiveForm */

?>
<div class="vendor-item-capacity-exception-form">

    <?php $form = ActiveForm::begin(); ?>
    <?php echo Html::csrfMetaTags() ?>
    <div class="row">
        <div class="col-md-4 col-sm-4 col-xs-4">
            <?= $form->field($model, 'item_id', [
                'template' => "{label}<div class='controls'>{input}</div>{hint} {error}"])
                ->dropDownList(\yii\helpers\ArrayHelper::map(\common\models\Vendoritem::findAll(['item_status'=>'Active','trash'=>'Default','vendor_id'=>Yii::$app->user->getId()]),'item_id','item_name'), ['prompt'=>'Select...']) ?>
        </div>
        <div class="col-md-4 col-sm-4 col-xs-4">
            <?= $form->field($model, 'exception_date',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"])->textInput(['maxlenght' => 255]) ?>
        </div>

        <div class="col-md-4 col-sm-4 col-xs-4">
            <?= $form->field($model, 'exception_capacity',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"])->textInput() ?>
        </div>
    </div>
    <div class="">
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success complete' : 'btn btn-primary complete']) ?>
            <?=  Html::a('Back', ['index', ], ['class' => 'btn btn-default ']) ?>
        </div>
	</div>
    <?php ActiveForm::end(); ?>
</div>

<?php 

$this->registerCssFile("@web/themes/default/plugins/bootstrap-datepicker/css/datepicker.css");
$this->registerJs("
    var check_item_url = '".Url::to(['/vendor/vendoritemcapacityexception/checkitems'])."';
    var update_value = '".$model->isNewRecord?'0':$model->exception_id."';
");

$this->registerJsFile('@web/themes/default/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('@web/themes/default/js/vendor_item_capacity.js', ['depends' => [\yii\web\JqueryAsset::className()]]);


