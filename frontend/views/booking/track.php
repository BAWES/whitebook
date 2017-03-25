<?php 

use yii\helpers\Url;
use yii\widgets\LinkPager;
use common\models\Order;
use common\components\CFormatter;

$this->title = Yii::t('frontend', 'Track Booking | Whitebook'); 

?>
<section id="inner_pages_sections">
    <div class="container ">
        <div class="title_main">
			<h1><?= Yii::t('frontend', 'Track Booking'); ?></h1>
		</div>
		<div class="account_setings_sections clearfix">

			<br />
			<br />

			<form class="form-horizontal form-track">
				
				<div class="input-group">
				  <input type="text" class="form-control" name="booking_token" placeholder="<?= Yii::t('frontend', 'Enter Token') ?>" />
				  <span class="input-group-addon" id="basic-addon2">
				  	<button class="btn btn-lg brn-primary">
						<?= Yii::t('frontend', 'Submit') ?>
					</button>
				  </span>
				</div>
				
			</form>
		</div>
	</div>
</section>
