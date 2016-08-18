<?php
    use yii\helpers\Html;
    use yii\widgets\Menu;
?>
<!-- coniner start -->

<section id="inner_pages_white_back">
    <div class="container paddng0">
        <div class="shop_sect">
            <div class="plan_inner_sec">
                <h2><?= Yii::t("frontend", "Shop") ?></h2>
                <h5 class="text-center"><?= Yii::t("frontend", "Shop is where you purchase, customise, and schedule delivery of your products and services") ?></h5>
            </div>
        </div>
        <div class="plan_catg">
            <?=Menu::widget(
                [
                'encodeLabels' => false,
                'items' => [
                    ['label' => '<span class="venue"></span><span class="responsi_common">'.Yii::t("frontend", "Venues").'</span>', 'url' => ["shop/products", 'slug' => 'venues']],
                    ['label' => '<span class="invitations"></span>'.Yii::t("frontend", "Invitations"), 'url' => ["shop/products", 'slug' => 'invitations']],
                    ['label' => '<span class="food1"></span>'.Yii::t("frontend", "Food & Beverage"), 'url' => ["shop/products", 'slug' => 'food-beverage']],
                    ['label' => '<span class="decor1"></span>'.Yii::t("frontend", "Decor"), 'url' => ["shop/products", 'slug' => 'decor']],
                    ['label' => '<span class="supplies1"></span>'.Yii::t("frontend", "Supplies"), 'url' => ["shop/products", 'slug' => 'supplies']],
                    ['label' => '<span class="entertainment"></span>'.Yii::t("frontend", "Entertainment"), 'url' => ["shop/products", 'slug' => 'entertainment']],
                    ['label' => '<span class="services"></span>'.Yii::t("frontend", "Services"), 'url' => ["shop/products", 'slug' => 'services']],
                    ['label' => '<span class="other1"></span>'.Yii::t("frontend", "Other"), 'url' => ["shop/products", 'slug' => 'others']],
                    ['label' => '<span class="say1"></span>'.Yii::t("frontend", "Gift Favors"), 'url' => ["shop/products", 'slug' => 'gift-favors']],
                ],
            ]);
            ?>
        </div>
        <div class="add_banner">
            <?= Html::img("@web/images/explore_banner.jpg", ['alt' => 'Banner']) ?>
        </div>
    </div>
</section>
<!-- continer end -->
