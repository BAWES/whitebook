<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Categoryads */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="col-md-8 col-sm-8 col-xs-8">
    <?php $form = ActiveForm::begin(); ?>

    <div class="form-group">
    <?= $form->field($model, 'category_id',['template' => "{label}<div class='controls'>{input}</div> {hint} {error}"
    ])->listbox($category,['multiple'=>true,'size' => 7]); ?>
    </div>
    <div class="form-group">
    <?= $form->field($model, 'top_ad')->textArea(['maxlength' => true]) ?>
    </div>

    <div class="form-group">
    <?= $form->field($model, 'bottom_ad')->textArea(['maxlength' => true]) ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<link href="<?= Url::to("@web/themes/default/plugins/bootstrap-select2/select2.css") ?>" rel="stylesheet" type="text/css" />
<script src="<?= Url::to("@web/themes/default/plugins/bootstrap-select2/select2.min.js") ?>" type="text/javascript"></script>
<script type="text/javascript">

$("#categoryads-category_id").select2({
    placeholder: "Choose category..",
});
</script>
