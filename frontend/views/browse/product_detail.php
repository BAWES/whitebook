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

                    <div id="main" role="main" class="col-md-6 padding-right0 product-left-width">
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
                    <div class="col-md-6 product-right-width paddng0">
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
                                <div class="panel-group" id="accordion">
                                        <?php if (!empty($model['item_description'])) { ?>
                                        <div class="panel panel-default">
                                            <div class="panel-heading" role="tab" id="headingOne">
                                                <h4 class="panel-title">
                                                    <a data-toggle="collapse" id="description_click" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                         <?= Yii::t('frontend', 'Product Description') ?>
                                                        <span class="produ_type">
                                                        (
                                                            <?= Yii::t('frontend', 'Product type') ?>:
                                                            <?= Yii::t(
                                                                    'frontend',
                                                                    ItemType::itemtypename($model['type_id'])
                                                                );
                                                            ?>
                                                        )
                                                        </span>
                                                        <span class="glyphicon glyphicon-menu-down text-align"></span></a>
                                                </h4>
                                            </div>
                                            <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                                                <div class="panel-body">
                                                    <p><?= $item_description; ?></p>
                                                    <h1 class="space_height"></h1>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>

                                        <?php if (!empty($model['item_additional_info'])) { ?>
                                        <div class="panel panel-default">
                                            <div class="panel-heading" role="tab" id="headingTwo">
                                                <h4 class="panel-title">
                                                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" id="additional_click" aria-controls="collapseTwo">
                                                        <?= Yii::t('frontend', 'Additional Information') ?>
                                                        <span class="glyphicon glyphicon-menu-right text-align"></span></a>
                                                </h4>
                                            </div>
                                            <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                                                <div class="panel-body">
                                                    <p><?= $item_additional_info; ?></p>
                                                    <h1 class="space_height"></h1>
                                                </div>
                                            </div>
                                        </div>

                                        <?php } ?>

                                        <?php

                                        if ($vendor_det['vendor_contact_number'] || $vendor_contact_address) {
                                        ?>
                                        <div class="panel panel-default">
                                            <div class="panel-heading" role="tab" id="headingThree">
                                                <h4 class="panel-title">
                                                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion" id="contact_click" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                                        <?= Yii::t('frontend', 'Contact Info') ?>
                                                        <span class="glyphicon glyphicon-menu-right text-align"></span>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                                                <div class="panel-body">
                                                    <div class="contact_information margin-4">
                                                        <address>
                                                            <div class="clearfix">
                                                                <?php if (trim($model->vendor->vendor_public_email) || trim($model->vendor->vendor_public_phone)) { ?>
                                                                    <div class="col-md-6 col-sm-6 col-xs-12 cont_ifo_left paddingleft0">
                                                                        <?php if (trim($model->vendor->vendor_public_email)) { ?>
                                                                            <h3>
                                                                                <a href="mailto:<?=$model->vendor->vendor_public_email; ?>" title="<?=$model->vendor->vendor_public_email; ?>"><?=$model->vendor->vendor_public_email; ?>&nbsp;</a>
                                                                            </h3>
                                                                            <span class="border-bottom"></span>
                                                                        <?php } ?>
                                                                        <?php if (trim($model->vendor->vendor_public_phone)) { ?>
                                                                            <h4 class="margin-top-13">
                                                                                <a class="color-808080" href="tel:<?=$model->vendor->vendor_public_phone; ?>"><?=$model->vendor->vendor_public_phone; ?></a>&nbsp;
                                                                            </h4>
                                                                            <span class="border-bottom border-bottom-none"></span>
                                                                        <?php } ?>
                                                                    </div>
                                                                <?php } ?>
                                                                <?php if (trim($model->vendor->vendor_website) || trim($model->vendor->vendor_working_hours)) { ?>
                                                                    <div class="col-md-6 col-sm-6 col-xs-12 paddingright0 paddingleft0 cont_ifo_right">
                                                                        <?php if (trim($model->vendor->vendor_website)) { ?>
                                                                            <span class="links_left">
                                                                            <?php
                                                                            if (strpos($model->vendor->vendor_website,'http://') === false){
                                                                                $vendor_website = 'http://'.$model->vendor->vendor_website;
                                                                            } else {
                                                                                $vendor_website = $model->vendor->vendor_website;
                                                                            }
                                                                            ?>
                                                                                <a target="_blank" href="<?=$vendor_website; ?>" title="<?php echo $vendor_website; ?>">
                                                                                    <?php echo $vendor_website; ?>&nbsp;
                                                                                </a>
                                                                        </span>
                                                                            <span class="border-bottom"></span>
                                                                        <?php } ?>
                                                                        <?php if (trim($model->vendor->vendor_working_hours)) { ?>

                                                                            <span class="timer_common"><?php
                                                                                $from = explode(':',$model->vendor->vendor_working_hours);
                                                                                echo (isset($from[0])) ? $from[0] : '';
                                                                                echo (isset($from[1])) ? ':'.$from[1] : '';
                                                                                echo (isset($from[2])) ? ' '.$from[2] : ''
                                                                                ?></span>

                                                                            - <span class="timer_common">
                                                                            <?php
                                                                            $to = explode(':',$model->vendor->vendor_working_hours_to);
                                                                            echo (isset($to[0])) ? $to[0] : '';
                                                                            echo (isset($to[1])) ? ':'.$to[1] : '';
                                                                            echo (isset($to[2])) ? ' '.$to[2] : ''
                                                                            ?>
                                                                        </span>
                                                                        <?php } ?>
                                                                    </div>
                                                                <?php } ?>
                                                            </div>
                                                            <?php if (trim($model->vendor->vendor_contact_address)) { ?>
                                                                <div class="col-md-6 col-sm-6 col-xs-12 paddingleft0 address_ifo_left border-top">
                                                                    <h5 class="margin-top-13">
                                                                        <?=LangFormat::format($model->vendor->vendor_contact_address,$model->vendor->vendor_contact_address_ar); ?>
                                                                    </h5>
                                                                </div>
                                                            <?php } ?>
                                                        </address>
                                                    </div>
                                                    <h1 class="height-2"></h1>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>

                                        <?php if (VendorItemPricing::checkprice(
                                                    $model->item_id,
                                                    $model->type_id,
                                                    $model->item_price_per_unit
                                                  )
                                              ) { ?>

                                        <div class="panel panel-default">
                                            <div class="panel-heading" role="tab" id="headingFour">
                                                <h4 class="panel-title">
                                                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion" id="price_click" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                                        <?= Yii::t('frontend', 'Price Cart'); ?>
                                                        <span class="glyphicon glyphicon-menu-right text-align"></span></a>
                                                </h4>
                                            </div>
                                            <div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFour">
                                                <div class="panel-body">
                                                    <p><?= VendorItemPricing::loadviewprice($model->item_id, $model->type_id, $model->item_price_per_unit); ?></p>
                                                    <h1 class="space_height"></h1>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>

                                        <?php if (!empty($model['item_customization_description'])) { ?>
                                        <div class="panel panel-default">
                                            <div class="panel-heading" role="tab" id="headingFive">
                                                <h4 class="panel-title">
                                                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion" id="custom_click" href="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                                        <?= Yii::t('frontend', 'Customization') ?>
                                                        <span class="glyphicon glyphicon-menu-right text-align"></span>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="collapseFive" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFive">
                                                <div class="panel-body">
                                                    <p><?= $model['item_customization_description']; ?></p>
                                                    <h1 class="space_height"></h1>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
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

$this->registerJsFile('@web/js/product_detail.js', ['depends' => [\yii\web\JqueryAsset::className()]]);


$this->registerCss("
.color-808080{color: #808080!important;}
.color-999999{color: #999999!important;}
.margin-top-13{margin-top: 13px!important;}
.width-530-px{width:530px !important;}
.fa-whatsapp{font-size: 169%;margin-top: 2px;}
.height-2{height:2px!important;}
.margin-4{margin: 4px 0 0px;}

");