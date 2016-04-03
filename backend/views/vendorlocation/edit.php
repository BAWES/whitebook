<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use common\models\Location;
use common\models\Vendorlocation;

/* @var $this yii\web\View */
/* @var $model common\models\vendorlocation */
/* @var $form yii\widgets\ActiveForm */
$this->title = 'Area';
$this->params['breadcrumbs'][] = ['label' => 'Locations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$vendor_count = Location::find()->where(['status'=>'Active', 'trash' => 'Default'])->orderBy('city_id')->count();

?>

	<div class="col-md-8 col-sm-8 col-xs-8">	
    <?php $form = ActiveForm::begin();  ?>

    <div class="form-group" style="display:none;">   
	<?=  $form->field($model, 'area_id',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->textInput(['maxlength' => 100]); ?>
 
	</div>

		<div class="form-group">   
	<p>Country</p>
 		<input type="text" value="Kuwait" class="form-control" disabled="disabled">
	</div>


	<p style="font-size:14px"> Select area</p>   			
	 
	<div class="form-group" style="height:200px; overflow:scroll; border:1px solid #ccc;padding:10px 5px 0px 10px;">   
    <?php 
       foreach ($cities as $key => $value) {  ?>
        <input type="hidden" name="city[]" value=<?= $value['city_id']; ?>>
        <label><b> <?= $value['city_name']; ?></b></label>
    <?php 
      $area = Location::find()->where(['status'=>'Active', 'trash' => 'Default', 'city_id' => $value['city_id']])->orderBy('city_id')->asArray()->all();
      
      foreach ($area as $key => $value) {  

      $vendor_area = Vendorlocation::find()->select(['area_id'])->where(['area_id'=>$value['id']])->one();
      
     ?>
     <input type="checkbox" name="location[]" id="loc" value=<?= $value['id']; ?>  <?php echo ($value['id'] == $vendor_area['area_id'] ? 'checked' : '');?> style=" margin-left:25px;"><?= $value['location']; ?></br>
     <?php  }  
      } 
    ?>    
	</div>

   <div class="form-group">

     <input type="button" class="check" value="Check all" style="background:#2D3139; color:#fff"/>

        <?= Html::submitButton('Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        
    </div>
	</div>
    <?php ActiveForm::end(); ?>

</div>
<script>
$(document).ready(function(){

  if($(":checkbox:checked").length == <?php echo $vendor_count; ?>)
  {
    $('.check:button').val('Uncheck all');
  }
  else
  {
    $('.check:button').val('Check all');
  }
});

    $('.check:button').toggle(function(){       
      if($(this).val() == 'Uncheck all')
      {        
        $('input:checkbox').removeAttr('checked');        
        $(this).val('Check all');
      }  
      else if($(this).val() == 'Check all')
      {        
        $('input:checkbox').attr('checked','checked');
        $(this).val('Uncheck all');
      }       
    },function(){
      $('input:checkbox').attr('checked','checked');
        $(this).val('Uncheck all');        
    });
</script>