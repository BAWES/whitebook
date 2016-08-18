<?php

use yii\helpers\Html;
use yii\helpers\Url;

?>

<section id="inner_pages_white_back">
    <div class="container paddng0">
        <div class="shop_sect">
            <div class="plan_inner_sec">
                <h2><?= Yii::t("frontend", "Shop") ?></h2>
                <h5 class="text-center"><?= Yii::t("frontend", "Shop is where you purchase, customise, and schedule delivery of your products and services") ?></h5>
            </div>
        </div>
        <div class="plan_catg">
        <ul>
                <li>
                    <a href="<?= Url::to(["shop/products", 'slug' => 'venues']); ?>" style="width: 50px;">
                        <span class="venue"></span>
                        <span class="responsi_common"><?= Yii::t("frontend", "Venues") ?></span>
                    </a>
                </li>
                <li>
                    <a href="<?= Url::to(["shop/products", 'slug' => 'invitations']); ?>" style="width: 70px;">
                        <span class="invitations "></span>
                        <?= Yii::t("frontend", "Invitations") ?>
                    </a>
                </li>
                <li>
                    <a href="<?= Url::to(["shop/products", 'slug' => 'food-beverage']); ?>">
                        <span class="food1"></span>
                        <?= Yii::t("frontend", "Food & Beverage") ?>
                    </a>
                </li>
                <li>
                    <a href="<?= Url::to(["shop/products", 'slug' => 'decor']); ?>" >
                        <span class="decor1"></span>
                        <?= Yii::t("frontend", "Decor") ?>
                    </a>
                </li>
                <li>
                    <a href="<?= Url::to(["shop/products", 'slug' => 'supplies']); ?>">
                        <span class="supplies1"></span>
                        <?= Yii::t("frontend", "Supplies") ?>
                    </a>
                </li>
                <li>
                    <a href="<?= Url::to(["shop/products", 'slug' => 'entertainment']); ?>" style="width: 90px;">
                        <span class="entertainment  "></span>
                        <?= Yii::t("frontend", "Entertainment") ?>
                    </a>
                </li>
                <li>
                    <a href="<?= Url::to(["shop/products", 'slug' => 'services']); ?>" style="width: 50px;">
                        <span class="services  "></span>
                        <?= Yii::t("frontend", "Services") ?>
                    </a>
                </li>
                <li>
                    <a href="<?= Url::to(["shop/products", 'slug' => 'others']); ?>" style="width: 40px;">
                        <span class="other1"></span>
                        <?= Yii::t("frontend", "Others") ?>
                    </a>
                </li>
                <li>
                    <a href="<?= Url::to(["shop/products", 'slug' => 'gift-favors']); ?>">
                        <span class="say1"></span>
                        <?= Yii::t("frontend", "Gift Favors") ?>
                    </a>
                </li>
            </ul>
        </div>
        <div class="add_banner">
            <?= Html::img("@web/images/explore_banner.jpg", ['alt' => 'Banner']) ?>
        </div>
    </div>
</section>
<!-- continer end -->
