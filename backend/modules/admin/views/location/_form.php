<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
/* @var $this yii\web\View */
/* @var $model backend\models\Location */
/* @var $form yii\widgets\ActiveForm */
?>
<?= Html::csrfMetaTags() ?>

<div class="col-md-8 col-sm-8 col-xs-8">	

    <?php $form = ActiveForm::begin(); ?>
    <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />

    <div class="form-group">   
	<?= $form->field($model, 'country_id',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->dropDownList($country, ['prompt'=>'Select...']) ?>
	</div>
   
   <?php if($model->isNewRecord) { $city = array(); } ?>
   <div class="form-group">   
	<?= $form->field($model, 'city_id',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->dropDownList($city, ['prompt'=>'Select...']);  ?>
	</div>	
	
	
	<div class="form-group">   
	<?= $form->field($model, 'location',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->textInput(['maxlength' => 50])  ?>
	</div>
	  

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('Back', ['index', ], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script type="text/javascript">
$(function (){ 
    $("#location-country_id").change(function (){  
		var csrfToken = $('meta[name="csrf-token"]').attr("content");
        var country_id = $('#location-country_id').val();       
        var path = "<?php echo Url::to(['/admin/location/city']); ?> ";
        $.ajax({  
        type: 'POST',      
        url: path, //url to be called
        data: { country_id: country_id ,_csrf : csrfToken}, //data to be send
        success: function( data ) {			
             $('#location-city_id').html(data);
         }
        })

     });
 });
</script>


