<?php 

use yii\helpers\Url;
use yii\web\view;

$this->title = Yii::t('frontend', 'Checkout | Whitebook'); 

if(Yii::$app->user->isGuest) {
    $step_width = 'col-xs-4';
}else{
    $step_width = 'col-xs-6';
}
?>

<section id="inner_pages_white_back">
    <div class="container paddng0">
       
        <div class="title_main">
			<h1><?= Yii::t('frontend', 'Checkout'); ?></h1>
		</div>

		<div class="lead text-center row checkout-wizard">

            <?php if(Yii::$app->user->isGuest) { ?>
            <div id="ar-step-login" class="<?= $step_width ?> checkout-step text-muted" data-checkout-step="1">
                <div class="progress"><div class="progress-bar"></div></div>
                <div class="glyphicon-stack">
                    <i class="glyphicon glyphicon-user"></i>
                </div>
                <div class="glyphicon-title">
                    <span><?= Yii::t('frontend', 'Login') ?></span>
                </div>
            </div>
            <?php } ?>
            
            <div id="ar-step-address" class="<?= $step_width ?> checkout-step text-muted" data-checkout-step="2">
                <div class="progress"><div class="progress-bar"></div></div>
                <div class="glyphicon-stack">
                    <i class="glyphicon glyphicon-map-marker"></i>
                </div>
                <div class="glyphicon-title">
                    <span><?= Yii::t('frontend', 'Address') ?></span>
                </div>
            </div>
            <div id="ar-step-payment" class="<?= $step_width ?> checkout-step text-muted" data-checkout-step="3">
                <div class="progress"><div class="progress-bar"></div></div>
                <div class="glyphicon-stack">
                    <i class="glyphicon glyphicon-list-alt"></i>
                </div>
                <div class="glyphicon-title">
                    <span><?= Yii::t('frontend', 'Confirm Request') ?></span>
                </div>
            </div>
        </div>

        <div class="checkout_message_wrapper"></div>

        <div class="checkout_content_wrapper">
        	
        </div>

    </div>
</section>

<?php

$this->registerJs("

    var login_url = '".Url::to(['checkout/login'])."'; 
    var cart_url = '".Url::to(['cart/index'])."'; 
    var address_url = '".Url::to(['checkout/address'])."'; 
	var save_address_url = '".Url::to(['checkout/save-address'])."'; 
    var save_guest_address_url = '".Url::to(['checkout/save-guest-address'])."'; 
	var payment_url = '".Url::to(['checkout/payment'])."'; 
	var save_payment_url = '".Url::to(['checkout/save-payment'])."'; 
	var confirm_url = '".Url::to(['checkout/confirm'])."'; 
    var add_address_url = '".Url::to(['checkout/add-address'])."'; 
    var questions_url = '".Url::to(['/users/questions'])."';
    var city_url = '".Url::to(['/site/city'])."';
    var area_url = '".Url::to(['/site/area'])."';
    var msg_please_select_address_for_each_items = '".Yii::t('frontend', 'Error: Please select address for each items!')."';

", View::POS_HEAD);

$this->registerJsFile('@web/js/checkout.js?v=1.5', ['depends' => [\yii\web\JqueryAsset::className()]]);