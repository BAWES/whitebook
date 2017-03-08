<?php
	$this->title = Yii::t('frontend', 'Success | Whitebook');
?>
<section id="inner_pages_white_back">
    <div class="container paddng0">
        <div class="title_main">
			<h1><?= Yii::t('frontend', 'Success'); ?></h1>
		</div>
		<center>
			<h2><?= Yii::t('frontend', 'Congratulation! Your Booking Request Sent To Admin Successfully!') ?></h2>
			<br />
			<p><?= Yii::t(
					'frontend', 
					'Product Request has been sent successfully. Your Booking ID {id}. You can track and view detail of your booking in <a href="{booking_page}">Pending Bookings</a>. Thank You, for shopping with us.',
					[
						'id' => implode(', ', $arr_booking_id),
						'booking_page' => $booking_page
					]
				); ?></p>

		</center>
		<br />
		<br />
		<br />
	</div>
</section>
