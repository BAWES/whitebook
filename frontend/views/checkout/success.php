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

?>
<section id="inner_pages_white_back">
    <div class="container paddng0">
        <div class="title_main">
            <h1><?= Yii::t('frontend', 'Success'); ?></h1>
        </div>
        <center>
            <h2><?= Yii::t('frontend', 'Congratulation! Your Booking Request Sent To Admin Successfully!') ?></h2>
            <br />
            <p>
                <?php
                if (Yii::$app->user->isGuest) {
                    $booking_page = \yii\helpers\Url::to(['booking/view'],true);
                    echo Yii::t(
                        'frontend',
                        'Product Request has been sent successfully. Your Booking ID {id}. You can track and view detail of your booking in <a href="{booking_page}">Track Bookings</a> using booking Token sent in your email. <br/>Thank You, for shopping with us.',
                        [
                            'id' => implode(', ', $ids),
                            'booking_page' => $booking_page
                        ]
                    );
                } else {
                    $booking_page = \yii\helpers\Url::to(['booking/index'], true);
                    echo Yii::t(
                        'frontend',
                        'Product Request has been sent successfully. Your Booking ID {id}. You can track and view detail of your booking in <a href="{booking_page}">My Bookings</a>. Thank You, for shopping with us.',
                        [
                            'id' => implode(', ', $ids),
                            'booking_page' => $booking_page
                        ]
                    );
                }
                ?></p>

        </center>
        <br />
        <br />
        <br />
    </div>
</section>