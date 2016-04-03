<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Blockeddate */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Manage weekly off';
$this->params['breadcrumbs'][] = ['label' => 'Block date', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="blockeddate-form">
	<div class="col-md-8 col-sm-8 col-xs-8">	
    <?php $form = ActiveForm::begin(); ?>
    <div class="form-group">
	<div class="form-group">
<?php 
if(!empty($block)){
$b=explode(',',$block);
foreach ($b as $b1){
  //echo $i.$b1;$i++;
  if($b1==7){$model->sunday='7';}
  if($b1==1){$model->monday='1';}
  if($b1==2){$model->tuesday='2';}
  if($b1==3){$model->wednesday='3';}
  if($b1==4){$model->thursday='4';}
  if($b1==5){$model->friday='5';}
  if($b1==6){$model->saturday='6';}
}}?>
      <?= $form->field($model, 'sunday',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
  ])->checkbox(['value' => '7'])->label('Weekdays Block',['class'=> 'form-label-cap'])?>
    <?= $form->field($model, 'monday')->checkbox(['value' => '1'])?>
    <?= $form->field($model, 'tuesday')->checkbox(['value' => '2'])?>
    <?= $form->field($model, 'wednesday')->checkbox(['value' => '3'])?>
    <?= $form->field($model, 'thursday')->checkbox(['value' => '4'])?>
    <?= $form->field($model, 'friday')->checkbox(['value' => '5'])?>
    <?= $form->field($model, 'saturday')->checkbox(['value' => '6'])?>
  </div>  

</div> 
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Update' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        
    </div>
	</div>
    <?php ActiveForm::end(); ?>

</div>

<!-- BEGIN PLUGIN CSS -->
<link href="<?php echo Yii::$app->themeURL->createAbsoluteUrl(''); ?>plugins/bootstrap-datepicker/css/datepicker.css" rel="stylesheet" type="text/css" />
<!-- END PLUGIN CSS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="<?php echo Yii::$app->themeURL->createAbsoluteUrl(''); ?>plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->

<script>
$('#blockeddate-block_date').datepicker({ format: 'dd-mm-yyyy',autoclose:true, startDate: 'today',});
</script>
