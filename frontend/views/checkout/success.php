<?php
	$this->title = Yii::t('frontend', 'Success | Whitebook');
?>
<section id="inner_pages_white_back">
    <div class="container paddng0">
        <div class="title_main">
			<h1><?= Yii::t('frontend', 'Success'); ?></h1>
		</div>
		<center>
			<h2><?= Yii::t('frontend', 'Congratulation! Your Order Request Sent To Admin Successfully!') ?></h2>
			<br />
			<p><?= Yii::t(
					'frontend', 
					'Product Request has been sent successfully. Your Request Order ID is #{id}. You can track and view detail of your request order in <a href="{order_page}">My Orders</a>. Thank You, for shopping with us.',
					[
						'id' => $order_id,
						'order_page' => $order_page
					]
				); ?></p>

		</center>
		<br />
		<br />
		<br />
	</div>
</section>
