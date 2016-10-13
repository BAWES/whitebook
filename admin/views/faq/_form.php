<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Faq */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="faq-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'faq_group_id')->dropDownList($faq_group_drdwn); ?>

    <?= $form->field($model, 'question')->textarea(['rows' => 6]); ?>

    <?= $form->field($model, 'question_ar'])->textarea(['rows' => 6]); ?>

	<?= $form->field($model, 'answer')->textarea(['rows' => 6]); ?>

    <?= $form->field($model, 'answer_ar')->textarea(['rows' => 6]); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('Back', ['index', ], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
