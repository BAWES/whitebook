<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yii\web\view;
use common\models\Vendoritempricing;
use common\models\Location;

if (Yii::$app->language == "en") {
    $item_name = $model->item_name;
    $category_name = $model->category->category_name;
    $vendor_name = $model->vendor->vendor_name;
    $item_description = strip_tags($model->item_description);
    $item_additional_info = strip_tags($model->item_additional_info);
    $vendor_contact_address = $model->vendor->vendor_contact_address;
} else {
    $item_name = $model->item_name_ar;
    $category_name = $model->category->category_name_ar;
    $vendor_name = $model->vendor->vendor_name_ar;
    $item_description = strip_tags($model->item_description_ar);
    $item_additional_info = strip_tags($model->item_additional_info_ar);
    $vendor_contact_address = $model->vendor->vendor_contact_address_ar;
}             

$this->title = 'Whitebook - ' . $item_name;
$this->params['breadcrumbs'][] = ['label' => ucfirst($category_name), 'url' => Url::to(["shop/products", 'slug' => $model->category->slug])];
$this->params['breadcrumbs'][] = ucfirst($item_name);
?>
<!-- coniner start -->
<section id="inner_pages_white_back" class="product_details_com">

    <div id="event_slider_wrapper">
        <div class="container paddng0">
            <?php $this->render('/product/events_slider.php'); ?>
        </div>
    </div>

    <div class="container paddng0">
       
        <div class="breadcrumb_common">
            <div class="bs-example">
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
                <div class="product_detials_common normal_tables">

                    <div class="col-md-6 paddig0 resp_hide">
                        <div class="left_descrip mobile-view">
                            <h2><?= $vendor_name; ?></h2>
                            <label><?= $item_name; ?></label>
                            <b><?php echo number_format($model['item_price_per_unit'], 2) . " " . Yii::$app->params['CURRENCY_CODE']; ?></b>
                        </div>
                        <!-- Indicators responsive slider -->
                        <div class="responsive_slider_detials">

                            <!--23-10-2015 slider start-->
                            <div class="carousel-inner owl-carousel" id="mobile-slider">
                                <?php
                                if (count($model->images) > 0) {
                                    foreach ($model->images as $image) {
                                        if ($image->image_path) {
                                            echo '<div class="item">'.Html::img(Yii::getAlias("@s3/vendor_item_images_530/"). $image->image_path,['alt'=>'item detail image','style'=>"width:530px !important;"]).'</div>';
                                        } else {
                                            echo '<div class="item">'.Html::img(Yii::$app->homeUrl . Yii::getAlias('@vendor_images/') . 'no_image.jpg',['alt'=>'item detail image','style'=>"width:530px !important;"]).'</div>';
                                        }
                                    }
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
                                    if (count($model->images) > 0) {
                                        foreach ($model->images as $image) {
                                            if ($image->image_path) {
                                                echo '<li>'.Html::img(Yii::getAlias("@s3/vendor_item_images_530/"). $image->image_path,['alt'=>'item detail image','style'=>"width:530px !important;"]).'</li>';
                                            } else {
                                                echo '<li>'.Html::img(Yii::$app->homeUrl . Yii::getAlias('@vendor_images/') . 'no_image.jpg',['alt'=>'item detail image','style'=>"width:530px !important;"]).'</li>';
                                            }
                                        }
                                    }
                                     ?>
                                </ul>
                            </div>
                            <?php if (count($model->images) > 1) { ?>
                                <div id="carousel" class="flexslider display_none_thumb">
                                    <ul class="slides">

                                        <?php

                                        foreach ($model->images as $image) {
                                            if ($image->image_path) {
                                                echo '<li>'.Html::img(Yii::getAlias("@s3/vendor_item_images_530/"). $image->image_path,['alt'=>'item detail image']).'</li>';
                                            } else {
                                                echo '<li>'.Html::img(Yii::$app->homeUrl . Yii::getAlias('@vendor_images/') . 'no_image.jpg',['alt'=>'item detail image']).'</li>';
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
                                    <a title="<?= $model->vendor->vendor_name; ?>" href="<?= Url::to(["site/vendor_profile", 'slug' => $model->vendor->slug]) ?>">
                                        <h2><?= $vendor_name; ?></h2>
                                    </a>
                                    <label><?= $item_name; ?></label>
                                    <b><?= Yii::$app->formatter->asCurrency($model['item_price_per_unit'])  ?></b>
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
                                            <a href="" class="faver_evnt_product" data-toggle="modal" data-target="#myModal" onclick="show_login_modal_wishlist(<?php echo $model['item_id']; ?>);"  title="<?php echo Yii::t('frontend', 'Add to Favourite'); ?>">
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
                                            <a class="faver_evnt_product" href="javascript:;"  title="<?php echo Yii::t('frontend', 'Add to Favourite'); ?>" id="<?php echo $model['item_id']; ?>">
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
                                        <div class="buy_events">
                                        <?php
                                        if (Yii::$app->user->isGuest) {
                                            echo Html::a(Yii::t('frontend', 'Buy'),'#',['onclick'=>"show_login_modal('-2')",'class'=>'buy_item','data-target'=>'#myModal','data-toggle'=>"modal"]);
                                        } else if (!$AvailableStock) {
                                            echo Html::a(Yii::t('frontend', 'Out of stock'),'#',['class'=>'stock','id'=>$model['item_id']]);
                                        } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php if (!Yii::$app->user->isGuest && $AvailableStock) { ?>
                            <form id="form_product_option" method="POST" class="form center-block">

                                <input name="item_id" value="<?= $model->item_id ?>" type="hidden" />

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?= Yii::t('frontend', 'Quantity');?></label>
                                            <input type="text" name="quantity" id="quantity" class="form-control" />
                                            <span class="error cart_quantity"></span>
                                        </div>
                                    </div><!-- END .col-md-6 -->

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?= Yii::t('frontend', 'Delivery date');?></label>
                                            <div data-date-format="dd-mm-yyyy" data-date="12-02-2012" class="input-append date" style="position: relative;"  id="delivery_date_wrapper">
                                                <input readonly="true" name="delivery_date" id="delivery_date" class="form-control required"  placeholder="<?php echo Yii::t('frontend', 'Choose Delivery Date'); ?>" style="height: 40px;">
                                                <span class="add-on position_product_option"> <i class="flaticon-calendar189"></i></span>
                                            </div>
                                            <span class="error cart_delivery_date"></span>
                                        </div>
                                        
                                    </div><!-- END .col-md-6 -->
                                </div><!-- END .row -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?= Yii::t('frontend', 'Delivery Area') ?></label>
                                            <div class="select_boxes">
                                                <?php
                                                echo Html::dropDownList('area_id', null,
                                                    \yii\helpers\ArrayHelper::map($vendor_area, 'area_id', 'locationName','cityName' ),
                                                    ['data-height'=>"100px",'data-live-search'=>"true",'id'=>"area_id", 'class'=>"selectpicker", 'data-size'=>"10", 'data-style'=>"btn-primary"]
                                                );
                                                ?>
                                            </div>
                                            <span class="error area_id"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?= Yii::t('frontend', 'Delivery timeslot');?></label>
                                            <select name="timeslot_id" id="timeslot_id" class="selectpicker" data-size="10" data-style="btn-primary">
                                            </select>
                                            <span class="error timeslot_id"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="button-signin">
                                    <button type="submit" class="btn btn-primary btn-custome-1" name="submit">
                                        <?= Yii::t('frontend', 'Buy') ?>
                                    </button>&nbsp;&nbsp;&nbsp;
                                </div>
                                <span style="display: block;margin-top: 18px;" id="available"></span>
                            </form>
                            <?php } ?>

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
                                                            <?= Yii::t('frontend', $model->type->type_name); ?>
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

                                        <?php }
                                        if ($model->vendor->vendor_contact_number || $vendor_contact_address) {
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
                                                            $num = explode(',', $model->vendor->vendor_contact_number);
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

                                $image = isset($baselink) ? $baselink : '';

                                ?>
                                <h3><?= Yii::t('frontend', 'Share this'); ?></h3>
                                <ul>
                                    <li>
                                    <a title="Facebook" onclick="window.open('http://www.facebook.com/sharer.php?s=100&amp;p[title]=<?php echo $title; ?>&amp;p[summary]=<?php echo $summary; ?>&amp;p[url]=<?php echo $url; ?>&amp;&p[images][0]=<?php echo $image; ?>', 'sharer', 'toolbar=0,status=0,width=620,height=280');" href="javascript: void(0)"><span class="flaticon-facebook55"></span></a></li>

                                    <li><a  title="Twitter" href="https://twitter.com/share" class="twitter" target="_blank" data-url="<?php echo $url; ?>" data-text="<?php echo $title; ?>" data-lang="en" data-size="medium" data-count="none"><span class="flaticon-twitter13"></span></a></li>

                                    <li><a  title="Pinterest" target="_blank" href="//www.pinterest.com/pin/create/button/?url=<?php echo $url; ?>&media=<?php echo $image; ?>&description=<?php echo substr($summary, 0, 499); ?>" data-pin-do="buttonPin"><span class="flaticon-image87"></span></a></li>
                                    
                                    <li><a target="_blank" href="https://plus.google.com/share?url=<?php echo $url; ?>" title="Google+"><span class="flaticon-google109"></span></a></li>
                                    
                                    <li><a target="_blank" href="http://tumblr.com/share?s=&v=3&t=<?php echo $title; ?>&u=<?php echo $url; ?>
                                           " title="Tumblr"><span class="flaticon-tumblr14"></span></a></li>
                                    
                                    <li><a href="mailto:<?= $model->vendor->vendor_contact_email; ?>" title="<?= $model->vendor->vendor_contact_email; ?>"><i class="flaticon-email5"></i> </a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mobile end Here-->

                <div class="clearfix"></div>
                <div class="similar_product_listing">
                    <div class="feature_product_title">
                        <h2><?= Yii::t('frontend', 'Similar products') ?></h2>
                    </div>
                    <div class="feature_product_slider">
                        <div id="similar-products-slider">
                            <?php
                            
                            foreach ($similiar_item as $s) {
                                
                                $sql = 'SELECT image_path FROM whitebook_image WHERE item_id=' . $s['gid'] . ' order by vendorimage_sort_order';
                                
                                $command = Yii::$app->DB->createCommand($sql);
                                
                                $out = $command->queryAll();
                                
                                if ($out) {
                                    $imglink = Yii::getAlias('@vendor_images/') . $out[0]['image_path'];
                                    $baselink = Yii::getAlias("@s3/vendor_item_images_530/") . $out[0]['image_path'];
                                } else {
                                    $imglink = Yii::getAlias('@vendor_images/no_image.png');
                                    $baselink = Yii::$app->homeUrl . Yii::getAlias('@vendor_images/no_image.png');
                                }

                                if($s['item_for_sale'] == 'Yes'){
                                    $item_url = Url::to(["shop/product", 'slug' => $s['slug']]);
                                }else{
                                    $item_url = Url::to(["product/product", 'slug' => $s['slug']]);
                                }

                                ?>
                                <div class="item">
                                    <div class="fetu_product_list">
                                        <?php if ($s['slug'] != '') { ?>
                                            <a href="<?= $item_url ?>" title="Products" class="similar">
                                                
                                                <img src="<?php echo $baselink; ?>" alt="Slide show images" width="208" height="219" />
                                        
                                                <?php if (file_exists($imglink)) { ?>
                                                    <img src="<?php echo $baselink; ?>" alt="Slide show images" width="208" height="219" />
                                                <?php } ?>

                                                <div class="deals_listing_cont">
                                                    
                                                    <?php if(Yii::$app->language == "en"){ ?>
                                                        <?= $s['vname']; ?>
                                                        <h3><?= $s['iname']; ?></h3>
                                                    <?php }else{ ?>
                                                        <?= $s['vname_ar']; ?>
                                                        <h3><?= $s['iname_ar']; ?></h3>
                                                    <?php } ?>

                                                    <p><?= $s['price']; ?>KD</p>
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
            </div><!--product detail end-->
        </div>
        <!-- one end -->
    </div>
</section>
<!-- continer end -->
<!-- end -->

<div id="option_modal_wrapper"></div>

<?php

$this->registerJs("
    var isGuest = ".(int)Yii::$app->user->isGuest.";
    var vendor_id = '".$model['vendor_id']."';
    var customer_id = '".Yii::$app->user->id."';
    var addtobasket_url = '".Yii::$app->urlManager->createAbsoluteUrl('cart/add')."';
    var getdeliverytimeslot_url = '".Url::toRoute('cart/getdeliverytimeslot')."';
    var area_option_url = '".Url::toRoute('site/area')."';
    var availablity = '".Url::toRoute('shop/product-available')."';
", View::POS_HEAD);

$this->registerJs("
    function loadProductAvailability() {
        jQuery.post(
            availablity,
            jQuery('#form_product_option').serialize(),
            function (data) {
                jQuery('#available').html(data);
                return false;
            }
        );
    }

    if ($('#form_product_option').length>0) {
        loadProductAvailability();
    }
", View::POS_READY);

$this->registerCss("
.datepicker{
        border: 2px solid rgb(242, 242, 242);
    }
    .datepicker table {
        font-size: 12px;
    }
    .form-group{
        margin-bottom: 15px;
        width: 92%;
        margin-left: 11px;
   }");

$this->registerJsFile('@web/js/product_detail.js', ['depends' => [\yii\web\JqueryAsset::className()]]);