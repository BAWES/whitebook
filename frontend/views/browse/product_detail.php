<?php

use yii\web\view;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use common\components\LangFormat;
use common\components\CFormatter;
use common\models\VendorItemPricing;
use common\models\ItemType;

$vendor_det = $model->vendor;

$item_name = LangFormat::format($model['item_name'],$model['item_name_ar']);
$vendor_name = LangFormat::format($vendor_det['vendor_name'],$vendor_det['vendor_name_ar']);
$item_description = LangFormat::format($model['item_description'],$model['item_description_ar']);
$item_additional_info = LangFormat::format($model['item_additional_info'],$model['item_additional_info_ar']);
$vendor_contact_address = LangFormat::format($vendor_det['vendor_contact_address'],$vendor_det['vendor_contact_address_ar']);

$this->title = 'Whitebook - ' . $item_name;

$this->params['breadcrumbs'][] = $item_name;

if($model->images) {
    $image = Yii::getAlias("@s3/vendor_item_images_530/"). $model->images[0]->image_path;
}else{
    $image = 'https://placeholdit.imgix.net/~text?txtsize=33&txt=530x530&w=530&h=550';
}

?>

<script type="application/ld+json">
{
  "@context": "http://schema.org/",
  "@type": "Product",
  "name": "<?= $item_name; ?>",
  "image": "<?= $image ?>",
  "description": "<?= $item_description ?>",
   "offers": {
    "@type": "Offer",
    "priceCurrency": "KWD",
    "price": "<?= $model['item_price_per_unit'] ?>",
    "availability": "http://schema.org/InStock",
    "seller": {
      "name": "<?= $vendor_name; ?>"
    }
  }
}
</script>

<!-- coniner start -->
<section id="inner_pages_white_back" class="product_details_com">

    <div id="event_slider_wrapper">
        <div class="container paddng0">
            <?php echo $this->render('events_slider'); ?>
        </div>
    </div>

    <div class="container paddng0">
        <div class="breadcrumb_common">
            <div class="bs-example">

                <?= Breadcrumbs::widget([
                        'options' => ['class' => 'new breadcrumb'],
                        'homeLink' => [
                            'label' => Yii::t('yii', 'Home'),
                            'url' => Yii::$app->homeUrl,
                        ],
                        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                    ]); ?>

            </div>
        </div>

        <!-- Mobile start Here-->
        <div class="product_detail_section responsive-detail-section"><!--product detail start-->
            <div class="col-md-12 padding0">
                
                <div class="product_detials_common normal_tables">

                    <div class="col-md-6 paddig0 resp_hide">
                        <div class="left_descrip mobile-view">
                            <h2><?= $item_name; ?></h2>
                            <label>
                                <a class="color-999999" title="<?= $vendor_det['vendor_name']; ?>" href="<?= Url::to(["directory/profile",'slug'=>'all','vendor' => $vendor_det['slug']]) ?>" >
                                    <?= $vendor_name; ?>
                                </a>
                            </label>

                            <b>
                                <?= CFormatter::format($model->item_price_per_unit)  ?>       
                            </b>

                        </div>
                        <!-- Indicators responsive slider -->
                        <div class="responsive_slider_detials">

                            <!--23-10-2015 slider start-->
                            <div class="carousel-inner owl-carousel" id="mobile-slider">
                                <?php
                                $baselink = Yii::$app->homeUrl . Yii::getAlias('@vendor_images/') . 'no_image.png';
                                if (isset($model->images) && count($model->images)>0) {
                                    foreach ($model->images as $img) {
                                        if ($img) {
                                            $imgPath = ($img->module_type == 'guides') ? Yii::getAlias("@s3/sales_guide_images/") : Yii::getAlias("@s3/vendor_item_images_530/");
                                            $baselink = $imgPath . $img->image_path;
                                        }
                                        ?>
                                        <div class="item"><img src="<?php echo $baselink; ?>" alt="item detail image" class="width-530-px"></div>
                                    <?php }
                                }
                                    ?>
                            </div>
                            <!--23-10-2015 slider end-->
                        </div>
                        <!-- Indicators responsive slider end -->
                    </div>

                    <div id="main" role="main" class="col-md-5 padding-right0 product-left-width">
                        <div class="slider">
                            <div id="slider" class="flexslider display_none">
                                <ul class="slides">

                                    <?php
                                        $baselink = Yii::$app->homeUrl . Yii::getAlias('@vendor_images/') . 'no_image.png';
                                    if (isset($model->images) && count($model->images)>0) {
                                        foreach ($model->images as $img) {
                                            if ($img) {
                                                    $imgPath = ($img['module_type'] == 'guides') ? Yii::getAlias("@s3/sales_guide_images/") : Yii::getAlias("@s3/vendor_item_images_530/");
                                                    $baselink = $imgPath . $img['image_path'];
                                                }
                                                ?>
                                                <li><img src="<?php echo $baselink; ?>" alt="item detail image" class="width-530-px"></li>
                                            <?php }
                                    } ?>
                                </ul>
                            </div>

                            <?php if (count($model->images) > 1) { ?>
                                <div id="carousel" class="flexslider display_none_thumb">
                                    <ul class="slides">
                                        <?php
                                        $baselink = Yii::$app->homeUrl . Yii::getAlias('@vendor_images/') . 'no_image.png';
                                        if (isset($model->images) && count($model->images)>0) {
                                            foreach ($model->images as $img) {
                                                if ($img) {
                                                    $imgPath = ($img['module_type'] == 'guides') ? Yii::getAlias("@s3/sales_guide_images/") : Yii::getAlias("@s3/vendor_item_images_530/");
                                                    $baselink = $imgPath . $img['image_path'];
                                                }
                                                ?>
                                                <li><img src="<?php echo $baselink; ?>" alt="<?=Yii::t('frontend','item detail image')?>"></li>
                                                <?php
                                            }
                                        }
                                            ?>
                                    </ul>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="col-md-7 product-right-width paddng0">
                        <div class="right_descr_product">
                            <div class="product_name">
                                <div class="left_descrip desktop-view">
                                    <h2><?= $item_name; ?></h2>
                                    
                                    <label>
                                        <a title="<?= $vendor_det['vendor_name']; ?>" href="<?= Url::to(["directory/profile",'slug'=>'all','vendor' => $vendor_det['slug']]) ?>"  class="color-999999">
                                            <?= $vendor_name; ?>
                                        </a>
                                    </label>

                                    <b><?= CFormatter::format($model->item_price_per_unit)  ?></b>

                                </div>
                                <div class="right_descrip">
                                    <div class="responsive_width">
                                        <!-- add to event start -->

                                        <?php if (Yii::$app->user->isGuest) { ?>
                                            <a href="" data-toggle="modal" class="add_events" data-target="#myModal" title="<?=Yii::t('frontend','Add to event')?>"  onclick="add_event_login(<?php echo $model['item_id']; ?>)">
                                                <span class="plus-icon-prod">
                                                    <?php echo Yii::t('frontend', 'Add to Event'); ?>
                                                </span>
                                            </a>
                                        <?php } else { ?>
                                            <a  href="#" role="button" id="<?php echo $model['item_id']; ?>" name="<?php echo $model['item_id']; ?>" class="add_events"  data-target="#add_to_event<?php echo $model['item_id']; ?>"   onclick="addevent('<?php echo $model['item_id']; ?>')" data-toggle="modal"  class="add_events" title="<?php echo Yii::t('frontend', 'Add to Event'); ?>">
                                                <span class="plus-icon-prod">
                                                    <?php echo Yii::t('frontend', 'Add to Event'); ?>
                                                </span>
                                            </a>
                                        <?php } ?>
                                        <!-- add to event end here -->


                                        <!-- Add to favourite start -->

                                        <?php if (Yii::$app->user->isGuest) { ?>
                                            <a href="" class="faver_evnt_product" data-toggle="modal" data-target="#myModal" onclick="show_login_modal_wishlist(<?php echo $model['item_id']; ?>);"  title="<?php echo Yii::t('frontend', 'Add to Things I like'); ?>">
                                                <span class="heart-product"></span>
                                            </a>
                                        <?php
                                        } else {
                                            $k = array();
                                            foreach ($customer_events_list as $l) {
                                                $k[] = $l['item_id'];
                                            }
                                            $result = array_search($model['item_id'], $k);
                                            ?>
                                            <a class="faver_evnt_product" href="javascript:;"  title="<?php echo Yii::t('frontend', 'Add to Things I like'); ?>" id="<?php echo $model['item_id']; ?>">
                                                <span class="<?php if (is_numeric($result)) {
                                                    echo "heart-product heart-product-hover";
                                                } else {
                                                    echo "heart-product";
                                                } ?>"></span>
                                            </a>
                                        <?php } ?>

                                        <div id="loading_img hide">
                                            <?php $giflink = Url::to("@web/images/ajax-loader.gif"); ?>
                                            <img id="loading-image" src="<?= $giflink; ?>" alt="Loading..." />
                                        </div>
                                        <!-- Add to Event End here -->

                                        <?php /*if (Yii::$app->user->isGuest) { ?>
                                            <div class="buy_events"><a href="" data-toggle="modal" onclick="show_login_modal('-2');" data-target="#myModal"><?= Yii::t('frontend', 'Buy') ?></a></div>
                                        <?php } else if ($model->type_id ==2 && $model->item_for_sale == 'Yes' && $model->item_amount_in_stock > 0) { ?>
                                            <div class="buy_events"><a href="#" id="<?php echo $model['item_id']; ?>" class="buy_item" data-slug="<?php echo $model['slug']; ?>"><?= Yii::t('frontend', 'Buy') ?></a></div>
                                        <?php } else { ?>
                                            <div class="buy_events"><a href="#" id="<?php echo $model['item_id']; ?>" class="stock"><?= Yii::t('frontend', 'Out of stock') ?></a></div>
                                        <?php }*/ ?>
                                    </div>
                                </div>
                            </div>

                            <div class="accad_menus">
                                <div class="panel-group vendor-item-detail" id="accordion">
                                    <?php if (!empty($model['item_description'])) { ?>
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                          <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse1">
                                                <?= Yii::t('frontend', 'Product Description') ?>
                                                <span class="produ_type">
                                                ( 
                                                    <?= Yii::t('frontend', 'Product type') ?>: 
                                                    <?= Yii::t('frontend', $model->type->type_name); ?>
                                                )
                                                </span>
                                            </a>
                                          </h4>
                                        </div>
                                        <div id="collapse1" class="panel-collapse collapse in">
                                          <div class="panel-body">
                                            <p><?= $item_description; ?></p>
                                          </div>
                                        </div>
                                    </div><!-- END .panel -->
                                    <?php } ?>

                                    <?php if (!empty($model['item_additional_info'])) { ?>
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                          <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse2" class="collapsed">
                                                <?= Yii::t('frontend', 'Additional Information') ?>
                                            </a>
                                          </h4>
                                        </div>
                                        <div id="collapse2" class="panel-collapse collapse">
                                          <div class="panel-body">
                                            <p><?= $item_additional_info; ?></p>
                                          </div>
                                        </div>
                                    </div><!-- END .panel -->
                                    <?php } ?>

                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                          <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse3" class="collapsed">
                                                <?= Yii::t('frontend', 'Contact info'); ?>
                                            </a>
                                          </h4>
                                        </div>
                                        <div id="collapse3" class="panel-collapse collapse">
                                            <div class="panel-body vendor_social_info">
                                                <ul>         
                                                    <?php if($phones) { ?>
                                                    <li class="vendor_phone_list">
                                                        <?php foreach ($phones as $key => $value) { ?>
                                                            <a class="color-808080" href="tel:<?= $value->phone_no; ?>"><i class="<?= $phone_icons[$value->type] ?>"></i><?= $value->phone_no; ?>
                                                            </a>
                                                        <?php } ?>
                                                    </li> 
                                                    <?php } ?>

                                                    <?php if (!empty($vendor_detail['vendor_contact_address'])) { ?>
                                                    <li>
                                                        <a target="_blank" href="http://maps.google.com/?q=<?= $vendor_detail['vendor_contact_address'] ?>">
                                                            <i class="fa fa-map-marker"></i>
                                                            <?= LangFormat::format($vendor_detail['vendor_contact_address'], $vendor_detail['vendor_contact_address_ar']); ?>
                                                        </a>
                                                    </li>
                                                    <?php } ?>

                                                    <?php if (!empty($vendor_detail['vendor_working_hours'])) { ?>
                                                    <li class="vendor_working_hours">    
                                                        <a>
                                                        <i class="fa fa-clock-o"></i>
                                                        <?php
                                                            $from = explode(':',$vendor_detail['vendor_working_hours']);
                                                            echo (isset($from[0])) ? $from[0] : '';
                                                            echo (isset($from[1])) ? ':'.$from[1] : '';
                                                            echo (isset($from[2])) ? ''.$from[2] : ''
                                                        ?>
                                                        - 
                                                        <?php
                                                            $to = explode(':',$vendor_detail['vendor_working_hours_to']);
                                                            echo (isset($to[0])) ? $to[0] : '';
                                                            echo (isset($to[1])) ? ':'.$to[1] : '';
                                                            echo (isset($to[2])) ? ''.$to[2] : ''
                                                        ?>

                                                        <?php if($txt_day_off) { ?>
                                                        |
                                                        <?= Yii::t('frontend', '{txt_day_off} off', [
                                                                    'txt_day_off' => $txt_day_off
                                                                ]); ?>
                                                        <?php } ?>
                                                        </a>
                                                    </li>
                                                    <?php } ?>                                                

                                                    <?php if (!empty($vendor_detail['vendor_public_email'])) { ?>
                                                    <li>                                                    
                                                        <a href="mailto:<?=$vendor_detail['vendor_public_email']; ?>" title="<?= $vendor_detail['vendor_public_email']; ?>">
                                                            <i class="fa fa-envelope-o"></i>
                                                            <?= $vendor_detail['vendor_public_email']; ?>
                                                        </a>
                                                    </li>
                                                    <?php } ?>

                                                    <?php if (!empty($vendor_detail['vendor_website'])) { ?>
                                                    <li>
                                                        <a target="_blank" href="<?= $vendor_detail['vendor_website']; ?>" title="<?php echo $vendor_detail['vendor_website']; ?>">
                                                            <i class="fa fa-globe"></i>
                                                            <?php echo $vendor_detail['vendor_website']; ?>
                                                        </a>
                                                    </li>     
                                                    <?php } ?>

                                                    <?php if($vendor_detail['vendor_instagram']) { ?>
                                                    <li>
                                                        <a target="_blank" href="<?= $vendor_detail['vendor_instagram'] ?>" alt="<?= Yii::t('frontend', 'Instatgram') ?>"><i class="fa fa-instagram"></i>
                                                            <?= $vendor_detail['vendor_instagram_text'] ?>
                                                        </a>
                                                    </li>
                                                    <?php } ?>  

                                                    <?php if($vendor_detail['vendor_twitter']) { ?>
                                                    <li>
                                                        <a target="_blank" href="<?= $vendor_detail['vendor_twitter'] ?>" alt="<?= Yii::t('frontend', 'Twitter') ?>"><i class="fa fa-twitter"></i>
                                                            <?= $vendor_detail['vendor_twitter_text'] ?>
                                                        </a>
                                                    </li>
                                                    <?php } ?>

                                                    <?php if($vendor_detail['vendor_facebook']) { ?>
                                                    <li>
                                                        <a target="_blank" href="<?= $vendor_detail['vendor_facebook'] ?>" alt="<?= Yii::t('frontend', 'Facebook') ?>"><i class="fa fa-facebook"></i>
                                                            <?= $vendor_detail['vendor_facebook_text'] ?>
                                                        </a>
                                                    </li>
                                                    <?php } ?>

                                                    <?php if($vendor_detail['vendor_youtube']) { ?>
                                                    <li>
                                                        <a target="_blank" href="<?= $vendor_detail['vendor_youtube'] ?>" alt="<?= Yii::t('frontend', 'Youtube') ?>"><i class="fa fa-youtube"></i>
                                                            <?= $vendor_detail['vendor_youtube_text'] ?>
                                                        </a>
                                                    </li>
                                                    <?php } ?>
                                                </ul>
                                            </div><!-- END .panel-body -->
                                        </div>
                                    </div><!-- END .panel -->

                                    <?php if (VendorItemPricing::checkprice(
                                                $model->item_id,
                                                $model->type_id,
                                                $model->item_price_per_unit
                                              )
                                          ) { ?>

                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                          <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse4" class="collapsed">
                                                <?php echo Yii::t('frontend', 'Price Chart'); ?>
                                            </a>
                                          </h4>
                                        </div>
                                        <div id="collapse4" class="panel-collapse collapse">
                                          <div class="panel-body">
                                            <p><?= VendorItemPricing::loadviewprice($model->item_id, $model->type_id, $model->item_price_per_unit); ?></p>
                                          </div>
                                        </div>
                                    </div><!-- END .panel -->
                                    <?php } ?>

                                    <?php if (!empty($model['item_customization_description'])) { ?>
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                          <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse5" class="collapsed">
                                                <?php echo Yii::t('frontend', 'Customization'); ?>
                                            </a>
                                          </h4>
                                        </div>
                                        <div id="collapse5" class="panel-collapse collapse">
                                          <div class="panel-body">
                                            <p><?= $model['item_customization_description']; ?></p>
                                          </div>
                                        </div>
                                    </div><!-- END .panel -->
                                    <?php } ?>
                                </div><!-- END #accordion -->
                            </div><!-- END .accad_menus -->

                            <div class="social_share">
                                <?php

                                $title = Yii::$app->name.' ' . ucfirst($vendor_name);
                                $summary = Yii::$app->name.' '. ucfirst($item_name).' from '.ucfirst($vendor_name);

                                $image = isset($baselink) ? $baselink : '';
                                $url = Url::toRoute(['browse/detail','slug'=>$model->slug],true);
                                $mailbody = "Check out ".ucfirst($item_name)." on ".Yii::$app->name." ".$url;
                                ?>
                                <h3><?= Yii::t('frontend', 'Share this'); ?></h3>
                                <ul>
                                    <li><a title="Facebook" href='https://www.facebook.com/sharer/sharer.php?u=<?=urlencode($url)?>' onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"><span class="flaticon-facebook55"></span></a></li>
                                    <li><a onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" href="http://twitter.com/share?text=<?=$summary?>&url=<?=$url; ?>" ><span class="flaticon-twitter13"></span></a></li>
                                    <li><a  title="Pinterest" target="_blank" href="//www.pinterest.com/pin/create/button/?url=<?php echo $url; ?>&media=<?php echo $image; ?>&description=<?php echo substr($summary, 0, 499); ?>" data-pin-do="buttonPin"><span class="flaticon-image87"></span></a></li>
                                    <li><a target="_blank" href="https://plus.google.com/share?url=<?php echo $url; ?>" title="Google+"><span class="flaticon-google109"></span></a></li>
                                    <li class="hidden-lg hidden-md"><a href="whatsapp://send?text=<?=$mailbody?>" data-action="share/whatsapp/share"><i class="fa fa-whatsapp" aria-hidden="true"></i></a></li>
                                    <li><a target="_blank" href="http://tumblr.com/share?s=&v=3&t=<?php echo $title; ?>&u=<?php echo $url; ?>" title="Tumblr"><span class="flaticon-tumblr14"></span></a></li>
                                    <li><a href="mailto:?subject=TWB Inquiry&body=<?php echo $mailbody; ?>" title="MailTo"><i class="flaticon-email5"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mobile end Here-->

                <div class="clearfix"></div>

                <?php if($similiar_item) { ?>
                <div class="similar_product_listing">
                    <div class="feature_product_title">
                        <h2>
                            <?php
                            $vendor = LangFormat::format($model->vendor->vendor_name,$model->vendor->vendor_name_ar);
                            echo Yii::t('frontend', 'More from {vendor_name}', [
                                        'vendor_name' => '<b>'.$vendor.'</b>'
                                    ]); ?>                            
                        </h2>
                    </div>
                    <div class="feature_product_slider">
                        <div id="similar-products-slider">
                            <?php
                            
                            $imgUrl = '';
                            
                            $baselink = 'https://placeholdit.imgix.net/~text?txtsize=20&txt=No%20Image&w=210&h=208';
                            
                            foreach ($similiar_item as $s) {
                                
                                if (isset($s->images) && count($s->images) > 0) {
                                    $baselink = Yii::getAlias("@s3/vendor_item_images_210/") . $s->images[0]['image_path'];
                                }

                            ?>
                                <div class="item">
                                    <div class="fetu_product_list">
                                        <?php if ($s['slug'] != '') { ?>
                                            <a href="<?= Url::to(["browse/detail", 'slug' => $s['slug']]) ?>" title="Products" class="similar">

                                                <img src="<?php echo $baselink; ?>" alt="Slide show images" width="208" height="219" />

                                                <div class="deals_listing_cont">
                                                    <h3><?= LangFormat::format($s->item_name,$s->item_name_ar); ?></h3>
                                                    <p><?= CFormatter::format($s['item_price_per_unit'])  ?></p>
                                                </div>
                                            </a>
                                        <?php } ?>
                                    </div>
                                </div>
                            <?php
                            }//END foreach ?>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div><!--product detail end-->
        </div>
        <!-- one end -->
    </div>
</section>
<!-- continer end -->
<!-- end -->

<?php

$this->registerJs("

    var isGuest = ".(int)Yii::$app->user->isGuest.";
    var vendor_id = '".$model['vendor_id']."';
    var customer_id = '".Yii::$app->user->id."';
    var addtobasket_url = '".Yii::$app->urlManager->createAbsoluteUrl('users/addtobasket')."';
    var getdeliverytimeslot_url = '".Url::toRoute('/product/get-delivery-timeslot')."';

", View::POS_HEAD);

$this->registerJsFile('@web/js/product_detail.js?v=1.1', ['depends' => [\yii\web\JqueryAsset::className()]]);


$this->registerCss("
.color-808080{color: #808080!important;}
.color-999999{color: #999999!important;}
.margin-top-13{margin-top: 13px!important;}
.width-530-px{width:530px !important;}
.fa-whatsapp{font-size: 169%;margin-top: 2px;}
.height-2{height:2px!important;}
.margin-4{margin: 4px 0 0px;}

");