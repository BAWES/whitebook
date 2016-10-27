<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;

?>

<div class="customer-form">
<div class="row">


    <?php $form = ActiveForm::begin(); ?>
    <div class="col-md-3 col-sm-3 col-xs-3">
	    <?= $form->field($model, 'event_name')->textInput(['maxlength' => 128])?>
    </div>
    <div class="col-md-3 col-sm-3 col-xs-3">
    	<?= $form->field($model, 'event_date')->textInput(['maxlength' => 128])?>
    </div>
    <div class="col-md-3 col-sm-3 col-xs-3">
        <?= $form->field($model, 'event_type')->dropDownList(\yii\helpers\ArrayHelper::map(\admin\models\EventType::findAll(['trash'=>'Default']),'type_name','type_name'))?>
    </div>
    <div class="col-md-3 col-sm-3 col-xs-3">
        <?= $form->field($model, 'slug')->textInput(['maxlength' => 128])?>
    </div>
    <div class="col-md-3 col-sm-3 col-xs-3">
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            <?= Html::a('Back', ['index', ], ['class' => 'btn btn-defauult']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<?php

$this->registerJsFile('@web/themes/default/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerCssFile('@web/themes/default/plugins/bootstrap-datepicker/css/datepicker.min.css', ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerJs("
	jQuery('#events-event_date').datepicker({
		format: 'yyyy-mm-dd',
	});
", View::POS_READY);
