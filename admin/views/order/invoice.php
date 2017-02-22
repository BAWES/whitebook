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
use common\models\SuborderItemMenu;
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
                Order ID: #<?= $model->order_id ?> 
            </td>
            <td>
                Payment method: <?= $model->order_payment_method ?>
            </td>
        </tr>
        <tr>
            <td>
                Customer: <?= $model->customerName ?>
            </td>
            <td>
                Date: <?= date('d/m/Y', strtotime($model->created_datetime)) ?>                
            </td>
        </tr>      
        <tr>
            <td colspan="2">  
                Transaction ID: <?= $model->order_transaction_id ?>                
            </td>
        </tr>
    </table>
    
    <?php 

        foreach($suborder as $row){

            $vendor = Vendor::findOne($row->vendor_id);

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
                                echo OrderStatus::findOne($row->status_id)->name;
                              } else {
                                echo OrderStatus::findOne($row->status_id)->name_ar;
                              } ?>  
                    </td>
                    <td>
                        <?= Yii::t('frontend', 'Contact Email') ?>: <?= $vendor->vendor_public_email ?>
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
            <?php foreach (Order::subOrderItems($row->suborder_id) as $item) { ?>
                <tr>
                    <td align="left">
                        <?php if(Yii::$app->language == 'en') {
                            echo $item->vendoritem->item_name;
                        } else {
                            echo $item->vendoritem->item_name_ar; 
                        } 

                        $menu_items = SuborderItemMenu::findAll(['purchase_id' => $item->purchase_id]);

                        foreach ($menu_items as $key => $menu_item) { 
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
                        <?= date('d/m/Y', strtotime($item->purchase_delivery_date)) ?>

                        <br />

                        <?php if($item->timeslot) { ?>
                            <?= date('h:m A', strtotime($item->timeslot->working_start_time)) ?> -
                            <?= date('h:m A', strtotime($item->timeslot->working_end_time)); ?>
                        <?php } ?>
                    </th>
                    <td aligh="left">
                        <?= $item->purchase_delivery_address ?>
                    </th>
                    <td aligh="left"><?= $item->purchase_quantity ?></th>
                    <td align="right"><?= $item->purchase_price_per_unit ?> KWD</th>
                    <td align="right"><?= $item->purchase_total_price ?> KWD</th>   
                </tr>
                <?php } ?>
                <tr>
                    <td align="right" colspan="5">Sub Total</td>
                    <td align="right"><?= $row->suborder_total_without_delivery ?> KWD</td>
                </tr>
                <tr>
                    <td align="right" colspan="5">Delivery Charge</td>
                    <td align="right"><?= $row->suborder_delivery_charge ?> KWD</td>
                </tr>
                <tr>
                    <td align="right" colspan="5">Total</td>
                    <td align="right"><?= $row->suborder_total_with_delivery ?> KWD</td>
                </tr>
            </tbody>
        </table>
    <?php } ?>

</div>
