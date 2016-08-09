<?php

use yii\helpers\Url;
use yii\helpers\Html;
?>
<!-- coniner start -->

<section id="inner_pages_white_back">
    <div class="container paddng0">
        <div class="plan_sect">
            <div class="plan_inner_sec">
                <h2><?= Yii::t("frontend", "Plan") ?></h2>
                <h5 style='text-align: center;'><?= Yii::t("frontend", "Plan is where you browse, get ideas, and plan your event") ?></h5>
            </div>
        </div>
        <div class="plan_catg">
            <ul>
                <li>
                    <a href="<?= Url::to(["plan/plan", 'slug' => 'venues']); ?>" style="width: 50px;">
                        <span class="venue"></span>
                        <span class="responsi_common"><?= Yii::t("frontend", "Venues") ?></span>
                    </a>
                </li>
                <li>
                    <a href="<?= Url::to(["plan/plan", 'slug' => 'invitations']); ?>" style="width: 70px;">
                        <span class="invitations "></span>
                        <?= Yii::t("frontend", "Invitations") ?>
                    </a>
                </li>
                <li>
                    <a href="<?= Url::to(["plan/plan", 'slug' => 'food-beverage']); ?>">
                        <span class="food1"></span>
                        <?= Yii::t("frontend", "Food & Beverage") ?>
                    </a>
                </li>
                <li>
                    <a href="<?= Url::to(["plan/plan", 'slug' => 'decor']); ?>" >
                        <span class="decor1"></span>
                        <?= Yii::t("frontend", "Decor") ?>
                    </a>
                </li>
                <li>
                    <a href="<?= Url::to(["plan/plan", 'slug' => 'supplies']); ?>">
                        <span class="supplies1"></span>
                        <?= Yii::t("frontend", "Supplies") ?>
                    </a>
                </li>
                <li>
                    <a href="<?= Url::to(["plan/plan", 'slug' => 'entertainment']); ?>" style="width: 90px;">
                        <span class="entertainment  "></span>
                        <?= Yii::t("frontend", "Entertainment") ?>
                    </a>
                </li>
                <li>
                    <a href="<?= Url::to(["plan/plan", 'slug' => 'services']); ?>" style="width: 50px;">
                        <span class="services  "></span>
                        <?= Yii::t("frontend", "Services") ?>
                    </a>
                </li>
                <li>
                    <a href="<?= Url::to(["plan/plan", 'slug' => 'others']); ?>" style="width: 40px;">
                        <span class="other1"></span>
                        <?= Yii::t("frontend", "Other") ?>
                    </a>
                </li>
                <li>
                    <a href="<?= Url::to(["plan/plan", 'slug' => 'gift-favors']); ?>">
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
