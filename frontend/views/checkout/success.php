<?php

$this->title = Yii::t('frontend', 'Success | Whitebook');

$ids = [];

foreach ($arr_booking_id as $key => $value) 
{
    if(strlen($value) < 4) {
        $ids[] = '#'.str_repeat(0, 4 - strlen($value)).$value;
    } else {
        $ids[] = '#'.$value;
    }
}

$booking_page = \yii\helpers\Url::to(['booking/view'],true);

?>
<section id="inner_pages_white_back">
    <div class="container paddng0">
        <div class="title_main">
            <h1><?= Yii::t('frontend', 'Success'); ?></h1>
        </div>
        
        <center>
            <h2><?= Yii::t('frontend', 'Your Booking Requests have been sent') ?></h2>
            
            <h3>
                <?= Yii::t('frontend', 'You\'ll receive a payment link once the vendors have confirmed your booking.') ?>
            </h3>
            <h3 style="color: #8F8F8F;">
                <?= Yii::t('frontend', 'Booking ID: {id}', [
                        'id' => implode(', ', $ids)
                    ]) ?> 
            </h3>

            <Br />

            <a href="<?= $booking_page ?>" class="btn btn-booking-success btn-primary btn-lg">
                <?= Yii::t('frontend', 'Track your order') ?>
            </a>
        </center>
        <br />
        <br />
        <br />
    </div>
</section>