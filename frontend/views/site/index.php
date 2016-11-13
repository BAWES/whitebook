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

<div id="home_slider" class="position-relative">
    <?php
        if(Yii::$app->language == 'en'){
            $url = 'https://slider.thewhitebook.com.kw/embed_whitebook.php?alias='.$home_slider_alias;
        }else{
            $url = 'https://slider.thewhitebook.com.kw/embed_whitebook.php?alias=arabic-slider';
        }

        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        echo curl_exec($ch);
        curl_close($ch);
    ?>

<?=$this->render('_search');?>
</div>

<!-- Content start -->
<section id="content_section">

<?php if (!Yii::$app->user->isGuest) { ?>
    <br />
    <div id="event_slider_wrapper">
        <div class="container paddng0">
        <?=$this->render('/product/events_slider.php'); ?>
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
<!-- hide temporary
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
</div>-->

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
        <h2><?=\common\components\LangFormat::format($value['group_name'],$value['group_name_ar']); ?></h2>
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

                                $item_url = Url::to(['browse/detail',
                                    'slug' => $product_val["slug"]
                                ]);

                            ?>
                            <div class="item">
                                <div class="fetu_product_list index_redirect" data-hr='<?= $item_url ?>'>

                                    <a href="<?= $item_url ?>" class='index_redirect' data-hr='<?= $item_url; ?>'>

                                        <?= Html::img($imglink); ?>

                                        <div class="deals_listing_cont">
                                            <?=\common\components\LangFormat::format($product_val['vendor_name'],$product_val['vendor_name_ar']); ?>
                                            <?=\common\components\LangFormat::format($product_val['item_name'],$product_val['item_name_ar']); ?>
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

$this->registerCss("
.fetu_product_list .index_redirect img {width: 100%;height: 219px;}
.color-white{color:#fff;}
.left-offset-25{float: left;width: 15%;}
.left-div{width:100%;position:absolute;bottom: 1px;padding:13px;    z-index: 999;}
.date-div{padding-right: 0px; margin-bottom: 13px;}
.black-overlay{width:100%;background-color: #000;position:absolute;bottom: 1px;padding: 42px;opacity: 0.69}
.location-div{padding-right: 0px; margin-bottom: 13px;}
#delivery_date{height: 45px;color: #000! important;}
.btn-submit{padding: 12px;}
#top_header {z-index: 9999;}
.bootstrap-select .dropdown-toggle {padding: 12px 12px;}
.datepicker{border: 2px solid rgb(242, 242, 242);}
.datepicker table {font-size: 12px;}
.position-relative{position: relative;}
.height-46{height:46px}
.position_news{top: 6px;}
#delivery_date{color:#000! important;}
.bg-000 {background:#000! important;}
.color-fff {color:#fff! important;}
.width-5-percent{width: 86px;}
.padding-right-0{    padding-right: 0px;}
.padding-left-0{    padding-left: 0px;}
.or-area{
    width: 40px;
    color: #fff;
    text-align: center;
    padding: 13px 0px;
}
.width-45-percent{width:45%}
.width-44-percent{width:44%}
.width-50-percent{width:50%}
.width-10-percent{width:10%}
.width-100-percent{width:100%}
@media screen and (max-width: 770px) {
.desktop-view-search{display:none!important;}
.mobile-view-search{display:block!important;}
.or_mobile{text-align:center;color:#fff;padding:13px 0;width:35px;}
.add-on.position_news{top:7px;}
.black-overlay{padding:28px;}
}
.mobile-view-form-popup{
    background: #fff none repeat scroll 0 0;
    height: 100%;
    left: 1px;
    position: fixed;
    text-align: center;
    top: 65px;
    width: 100%;
}
.mobile-view-form-popup h4 a{
    float: right;
    margin-right: 15px;
    color:#000;
}
.mobile-view-form-popup h4{
 background: #f2f2f2 none repeat scroll 0 0;
    margin: 0 0 10px;
    padding: 17px;
}
.margin-top-15{margin-top:15px;}
.mobile-search-enable .mobile-logo-text{
    display:none;
}
.mobile-search-enable .border-top-yellow{
    min-height:63px;
}

");?>
<!-- Hide BG FOR EVENT SLIDER
 VIDEO PLAY HOME END -->

<!--END RESPONSIVE FOR HOME PAGE PLAN, SHOP, EXPERIENCE IMAGES WITH A TAG IMPORTANT-->
