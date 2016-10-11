<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;
use common\models\Location;

?>

<div class="vendorlocation-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'area_id')->dropDownList(Location::areaOptions(), 
            ['class' => 'selectpicker', 'data-live-search' => 'true', 'data-size' => 10]
        ); ?>

    <?= $form->field($model, 'delivery_price')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php 

$this->registerCssFile('@web/themes/default/plugins/bootstrap-select2/select2.min.css');
$this->registerJsFile('@web/themes/default/plugins/bootstrap-select2/select2.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerJs("
	jQuery('.selectpicker').select2();
", View::POS_READY);

$this->registerCss("
	.select2-container{
		width: 100%;
	}
");