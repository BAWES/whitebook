<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = Yii::t('frontend','404 Not Found');
?>
<div class="site-error container event_middle_tab">
    <div class="content">
        <h1><?=Yii::t('frontend','404 Not Found')?></h1>
        <p><?=Yii::t('frontend','The above error occurred while the Web server was processing your request')?></p>
        <p><?=Yii::t('frontend','Please contact us if you think this is a server error. Thank you')?></p>
    </div>
</div>
