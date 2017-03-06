<?php
	$this->title = Yii::t('frontend', 'Success | Whitebook');
?>
<section id="inner_pages_white_back">
    <div class="container paddng0">
        <div class="title_main">
			<h1><?= Yii::t('frontend', 'Success'); ?></h1>
		</div>
		<center>
			<h2><?= Yii::t('frontend', 'Congratulation! Your Payment Processed Successfully!') ?></h2>
			<br />
			<p><?= Yii::t(
					'frontend', 
					'We received your payment successfully. Your Booking ID is #{id}. You can view detail of your booking <a href="{lnk_track}">here</a>. Thank You, for shopping with us.',
					[
						'id' => $booking_id,
						'lnk_track' => $lnk_track
					]
				); ?></p>

		</center>
		<br />
		<br />
		<br />
	</div>
</section>
