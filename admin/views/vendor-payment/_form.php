<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\VendorPayment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="vendor-payment-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'vendor_id')->dropDownList($vendors) ?>

    <div class="form-group booking-wrapper">   
    	<label>Select bookings for payment</label>
		<select class="form-control" name="bookings[]" multiple>
    	</select> 	
    </div>

    <?= $form->field($model, 'amount')->textInput(['maxlength' => true, 'readonly' => 'readonly']) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success btn-submit' : 'btn btn-primary btn-submit', 'disabled' => 'disabled']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php 

echo Html::hiddenInput('unapid_url', Url::to(['vendor-payment/unpaid']), ['id' => 'unapid_url']);

$this->registerJsFile("@web/themes/default/js/vendor_payment.js", ['depends' => [\yii\web\JqueryAsset::className()]]);