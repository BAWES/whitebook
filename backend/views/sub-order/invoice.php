<?php

use yii\web\View;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Order;
use common\models\Vendor;
use common\models\OrderStatus;
use common\models\SuborderItemPurchase;
use common\models\Siteinfo;

$site_info = Siteinfo::find()->one();

?>

<h1><?= $site_info->app_name ?></h1>

<div id="store_details">
    <p>Email: <?= $site_info->email_id ?></p>
    <p>Phone no: <?= $site_info->phone_number ?></p>
    <p>Address: <?= $site_info->site_location ?></p>
</div>

<hr />

<div class="order-view">

    <table class="table table-bordered">
        <tr>
            <td>
                Order ID: #<?= $model->order_id ?> 
            </td>
            <td>
                Payment method: <?= $model->order->order_payment_method ?>
            </td>
        </tr>
        <tr>
            <td>
                Customer: <?= $model->order->customerName ?>
            </td>
            <td>
                Date: <?= date('d/m/Y', strtotime($model->created_datetime)) ?>                
            </td>
        </tr>      
        <tr>
            <td colspan="2">  
                Transaction ID: <?= $model->order->order_transaction_id ?>                
            </td>
        </tr>
    </table>
    
    <?php 

        $vendor = Vendor::findOne($suborder->vendor_id);

    ?>

    <table class="table table-bordered">
        <thead>
            <tr>
                <td colspan="2">
                    <b><?= $vendor->vendor_name; ?></b>
                </td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                   <?= Yii::t('frontend', 'Order status') ?>: 
                    <?php if(Yii::$app->language == 'en') { 
                            echo OrderStatus::findOne($suborder->status_id)->name;
                          } else {
                            echo OrderStatus::findOne($suborder->status_id)->name_ar;
                          } ?>  
                </td>
                <td>
                    <?= Yii::t('frontend', 'Contact Email') ?>: <?= $vendor->vendor_public_email ?> <br />
                    <?= Yii::t('frontend', 'Contact Number') ?>: <?= $vendor->vendor_public_phone ?>
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
        <?php foreach (Order::subOrderItems($suborder->suborder_id) as $item) { ?>
            <tr>
                <td align="left">
                    <?php if(Yii::$app->language == 'en') {
                        echo $item->vendoritem->item_name;
                    } else {
                        echo $item->vendoritem->item_name_ar; 
                    } ?>
                </th>
                <td align="left"><?= date('d/m/Y', strtotime($item->purchase_delivery_date)) ?></th>
                <td aligh="left"><?= $item->purchase_delivery_address ?></th>
                <td aligh="left"><?= $item->purchase_quantity ?></th>
                <td align="right"><?= $item->purchase_price_per_unit ?> KWD</th>
                <td align="right"><?= $item->purchase_total_price ?> KWD</th>   
            </tr>
            <?php } ?>
            <tr>
                <td align="right" colspan="5">Sub Total</td>
                <td align="right"><?= $suborder->suborder_total_without_delivery ?> KWD</td>
            </tr>
            <tr>
                <td align="right" colspan="5">Delivery Charge</td>
                <td align="right"><?= $suborder->suborder_delivery_charge ?> KWD</td>
            </tr>
            <tr>
                <td align="right" colspan="5">Total</td>
                <td align="right"><?= $suborder->suborder_total_with_delivery ?> KWD</td>
            </tr>
        </tbody>
    </table>


</div>