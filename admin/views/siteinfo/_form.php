<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Siteinfo */
/* @var $form yii\widgets\ActiveForm */
?>
 <div class="col-md-8 col-sm-8 col-xs-8">	
	 
	<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
	
	<div class="form-group">   
	<?= $form->field($model, 'app_name',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->textInput(['maxlength' => 100]) ?>
	</div>

	<div class="form-group">    
	<?= $form->field($model, 'app_desc',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->textarea(['rows' => 6,'placeholder' => 'Enter app_desc','class'=> 'form-control']) ?>
	</div>

	<div class="form-group">    
	<?= $form->field($model, 'home_slider_alias',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->textInput(['placeholder' => 'Enter slider alias','class'=> 'form-control']) ?>
	</div>

	<div class="form-group">   
	<?= $form->field($model, 'meta_keyword',[ 'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->textInput(['maxlength' => 250]) ?>
	</div>
	<div class="form-group">    
		<?= $form->field($model, 'meta_desc',[ 'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 	
		])->textarea(['rows' => 6,'placeholder' => 'Enter meta_desc','class'=> 'form-control']) ?>
	</div>

	<div class="form-group">  
	<?= $form->field($model, 'email_id',['template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 	
		])->textInput(['maxlength' => 50]) ?>
	</div>

	<div class="form-group"> 
		<?= $form->field($model, 'phone_number',['template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 	
		])->textInput(['maxlength' => 50]) ?>
	</div>
	
	<div class="form-group"> 
    <?= $form->field($model, 'site_location',[ 'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
   		])->textArea(['maxlength' => 50]) ?>   	
	</div>
  
  
    <div class="form-group"> 
	<?= $form->field($model, 'site_copyright',['template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 		
		])->textInput(['maxlength' => 200]) ?>
    </div>
  
	<div class="form-group">   
	<?= $form->field($model, 'currency_symbol',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->dropDownList(['KD' => 'KD', 'USD' => 'USD', 'INR' => 'INR','EURO'=>'EURO']) ?>
    </div>
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
     <?= Html::a('Back', ['site/index'], ['class' => 'btn btn-default']) ?>
     </div>

    <?php ActiveForm::end(); ?>

</div>
