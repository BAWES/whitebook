<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\view;
use common\models\Image;
use common\models\CustomerCart;
use common\components\CFormatter;
use common\components\LangFormat;
use common\models\VendorItem;
use common\models\VendorItemPricing;
use common\models\CustomerCartMenuItem;
use yii\helpers\ArrayHelper;

$this->title = Yii::t('frontend', 'Shopping Cart | Whitebook'); 

$session = $session = Yii::$app->session;
$delivery_location   = ($session->has('delivery-location')) ? $session->get('delivery-location') : null;
$delivery_date  = ($session->has('delivery-date')) ? $session->get('delivery-date') : '';
$event_time  = ($session->has('event_time')) ? $session->get('event_time') : '';

?>
<a data-toggle="dropdown" class="btn-mini-cart">
    <?php echo Yii::t('frontend', 'Cart ({count})', ['count' => count($items)]); ?>
</a> 
<minibag-dropdown class="minibag-dropdown dropdown-menu">
    <div class="minibag-dropdown-content minibag-dropdown-content--double">
        <div class="minibag-overflow-container">
            <?php if($items) { ?>
            <minibag-item-list class="minibag-item-list">
                <ul class="bag-items">
                    <?php 
                    $have_more = false;
                    $sub_total = $delivery_charge = 0;

                    if(sizeof($items) > 2) 
                    {
                        $items = array_slice($items, -2, 2);
                        $have_more = true;
                    }

                    foreach ($items as $item) { 
                        
                        $vendor_name = CustomerCart::getVendorDetail($item['vendor_id'])->vendor_name;
                        
                        $vendors[$item['vendor_id']] = [
                                'vendor' => $vendor_name,
                                'area_id' => $delivery_location
                            ];

                        $row_total = ($item['item']['item_base_price']) ? $item['item']['item_base_price'] : 0;
                        
                        $menu_option_items = CustomerCartMenuItem::find()
                            ->select('{{%vendor_item_menu_item}}.price, {{%vendor_item_menu_item}}.menu_item_id, {{%vendor_item_menu_item}}.menu_id, {{%vendor_item_menu_item}}.menu_item_name, {{%vendor_item_menu_item}}.menu_item_name_ar, {{%customer_cart_menu_item}}.quantity')
                            ->joinVendorItemMenuItem()
                            ->joinVendorItemMenu()
                            ->cartID($item['cart_id'])
                            ->andWhere(['menu_type' => 'options'])
                            ->asArray()
                            ->all();

                        $menu_addon_items = CustomerCartMenuItem::find()
                            ->select('{{%vendor_item_menu_item}}.price, {{%vendor_item_menu_item}}.menu_item_id, {{%vendor_item_menu_item}}.menu_id, {{%vendor_item_menu_item}}.menu_item_name, {{%vendor_item_menu_item}}.menu_item_name_ar, {{%customer_cart_menu_item}}.quantity')
                            ->joinVendorItemMenuItem()
                            ->joinVendorItemMenu()
                            ->cartID($item['cart_id'])
                            ->andWhere(['menu_type' => 'addons'])
                            ->asArray()
                            ->all();

                        $errors = CustomerCart::validate_item([
                            'item_id' => $item['item_id'],
                            'time_slot' => Yii::$app->session->get('event_time'),
                            'delivery_date' => Yii::$app->session->get('delivery-date'),
                            'area_id' => Yii::$app->session->get('delivery-location'),
                            'quantity' => $item['cart_quantity'],
                            'menu_item' => ArrayHelper::map(
                                    array_merge($menu_option_items, $menu_addon_items), 'menu_item_id', 'quantity'
                                )
                        ], true);

                        $delivery_area = CustomerCart::geLocation($delivery_location, $item['vendor_id']);

                        $row_total = VendorItem::itemFinalPrice(
                            $item['item_id'], 
                            $item['cart_quantity'], 
                            array_merge($menu_option_items, $menu_addon_items)
                        );

                        $sub_total += $row_total; // not in use

                    ?>
                    <li class="bag-item-holder" data-remove-text="Item deleted">
                    <div class="bag-item-padding">
                    <div class="bag-item-border">

                        <minibag-item-product class="bag-item bag-item--product">
                            <minibag-item-image class="bag-item-image">
                                <?php 
                                $image_row = Image::find()->select(['image_path'])
                                    ->item($item['item_id'])
                                    ->orderby(['vendorimage_sort_order' => SORT_ASC])
                                    ->asArray()
                                    ->one();

                                if ($image_row) {
                                    $imglink = Yii::getAlias("@s3/vendor_item_images_210/")
                                        . $image_row['image_path'];
                                } else {
                                    $imglink = Url::to("@web/images/item-default.png");    
                                } ?>
                                <a href="<?= Url::to(["browse/detail", 'slug' => $item['slug']]) ?>">
                                    <img class="bag-item-image-img" src="<?= $imglink ?>" />
                                </a>
                            </minibag-item-image>
                            <div class="bag-item-descriptions">
                                <minibag-price class="bag-item-price">
                                    <p class="bag-item-price-holder">
                                        <span class="bag-item-price bag-item-price--current">
                                            <?= CFormatter::format($row_total)  ?>
                                        </span>
                                    </p>
                                </minibag-price>
                                <p class="bag-item-name">
                                    <a><?= LangFormat::format($item['item_name'], $item['item_name_ar']); ?></a>
                                </p>
                                <p class="bag-item-variants">
                                    
                                    <!-- options -->

                                    <?php foreach ($menu_option_items as $key => $menu_item) { ?>
                                    <span class="bag-item-variant bag-item-variant--colour">
                                        <?php if(Yii::$app->language == 'en') {
                                            echo $menu_item['menu_item_name'].' x '.$menu_item['quantity'];
                                        }else{
                                            echo $menu_item['menu_item_name_ar'].' x '.$menu_item['quantity'];
                                        } ?>
                                    </span>
                                    <?php } ?>

                                    <!-- addons -->

                                    <?php foreach ($menu_addon_items as $key => $menu_item) { ?>
                                    <span class="bag-item-variant bag-item-variant--colour">
                                        <?php if(Yii::$app->language == 'en') { 
                                            echo $menu_item['menu_item_name'].' x '.$menu_item['quantity'];
                                        }else{
                                            echo $menu_item['menu_item_name_ar'].' x '.$menu_item['quantity'];
                                        } ?>
                                    </span>
                                    <?php } ?>
                                    
                                    <!-- questions --> 

                                    <?php 

                                    $questionAnswers = \common\models\CustomerCartItemQuestionAnswer::getCartQuestionAnswer($item['cart_id']);
                                    
                                    foreach($questionAnswers as $answer) { ?>
                                        <span class="bag-item-variant bag-item-variant--size">
                                            <?= $answer->answer ?>
                                        </span>
                                    <?php } ?>

                                    <!-- female service --> 

                                    <?php if($item['female_service']) { ?>
                                        <span class="bag-item-variant bag-item-variant--size">
                                            <?= Yii::t('frontend', 'Female service') ?>
                                        </span>
                                    <?php } ?>

                                    <!-- special request -->

                                    <?php if($item['special_request']) { ?>
                                        <span class="bag-item-variant bag-item-variant--size">
                                            <?= $item['special_request'] ?>
                                        </span>
                                    <?php } ?>
                                </p>
                                <p class="bag-item-quantity">
                                    <span data-bind="miniBagLocaleText: 'minibag-item-quantity-prefix'">Qty</span> 
                                    <span class="bag-item-variant bag-item-variant--quantity">
                                        <?= $item['cart_quantity'] ?>
                                    </span>
                                </p>
                            </div>
                        </minibag-item-product>
                        <minibag-remove class="bag-item-remove-holder"><button class="bag-item-remove" data-id="" title="Delete this item"></button>
                        </minibag-remove>
                    </div>
                    </div>
                    </li>
                    <?php } ?>

                    <?php if($have_more) { ?>
                    <li class="bag-item-holder" style="padding: 10px;color: #ccc;">
                        <center><?= Yii::t('frontend', 'View cart to see all item(s)') ?></center>
                    </li>
                    <?php } ?>
                </ul>   
            </minibag-item-list>
            <div class="minibag-meta-container">
                <minibag-sub-total class="minibag-subtotal" params="price: summary.totalPrice.text">
                    <div class="minibag-subtotal-holder">
                        <h3 class="minibag-subtotal-subtotal">
                            <span class="minibag-subtotal-title"><?= Yii::t('frontend', 'Sub-Total') ?></span>
                            <span class="minibag-subtotal-price" data-bind="text: price">
                                <?= CFormatter::format($sub_total) ?>
                            </span>
                        </h3>
                        <?php
                        foreach ($vendors as $key => $vendor) {
                            $charge = \common\models\Booking::getDeliveryCharges('',$key,$vendor['area_id']);

                            $delivery_charge += (int) $charge;
                            
                            if($charge < 1)
                                continue;

                            ?>
                            <h3 class="minibag-subtotal-subtotal">
                                <span class="minibag-subtotal-title">
                                    <?= Yii::t('frontend', 'Delivery Charge') ?>
                                    <small>( <?=$vendor['vendor']?> )</small>
                                </span>
                                <span class="minibag-subtotal-price">
                                    <?= CFormatter::format($charge) ?>
                                </span>
                            </h3>
                        <?php } ?>
                        <h3 class="minibag-subtotal-subtotal">
                            <span class="minibag-subtotal-title"><?= Yii::t('frontend', 'Total') ?></span>
                            <span class="minibag-subtotal-price">
                                <?= CFormatter::format($sub_total + $delivery_charge) ?>
                            </span>
                        </h3>
                    </div>
                </minibag-sub-total>
                <p class="minibag-bag-buttons">
                    <span class="minibag-button-holder minibag-button-holder--view-bag">
                        <a class="minibag-button minibag-button--view-bag" href="<?= Url::to(['cart/index']) ?>">
                            <span class="minibag-button-text-content">
                                <?= Yii::t('frontend', 'VIEW BAG') ?>
                            </span>
                        </a>
                    </span>
                    <span class="minibag-button-holder minibag-button-holder--checkout">
                        <a class="minibag-button minibag-button--checkout" href="<?= Url::to(['checkout/index']) ?>">
                            <span class="minibag-button-text-content">
                                <?= Yii::t('frontend', 'CHECKOUT') ?>                                
                            </span>
                        </a>
                    </span>
                </p>
            </div>
            <?php } else { ?>
                <center class="empty-cart-msg">
                    <i class="flaticon-shopping-cart16"></i>
                    <div class="clearfix"></div>
                    <h2><?= Yii::t('frontend', 'Your cart is empty!') ?></h2>
                </center>
            <?php } ?>
        </div>
    </div>
</minibag-dropdown>