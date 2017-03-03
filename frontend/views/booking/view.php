<?php 

use common\models\Order;
use common\models\Vendor;
use common\models\OrderStatus;
use common\components\CFormatter;
use common\components\LangFormat;
use common\models\SuborderItemMenu;
use common\models\OrderRequestStatus;

$this->title = Yii::t('frontend', 'View Booking | Whitebook');

?>

<section id="inner_pages_sections">
    <div class="container">
        <div class="title_main">
			<h1><?= Yii::t('frontend', 'View Booking'); ?></h1>
		</div>
        <div class="account_setings_sections">
        <?=$this->render('/users/_sidebar_menu');?>
            <div class="col-md-9 border-left">

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <td colspan="2"><?= Yii::t('frontend', 'Booking Details') ?></td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <b><?= Yii::t('frontend', 'Booking ID') ?>:</b> #<?= $booking->booking_id?>
                            </td>
                            <td>
                                <b><?= Yii::t('frontend', 'Date Added') ?>:</b> <?= date('d/m/Y', strtotime($booking->created_datetime)) ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <?php
                $item = $booking->bookingItems;
                $vendor = Vendor::findOne($booking->vendor_id);

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
                                <?= Yii::t('frontend', 'Payment Method') ?>: <?= $booking->payment_method ?>
                            </td>
                            <td>
                                <?= Yii::t('frontend', 'Transaction ID') ?>: <?= $booking->transaction_id ?>
                            </td>
                        </tr>    
                        <tr>
                            <td>
                                <?= Yii::t('frontend', 'Order status') ?>: <?=$booking->getStatusName();?>
                                <br />
                            </td>
                            <td>
                                <?= Yii::t('frontend', 'Contact Email') ?>: <?= $vendor->vendor_public_email ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?= Yii::t('frontend', 'Booking note') ?>: <?= $booking->booking_note?>
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
                        <tr>
                            <td align="left">
                                <?php 

                                echo LangFormat::format($item->item_name, $item->item_name_ar);

//                                $menu_items = SuborderItemMenu::findAll(['purchase_id' => $item->purchase_id]);
                                
//                                foreach ($menu_items as $key => $menu_item) {
//                                    if (Yii::$app->language == 'en') {
//                                        echo '<i class="cart_menu_item"> - '.$menu_item['menu_item_name'].' x '.$menu_item['quantity'];
//                                    } else {
//                                        echo '<i class="cart_menu_item"> - '.$menu_item['menu_item_name_ar'].' x '.$menu_item['quantity'];
//                                    }
//
//                                    $menu_item_total = $menu_item['quantity'] * $menu_item['price'];
//
//                                    if($menu_item_total) {
//                                        echo ' = '.CFormatter::format($menu_item_total);
//                                    }
//
//                                    echo '</i>';
//                                }
                                
                                if($item['female_service']) {
                                    echo '<i class="cart_menu_item"> - '.Yii::t('frontend', 'Female service').'</i>';
                                }

                                if($item['special_request']) {
                                    echo '<i class="cart_menu_item"> - '.$item['special_request'].'</i>';
                                }
                                
                                ?>

                                <div class="visible-xs visible-sm">
                                    x <?= $item->quantity ?> = <?= CFormatter::format($item->total) ?>
                                </div>
                            </th>
                            <td align="left">
                                <?= date('d/m/Y', strtotime($item->delivery_date)) ?>

                                <br />
                                <?= $item->timeslot?>
                            </th>
                            <td aligh="left"><?= $item->delivery_address ?></th>
                            <td aligh="left" class="hidden-xs hidden-sm"><?= $item->quantity ?></th>
                            <td align="right" class="hidden-xs hidden-sm"><?= CFormatter::format($item->price) ?></th>
                            <td align="right" class="hidden-xs hidden-sm"><?= CFormatter::format($item->total) ?></th>
                        </tr>

                        <!-- for small device -->
                        <tr class="visible-xs visible-sm">
                            <td align="right" colspan="2"><?=Yii::t('frontend','Sub Total')?></td>
                            <td align="right">
                                <?= CFormatter::format($booking->total_without_delivery) ?>
                            </td>
                        </tr>
                        <tr class="visible-xs visible-sm">
                            <td align="right" colspan="2"><?=Yii::t('frontend','Delivery Charge')?></td>
                            <td align="right">
                                <?= CFormatter::format($booking->total_delivery_charge) ?></td>
                        </tr>
                        <tr class="visible-xs visible-sm">
                            <td align="right" colspan="2"><?=Yii::t('frontend','Total')?></td>
                            <td align="right">
                                <?= CFormatter::format($booking->total_with_delivery) ?>
                            </td>
                        </tr>

                        <!-- for desktop -->
                        <tr class="hidden-xs hidden-sm">
                            <td align="right" colspan="5"><?=Yii::t('frontend','Sub Total')?></td>
                            <td align="right">
                                <?= CFormatter::format($booking->total_without_delivery) ?>
                            </td>
                        </tr>
                        <tr class="hidden-xs hidden-sm">
                            <td align="right" colspan="5"><?=Yii::t('frontend','Delivery Charge')?></td>
                            <td align="right">
                                <?= CFormatter::format($booking->total_delivery_charge) ?></td>
                        </tr>
                        <tr class="hidden-xs hidden-sm">
                            <td align="right" colspan="5"><?=Yii::t('frontend','Total')?></td>
                            <td align="right">
                                <?= CFormatter::format($booking->total_with_delivery) ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
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


