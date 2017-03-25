<?php
use common\components\CFormatter;

?>
<div class="container">
<div class="col-lg-8">
<tr>
    <td width="20"></td>
    <td style=" font:normal 14px/21px arial; color:#333333;">
        Hi Vendor,
    </td>
    <td width="20"></td>
</tr>
<tr height="5"></tr>
<tr>
    <td width="20"></td>
    <td style=" font:normal 15px arial; color:#333333;">
        New booking request registered.
    </td>
    <td width="20"></td>
</tr>
<tr>
    <td width="20"></td>
    <td style=" font:normal 15px arial; color:#333333;">

        <br />

        <table class="table table-bordered" style="width:100%;">
            <tr>
                <td style="border-right: 1px solid #ddd; border-bottom: 1px solid #ddd; border-top: 1px solid #ddd;">
                    Booking ID: #<?= $booking->booking_id ?>
                </td>
                <td style="border-bottom: 1px solid #ddd; border-top: 1px solid #ddd;">
                    Booking Token : <?= $booking->booking_token ?>
                </td>
            </tr>
            <tr>
                <td style="border-right: 1px solid #ddd; border-bottom: 1px solid #ddd;">
                    Customer: <?= $booking->customer_name ?>
                </td>
                <td style="border-bottom: 1px solid #ddd;">
                    Date: <?= date('d/m/Y', strtotime($booking->created_datetime)) ?>
                </td>
            </tr>
        </table>

        <br />

        <table class="table table-bordered" style="width:100%;">
            <thead>
            <tr>
                <td colspan="2" style="border-top: 1px solid #ddd; border-bottom: 1px solid #DDDDDD;">
                    <b><?= $vendor->vendor_name; ?></b>
                </td>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td style="border-right: 1px solid #ddd;">
                    <?= Yii::t('frontend', 'Booking status') ?>:
                    <?= $booking->status_name ?>
                </td>
                <td>
                    <?= Yii::t('frontend', 'Contact Email') ?>: <?= $vendor->vendor_public_email ?>
                </td>
            </tr>
            </tbody>
        </table>

        <br />

        <table class="table table-bordered" style="width:100%;">
            <thead>
            <tr>
                <td align="left" style="border-top: 1px solid #ddd; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD;">
                    <b><?= Yii::t('frontend', 'Item Name') ?></b></th>
                <td align="left" style="border-top: 1px solid #ddd; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD;">
                    <b><?= Yii::t('frontend', 'Delivery Address') ?></b></th>
                <td align="right" style="border-top: 1px solid #ddd; border-bottom: 1px solid #DDDDDD;">
                    <b><?= Yii::t('frontend', 'Total') ?></b></th>
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
                        <br />
                        x <?= $item->quantity ?>
                    </th>
                    <td aligh="left" style="border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD;">
                        <?= $item->delivery_address ?>
                        <br />

                        <?= date('d/m/Y', strtotime($item->delivery_date)) ?>

                        <br />

                        <?= $item->timeslot ?>

                    </th>
                    <td align="right" style="border-bottom: 1px solid #DDDDDD;">
                        <?= $item->total ?> KWD</th>
                </tr>
            <?php } ?>

            <tr>
                <td align="right" colspan="2" style="border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD;"><b>Sub Total</b></td>
                <td align="right" style="border-bottom: 1px solid #DDDDDD;"><?= $booking->total_without_delivery ?> KWD</td>
            </tr>
            <tr>
                <td align="right" colspan="2" style="border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD;"><b>Delivery Charge</b></td>
                <td align="right" style="border-bottom: 1px solid #DDDDDD;"><?= $booking->total_delivery_charge ?> KWD</td>
            </tr>
            <tr>
                <td align="right" colspan="2" style="border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD;"><b>Total</b></td>
                <td align="right" style="border-bottom: 1px solid #DDDDDD;"><?= $booking->total_with_delivery ?> KWD</td>
            </tr>

            <tr>
                <td align="center" colspan="3" style="border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD;">
                    <a href="<?=Yii::$app->urlManagerVendor->createUrl(['booking/accept']);?>" style="color: #fff;background-color: #449d44;border-color: #398439;padding: 6px 12px;border: 1px solid transparent;">Accept</a> |
                    <a href="<?=Yii::$app->urlManagerVendor->createUrl(['booking/accept']);?>" style="color: #fff;background-color: #ff3853;border-color: #ff3853;padding: 6px 12px;border: 1px solid transparent;">Reject</a>
                    <?=Yii::$app->urlManagerVendor->createUrl('booking/accept');?>
                </td>
            </tr>
            </tbody>
        </table>
    </td>
    <td width="20"></td>
</tr>


</div>
</div>
