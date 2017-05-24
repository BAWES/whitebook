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

            <?php

                if(!Yii::$app->user->isGuest)
                {
                    $class = 'col-md-9 border-left';

                    echo $this->render('/users/_sidebar_menu');
                }
                else
                {
                    $class = 'col-md-12';

                    echo '<br /><br />';
                }

            ?>

            <div class="<?= $class; ?>">
            <?php foreach ($bookings as $key => $booking) { ?>
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
                                <?= Yii::t('frontend', 'Order status') ?>: <?=$booking->getStatusName();?>
                            </td>
                        </tr>
                        <tr>
                        
                            <?php if($booking->transaction_id) { ?>
                            <td>
                                <?= Yii::t('frontend', 'Transaction ID') ?>: <?= $booking->transaction_id ?>
                            </td>
                            <?php } ?>

                            <?php if($booking->booking_note) { ?>
                            <td>
                                <?= Yii::t('frontend', 'Booking note') ?>: <?= $booking->booking_note ?>
                            </td>
                            <?php } ?>

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
                            <td align="right" class="hidden-xs hidden-sm"><?= Yii::t('frontend', 'Base Price') ?></th>
                            <td align="right" class="hidden-xs hidden-sm"><?= Yii::t('frontend', 'Total') ?></th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php foreach ($booking->bookingItems as $item) { ?>
                        <tr>
                            <td align="left" style="border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD;">
                                <?php if(Yii::$app->language == 'en') {
                                    echo $item->item_name;
                                } else {
                                    echo $item->item_name_ar;
                                }

                                foreach ($item->bookingItemMenus as $key => $menu_item) {
                                    echo '<div class="clearfix"></div><i class="cart_menu_item"> - '.$menu_item['menu_item_name'].' x '.$menu_item['quantity'];

                                    $menu_item_total = $menu_item['quantity'] * $menu_item['price'];

                                    if($menu_item_total) {
                                        echo ' = '.CFormatter::format($menu_item_total);
                                    }

                                    echo '</i>';
                                }


                                if($booking->bookingItemAnswers)
                                {
                                    echo '<div class="clearfix"></div><b>Custom</b><br/>';

                                    $q =1;
                                    foreach($booking->bookingItemAnswers as $answer) {
                                        echo "Question $q: <i>".$answer->question.'</i>';
                                        echo "<br/>answer $q: <i>".$answer->answer.'</i><br/>';
                                        $q++;
                                    }
                                }


                                if($item['female_service']) {
                                    echo '<div class="clearfix"></div><i class="cart_menu_item"> - '.Yii::t('frontend', 'Female service').'</i>';
                                }

                                if($item['special_request']) {
                                    echo '<div class="clearfix"></div><i class="cart_menu_item"> - '.$item['special_request'].'</i>';
                                }

                                ?>
                            </td>
                            <td>
                                <?= date('d/m/Y', strtotime($item->delivery_date)) ?>

                                <br />

                                <?= $item->timeslot ?>
                            </td>
                            <td aligh="left">
                                <?= $item->delivery_address ?>
                            </td>
                            <td class="hidden-xs hidden-sm" align="left">
                                <?= $item->quantity ?>
                            </td>

                            <td class="hidden-xs hidden-sm" align="right">
                                <?= $item->price ?>
                            </td>

                            <td class="hidden-xs hidden-sm" align="right">
                                <?= ($item->item_base_price != '0.000') ? $item->item_base_price : Yii::t('frontend','Price based <br/>on selection'); ?>
                            </td>
                            <td class="hidden-xs hidden-sm" align="right">
                                <?= $item->total ?> KD</th>
                        </tr>
                    <?php } ?>
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
                        <td align="right" colspan="6"><?=Yii::t('frontend','Sub Total')?></td>
                        <td align="right">
                            <?= CFormatter::format($booking->total_without_delivery) ?>
                        </td>
                    </tr>
                    <tr class="hidden-xs hidden-sm">
                        <td align="right" colspan="6"><?=Yii::t('frontend','Delivery Charge')?></td>
                        <td align="right">
                            <?= CFormatter::format($booking->total_delivery_charge) ?></td>
                    </tr>
                    <tr class="hidden-xs hidden-sm">
                        <td align="right" colspan="6"><?=Yii::t('frontend','Total')?></td>
                        <td align="right">
                            <?= CFormatter::format($booking->total_with_delivery) ?>
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


