<?php
$this->title = Yii::t('frontend', 'Checkout Error | Whitebook'); 

?>

<section id="inner_pages_white_back">
    <div class="container paddng0">
       
        <div class="title_main">
			<h1><?= Yii::t('frontend', 'Warning'); ?></h1>
		</div>

		<center>

			<h2><?= Yii::t('frontend', 'Transaction not completed successfully!') ?></h2>
			
			<br />

			<p><?= $message ?></p>

		</center>
		
		<br />
		<br />
		<br />

	</div>
</section>
