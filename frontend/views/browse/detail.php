<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yii\web\view;
use common\models\VendorItemPricing;
use common\models\VendorItemMenuItem;
use common\models\CustomerCart;
use common\models\CustomerCartMenuItem;
use common\models\VendorItem;
use common\components\LangFormat;
use common\components\CFormatter;

$item_name = LangFormat::format($model->item_name,$model->item_name_ar);
$vendor_name = LangFormat::format($model->vendor->vendor_name,$model->vendor->vendor_name_ar);
$item_description = LangFormat::format($model->item_description, $model->item_description_ar);
$item_additional_info = LangFormat::format($model->item_additional_info, $model->item_additional_info_ar);

$vendor_contact_address = LangFormat::format($model->vendor->vendor_contact_address, $model->vendor->vendor_contact_address_ar);

$max_time = LangFormat::format($model->max_time, $model->max_time_ar);
$set_up_time = LangFormat::format($model->set_up_time, $model->set_up_time_ar);
$requirements = LangFormat::format($model->requirements, $model->requirements_ar);

$this->title = 'Whitebook - ' . $item_name;
$this->params['breadcrumbs'][] = ' '.$item_name;

$session = $session = Yii::$app->session;
$deliver_location   = ($session->has('delivery-location')) ? $session->get('delivery-location') : null;
$deliver_date  = ($session->has('delivery-date')) ? $session->get('delivery-date') : '';

if($model->type) {
    $item_type_name = $model->type->type_name;
} else {
    $item_type_name = 'Product';
}

$min_quantity_to_order = 1;

if($model['item_minimum_quantity_to_order'] > 0) {
    $min_quantity_to_order = $model['item_minimum_quantity_to_order'];
} 
    
if($model['included_quantity'] > $min_quantity_to_order) {
    $min_quantity_to_order = $model['included_quantity'];
}

$quantity = $min_quantity_to_order;

$capacity = $model->item_default_capacity;

if (isset($model->vendorItemCapacityExceptions) && count($model->vendorItemCapacityExceptions)>0) {

    $exceptionDate = \yii\helpers\ArrayHelper::map($model->vendorItemCapacityExceptions, 'exception_date', 'exception_capacity');

    if (isset($exceptionDate) && count($exceptionDate) > 0) {
        if ($deliver_date && isset($exceptionDate[date('Y-m-d',strtotime($deliver_date))])) {
            $capacity = $exceptionDate[date('Y-m-d', strtotime($deliver_date))];
        }
    }
}

if($model->images) {
    $image = Yii::getAlias("@s3/vendor_item_images_530/"). $model->images[0]->image_path;
}else{
    $image = 'https://placeholdit.imgix.net/~text?txtsize=33&txt=530x530&w=530&h=550';
}

$cart_id = Yii::$app->request->get('cart_id');

$cart = CustomerCart::findOne($cart_id);

if($cart)
{
    $quantity = $cart->cart_quantity;
    $txt_cart_btn = Yii::t('frontend', 'Update Cart');
    $cart_url = Yii::$app->urlManager->createAbsoluteUrl('cart/update-cart-item');
    echo Html::hiddenInput('update_cart', 1, ['id' => 'update_cart']);
}
else
{
    $txt_cart_btn = Yii::t('frontend', 'Add To Cart');
    $cart_url = Yii::$app->urlManager->createAbsoluteUrl('cart/add');
    echo Html::hiddenInput('update_cart', 0, ['id' => 'update_cart']);
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
    "price": "<?= $model['item_price_per_unit'] * $min_quantity_to_order ?>",
    "availability": "http://schema.org/InStock",
    "seller": {
      "name": "<?= $vendor_name; ?>"
    }
  }
}
</script>

<!-- coniner start -->
<section id="inner_pages_white_back" class="product_details_com <?=Yii::$app->controller->id;?>">

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

        <?php if ($AvailableStock) { ?>
        <form id="form_product_option" method="POST" class="form center-block margin-top-0">

        <input id="item_id" name="item_id" value="<?= $model->item_id ?>" type="hidden" />

        <?php if($cart) { ?>
        <input id="cart_id" name="cart_id" value="<?= $cart->cart_id ?>" type="hidden" />
        <?php } ?>

        <?php } ?>

        <!-- Mobile start Here-->
        <div class="product_detail_section responsive-detail-section"><!--product detail start-->
            <div class="col-md-12 padding0">
                <div class="product_detials_common normal_tables">
                    
                    <div id="main" role="main" class="col-md-5 padding-left-0 normal_mode left-sidebar">

                        <div class="col-md-6 paddig0 resp_hide mobile_mode">
                            <div class="left_descrip mobile-view">
                                <h2><?= $item_name; ?></h2>
                                <label>
                                    <a title="<?= $model->vendor->vendor_name; ?>" href="<?= Url::to(["site/vendor_profile", 'slug' => $model->vendor->slug]) ?>" class="color-violet">
                                        <?= $vendor_name; ?>
                                    </a>
                                </label>
                                <b class="font-27">
                                    <p class="item-final-price">
                                        <i class="fa fa-spinner fa-spin"></i>
                                    </p>
                                </b>

                                <?php
                                
                                if ($menu) {
                                    echo '<span class="small price_warning" style="font-weight: bold;font-size:18px; color: brown; display: none;">'.Yii::t('frontend','Price on selection of menu items').'</span>';
                                }else{
                                    echo '<span class="small price_warning" style="font-weight: bold;font-size:18px; display: none;">'.Yii::t('frontend','Price based on selection').'</span>'; // price warning for 0 amount
                                }

                                ?>
                                <?php

                                $pricing = VendorItemPricing::find()
                                    ->where(['item_id'=> $model->item_id])
                                    ->all();

                                if($pricing && !$model->hide_price_chart) { ?>

                                    <a class="lnk-price-chart">
                                        <i class="fa fa-plus-square-o"></i>
                                        <span class="color-violet">
                                            <?= Yii::t('frontend', 'View full price chart') ?>
                                        </span>
                                    </a>

                                    <div class="price_chart_wrapper hidden text-center">
                                        <table class="table table-striped table-bordered detail-view price_range">
                                            <thead>
                                                <tr>
                                                    <th><?= Yii::t('frontend', 'Quantity') ?></th>
                                                    <th><?= Yii::t('frontend', 'Price') ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($pricing as $key => $value) { ?>
                                                <tr>
                                                    <td><?= $value['range_from'] ?>+</td>
                                                    <td>
                                                        <?= CFormatter::format($value['pricing_price_per_unit']) ?>
                                                    </td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div><!-- END .price_chart_wrapper -->

                                <?php } //if pricing ?>
                            </div>
                        </div>
                        
                        <?php if($model['item_how_long_to_make'] > 0) { ?>
                        <div class="callout-container">
                            <span class="callout light">
                                <?php

                                if($model['notice_period_type'] == 'Day')
                                {
                                    if ($model['item_how_long_to_make'] % 7 == 0) {
                                        echo Yii::t('frontend', 'Notice: {count} week(s)', [
                                            'count' => substr(($model['item_how_long_to_make'] / 7),0,3)
                                        ]);
                                    } else {
                                        echo Yii::t('frontend', 'Notice: {count} day(s)', [
                                            'count' => $model['item_how_long_to_make']
                                        ]);
                                    }
                                }
                                else
                                {
                                    if ($model['item_how_long_to_make'] >= 24) {
                                        echo Yii::t('frontend', 'Notice: {count} day(s)', [
                                            'count' => substr(($model['item_how_long_to_make'] / 24),0,3)
                                        ]);
                                    } else {
                                        echo Yii::t('frontend', 'Notice: {count} hours', [
                                            'count' => $model['item_how_long_to_make']
                                        ]);
                                    }

                                } ?>
                            </span>
                        </div>
                        <?php } ?>

                        <div class="item-images">
                            <div class="main-image">
                                <?php  
                                if($model->images) { 
                                    echo Html::img(Yii::getAlias("@s3/vendor_item_images_530/"). $model->images[0]->image_path,['alt'=>'item detail image']);
                                } else if($model->videos) { 
                                    echo Html::img('https://img.youtube.com/vi/'.$model->videos[0]->video.'/default.jpg', ['alt'=>'item detail image']);
                                } else {
                                    echo Html::img(Url::to("@web/images/item-default.png"));
                                } ?>
                            </div>                            
                            <?php if ($model->images || $model->videos) { ?>
                            <div class="thumb-images">
                                <ul>
                                    <?php foreach ($model->images as $image) { ?>
                                    <li>
                                        <a data-type="image" data-src="<?= Yii::getAlias("@s3/vendor_item_images_530/"). $image->image_path ?>">
                                            <?= Html::img(Yii::getAlias("@s3/vendor_item_images_210/"). $image->image_path, ['alt'=>'item detail image']) ?>
                                        </a>
                                    </li>
                                    <?php } ?>
                                    <?php foreach ($model->videos as $video) { ?>
                                    <li>
                                        <a data-type="videos" data-src="<?= $video->video ?>"> 
                                            <?= Html::img('https://img.youtube.com/vi/'.$video->video.'/default.jpg', ['alt'=>'item detail image']) ?>
                                        </a>
                                    </li>
                                <?php } ?>
                                </ul>
                            </div>
                            <?php } ?>
                            <div class="clearfix"></div>
                        </div><!-- END .item-images --> 

                    </div>
                    <div class="col-md-7 right-sidebar">
                        <div class="right_descr_product">
                            <div class="product_name clearfix">
                                <div class="left_descrip desktop-view margin-bottom-14">
                                    <h2><?= $item_name; ?></h2>

                                    <label>
                                        <a title="<?= $model->vendor->vendor_name; ?>" href="<?= Url::to(["community/profile", 'slug'=>'all','vendor' => $model->vendor->slug]) ?>" class="color-violet">
                                            <?= $vendor_name; ?>
                                        </a>
                                    </label>

                                    <b class="font-27 item-final-price">
                                        <i class="fa fa-spinner fa-spin"></i>
                                    </b>
                                    <?php
                                    
                                    if ($menu) {
                                        echo '<span class="small price_warning" style="font-weight: bold;font-size:18px; color: brown; display: none;">'.Yii::t('frontend','Price on selection of menu items').'</span>';
                                    }else{
                                        echo '<span class="small price_warning" style="font-weight: bold;font-size:18px; display: none;">'.Yii::t('frontend','Price base on selection').'</span>'; // price warning for 0 amount
                                    }

                                    ?>

                                    <?php if($model['min_order_amount'] > 0) {

                                        echo '<h5 style="clear: both;display: block;color: brown;">';

                                        echo Yii::t('frontend','Min. order amount : {amount}', [
                                                'amount' => CFormatter::format($model['min_order_amount'])
                                            ]);

                                        echo '</h5>';

                                    } ?>

                                    <?php

                                    $pricing = VendorItemPricing::find()
                                        ->where(['item_id'=> $model->item_id])
                                        ->all();

                                    if($pricing && !$model->hide_price_chart) { ?>

                                        <a class="lnk-price-chart">
                                            <i class="fa fa-plus-square-o"></i>
                                            <span class="color-violet">
                                                <?= Yii::t('frontend', 'View full price chart') ?>
                                            </span>
                                        </a>

                                        <div class="price_chart_wrapper hidden">
                                            <table class="table table-striped table-bordered detail-view price_range">
                                                <thead>
                                                    <tr>
                                                        <th><?= Yii::t('frontend', 'Quantity') ?></th>
                                                        <th><?= Yii::t('frontend', 'Price') ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($pricing as $key => $value) { ?>
                                                    <tr>
                                                        <td><?= $value['range_from'] ?>+</td>
                                                        <td>
                                                            <?= CFormatter::format($value['pricing_price_per_unit']) ?>
                                                        </td>
                                                    </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div><!-- END .price_chart_wrapper -->

                                    <?php } //if pricing ?>

                                </div>
                                <div class="right_descrip">
                                    <div class="responsive_width">
                                        <?php
                                        /* @TODO Removed Event Section
                                        if (Yii::$app->user->isGuest) { ?>
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
                                        <?php }  */ ?>
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

<!--                                        <div class="clerfix"></div>-->
<!---->
<!--                                        <button type="button" class="btn btn-default btn-booking-modal">-->
<!--                                            --><?//= Yii::t('frontend', 'REQUEST BOOKING SERVICE') ?>
<!--                                        </button>-->

                                        
                                        <?php if (!$AvailableStock) { ?>
                                            <div class="buy_events">
                                                <?= Html::a(Yii::t('frontend', 'Out of stock'),'#',['class'=>'stock','id'=>$model['item_id']]); ?>
                                            </div>
                                        <?php } ?>

                                        <a class="color-violet lnk-company-policy" href="<?= Url::to(["community/profile", 'slug'=>'all','vendor' => $model->vendor->slug]) ?>">
                                            <?= Yii::t('frontend', 'Company Refund Policy') ?>
                                        </a>

                                    </div>
                                </div>

                                <div class="clearfix"></div>

                                <?php if($AvailableStock && $capacity > 1 && $item_type_name != 'Package') { ?>
                                <div class="qty_box">
                                    <div class="quantity-lbl">
                                        <label>
                                            <?= Yii::t('frontend', $model['quantity_label']); ?>
                                        </label>
                                    </div>

                                    <div class="qantity-div">
                                        <div class="form-group qty" style="margin: 0px;">
                                            <a href="#" class="btn-stepper" data-case="0">-</a>
                                            <input type="text" name="quantity" id="quantity" class="form-control" data-min="<?= $quantity ?>" value="<?=$quantity ?>"/>
                                            <a href="#" class="btn-stepper" data-case="1">+</a>
                                        </div>
                                        <span class="error cart_quantity"></span>
                                    </div>
                                </div>
                                <?php } else { ?>
                                    <input type="hidden" name="quantity" id="quantity" class="form-control" data-min="<?= $quantity?>" value="<?= $quantity ?>" />
                                <?php } ?>

                                <div class="social_share hidden-xs hidden-sm">
                                    <?php

                                    $title = Yii::$app->name.' ' . ucfirst($vendor_name);
                                    $summary = Yii::$app->name.' '. ucfirst($item_name).' from '.ucfirst($vendor_name);

                                    $image = isset($baselink) ? $baselink : '';
                                    $url = Url::toRoute(['browse/detail','slug'=>$model->slug],true);
                                    $mailbody = "Check out ".ucfirst($item_name)." on ".Yii::$app->name." ".$url;
                                    ?>
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

                            <div class="accad_menus">
                                <div class="panel-group vendor-item-detail" id="accordion">

                                    <?php if (!empty($model['item_description'])) { ?>
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                          <h4 class="panel-title">
                                            <a data-toggle="collapse"  href="#collapse1">
                                                <?= Yii::t('frontend', 'Description') ?>
                                            </a>
                                          </h4>
                                        </div>
                                        <div id="collapse1" class="panel-collapse collapse in">
                                          <div class="panel-body">

                                            <?php if($set_up_time || $max_time || $requirements) { ?>
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
                                                    <span class="title"><?= Yii::t('frontend', 'Duration') ?></span>
                                                    <span class="value"><?= $max_time ?></span>
                                                </div>
                                                <?php } ?>

                                                <span class="clearfix"></span>
                                            </div>
                                            <?php } ?>

                                            <h4><?= Yii::t('frontend', 'Brief') ?></h4>
                                            <p><?= nl2br($item_description); ?></p>

                                            <?php if($item_additional_info) { ?>
                                                <h4><?= Yii::t('frontend', 'Additional Information') ?></h4>
                                                <p><?= nl2br($item_additional_info); ?></p>
                                            <?php } ?>

                                          </div>
                                        </div>
                                    </div><!-- END .panel -->
                                    <?php } ?>


                                    <?php require 'detail/delivery.php'; ?>

                                    <?php if($menu) { ?>
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                          <h4 class="panel-title">
                                            <a data-toggle="collapse"  href="#collapse-options" aria-expanded="true">
                                                <?= Yii::t('frontend', 'Options') ?>
                                            </a>
                                          </h4>
                                        </div>
                                        <div id="collapse-options" class="panel-collapse collapse in">
                                          <div class="panel-body">
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
                                                            
                                                            <span class="menu-hint" data-max-quantity="<?= $value->max_quantity ?>" data-min-quantity="<?= $value->min_quantity ?>" data-txt-min="<?= Yii::t('frontend', 'atleast {qty} '); ?>" data-txt-max="<?= Yii::t('frontend', 'upto {qty} '); ?>">

                                                                <?php

                                                                echo Yii::t('frontend', 'Select ');

                                                                if($value->min_quantity) {
                                                                    echo Yii::t('frontend', 'atleast {qty} ', [
                                                                        'qty' => $value->min_quantity
                                                                    ]);
                                                                }

                                                                /*if($value->min_quantity && $value->max_quantity) {
                                                                    echo ' , ';
                                                                }

                                                                if($value->max_quantity) {
                                                                    echo Yii::t('frontend', ' upto {qty}', [
                                                                        'qty' => $value->max_quantity
                                                                    ]);
                                                                }*/

                                                                ?>
                                                            </span>
                                                            <?php } ?>
                                                        </h3>

                                                        <span class="error menu_<?= $value->menu_id ?>"></span>

                                                        <ul class="menu-items" data-max-quantity="<?= $value->max_quantity ?>">
                                                        <?php

                                                        $menu_items = VendorItemMenuItem::findAll(['menu_id' => $value->menu_id]);

                                                        foreach ($menu_items as $menu_item) { 

                                                            if($cart) 
                                                            {
                                                                $cart_menu_item = CustomerCartMenuItem::findOne([
                                                                    'menu_item_id' => $menu_item->menu_item_id,
                                                                    'cart_id' => $cart->cart_id
                                                                ]);    
                                                            }
                                                            else
                                                            {
                                                                $cart_menu_item = null;
                                                            }

                                                            ?>

                                                            <li>

                                                                <table>
                                                                <tr>
                                                                <?php if($value->quantity_type == 'selection') { ?>

                                                                <!-- qty box -->

                                                                <td class="menu-item-qty-box">
                                                                    <i class="fa fa-minus"></i>
                                                                    <input name="menu_item[<?= $menu_item->menu_item_id ?>]" class="menu-item-qty" value="<?= isset($cart_menu_item)?$cart_menu_item->quantity:0 ?>" />
                                                                    <i class="fa fa-plus"></i>
                                                                </td>

                                                                <!-- item name -->

                                                                <td class="menu-item-name">
                                                                    <?php if(Yii::$app->language == 'en') {
                                                                            echo $menu_item->menu_item_name;
                                                                      } else {
                                                                            echo $menu_item->menu_item_name_ar;
                                                                      } ?>

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
                                                                </td>

                                                                <?php } else { ?>

                                                                <td>
                                                                    <div  class="checkbox checkbox-inline">
                                                                        <input name="menu_item[<?= $menu_item->menu_item_id ?>]" id="menu_item[<?= $menu_item->menu_item_id ?>]" class="menu-item-qty" value="1" type="checkbox" <?php if(!empty($cart_menu_item)) echo 'checked'; ?> />

                                                                        <label for="menu_item[<?= $menu_item->menu_item_id ?>]">
                                                                            <?php if(Yii::$app->language == 'en') {
                                                                                    echo $menu_item->menu_item_name;
                                                                              } else {
                                                                                    echo $menu_item->menu_item_name_ar;
                                                                              } ?>

                                                                            <!-- hint -->
                                                                            
                                                                            <?php

                                                                            $hint =  Yii::$app->language == 'en' ? $menu_item->hint : $menu_item->hint_ar;

                                                                            if($hint) { ?>
                                                                            <span class="menu-item-hint" data-toggle="tooltip" title="<?= $hint ?>"><i class="fa fa-info-circle"></i></span>
                                                                            <?php } ?>
                                                                        </label>
                                                                    </div>

                                                                    <!-- price -->

                                                                    <?php if($menu_item->price > 0) { ?>
                                                                    <span class="menu_item_price">
                                                                        (+<?= CFormatter::format($menu_item->price) ?>)
                                                                    </span>
                                                                    <?php  } ?>

                                                                    <span class="error menu_item_<?= $menu_item->menu_item_id ?>"></span>

                                                                    &nbsp;
                                                                </td>

                                                                <?php } ?>
                                                                
                                                                <?php if($menu_item->image) { ?>
                                                                <td>
                                                                <a class="menu-item-image" href="<?= Yii::getAlias("@s3/"). VendorItem::UPLOADFOLDER_MENUITEM. $menu_item->image ?>" data-lightbox="menu-items" data-title="<?= Yii::$app->language == 'en'?$menu_item->menu_item_name:$menu_item->menu_item_name_ar; ?>"><img src="<?= Yii::getAlias("@s3/"). VendorItem::UPLOADFOLDER_MENUITEM_THUMBNAIL. $menu_item->image ?>"></a>
                                                                </td>
                                                                <?php } ?>

                                                                </tr>
                                                                </table>
                                                            </li>
                                                        <?php } ?>
                                                        </ul>
                                                    </div><!-- END .menu-detail -->
                                                <?php } ?>
                                             </div><!-- END .menu-item-detail -->

                                          </div>
                                        </div>
                                    </div><!-- END .panel -->
                                    <?php } ?>

                                    <?php if($addons) { ?>
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                          <h4 class="panel-title">
                                            <a data-toggle="collapse"  aria-expanded="true" href="#collapse-addons">
                                                <?= Yii::t('frontend', 'Addons') ?>
                                            </a>
                                          </h4>
                                        </div>
                                        <div id="collapse-addons" class="panel-collapse collapse in">
                                          <div class="panel-body">
                                             <div class="menu-item-detail">
                                                <?php foreach ($addons as $key => $value) { ?>
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

                                                        foreach ($menu_items as $menu_item) { 

                                                            if($cart)
                                                            {
                                                                $cart_menu_item = CustomerCartMenuItem::findOne([
                                                                    'menu_item_id' => $menu_item->menu_item_id,
                                                                    'cart_id' => $cart->cart_id
                                                                ]);
                                                            }
                                                            else
                                                            {
                                                                $cart_menu_item = null;   
                                                            }
                                                            
                                                            ?>

                                                            <li>
                                                                <table>
                                                                <tr>

                                                                <!-- qty box -->

                                                                <td class="menu-item-qty-box">
                                                                    <i class="fa fa-minus"></i>
                                                                    <input name="menu_item[<?= $menu_item->menu_item_id ?>]" class="menu-item-qty" value="<?= $cart_menu_item?$cart_menu_item->quantity:0 ?>" />
                                                                    <i class="fa fa-plus"></i>
                                                                </td>

                                                                <!-- item name -->
                                                                
                                                                <td class="menu-item-name">
                                                                    
                                                                    <?php if(Yii::$app->language == 'en') {
                                                                            echo $menu_item->menu_item_name;
                                                                      } else {
                                                                            echo $menu_item->menu_item_name_ar;
                                                                      } ?>
                                                                    
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
                                                                </td>

                                                                <?php if($menu_item->image) { ?>
                                                                <td>
                                                                    <a class="menu-item-image" href="<?= Yii::getAlias("@s3/"). VendorItem::UPLOADFOLDER_MENUITEM. $menu_item->image ?>" data-lightbox="menu-items" data-title="<?= Yii::$app->language == 'en'?$menu_item->menu_item_name:$menu_item->menu_item_name_ar; ?>"><img src="<?= Yii::getAlias("@s3/"). VendorItem::UPLOADFOLDER_MENUITEM_THUMBNAIL. $menu_item->image ?>"></a>
                                                                </td>
                                                                <?php } ?>

                                                                </tr>
                                                                </table>
                                                            </li>
                                                        <?php } ?>
                                                        </ul>
                                                    </div><!-- END .menu-detail -->
                                                <?php } ?>
                                             </div><!-- END .menu-item-detail -->

                                          </div>
                                        </div>
                                    </div><!-- END .panel -->
                                    <?php } ?>

                                    <?php if($model->itemQuestions) { ?>
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a data-toggle="collapse"  aria-expanded="true" href="#collapse-customs">
                                                        <?= Yii::t('frontend', 'Customs') ?>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="collapse-customs" class="panel-collapse collapse in">
                                                <div class="panel-body">
                                                    <div class="menu-item-detail">
                                                        <?php foreach ($model->itemQuestions as $key => $question) {
                                                            $answer = '';
                                                            if ($cart) {
                                                                $answerData = \common\models\CustomerCartItemQuestionAnswer::findOne(
                                                                        [
                                                                                'question_id'=>$question->item_question_id,
                                                                                'item_id' => $cart->item_id,
                                                                                'cart_id' => $cart->cart_id
                                                                        ]);
                                                                if ($answerData) {
                                                                    $answer = $answerData->answer;
                                                                }
                                                            }

                                                            ?>
                                                            <div class="row margin-bottom-10" style="margin-bottom: 20px">
                                                                <div class="col-lg-12 col-md-12 question">
                                                                    <span style="text-transform: capitalize"><?=$question->question?></span> <?=($question->required) ? '<span style="color: brown;">*</span>' : '';?>
                                                                </div>
                                                                <div class="col-lg-12 col-md-12 answer">
                                                                    <input type="text" name="answer[<?=$question->item_question_id?>]" id="answer-<?=$question->item_question_id?>" class="form-control input-lg" value="<?=$answer ?>"/>
                                                                </div>
                                                                <span class="col-lg-12 col-md-12 error question-<?=$question->item_question_id?>"></span>
                                                            </div>

                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!-- END .panel -->
                                    <?php } ?>

                                    <?php if($model->allow_special_request) { ?>
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                          <h4 class="panel-title">
                                            <a data-toggle="collapse"  href="#collapse-special-request" aria-expanded="true">
                                                <?= Yii::t('frontend', 'Special request') ?>
                                            </a>
                                          </h4>
                                        </div>
                                        <div id="collapse-special-request" class="panel-collapse collapse in">
                                          <div class="panel-body">

                                            <br />

                                            <textarea name="special_request" class="form-control"><?= $cart?$cart->special_request:'' ?></textarea>
                                          </div>
                                        </div>
                                    </div>
                                    <?php } ?>

                                    <?php if($model->have_female_service) { ?>
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                          <h4 class="panel-title">
                                            <a data-toggle="collapse"  href="#collapse-female" aria-expanded="true">
                                                <?= Yii::t('frontend', 'Female Service') ?>
                                            </a>
                                          </h4>
                                        </div>
                                        <div id="collapse-female" class="panel-collapse collapse in">
                                          <div class="panel-body">
                                            <div class="form-group checkbox" style="margin-left: 0px;">
                                                <input type="checkbox" name="female_service" value="1" id="chk_female_service" <?php if(!empty($cart->female_service)) echo 'checked'; ?> />
                                                <label for="chk_female_service">
                                                    <?= Yii::t('frontend', 'Include Female Service') ?>
                                                </label>
                                            </div>
                                          </div>
                                        </div>
                                    </div>
                                    <?php } ?>

                                    <?php if ($AvailableStock) { ?>

                                        <button type="submit" class="btn btn-primary btn-custome-1 width-100-percent" name="submit" style="padding: 12px 5px; margin-top: 10px; max-width: 240px;"><?= $txt_cart_btn ?></button>

                                    <?php } ?><!-- END available in stock and for sale -->

                                    <?php if (!empty($model['item_customization_description'])) { ?>
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                          <h4 class="panel-title">
                                            <a data-toggle="collapse"  href="#collapse5" aria-expanded="true">
                                                <?php echo Yii::t('frontend', 'Customization'); ?>
                                            </a>
                                          </h4>
                                        </div>
                                        <div id="collapse5" class="panel-collapse collapse in">
                                          <div class="panel-body">
                                            <p><?= nl2br($model['item_customization_description']); ?></p>
                                          </div>
                                        </div>
                                    </div><!-- END .panel -->
                                    <?php } ?>
                                </div>
                            </div>

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

                            $baselink = Url::to("@web/images/item-default.png");

                            foreach ($similiar_item as $s) {

                                if (
                                    $s->item_approved == 'Yes' &&
                                    $s->trash == 'Default' &&
                                    $s->item_status == 'Active'
                                ) {
                                    $AvailableStock = true;
                                } else {
                                    $AvailableStock = false;
                                }

                                if (isset($s->images) && count($s->images) > 0) {
                                    $baselink = Yii::getAlias("@s3/vendor_item_images_210/") . $s->images[0]['image_path'];
                                }
                            ?>
                                <div class="item">
                                    <?php if (!$AvailableStock) { ?>
                                        <img src="<?php echo Url::to("@web/images/sold-out.png");?>" class="sold-out">
                                    <?php } ?>
                                    <div class="fetu_product_list">
                                        <?php if ($s['slug'] != '') { ?>
                                            <a href="<?= Url::to(["browse/detail", 'slug' => $s['slug']]) ?>" title="Products" class="similar">

                                                <img src="<?php echo $baselink; ?>" alt="Slide show images" />

                                                <div class="deals_listing_cont">
                                                    <h3>
                                                        <?=LangFormat::format($s->item_name,$s->item_name_ar); ?>
                                                    </h3>
                                                    <p>
                                                        <?php 

                                                        if (trim($s['item_base_price'])) 
                                                        {                                                     
                                                            echo CFormatter::format($s['item_base_price']); 
                                                        } else {
                                                            echo '<span class="small">' . Yii::t('app', 'Price based on selection') . '<span>';
                                                        } ?>
                                                    </p>
                                                </div>

                                                <?php

                                                if($s['item_how_long_to_make'] > 0) { ?>
                                                <div class="callout-container" style="top: 170px; bottom: auto; right: 5px;">
                                                    <span class="callout light">
                                                        <?php

                                                        if($s['notice_period_type'] == 'Day')
                                                        {
                                                            if ($s['item_how_long_to_make'] % 7 == 0) {
                                                                echo Yii::t('frontend', 'Notice: {count} week(s)', [
                                                                    'count' => substr(($s['item_how_long_to_make'] / 7),0,3)
                                                                ]);
                                                            } else {
                                                                echo Yii::t('frontend', 'Notice: {count} day(s)', [
                                                                    'count' => $s['item_how_long_to_make']
                                                                ]);
                                                            }
                                                        }
                                                        else
                                                        {
                                                            if ($s['item_how_long_to_make'] >= 24) {
                                                                echo Yii::t('frontend', 'Notice: {count} day(s)', [
                                                                    'count' => substr(($s['item_how_long_to_make'] / 24),0,3)
                                                                ]);
                                                            } else {
                                                                echo Yii::t('frontend', 'Notice: {count} hours', [
                                                                    'count' => $s['item_how_long_to_make']
                                                                ]);
                                                            }

                                                        }
                                                        ?>
                                                    </span>
                                                </div>
                                                <?php } ?>

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
        </form>
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
                    <input value="<?= $customer_name ?>" class="form-control input-lg" name="name" placeholder="Your name" required />
                    <span class="error name"></span>
                </div>
                <div class="form-group">
                    <label>Phone</label>
                    <input value="<?= $customer_phone ?>" class="form-control input-lg" name="phone" pattern='\d' placeholder="Your phone no" title="Digits only" required />
                    <span class="error phone"></span>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input value="<?= $customer_email ?>" type="email" class="form-control input-lg" name="email" placeholder="Your email address" required />
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

echo Html::hiddenInput('notice_period_type', $model['notice_period_type'], ['id' => 'notice_period_type']);
echo Html::hiddenInput('item_how_long_to_make', $model['item_how_long_to_make'], ['id' => 'item_how_long_to_make']);
echo Html::hiddenInput('final_price_url', Url::to(['browse/final-price']), ['id' => 'final_price_url']);
echo Html::hiddenInput('save-delivery-timeslot-url', Url::to(['cart/save-delivery-timeslot']), ['id' => 'save-delivery-timeslot-url']);

echo Html::hiddenInput('txt-select', Yii::t('frontend', 'Select '), ['id' => 'txt-select']);
echo Html::hiddenInput('txt-min', Yii::t('frontend', 'atleast {qty} '), ['id' => 'txt-min']);
echo Html::hiddenInput('txt-max', Yii::t('frontend', ' upto {qty}'), ['id' => 'txt-max']);
echo Html::hiddenInput('txt-timeslot-not-available', Yii::t('frontend', 'Delivery timeslot not available'), ['id' => 'txt-timeslot-not-available']);

echo Html::hiddenInput('item_type_name', $item_type_name, ['id' => 'item_type_name']);
echo Html::hiddenInput('capacity', $capacity, ['id' => 'capacity']);
echo Html::hiddenInput('minimum_increment', $model->minimum_increment, ['id' => 'minimum_increment']);

$this->registerJs("
    var deliver_date = '".$deliver_date."';
    var isGuest = ".(int)Yii::$app->user->isGuest.";
    var vendor_id = '".$model['vendor_id']."';
    var customer_id = '".Yii::$app->user->id."';
    var addtobasket_url = '".$cart_url."';
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
    .filter-bar{margin-top: 5px;padding-left: 0px;}
    .date-picker-box{height: 44px;border-radius: 0px;box-shadow: none;border-color: #e6e6e6;}
    .filter-bar .fa-calendar{position: absolute;right: 8px;top: 12px;font-size: 15px;color:#e6e6e6;}
    #form_product_option .selectpicker.btn-primary {color: #555!important;}
    #delivery_date_wrapper{position:relative;}
    .padding-right-0{padding-right:0px!important;}
    .padding-left-0{padding-left:0px!important;}
    .selectpicker,#area_id,#delivery_date,#timeslot_id{color:#000!important;}
    .margin-left-0{margin-left:0px!important;}
    .filter-bar .submit-btn{border-radius: 0px;padding: 10px;width: 72%;}
    .filter-bar .form-group label{font-weight:normal;color: black !important;font-size: 13px;}
    .margin-top-0{margin-top:0px!important;}
    .padding-top-12{padding-top: 12px;}
    .form-group input[name=quantity] {float: left;width: 38%;line-height: 38px;height: 100%!important;text-align: center;margin: 0;border-top: 1px solid #e6e6e6;box-shadow: none;border-bottom: 1px solid #e6e6e6;}
    .product_detail_section .panel-body p{text-align:justify;}
    .font-27{font-size:27px!important;}
    .margin-bottom-14{margin-bottom:14px!important;}
    .qty a:hover, .qty a:focus {color: #fff!important;}
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
    .timeslot_id_div .text { font-weight : bold; }

    .timeslot-filter button.dropdown-toggle,
    .area-filter button.dropdown-toggle {
        background: #EEEEEE;
        color: black;
        border-radius: 0px;
        height: 42px;
        border-color: #C2C2C2;
    }

    .filter-bar .fa-calendar {
        color: #C2C2C2;
    }

    #item_delivery_date {
        border-color: #C2C2C2;
        background: #EEEEEE !important;
        color: black !important;
    }
");

$this->registerCssFile('@web/css/lightbox.css', ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerJsFile('@web/js/lightbox.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerJsFile('@web/js/product_detail.js?v=1.32', ['depends' => [\yii\web\JqueryAsset::className()]]);
