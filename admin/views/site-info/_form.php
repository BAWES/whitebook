<?php

use yii\helpers\Html;
use yii\helpers\BaseHtml;

?>

<div class="col-md-8 col-sm-8 col-xs-8">	
	 
	<?= BaseHtml::beginForm(); ?>
	
	<?php foreach ($data as $key => $value) { ?>	
		<div class="form-group"> 
			<?= Html::label(
					ucfirst(str_replace('_', ' ', $value->name)), 
					$value->name, 
					['class' => 'control-label']
				) ?>
			<div class="controls">
				<?= Html::input('text', $value->name, $value->value, ['class' => 'form-control']); ?>
			</div>
		</div>		
	<?php } ?>


    <div class="form-group">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
    	<?= Html::a('Back', ['site/index'], ['class' => 'btn btn-default']) ?>
    </div>

    <?= BaseHtml::endForm(); ?>

</div>
