<?php 

use yii\helpers\Url;

?>

<div class="panel panel-default">
    <div class="panel-heading">
            <h4 class="panel-title">
                    <?= Yii::t('frontend', 'Select payment method') ?>                        
            </h4>
    </div>
    <div class="panel-body">

    	<?= Yii::t('frontend', 'Please select the preferred payment method to use on this order.') ?>

    	<div class="radio">
    	<input type="radio" name="payment_method" value="cod" checked="checked" />
		    <label>		        
		        <?= Yii::t('frontend', 'Cash On Delivery') ?>
		    </label>
		</div>
    </div>
</div>

<div class="btn-set">
        <button onclick="address();" class="btn btn-primary btn-checkout pull-left" style="margin-left: 0;">
                <?= Yii::t('frontend', 'Back') ?>
        </a>

        <button class="btn btn-primary btn-checkout pull-right" onclick="save_payment();">
                <?= Yii::t('frontend', 'Next') ?>
        </button>
</div>

<br />
<br />
<br />
