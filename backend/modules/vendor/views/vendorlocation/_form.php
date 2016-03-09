<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use backend\models\Location;

/* @var $this yii\web\View */
/* @var $model backend\models\vendorlocation */
/* @var $form yii\widgets\ActiveForm */
?>

	<div class="col-md-8 col-sm-8 col-xs-8">	
    <?php $form = ActiveForm::begin();  ?>

    <div class="form-group">   
	<p>Country</p>
 		<input type="text" value="Kuwait" >
	</div>

	<p style="font-size:14px"> Select area <span style="color:red">*</span></p>   			
 
	<div class="form-group" style="height:200px; overflow:scroll; border:1px solid #ccc;padding:10px 5px 0px 10px;">   
    <?php 
        foreach ($cities as $key => $value) {  ?>
        <input type="hidden" name="city[]" value=<?= $value['city_id']; ?>>
        <label><b> <?= $value['city_name']; ?></b></label>
    <?php 
      $area = Location::find()->where(['status'=>'Active', 'trash' => 'Default', 'city_id' => $value['city_id']])->orderBy('city_id')->asArray()->all();
      
      foreach ($area as $key => $value) {      
     ?>
     <input type="checkbox" name="location[]" value=<?= $value['id']; ?> style=" margin-left:25px;"><?= $value['location']; ?></br>
     <?php }  
     } 
    ?>    
	</div>

   <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?=  Html::a('Back', ['index', ], ['class' => 'btn btn-default']) ?>
    </div>
	</div>
    <?php ActiveForm::end(); ?>

</div>
