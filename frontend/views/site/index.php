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
    $session = Yii::$app->session;
    $dLocation = $session->get('deliver-location');
    $date = $session->get('deliver-date');
    ?>

    <div class="col-lg-12 col-md-12 col-sm-12 clearfix left-div">
            <form id='area-selection' name='area-selection' action="<?=Url::toRoute(['browse/list'],true);?>">
                <p class="text-center color-white">Select Product Delivery Location & Date</p>
                <input type="hidden" name="slug" value="all">
                <div class="left-offset-25">&nbsp;</div>
                    <div class="col-lg-3 col-sm-3 col-md-3 location-div">
                        <select class="selectpicker trigger" name="location" data-style="btn-default" id="location_name" data-live-search="true" data-size="10">
                            <option value="">All</option>
                            <?php
                            $cities = \common\models\City::find()->where(['trash'=>'Default','status'=>'Active'])->with('locations')->all();
                            $list = '';
                            foreach ($cities as $city) {
                                $city_name = (Yii::$app->language == 'en') ? $city->city_name : $city->city_name_ar;
                                $list .= '<optgroup label='.$city_name.'>';
                                if (isset($city->locations)) {
                                    foreach ($city->locations as $location) {
                                        if ($location->trash == 'Default' && $location->status=='Active') {
                                            $location_name = (Yii::$app->language == 'en') ? $location->location : $location->location_ar;
                                            $selected = (isset($dLocation) && $dLocation != '' && $dLocation == $location->id) ? 'selected="selected"' : '';
                                            $list .= '<option value="'.$location->id.'" '.$selected.'>'.$location_name.'</option>';
                                        }
                                    }
                                }
                                $list .= '</optgroup>';
                            }
                            echo $list;
                            ?>
                        </select>
                    </div>
                    <div class="col-lg-3 col-sm-3 col-md-3 date-div">
                        <div data-date-format="dd-mm-yyyy" data-date="12-02-2012" id="dp3" class="input-append date">
                        <input value="<?=$date?>" type="text" name="date" id="delivery_date" readonly size="16" class="form-control required datetimepicker date1" placeholder="<?php echo Yii::t('frontend', 'Choose Event Date'); ?>" title="<?php echo Yii::t('frontend', 'Choose Delivery Date'); ?>">
                        <span class="add-on position_news"> <i class="flaticon-calendar189"></i></span>
                        </div>
                    </div>
                    <div class="col-lg-2 col-sm-2 col-md-2">
                        <input type="submit" class="btn btn-default btn-submit" value="Find">
                    </div>
            </form>
        </div>
    <div class="col-lg-12 col-sm-12 col-md-12 black-overlay">&nbsp;</div>
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

                                $item_url = Url::to(['browse/detail',
                                    'slug' => $product_val["slug"]
                                ]);

                            ?>
                            <div class="item">
                                <div class="fetu_product_list index_redirect" data-hr='<?= $item_url ?>'>

                                    <a href="<?= $item_url ?>" class='index_redirect' data-hr='<?= $item_url; ?>'>

                                        <?= Html::img($imglink); ?>

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

$this->registerCss("
.fetu_product_list .index_redirect img {
    width: 100%;
    height: 219px;
}
.color-white{color:#fff;}
.left-offset-25{float: left;width: 25%;}
.left-div{width:100%;position:absolute;bottom: 1px;padding:19px;    z-index: 999;}
.date-div{padding-right: 0px; margin-bottom: 13px;}
.black-overlay{width:100%;background-color: #000;position:absolute;bottom: 1px;padding: 55px;opacity: .4}
.location-div{padding-right: 0px; margin-bottom: 13px;}
#delivery_date{height: 45px;color: #000! important;}
.btn-submit{padding: 12px;}
#top_header {z-index: 9999;}
.bootstrap-select .dropdown-toggle {
    padding: 12px 12px;
}
.datepicker{
    border: 2px solid rgb(242, 242, 242);
}
.datepicker table {
    font-size: 12px;
}
.position-relative{position: relative;}
.position_news{top: 6px;}
#delivery_date{color:#000! important;}
");?>
<!-- Hide BG FOR EVENT SLIDER
 VIDEO PLAY HOME END -->

<!--END RESPONSIVE FOR HOME PAGE PLAN, SHOP, EXPERIENCE IMAGES WITH A TAG IMPORTANT-->
