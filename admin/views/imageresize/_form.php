<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Imageresize */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="col-md-8 col-sm-8 col-xs-8">	
    <?php $form = ActiveForm::begin(); ?>

	<div class="form-group">  
	  <?= $form->field($model, 'logo_width',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"
	  	])->textInput() ?>
	</div>
	
	<div class="form-group"> 
	   <?= $form->field($model, 'logo_height',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"
					])->textInput() ?>
	</div>
	
	<div class="form-group">  
	  <?= $form->field($model, 'item_list_width',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"
	  	])->textInput() ?>
	</div>
	
	<div class="form-group">  
	  <?= $form->field($model, 'item_list_height',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"
	  	])->textInput() ?>
	</div>
	
	<div class="form-group">  
	  <?= $form->field($model, 'item_detail_width',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"
	  	])->textInput() ?>
	</div>
	
	<div class="form-group">  
	  <?= $form->field($model, 'item_detail_height',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"
	  	])->textInput() ?>
	</div>	
	<div class="form-group">  
	  <?= $form->field($model, 'item_cart_width',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"
	  	])->textInput() ?>
	</div>
	
	<div class="form-group">  
	  <?= $form->field($model, 'item_cart_height',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"
	  	])->textInput() ?>
	</div>	
		
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	<?= Html::a('Back', ['site/index', ], ['class' => 'btn btn-default']) ?>
	</div>
    <?php ActiveForm::end(); ?>

</div>
