<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Blockeddate */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="blockeddate-form">
	<div class="col-md-8 col-sm-8 col-xs-8">
    <?php $form = ActiveForm::begin(); ?>
    <div class="form-group">
	<div class="form-group">
<?php $b=explode(',',$block);
$k=array();
foreach ($b as $b1){
  if($b1==7){$k[]='0';}
  else
  {$k[]=$b1;}
}
?>    <?php if(!$model->isNewRecord){?>
   <?= $form->field($model, 'block_date',['template' => "{label}<div class='controls'><div class='input-group col-md-12'>{input}</div></div>{hint}{error}"])->textInput(['maxlenght' => 255, 'value' => date( 'd-m-Y', strtotime( $model->block_date ) )]) ?>
   <?php }else{ ?>
    <?= $form->field($model, 'block_date',['template' => "{label}<div class='controls'><div class='input-group col-md-12'>{input}</div></div>{hint}{error}"])->textInput(['maxlenght' => 255]) ?>
   <?php } ?>
</div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?=  Html::a('Back', ['index', ], ['class' => 'btn btn-default']) ?>
    </div>
	</div>
    <?php ActiveForm::end(); ?>

</div>

<!-- BEGIN PLUGIN CSS -->
<link href="<?= Url::to("@web/themes/default/plugins/bootstrap-datepicker/css/datepicker.css") ?>" rel="stylesheet" type="text/css" />
<!-- END PLUGIN CSS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="<?= Url::to("@web/themes/default/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js") ?>" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->

<script>
$('#blockeddate-block_date').datepicker({ daysOfWeekDisabled: <?php echo json_encode($k);?>,format: 'dd-mm-yyyy',autoclose:true, startDate: 'today',});
</script>
