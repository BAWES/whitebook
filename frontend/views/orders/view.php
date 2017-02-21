<?php 

use common\models\Order;
use common\models\Vendor;
use common\models\OrderStatus;
use common\components\CFormatter;
use common\components\LangFormat;
use common\models\SuborderItemMenu;

$this->title = Yii::t('frontend', 'View Order | Whitebook'); 

?>

<section id="inner_pages_sections">
    <div class="container">
        <div class="title_main">
			<h1><?= Yii::t('frontend', 'View Order'); ?></h1>
		</div>
        <div class="account_setings_sections">
        <?=$this->render('/users/_sidebar_menu');?>
            <div class="col-md-9 border-left">

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <td colspan="2"><?= Yii::t('frontend', 'Order Details') ?></td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <b><?= Yii::t('frontend', 'Order ID') ?>:</b> #<?= $order->order_id ?> <br />
                                <b><?= Yii::t('frontend', 'Date Added') ?>:</b> <?= date('d/m/Y', strtotime($order->created_datetime)) ?>
                            </td>
                            <td>
                                <b><?= Yii::t('frontend', 'Payment Method') ?>:</b> <?= $order->order_payment_method ?><br />
                                <b><?= Yii::t('frontend', 'Transaction ID') ?>:</b> <?= $order->order_transaction_id ?>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <?php

                foreach($suborder as $row){

                    $vendor = Vendor::findOne($row->vendor_id);

                ?>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <td colspan="2">
                            <?=LangFormat::format($vendor->vendor_name,$vendor->vendor_name_ar);?>
                            </td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <?= Yii::t('frontend', 'Order status') ?>:
                                <?=LangFormat::format(OrderStatus::findOne($row->status_id)->name,OrderStatus::findOne($row->status_id)->name_ar);?>
                                <br />
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
                            <td aligh="left" class="hidden-xs hidden-sm"><?= Yii::t('frontend', 'Quantity') ?></th>
                            <td align="right" class="hidden-xs hidden-sm"><?= Yii::t('frontend', 'Unit Price') ?></th>
                            <td align="right" class="hidden-xs hidden-sm"><?= Yii::t('frontend', 'Total') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach (Order::subOrderItems($row->suborder_id) as $item) { ?>
                        <tr>
                            <td align="left">
                                <?php 

                                echo LangFormat::format($item->vendoritem->item_name, $item->vendoritem->item_name_ar);

                                $menu_items = SuborderItemMenu::findAll(['purchase_id' => $item->purchase_id]);
                                
                                foreach ($menu_items as $key => $menu_item) { 
                                    if (Yii::$app->language == 'en') { 
                                        echo '<i class="cart_menu_item"> - '.$menu_item['menu_item_name'].' x '.$menu_item['quantity'];
                                    } else {
                                        echo '<i class="cart_menu_item"> - '.$menu_item['menu_item_name_ar'].' x '.$menu_item['quantity'];
                                    }                                    

                                    $menu_item_total = $menu_item['quantity'] * $menu_item['price'];

                                    if($menu_item_total) {
                                        echo ' = '.CFormatter::format($menu_item_total);    
                                    }
                                    
                                    echo '</i>';
                                } 
                                
                                if($item['female_service']) {
                                    echo '<i class="cart_menu_item"> - '.Yii::t('frontend', 'Female service').'</i>';
                                }

                                if($item['special_request']) {
                                    echo '<i class="cart_menu_item"> - '.$item['special_request'].'</i>';
                                }
                                
                                ?>

                                <div class="visible-xs visible-sm">
                                    x <?= $item->purchase_quantity ?> = <?= CFormatter::format($item->purchase_total_price) ?>
                                </div>
                            </th>
                            <td align="left">
                                <?= date('d/m/Y', strtotime($item->purchase_delivery_date)) ?>

                                <br />

                                <?= date('h:m A', strtotime($item->timeslot->working_start_time)) ?> -
                                <?=	date('h:m A', strtotime($item->timeslot->working_end_time)); ?>
                            </th>
                            <td aligh="left"><?= $item->purchase_delivery_address ?></th>
                            <td aligh="left" class="hidden-xs hidden-sm"><?= $item->purchase_quantity ?></th>
                            <td align="right" class="hidden-xs hidden-sm"><?= CFormatter::format($item->purchase_price_per_unit) ?></th>
                            <td align="right" class="hidden-xs hidden-sm"><?= CFormatter::format($item->purchase_total_price) ?></th>
                        </tr>
                        <?php } ?>

                        <!-- for small device -->
                        <tr class="visible-xs visible-sm">
                            <td align="right" colspan="2"><?=Yii::t('frontend','Sub Total')?></td>
                            <td align="right">
                                <?= CFormatter::format($row->suborder_total_without_delivery) ?>
                            </td>
                        </tr>
                        <tr class="visible-xs visible-sm">
                            <td align="right" colspan="2"><?=Yii::t('frontend','Delivery Charge')?></td>
                            <td align="right">
                                <?= CFormatter::format($row->suborder_delivery_charge) ?></td>
                        </tr>
                        <tr class="visible-xs visible-sm">
                            <td align="right" colspan="2"><?=Yii::t('frontend','Total')?></td>
                            <td align="right">
                                <?= CFormatter::format($row->suborder_total_with_delivery) ?>
                            </td>
                        </tr>

                        <!-- for desktop -->
                        <tr class="hidden-xs hidden-sm">
                            <td align="right" colspan="5"><?=Yii::t('frontend','Sub Total')?></td>
                            <td align="right">
                                <?= CFormatter::format($row->suborder_total_without_delivery) ?>
                            </td>
                        </tr>
                        <tr class="hidden-xs hidden-sm">
                            <td align="right" colspan="5"><?=Yii::t('frontend','Delivery Charge')?></td>
                            <td align="right">
                                <?= CFormatter::format($row->suborder_delivery_charge) ?></td>
                        </tr>
                        <tr class="hidden-xs hidden-sm">
                            <td align="right" colspan="5"><?=Yii::t('frontend','Total')?></td>
                            <td align="right">
                                <?= CFormatter::format($row->suborder_total_with_delivery) ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <?php } ?>
            </div>
        </div>
    </div>
</section>
<?php $this->registerCss("
table{    font-size: 12px;}
.header-updated{padding-bottom:0; margin-bottom: 0;}
.body-updated{background: white; margin-top: 0;}
#inner_pages_sections .container{background:#fff; margin-top:12px;}
.border-left{border-left: 1px solid #e2e2e2;}
");


