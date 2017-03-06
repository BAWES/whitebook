<?php

use yii\web\View;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Vendor;
use common\models\Booking;
use common\models\Siteinfo;
use common\components\CFormatter;

?>

<h1><?= Siteinfo::info('app_name') ?></h1>

<div id="store_details">
    <p>Email: <?= Yii::$app->params['adminEmail'] ?></p>
    <p>Phone no: <?= Siteinfo::info('phone_numbe') ?></p>
    <p>Address: <?= Siteinfo::info('site_location') ?></p>
</div>

<hr />

<div class="order-view">

    <table class="table table-bordered">
        <tr>
            <td>
                Booking ID: <?= $model->booking_id ?> 
            </td>
            <td>
                Booking Token : <?= $model->booking_token ?>                
            </td>
        </tr>
        <tr>
            <td>
                Customer : <?= $model->customer_name .' '.$model->customer_lastname ?>
            </td>
            <td>
                Customer Email : <?= $model->customer_email ?>
            </td>
        </tr>
        <tr>
            <td>
                Customer Mobile : <?= $model->customer_mobile ?>
            </td>
            <td>
                Date: <?= date('d/m/Y', strtotime($model->created_datetime)) ?>                
            </td>
        </tr>   
    </table>

    <table class="table table-bordered">
        <thead>
            <tr>
                <td colspan="2">
                    <b><?= $model->vendor->vendor_name; ?></b>
                </td>
            </tr>
        </thead>
        <tbody>
            <tr>                    
                <td>
                    Payment method : <?= $model->payment_method ?>
                </td>
                <td>
                    Transaction ID : <?= $model->transaction_id ?>
                </td>
            </tr>
            <tr>
                <td>
                    <?= Yii::t('frontend', 'Booking status') ?>: 
                    <?= $model->statusName ?>
                </td>
                <td>
                    <?= Yii::t('frontend', 'Contact Email') ?>: <?= $model->vendor->vendor_public_email ?>
                </td>
            </tr>
        </tbody>
    </table>

    <table class="table table-bordered">
        <thead>
            <tr>
                <td align="left"><?= Yii::t('frontend', 'Item Name') ?></th>
                <td align="left"><?= Yii::t('frontend', 'Delivery Datetime') ?></th>
                <td align="left"><?= Yii::t('frontend', 'Delivery Address') ?></th>
                <td aligh="left"><?= Yii::t('frontend', 'Quantity') ?></th>
                <td align="right"><?= Yii::t('frontend', 'Unit Price') ?></th>
                <td align="right"><?= Yii::t('frontend', 'Total') ?></th>   
            </tr>
        </thead>    
        <tbody>
        <?php foreach ($model->bookingItems as $item) { ?>
            <tr>
                <td align="left">
                    
                    <?php 

                    echo $item->item_name; 

                    foreach ($item->bookingItemMenus as $key => $menu_item) { 
                        
                        echo '<div class="clearfix"></div> - <i class="cart_menu_item">'.$menu_item['menu_item_name'].' x '.$menu_item['quantity'];

                        $menu_item_total = $menu_item['quantity'] * $menu_item['price'];

                        if($menu_item_total) {
                            echo ' = '.CFormatter::format($menu_item_total);    
                        }
                        
                        echo '</i>';
                    } 

                    if($item['female_service']) {
                        echo '<div class="clearfix"></div> - <i class="cart_menu_item">'.Yii::t('frontend', 'Female service').'</i>';
                    }

                    if($item['special_request']) {
                        echo '<div class="clearfix"></div> - <i class="cart_menu_item">'.$item['special_request'].'</i>';
                    }
                    ?>
                </th>
                <td align="left">
                    <?= date('d/m/Y', strtotime($item->delivery_date)) ?>

                    <br />

                    <?= $item->timeslot ?>
                </th>
                <td aligh="left">
                    <?= $item->delivery_address ?>
                </th>
                <td aligh="left"><?= $item->quantity ?></th>
                <td align="right"><?= $item->price ?> KWD</th>
                <td align="right"><?= $item->total ?> KWD</th>   
            </tr>
            <?php } ?>
            <tr>
                <td align="right" colspan="5">Sub Total</td>
                <td align="right"><?= $model->total_without_delivery ?> KWD</td>
            </tr>
            <tr>
                <td align="right" colspan="5">Delivery Charge</td>
                <td align="right"><?= $model->total_delivery_charge ?> KWD</td>
            </tr>
            <tr>
                <td align="right" colspan="5">Total</td>
                <td align="right"><?= $model->total_with_delivery ?> KWD</td>
            </tr>
        </tbody>
    </table>
</div>
