<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yii\web\view;
use common\models\VendorItemPricing;
use common\models\VendorItemMenuItem;
use common\components\LangFormat;
use common\components\CFormatter;

$item_name = LangFormat::format($model->item_name,$model->item_name_ar);
$vendor_name = LangFormat::format($model->vendor->vendor_name,$model->vendor->vendor_name_ar);
$item_description = LangFormat::format($model->item_description, $model->item_description_ar);
$item_additional_info = LangFormat::format($model->item_additional_info, $model->item_additional_info_ar);

$vendor_contact_address = LangFormat::format($model->vendor->vendor_contact_address, $model->vendor->vendor_contact_address_ar);

$whats_include = LangFormat::format($model->whats_include, $model->whats_include_ar);

$max_time = LangFormat::format($model->max_time, $model->max_time_ar);
$set_up_time = LangFormat::format($model->set_up_time, $model->set_up_time_ar);
$requirements = LangFormat::format($model->requirements, $model->requirements_ar);

$this->title = 'Whitebook - ' . $item_name;
$this->params['breadcrumbs'][] = ' '.$item_name;

$session = $session = Yii::$app->session;
$deliver_location   = ($session->has('deliver-location')) ? $session->get('deliver-location') : null;
$deliver_date  = ($session->has('deliver-date')) ? $session->get('deliver-date') : '';
$quantity = $model->item_minimum_quantity_to_order;

if (isset($model->vendorItemCapacityExceptions) && count($model->vendorItemCapacityExceptions)>0) {
    $exceptionDate = \yii\helpers\ArrayHelper::map($model->vendorItemCapacityExceptions, 'exception_date', 'exception_capacity');
    if (isset($exceptionDate) && count($exceptionDate) > 0) {
        if ($deliver_date && isset($exceptionDate[date('Y-m-d',strtotime($deliver_date))])) {
            $quantity = $exceptionDate[date('Y-m-d',strtotime($deliver_date))];
        }
    }
}

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
  "description": "<?= addslashes(strip_tags($item_description)) ?>",
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
<section id="inner_pages_white_back" class="product_details_com <?=Yii::$app->controller->id;?>">

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
        <?php if (!Yii::$app->user->isGuest && $AvailableStock && ($model->item_for_sale == 'Yes')) { ?>
        <form id="form_product_option" method="POST" class="form center-block margin-top-0">
        <div class="col-md-12 filter-bar ">
            <div class="col-md-3 padding-right-0 area-filter">
                <div class="form-group margin-left-0">
                    <label><?=Yii::t('frontend', 'Area'); ?></label>
                    <div class="select_boxes">
                        <?php
                            echo Html::dropDownList('area_id', $deliver_location,
                            $vendor_area,
                            ['data-height'=>"100px",'data-live-search'=>"true",'id'=>"area_id", 'class'=>"selectpicker", 'data-size'=>"10", 'data-style'=>"btn-primary"]);
                        ?>
                    </div>
                    <span class="error area_id"></span>
                </div>
            </div>
            <div class="col-md-2 padding-left-0 delivery-date-filter">
                <div class="form-group">
                    <label><?=Yii::t('frontend', 'Delivery Date'); ?></label>
                    <div data-date-format="dd-mm-yyyy" data-date="12-02-2012" class="input-append date" id="delivery_date_wrapper">
                        <input value="<?=$deliver_date?>" readonly="true" name="delivery_date" id="delivery_date" class="date-picker-box form-control required"  placeholder="<?php echo Yii::t('frontend', 'Date'); ?>" >
                        <i class="fa fa-calendar" aria-hidden="true"></i>
                    </div>
                    <span class="error cart_delivery_date"></span>
                </div>
            </div>
            <div class="col-md-5 padding-left-0 timeslot_id_div timeslot-filter">
                <div class="form-group">
                    <label><?=Yii::t('frontend', 'Delivery Time Slot'); ?></label>
                    <div class="text padding-top-12"><?=Yii::t('frontend','Please Select Delivery Date');?></div>
                </div>
            </div>
            <div class="col-md-3 padding-left-0 timeslot_id_select timeslot-filter" style="display: none;">
                <div class="form-group">
                    <label><?=Yii::t('frontend', 'Delivery Time Slot'); ?></label>
                    <select name="timeslot_id" id="timeslot_id" class="selectpicker" data-size="10" data-style="btn-primary"></select>
                    <span class="error timeslot_id"></span>
                </div>
            </div>
        </div>
        <?php } ?>
        <!-- Mobile start Here-->
        <div class="product_detail_section responsive-detail-section"><!--product detail start-->
            <div class="col-md-12 padding0">
                <div class="product_detials_common normal_tables">
                    <div class="col-md-6 paddig0 resp_hide mobile_mode">
                        <div class="left_descrip mobile-view">
                            <h2><?= $item_name; ?></h2>
                            <label>
                                <a title="<?= $model->vendor->vendor_name; ?>" href="<?= Url::to(["site/vendor_profile", 'slug' => $model->vendor->slug]) ?>" class="color-999999">
                                    <?= $vendor_name; ?>
                                </a>
                            </label>
                            <b class="font-27">
                                <p><?=(trim($model['item_price_per_unit'])) ? CFormatter::format($model['item_price_per_unit']) : '<span class="small">'.Yii::t('frontend','Price upon request').'<span>'  ?></p>
                            </b>
                        </div>
                        <!-- Indicators responsive slider -->
                        <div class="responsive_slider_detials">

                            <!--23-10-2015 slider start-->
                            <div class="carousel-inner owl-carousel" id="mobile-slider">
                                <?php if(!$model->images) { ?>
                                    <div class="item">
                                        <?= Html::img(Url::to("@web/images/item-default.png")) ?>
                                    </div>
                                <?php } ?>
                                <?php foreach ($model->images as $image) { ?>
                                    <div class="item">
                                        <?= Html::img(Yii::getAlias("@s3/vendor_item_images_530/"). $image->image_path) ?>
                                    </div>
                                <?php } ?>
                            </div>
                            <!--23-10-2015 slider end-->

                        </div>
                        <!-- Indicators responsive slider end -->
                    </div>

                    <div id="main" role="main" class="col-md-5 padding-left-0 normal_mode left-sidebar">
                        <div class="slider">
                            <div id="slider" class="flexslider display_none">
                                <ul class="slides">
                                    <?php if(!$model->images) { ?>
                                        <li>
                                            <?= Html::img(Url::to("@web/images/item-default.png")) ?>
                                        </li>
                                    <?php } ?>
                                    <?php foreach ($model->images as $image) { ?>
                                        <li>
                                            <?= Html::img(Yii::getAlias("@s3/vendor_item_images_530/"). $image->image_path,['alt'=>'item detail image']) ?>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                            <?php if (count($model->images) > 1) { ?>
                                <div id="carousel" class="flexslider display_none_thumb">
                                    <ul class="slides">
                                        <?php

                                        foreach ($model->images as $image) {
                                            echo '<li>'.Html::img(Yii::getAlias("@s3/vendor_item_images_530/"). $image->image_path,['alt'=>'item detail image']).'</li>';
                                        } ?>
                                    </ul>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="col-md-7 right-sidebar">
                        <div class="right_descr_product">
                            <div class="product_name clearfix">
                                <div class="left_descrip desktop-view margin-bottom-14">
                                    <h2><?= $item_name; ?></h2>

                                    <label>
                                    <a title="<?= $model->vendor->vendor_name; ?>" href="<?= Url::to(["directory/profile", 'slug'=>'all','vendor' => $model->vendor->slug]) ?>" class="color-999999">
                                        <?= $vendor_name; ?>
                                    </a>
                                    </label>

                                    <b class="font-27 item-final-price"><?=(trim($model['item_price_per_unit'])) ? CFormatter::format($model['item_price_per_unit']) : '<span class="small">'.Yii::t('app','Price upon request').'<span>'  ?></b>

                                    <strong><?= $model['item_price_description'] ?></strong>
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

                                        <div id="loading_img" class="hide">
                                            <?php $giflink = Url::to("@web/images/ajax-loader.gif"); ?>
                                            <img id="loading-image" src="<?= $giflink; ?>" alt="Loading..." />
                                        </div>

                                        <div class="clerfix"></div>

                                        <button type="button" class="btn btn-default btn-booking-modal">
                                            <?= Yii::t('frontend', 'REQUEST BOOKING SERVICE') ?>
                                        </button>

                                        <!-- Add to Event End here -->
                                        <div class="buy_events">
                                        <?php
                                        if ($model->item_for_sale == 'Yes') {
                                            if (Yii::$app->user->isGuest) {
                                                echo Html::a(Yii::t('frontend', 'Buy'),'#',['onclick'=>"show_login_modal('-2')",'class'=>'buy_item','data-target'=>'#myModal','data-toggle'=>"modal"]);
                                            } else if (!$AvailableStock) {
                                                echo Html::a(Yii::t('frontend', 'Out of stock'),'#',['class'=>'stock','id'=>$model['item_id']]);
                                            }
                                        }
                                        ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php if($set_up_time || $max_time || $requirements || $modal->min_order_amount) { ?>
                            <div class="menu-requirements">
                                <?php if($set_up_time) { ?>
                                <div>
                                    <i class="fa fa-clock-o"></i>
                                    <span class="title">Set-up Time</span>
                                    <span class="value"><?= $set_up_time ?></span>
                                </div>
                                <?php } ?>
                                <?php if($requirements) { ?>
                                <div>
                                    <i class="fa fa-cog"></i>
                                    <span class="title">Requirements</span>
                                    <span class="value"><?= $requirements ?></span>
                                </div>
                                <?php } ?>
                                <?php if($max_time) { ?>
                                <div>
                                    <i class="fa fa-info"></i>
                                    <span class="title">Max. Time</span>
                                    <span class="value"><?= $max_time ?></span>
                                </div>
                                <?php } ?>
                                <?php if($model->min_order_amount > 0) { ?>
                                <div>
                                    <i class="fa fa-truck"></i>
                                    <span class="title">Min. order value</span>
                                    <span class="value"><?= CFormatter::format($model->min_order_amount) ?></span>
                                </div>
                                <?php } ?>
                                <span class="clearfix"></span>
                            </div>
                            <?php } ?>

                            <?php if (!Yii::$app->user->isGuest && $AvailableStock && ($model->item_for_sale == 'Yes')) { ?>

                            <!-- menu detail -->

                            <div class="menu-item-detail">
                            
                            <?php foreach ($menu as $key => $value) { ?>
                                <div class="menu-detail">    
                                    <h3 class="menu-title">

                                        <span class="title">
                                            <?php if(Yii::$app->language == 'en') { 
                                                    echo $value->menu_name;
                                              } else { 
                                                    echo $value->menu_name_ar;
                                              } ?>
                                        </span>

                                        <?php if($value->min_quantity || $value->max_quantity) { ?>
                                        <span class="menu-hint">
                                            
                                            <?php 

                                            echo Yii::t('frontend', 'Quantity range : ');

                                            if($value->min_quantity) { 
                                                echo Yii::t('frontend', 'Minimum {qty}', [
                                                    'qty' => $value->min_quantity
                                                ]); 
                                            } 

                                            if($value->min_quantity && $value->max_quantity) { 
                                                echo ' , ';
                                            }

                                            if($value->max_quantity) { 
                                                echo Yii::t('frontend', 'Maximum {qty}', [
                                                    'qty' => $value->max_quantity
                                                ]); 
                                            } 
                                           
                                            ?>
                                        </span>                                        
                                        <?php } ?>
                                    </h3>

                                    <span class="error menu_<?= $value->menu_id ?>"></span>

                                    <ul class="menu-items"  data-max-quantity="<?= $value->max_quantity ?>">
                                    <?php 

                                    $menu_items = VendorItemMenuItem::findAll(['menu_id' => $value->menu_id]);

                                    foreach ($menu_items as $menu_item) { ?>

                                        <li> 
                                            <!-- qty box -->

                                            <span class="menu-item-qty-box">
                                                <i class="fa fa-minus"></i>
                                                <input name="menu_item[<?= $menu_item->menu_item_id ?>]" class="menu-item-qty" value="0" readonly />
                                                <i class="fa fa-plus"></i>
                                            </span>

                                            <!-- item name -->

                                            <span class="menu-item-name">
                                                <?php if(Yii::$app->language == 'en') { 
                                                        echo $menu_item->menu_item_name;
                                                  } else { 
                                                        echo $menu_item->menu_item_name_ar;
                                                  } ?> 
                                            </span>

                                            <!-- price -->

                                            <?php if($menu_item->price > 0) { ?>
                                            <span class="menu_item_price">
                                                (+<?= CFormatter::format($menu_item->price) ?>)
                                            </span>
                                            <?php  } ?>

                                            <!-- hint -->

                                            <?php 

                                            $hint =  Yii::$app->language == 'en' ? $menu_item->hint : $menu_item->hint_ar;

                                            if($hint) { ?>
                                            <span class="menu-item-hint" data-toggle="tooltip" title="<?= $hint ?>"><i class="fa fa-info-circle"></i></span>
                                            <?php } ?>
                                            
                                            <span class="error menu_item_<?= $menu_item->menu_item_id ?>"></span>
                                        </li>
                                    <?php } ?>
                                    </ul>
                                </div><!-- END .menu-detail -->
                            <?php } ?>

                            <input id="item_id" name="item_id" value="<?= $model->item_id ?>" type="hidden" />

                            </div><!-- END .menu-item-detail -->
                           
                            <div class="row margin-top-20">

                                <?php if(!$menu) { ?>
                                    <div class="col-md-4 padding-top-12 pull-left quantity-lbl">
                                        <label><?= Yii::t('frontend', 'Quantity');?></label>
                                    </div>
                                    <div class="col-md-4 clearfix padding-left-6px qantity-div">
                                        <div class="form-group qty">
                                            <a href="#" class="btn-stepper" data-case="0">-</a>
                                            <input type="text" name="quantity" id="quantity" class="form-control" data-min="<?=$quantity?>" value="<?=$quantity?>"/>
                                            <a href="#" class="btn-stepper" data-case="1">+</a>
                                        </div>
                                    </div>
                                <?php } else { ?>
                                    <input type="hidden" name="quantity" id="quantity" data-min="<?= $quantity ?>" value="<?= $quantity ?>" />
                                <?php } ?>

                                <div class="col-lg-5 buy-btn">
                                    <div class="button-signin">
                                        <button type="submit" class="btn btn-primary btn-custome-1 width-100-percent" name="submit">
                                            <?= Yii::t('frontend', 'Buy') ?>
                                        </button>&nbsp;&nbsp;&nbsp;
                                    </div>
                                </div>
                                <span class=" col-lg-12 error cart_quantity"></span>
                                <span id="available"></span>
                            </div><!-- END .row -->
                        </form>
                        <?php } ?>

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
                                                <p><?= nl2br($item_additional_info); ?></p>
                                              </div>
                                            </div>
                                        </div><!-- END .panel -->
                                        <?php } ?>

                                        <?php if($whats_include) { ?>
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                              <h4 class="panel-title">
                                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse6" class="collapsed">
                                                    <?= Yii::t('frontend', "What is include?") ?>
                                                </a>
                                              </h4>
                                            </div>
                                            <div id="collapse6" class="panel-collapse collapse">
                                              <div class="panel-body">
                                                <p><?= nl2br($whats_include); ?></p>
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

                                                        <?php if ($vendor_detail['vendor_working_hours'] &&
                                                                    $vendor_detail['vendor_working_hours_to']) { ?>
                                                        <li class="vendor_working_hours">
                                                            <a>
                                                                <i class="fa fa-clock-o"></i>
                                                                <?php
                                                                    $from = explode(':', $vendor_detail['vendor_working_hours']);

                                                                    if($from)
                                                                    echo (isset($from[0])) ? $from[0] : '';
                                                                    echo (isset($from[1])) ? ':'.$from[1] : '';
                                                                    echo (isset($from[2])) ? ''.$from[2] : '';
                                                                ?>
                                                                -
                                                                <?php
                                                                    $to = explode(':', $vendor_detail['vendor_working_hours_to']);
                                                                    echo (isset($to[0])) ? $to[0] : '';
                                                                    echo (isset($to[1])) ? ':'.$to[1] : '';
                                                                    echo (isset($to[2])) ? ''.$to[2] : ''
                                                                ?>
                                                            </a>
                                                        </li>
                                                        <?php } ?>

                                                        <?php if($txt_day_off) { ?>
                                                        <li>
                                                            <a>
                                                                <i class="fa fa-clock-o"></i>
                                                                <?= Yii::t('frontend', '{txt_day_off} off', [
                                                                        'txt_day_off' => $txt_day_off
                                                                    ]); ?>
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
                                                <p><?= nl2br($model['item_customization_description']); ?></p>
                                              </div>
                                            </div>
                                        </div><!-- END .panel -->
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
                            echo Yii::t('frontend', 'More from {vendor_name}', ['vendor_name' => '<b>'.$vendor.'</b>']); ?>
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

                                                <img src="<?php echo $baselink; ?>" alt="Slide show images" />

                                                <div class="deals_listing_cont">
                                                    <h3><?=LangFormat::format($s->item_name,$s->item_name_ar); ?></h3>
                                                    <p><?=(trim($s['item_price_per_unit'])) ? CFormatter::format($s['item_price_per_unit']) : '<span class="small">'.Yii::t('app','Price upon request').'<span>'  ?></p>
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

<div id="modal_booking_service" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <form action="<?= Url::to(['browse/booking']) ?>" method="post">

        <input id="item_id" name="item_id" value="<?= $model->item_id ?>" type="hidden" />

        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title text-center" id="myModalLabel">
                <span>REQUEST BOOKING SERVICE </span>
            </h4>
          </div>

          <div class="modal-body">
            <div class="login-padding">
                <div class="form-group">    
                    <label>Name</label>
                    <input class="form-control input-lg" name="name" placeholder="Your name" required />
                    <span class="error name"></span>
                </div>
                <div class="form-group">    
                    <label>Phone</label>
                    <input class="form-control input-lg" name="phone" pattern='\d' placeholder="Your phone no" title="Digits only" required />
                    <span class="error phone"></span>
                </div>
                <div class="form-group">    
                    <label>Email</label>
                    <input type="email" class="form-control input-lg" name="email" placeholder="Your email address" required />
                    <span class="error email"></span>
                </div>
                <div class="form-group">
                    <div class="button-signin">
                        <button type="button" class="btn btn-primary btn-lg btn-block btn-booking-service">
                            <?= Yii::t('frontend', 'Submit') ?>
                        </button>
                    </div>
                </div>
            </div>
          </div><!--END .modal-body -->
        </div><!-- END .modal-content -->
    </form>
  </div>
</div>

<div id="option_modal_wrapper"></div>

<?php

echo Html::hiddenInput('final_price_url', Url::to(['browse/final-price']), ['id' => 'final_price_url']);

$this->registerJs("
    var deliver_date = '".$deliver_date."';
    var isGuest = ".(int)Yii::$app->user->isGuest.";
    var vendor_id = '".$model['vendor_id']."';
    var customer_id = '".Yii::$app->user->id."';
    var addtobasket_url = '".Yii::$app->urlManager->createAbsoluteUrl('cart/add')."';
    var getdeliverytimeslot_url = '".Url::toRoute('cart/get-delivery-timeslot')."';
    var area_option_url = '".Url::toRoute('site/area')."';
    var availablity = '".Url::toRoute('browse/product-available')."';
    var product_availability = '".Url::toRoute('cart/validation-product-available')."';
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
    $('.filter-bar').show();

", View::POS_READY);

$this->registerCss("

    .btn-booking-modal {
        margin-top: 7px;
        padding: 10px;
        width: 100%;
    }
    .width-100-percent{width:100%!important;}
    .margin-top-20{margin-top:20px;}
    .width-20-percent{width: 20%;}
    .width-63-percent{width: 63%!important;}
    .datepicker{border: 2px solid rgb(242, 242, 242);}
    .datepicker table{font-size: 12px;}
    .form-group{margin-bottom:15px;width: 92%;margin-left: 11px;}
    .filter-bar{margin-top: 22px;padding-left: 0px;}
    .date-picker-box{height: 44px;border-radius: 0px;box-shadow: none;border-color: #e6e6e6;}
    .filter-bar .fa-calendar{position: absolute;right: 8px;top: 12px;font-size: 15px;color:#e6e6e6;}
    #form_product_option .selectpicker.btn-primary {color: #555!important;}
    #delivery_date_wrapper{position:relative;}
    .padding-right-0{padding-right:0px!important;}
    .padding-left-0{padding-left:0px!important;}
    .selectpicker,#area_id,#delivery_date,#timeslot_id{color:#000!important;}
    .margin-left-0{margin-left:0px!important;}
    .filter-bar .submit-btn{border-radius: 0px;padding: 10px;width: 72%;}
    .filter-bar .form-group label{font-weight:normal;color: #999 !important;font-size: 13px;}
    .margin-top-0{margin-top:0px!important;}
    .padding-top-12{padding-top: 12px;}
    .btn-stepper {width: 31%;color: white;background-color: #000;display: inline-block;text-align: center;height: 100%;float: left;line-height: 43px;font-size: 25px;font-style: normal;font-weight: bold;}
    .form-group input[name=quantity] {float: left;width: 38%;line-height: 38px;height: 100%!important;text-align: center;margin: 0;border-top: 1px solid #e6e6e6;box-shadow: none;border-bottom: 1px solid #e6e6e6;}
    .qty {width: 91%;display: block;height: 45px;margin-right: 8px;overflow: hidden;}
    .product_detail_section .panel-body p{text-align:justify;}
    .font-27{font-size:27px!important;}
    .margin-bottom-14{margin-bottom:14px!important;}
    .qty a:hover, .qty a:focus {color: #fff!important;}
    button.dropdown-toggle{background: #fff;color: #000;border-radius: 0px;height: 42px;border-color: #e6e6e6;}
    .color-808080{color: #808080!important;}
    .height-2{height:2px!important;}
    .margin-4{margin: 4px 0 0px;}
    .product-right-width{width:607px;float:none;padding:0;}
    .resp_hide{text-align:center;}
    .normal_mode ul.slides li img{width:100%!important;}
    .mobile_mode .responsive_slider_detials ul.slides li img{width:auto!important;}
    .color-999999{color: #999999!important;}
    #available{display: none;margin-top: 18px;}
    .margin-top-13{margin-top: 13px!important;}
    .fa-whatsapp{font-size: 169%;margin-top: 2px;}
");

$this->registerJsFile('@web/js/product_detail.js?v=1.7', ['depends' => [\yii\web\JqueryAsset::className()]]);

