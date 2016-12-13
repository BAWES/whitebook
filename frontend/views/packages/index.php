<?php

use yii\helpers\Url;

?>
<div class="container">
    <div class="title_main">
        <h1><?= Yii::t('frontend', 'Event Packages') ?></h1>
    </div>

    <br />
    <br />

    <div class="package_content">

        <ul class="cards">
            <?php foreach ($packages as $key => $value) { ?>
            <li style="background-image: url(<?= Url::to("@s3/".$value->package_background_image); ?>)">
                <a href="<?= Url::to(['packages/detail', 'slug' => $value->package_slug]) ?>">
                    <span class="card-info">
                        <span class="title">
                            <?php 

                            if(Yii::$app->language == 'en') { 
                                echo $value->package_name;
                            } else {
                                echo $value->package_name_ar;
                            } ?>
                        </span>
                    </span>         
                </a>
            </li>
            <?php } ?>
        </ul>
    </div>

</div>

<br />
<br />