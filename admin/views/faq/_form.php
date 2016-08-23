<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Faq */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="faq-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="form-group">
        <?= $form->field($model, 'faq_group_id',['template' => "{label}<div class='controls'>{input}</div>{hint}
            {error}"])->dropDownList($faq_group_drdwn) ?>
    </div>

    <div class="form-group">
        <?= $form->field($model, 'question',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"])->textarea(['rows' => 6])?>
    </div>

    <div class="form-group">
        <?= $form->field($model, 'question_ar',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"])->textarea(['rows' => 6])?>
    </div>

    <div class="form-group">
    	<?= $form->field($model, 'answer',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"])->textarea(['rows' => 6])?>
    </div>

    <div class="form-group">
        <?= $form->field($model, 'answer_ar',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"])->textarea(['rows' => 6])?>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('Back', ['index', ], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
