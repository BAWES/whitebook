<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Adverthome */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="col-md-8 col-sm-8 col-xs-8">	
    <?php $form = ActiveForm::begin(['options'=>['enctype'=>'multipart/form-data','id' => 'myform','name' => 'myform','onsubmit'=>'return check_validation();']]); ?>
    
 <div class="form-group">
    </div>
    <div class="form-group"  id="advert_script">   
	<?= $form->field($model, 'advert_code',['template' => "{label}<div class='controls1'>{input}</div> {hint} {error}" 
	])->textArea(['rows' => 6]) ?>
    </div>
    <div class="form-group">
     <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-primary']) ?>
     </div>
<?php ActiveForm::end(); ?>
</div>

