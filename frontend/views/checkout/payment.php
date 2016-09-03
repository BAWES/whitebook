<?php 

use yii\helpers\Url;

?>

<div class="panel panel-default panel-pg-list">
    <div class="panel-heading">
            <h4 class="panel-title">
                    <?= Yii::t('frontend', 'Select payment method') ?>                        
            </h4>
    </div>
    <div class="panel-body">

    	<?= Yii::t('frontend', 'Please select the preferred payment method to use on this order.') ?>

        <?php foreach ($payment_gateway as $row) { ?>
    	<div class="radio">
            <input type="radio" name="payment_method" id="<?= $row->code ?>" value="<?= $row->code ?>" />   
    	    <label for="<?= $row->code ?>">                    
		        <?php if(Yii::$app->language == 'en') {
                    echo $row->name;
                } else {
                    echo $row->name_ar;
                } ?>
		    </label>
		</div>
        <?php } ?>
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
