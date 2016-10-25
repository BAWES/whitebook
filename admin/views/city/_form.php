<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<div class="col-md-8 col-sm-8 col-xs-8">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'country_id')->dropDownList($country, ['prompt'=>'Select...']); ?>
	<?= $form->field($model, 'city_name')->textInput(['maxlength' => 100]) ?>
    <?= $form->field($model, 'city_name_ar')->textInput(['maxlength' => 100]) ?>
    <?= $form->field($model, 'status')->radioList(['Active'=>'Enable','Deactive'=>'Disable'],['class'=>'clearfix']); ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('Back', ['index', ], ['class' => 'btn btn-default']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>

<?php

$this->registerCss("
#city-status label{
    float: left;
    margin-right: 17px;
}

");