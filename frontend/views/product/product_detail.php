<?php
use yii\helpers\Url;
use frontend\models\Vendor;
use yii\helpers\Html;
use common\models\Vendoritempricing;
use common\models\Itemtype;
use frontend\models\Category;
use yii\widgets\Breadcrumbs;
use yii\web\view;
use common\components\CFormatter;

$vendor_det = $model->vendor;
$category_det = Category::category_slug($model['category_id']);

if(Yii::$app->language == "en"){
    $item_name = $model['item_name'];
    $category_name = $category_det['category_name'];
    $vendor_name = $vendor_det['vendor_name'];
    $item_description = strip_tags($model['item_description']);
    $item_additional_info = strip_tags($model['item_additional_info']);
    $vendor_contact_address = $vendor_det['vendor_contact_address'];
}else{
    $item_name = $model['item_name_ar'];
    $category_name = $category_det['category_name_ar'];
    $vendor_name = $vendor_det['vendor_name_ar'];
    $item_description = strip_tags($model['item_description_ar']);
    $item_additional_info = strip_tags($model['item_additional_info_ar']);
    $vendor_contact_address = $vendor_det['vendor_contact_address_ar'];
}

$this->title = 'Whitebook - ' . $item_name;

?>

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

                <?php
                $this->params['breadcrumbs'][] = [
                    'label' => ucfirst($category_name),
                    'url' => Url::to(["plan/plan", 'slug' => $category_det['slug']])
                ];

                $this->params['breadcrumbs'][] = ucfirst($item_name);
                ?>

                <?=
                Breadcrumbs::widget([
                    'options' => ['class' => 'new breadcrumb'],
                    'homeLink' => [
                        'label' => Yii::t('yii', 'Home'),
                        'url' => Yii::$app->homeUrl,
                    ],
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]);
                ?>

            </div>
        </div>

        <!-- Mobile start Here-->
        <div class="product_detail_section responsive-detail-section"><!--product detail start-->
            <div class="col-md-12 padding0">
                <div class="select_items_select desktop-menu" style="display:none">
                    <div data-example-id="basic-forms" class="bs-example responsive_inner_top">
                        <form>
                            <div class="col-md-3 padding-right0 padding-right8">
                                <div class="form-group left_select_common">
                                    <div class="bs-docs-example">
                                        <select class="selectpicker" data-style="btn-primary" style="display: none;">
                                            <option><?= Yii::t('frontend', 'Select Delivery Area'); ?></option>
                                            <?php foreach ($vendor_area as $key => $value) { ?>
                                                <option><?= $value['location']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 padding8">
                                <div class="form-group date-time">
                                    <input class="form-control required datetimepicker date1" type="text" id="delivery_date" placeholder="Delivery Date">
                                </div>
                            </div>

                            <div class="col-md-2 padding8">
                                <div class="form-group">
                                    <div class="bs-docs-example">
                                        <select class="selectpicker" data-style="btn-primary" style="display: none;" id="delivery-time">
                                            <option><?= Yii::t('frontend', 'Select Delivery Time') ?></option>
                                            <?php /* foreach ($vendor_timeslot as $key => $value1) { ?>
                                              <option><?= $value1['timeslot_start_time'].' - '.$value1['timeslot_end_time'];?></option>
                                              <?php } */ ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1 padding8 select_buttons">
                                <button class="btn btn-default" type="submit" title="Select">
                                    <?= Yii::t('frontend', 'Select'); ?>
                                </button>
                            </div>
                            <div class="col-md-4">
                                <!-- <em class="error_text">This item is not available at the selected time</em> -->
                            </div>
                        </form>
                    </div>
                </div>

                <div class="product_detials_common normal_tables">

                    <div class="col-md-6 paddig0 resp_hide">
                        <div class="left_descrip mobile-view">
                            <h2><?= $item_name; ?></h2>
                            <label><?= $vendor_name; ?></label>
                            <b>
                                <?= CFormatter::asCurrency($model->item_price_per_unit)  ?>       
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
                                        <div class="item"><img src="<?php echo $baselink; ?>" alt="item detail image" style="width:530px;"></div>
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
                                                <li><img src="<?php echo $baselink; ?>" alt="item detail image" style="width:530px !important;"></li>
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
                                                <li><img src="<?php echo $baselink; ?>" alt="item detail image"></li>
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
                                        <a title="<?= $vendor_det['vendor_name']; ?>" href="<?= Url::to(["site/vendor_profile", 'slug' => $vendor_det['slug']]) ?>"  style="color: #999999">
                                            <?= $vendor_name; ?>
                                        </a>
                                    </label>

                                    <b><?= CFormatter::asCurrency($model->item_price_per_unit)  ?></b>
                                </div>
                                <div class="right_descrip">
                                    <div class="responsive_width">
                                        <!-- add to event start -->

                                        <?php if (Yii::$app->user->isGuest) { ?>
                                            <a href="" data-toggle="modal" class="add_events" data-target="#myModal" title="Add to event"  onclick="add_event_login(<?php echo $model['item_id']; ?>)">
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

                                        <div id="loading_img" style='display:none'>
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
                                                                    Itemtype::itemtypename($model['type_id'])
                                                                );
                                                            ?>
                                                        )
                                                        </span>
                                                        <span class="glyphicon glyphicon-menu-down text-align pull-right"></span></a>
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
                                                        <span class="glyphicon glyphicon-menu-right text-align pull-right"></span></a>
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
                                                        <span class="glyphicon glyphicon-menu-right text-align pull-right"></span>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                                                <div class="panel-body">
                                                    <p>
                                                        <?php echo $vendor_contact_address; ?>
                                                    </p>
                                                    <p>
                                                        <?php

                                                        $num = explode(
                                                            ',', $vendor_det['vendor_contact_number']);

                                                        echo $num[0];

                                                        ?>
                                                    </p>
                                                    <h1 class="space_height"></h1>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>

                                        <?php if (Vendoritempricing::checkprice(
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
                                                        <span class="glyphicon glyphicon-menu-right text-align pull-right"></span></a>
                                                </h4>
                                            </div>
                                            <div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFour">
                                                <div class="panel-body">
                                                    <p><?= Vendoritempricing::loadviewprice($model->item_id, $model->type_id, $model->item_price_per_unit); ?></p>
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
                                                        <span class="glyphicon glyphicon-menu-right text-align pull-right"></span>
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

                                $title = 'Whitebook Application' . ucfirst($vendor_name);
                                $url = urlencode(Yii::$app->homeUrl . $_SERVER['REQUEST_URI']);
                                $summary = 'Whitebook Application' . ucfirst($vendor_name) . ucfirst($item_name);

                                if(isset($baselink)) {
                                    $image = $baselink;
                                } else {
                                    $image = '';
                                }

                                ?>
                                <h3><?= Yii::t('frontend', 'Share this'); ?></h3>
                                <ul>
                                    <li>
                                    <a title="Facebook" onclick="window.open('http://www.facebook.com/sharer.php?s=100&amp;p[title]=<?php echo $title; ?>&amp;p[summary]=<?php echo $summary; ?>&amp;p[url]=<?php echo $url; ?>&amp;&p[images][0]=<?php echo $image; ?>', 'sharer', 'toolbar=0,status=0,width=620,height=280');" href="javascript: void(0)"><span class="flaticon-facebook55"></span></a></li>
                                    <li><a  title="Twitter" href="https://twitter.com/share" class="twitter" target="_blank" data-url="<?php echo $url; ?>" data-text="<?php echo $title; ?>" data-lang="en" data-size="medium" data-count="none"><span class="flaticon-twitter13"></span></a></li>
                                    <li><a  title="Pinterest" target="_blank" href="//www.pinterest.com/pin/create/button/?url=<?php echo $url; ?>&media=<?php echo $image; ?>&description=<?php echo substr($summary, 0, 499); ?>" data-pin-do="buttonPin"><span class="flaticon-image87"></span></a></li>
                                    <li><a target="_blank" href="https://plus.google.com/share?url=<?php echo $url; ?>" title="Google+"><span class="flaticon-google109"></span></a></li>
                                    <li><a target="_blank" href="http://tumblr.com/share?s=&v=3&t=<?php echo $title; ?>&u=<?php echo $url; ?>" title="Tumblr"><span class="flaticon-tumblr14"></span></a></li>
                                    <li><a href="mailto:<?= $model->vendor->vendor_contact_email; ?>" title="<?= $model->vendor->vendor_contact_email; ?>"><i class="flaticon-email5"></i> </a></li>
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
                        <h2><?= Yii::t('frontend', 'Similar products') ?></h2>
                    </div>
                    <div class="feature_product_slider">
                        <div id="similar-products-slider">
                            <?php
                            $imgUrl = '';
                            $imglink = Yii::getAlias('@vendor_images/no_image.png');
                            $baselink = Yii::$app->homeUrl . Yii::getAlias('@vendor_images/no_image.png');
                            foreach ($similiar_item as $s) {
                                if (isset($s->images) && count($s->images) > 0) {

                                    foreach ($s->images as $img) {
                                        if ($img['module_type'] == 'vendor_item') {
                                            $imgUrl = $img['image_path'];
                                            break;
                                        }
                                    }
                                    $baselink = Yii::getAlias("@s3/vendor_item_images_530/") . $imgUrl;
                                    $imglink = Yii::getAlias('@vendor_images/') . $imgUrl;
                                }
                                ?>
                                <div class="item">
                                    <div class="fetu_product_list">
                                        <?php if ($s['slug'] != '') { ?>
                                            <a href="<?= Url::to(["product/product", 'slug' => $s['slug']]) ?>" title="Products" class="similar">

                                                <img src="<?php echo $baselink; ?>" alt="Slide show images" width="208" height="219" />

                                                <?php if (file_exists($imglink)) { ?>
                                                    <img src="<?php echo $baselink; ?>" alt="Slide show images" width="208" height="219" />
                                                <?php } ?>

                                                <div class="deals_listing_cont">
                                                    <h3><?= (Yii::$app->language == "en") ? $s->item_name : $s->item_name_ar; ?></h3>
                                                    <p><?= $s['item_price_per_unit']; ?>KD</p>
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
    var getdeliverytimeslot_url = '".Url::toRoute('/product/getdeliverytimeslot')."';

", View::POS_HEAD);

$this->registerJsFile('@web/js/product_detail.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
