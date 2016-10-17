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
                    <a href="<?= Url::to(["plan/products", 'slug' => 'venues']); ?>" class="venue_lnk">
                        <span class="venue"></span>
                        <span class="responsi_common"><?= Yii::t("frontend", "Venues") ?></span>
                    </a>
                </li>
                <li>
                    <a href="<?= Url::to(["plan/products", 'slug' => 'invitations']); ?>" class="invitations_lnk">
                        <span class="invitations "></span>
                        <?= Yii::t("frontend", "Invitations") ?>
                    </a>
                </li>
                <li>
                    <a href="<?= Url::to(["plan/products", 'slug' => 'food-beverage']); ?>">
                        <span class="food1"></span>
                        <?= Yii::t("frontend", "Food & Beverage") ?>
                    </a>
                </li>
                <li>
                    <a href="<?= Url::to(["plan/products", 'slug' => 'decor']); ?>" >
                        <span class="decor1"></span>
                        <?= Yii::t("frontend", "Decor") ?>
                    </a>
                </li>
                <li>
                    <a href="<?= Url::to(["plan/products", 'slug' => 'supplies']); ?>">
                        <span class="supplies1"></span>
                        <?= Yii::t("frontend", "Supplies") ?>
                    </a>
                </li>
                <li>
                    <a href="<?= Url::to(["plan/products", 'slug' => 'entertainment']); ?>" class="entertainment_lnk">
                        <span class="entertainment  "></span>
                        <?= Yii::t("frontend", "Entertainment") ?>
                    </a>
                </li>
                <li>
                    <a href="<?= Url::to(["plan/products", 'slug' => 'services']); ?>" class="services_lnk">
                        <span class="services  "></span>
                        <?= Yii::t("frontend", "Services") ?>
                    </a>
                </li>
                <li>
                    <a href="<?= Url::to(["plan/products", 'slug' => 'others']); ?>" class="others_lnk">
                        <span class="other1"></span>
                        <?= Yii::t("frontend", "Others") ?>
                    </a>
                </li>
                <li>
                    <a href="<?= Url::to(["plan/products", 'slug' => 'gift-favors']); ?>">
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
