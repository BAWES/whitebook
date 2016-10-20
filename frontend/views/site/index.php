<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\view;
use common\models\FeatureGroup;
use common\models\FeatureGroupItem;
use common\models\VendorItem;
use common\models\Vendor;
use common\models\Themes;
use common\models\Image;
use frontend\models\Website;
use common\components\CFormatter;

$this->title = 'Home | Whitebook';

$model = new Website();

?>
<!-- content main start -->

<div id="home_slider">
    <?php
        if(Yii::$app->language == 'en'){
            $url = 'http://slider.thewhitebook.com.kw/embed_whitebook.php?alias='.$home_slider_alias;
        }else{
            $url = 'http://slider.thewhitebook.com.kw/embed_whitebook.php?alias=arabic-slider';
        }

        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        echo curl_exec($ch);
        curl_close($ch);
    ?>
</div>

<!-- Content start -->
<section id="content_section">

<?php if (!Yii::$app->user->isGuest) { ?>
    <br />
    <div id="event_slider_wrapper">
        <div class="container paddng0">
        <?php require(__DIR__ . '/../product/events_slider.php'); ?>
        </div>
    </div>
    <br />
<?php } ?>

<div class="container_plan">

<div class="container_common">

<?php if (Yii::$app->user->isGuest) { ?>
<span class="first_events">
    <?= Yii::t('frontend', 'My Events'); ?>
</span>
<div class="creatfirst_events">
    <p data-example-id="active-anchor-btns" class="bs-example">
        <a href="javascript:" role="button" class="btn btn-default"  data-toggle="modal" data-target="#myModal" onclick="show_login_modal(-1);">
            <?= Yii::t('frontend', 'Create Your First Event'); ?>
        </a>
    </p>
</div>
<br />
<br />
<?php } ?>

<!-- Events slider end -->

<div class="plan_sections">
<ul>
    <li>
        <div class="plan_list">
            <?= Html::img('@web/images/plan-home.jpg', ['alt' => 'Plan']) ?>
            <div class="inner_content_plan">
                <h1><?= Yii::t("frontend", "Plan") ?></h1>
                <p><?= Yii::t("frontend", "Plan is where you browse, get ideas, and plan your event") ?></p>
                <a href="<?= Url::toRoute('plan/plans'); ?>" role="button" class="btn btn-default"><?= Yii::t("frontend", "Discover") ?></a>
            </div>
        </div>
    </li>
    <li>
        <div class="plan_list">
            <?= Html::img('@web/images/shop-home.jpg', ['alt' => 'Shop']) ?>
            <div class="inner_content_plan">
                <h1><?= Yii::t("frontend", "Shop") ?></h1>
                <p><?= Yii::t("frontend", "Shop is where you purchase, customise, and schedule delivery of your products and services") ?></p>
                <a href="<?= Url::toRoute('site/shop'); ?>" role="button" class="btn btn-default"><?= Yii::t("frontend", "Discover") ?></a>
            </div>
        </div>
    </li>
    <li>
        <div class="plan_list">
            <?= Html::img('@web/images/experience-home.jpg', ['alt' => 'Experience']) ?>
            <div class="inner_content_plan">
                <h1><?= Yii::t("frontend", "Experience") ?></h1>
                <p><?= Yii::t("frontend", "Experience is a list of value added services provided by The White Book's team") ?></p>
                <a href="<?= Url::toRoute('site/experience'); ?>" role="button" class="btn btn-default"><?= Yii::t("frontend", "Discover") ?></a>
            </div>
        </div>
    </li>
</ul>
</div>

<!-- BEGIN FEATURE GROUP ITEM-->
<?php

$featured_produc = FeatureGroup::find()
    ->select(['group_id', 'group_name_ar', 'group_name'])
    ->where(['group_status' => 'Active', 'trash' => 'Default'])
    ->asArray()->all();

$i = 1;
foreach ($featured_produc as $key => $value) {

 $feature_group_sql_result = FeatureGroupItem::find()->select([
        '{{%vendor_item}}.*',
        '{{%feature_group_item}}.vendor_id',
        '{{%vendor}}.vendor_name',
        '{{%vendor}}.vendor_name_ar'
    ])
    ->joinWith('item')
    ->joinWith('vendor')
    //->join('inner join','{{%image}}','{{%image}}.item_id = {{%vendor_item}}.item_id')
    ->where(['{{%feature_group_item}}.group_id'=>$value["group_id"]])
    //->andWhere(['{{%vendor_item}}.type_id'=>2])
    ->andWhere(['{{%vendor_item}}.trash'=>"Default"])
    //->andWhere(['{{%vendor_item}}.item_for_sale'=>"Yes"])
    ->andWhere(['{{%vendor_item}}.item_status'=>"Active"])
    ->andWhere(['{{%feature_group_item}}.group_item_status'=>"Active"])
    ->andWhere(['{{%feature_group_item}}.trash'=>"Default"])
    ->asArray()
    ->all();

$count_items = count($feature_group_sql_result);

if (!empty($feature_group_sql_result)) {
?>
    <div class="feature_product_title">
        <h2>
        <?php

            if(Yii::$app->language == "en"){
                echo $value['group_name'];
            }else{
                echo $value['group_name_ar'];
            }
        ?>
        </h2>
    </div>
<?php } ?>


<!-- BEGIN FEATURE PRODUCT DESKTOP  -->
<div class="feature_product_slider">
    <div class="most_popular_slider">
        <div class="slider_new_up">
            <div class="flexslider3">
                <div id="demo">
                    <div class="owl-carousel twb-slider" id="feature-group-slider" >
                        <?php

                        $i = 0;

                        foreach ($feature_group_sql_result as $product_val) {

                            $image_row = Image::find()->select(['image_path'])
                                ->where(['item_id' => $product_val['item_id']])
                                ->orderby(['vendorimage_sort_order'=>SORT_ASC])
                                ->asArray()
                                ->one();

                            if ($image_row) {
                                $imglink = Yii::getAlias("@s3/vendor_item_images_210/")
                                    . $image_row['image_path'];
                            } else {
                                $imglink = Yii::getAlias("@web/images/no_image.jpg");
                            }

                            if($product_val['item_for_sale'] == 'Yes'){
                                
                                $item_url = Url::to(['shop/product', 
                                    'slug' => $product_val["slug"]
                                ]);

                            } else {
                                
                                $item_url = Url::to(['product/product', 
                                    'slug' => $product_val["slug"]
                                ]);
                                
                            }

                            ?>
                            <div class="item">
                                <div class="fetu_product_list index_redirect" data-hr='<?= $item_url ?>'>

                                    <a href="<?= $item_url ?>" class='index_redirect' data-hr='<?= $item_url; ?>'>

                                        <?= Html::img($imglink,['style'=>'width:208px; height:219px;']); ?>

                                        <div class="deals_listing_cont">

                                            <?php if(Yii::$app->language == "en"){ ?>
                                                <?php echo $product_val['vendor_name']; ?>
                                                <h3><?php echo $product_val['item_name']; ?></h3>
                                            <?php }else{ ?>
                                                <?php echo $product_val['vendor_name_ar']; ?>
                                                <h3><?php echo $product_val['item_name_ar']; ?></h3>
                                            <?php } ?>

                                            <p>
                                                <?= CFormatter::format($product_val['item_price_per_unit']) ?>
                                            </p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        <?php $i++;
                        } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php } ?>

<!-- END FEATURE PRODUCT DESKTOP  -->
<!-- BEGIN FEATURE PRODUCT RESPONSIVE -->

</div>
</section>

<?php
if(!Yii::$app->user->isGuest && count($featured_product) > 0){
foreach ($featured_product as $f) {
?>
<div class="modal fade" id="addevent<?php echo $f['item_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content  modal_member_login signup_poupu row">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <div class="text-center">
            <span class="yellow_top"></span>
        </div>
        <h4 class="modal-title text-center" id="myModalLabel"><?php echo Yii::t('frontend', 'You are Adding'); ?></h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-xs-8 col-xs-offset-2">
                <div class="product_popup_signup">
                    <div class="product_popup_prod">
                        <div class="desc_popup_cont">
                            <h4><?php echo $f['vendor']['vendor_name']; ?></h4>
                            <h3><?php echo $f['item_name']; ?></h3>
                            <div class="text-center"><span class="borderslid"></span></div>
                            <h5><?= CFormatter::format($f['item_price_per_unit']) ?></h5>
                        </div>
                    </div>
                </div>
                <div class="product_popup_signup_box">
                    <div class="product_popup_signup_log">
                        <div class="add_event_form">

                        </div>
                        <div class="create_event_form">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
<?php }
}

$this->registerJs("

    if(jQuery(window).width() < 991) {
        var lop = 0;
        jQuery('.plan_sections ul li').each(function (index, value) {
            var hrefli = jQuery(this).find('a').attr('href');
            jQuery(this).find('a').remove();
            jQuery(this).html('<a href=\"' + hrefli + '\" >' + jQuery(this).html() + '<a>');
        });
    }

    /* VIDEO PLAY HOME START */
    jQuery(document).ready(function () {
        jQuery('a.play_buttons').click(function () {
            jQuery('#video_click')[0].play();
            jQuery('#video_click').attr('controls', true);
            jQuery('a.play_buttons').hide();
        });
        jQuery('#video_click').bind('ended', function () {
            //$('#thisdiv').load(document.URL +  ' #thisdiv');
            jQuery('#video_click').load();
            jQuery('#video_click').attr('controls', false);
            jQuery('a.play_buttons').show();
        });
    });

    /* Hide BG FOR EVENT SLIDER IMPORTANT*/
    jQuery('.directory_slider,.container_eventslider').load(event_slider_url, function(){
        jQuery(this).css('background','transparent');
        jQuery('.container_common').css('margin','0');
        jQuery('.event_slider_top').css({'padding':'5px 0 0 0','display':'inline-block','width':'100%','margin':'4px 0 0 0'});
    });

", View::POS_READY);

?>

<!-- Hide BG FOR EVENT SLIDER
 VIDEO PLAY HOME END -->

<!--END RESPONSIVE FOR HOME PAGE PLAN, SHOP, EXPERIENCE IMAGES WITH A TAG IMPORTANT-->
