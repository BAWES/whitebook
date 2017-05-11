<?php

use yii\widgets\ActiveForm;
use yii\helpers\Url;
use common\models\Booking;
use common\components\CFormatter;

/* @var $this yii\web\View */
/* @var $model common\models\OrderRequestStatus */

$this->title = 'Booking Request ID : '. $model->booking_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Booking Request'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-request-status-view">

    <table class="table table-striped table-bordered detail-view">
        <tbody>
            <tr>
                <td>
                    <h4><?=Yii::t('app','Booking Detail');?></h4>
                </td>
                <td>
                    <h4><?=Yii::t('app','Requested Item Detail');?></h4>
                </td>
            </tr>
            <tr>
                <td>
                    <?php $form = ActiveForm::begin(); ?>
                    <table class="table table-striped table-bordered detail-view">
                        <tbody>
                        <tr><th><?=Yii::t('app','Booking Request ID')?></th><td><?=$model->booking_id?></td></tr>
                        <tr><th><?=Yii::t('app','Booking Request Status')?></th><td><?= $model->getStatusName(); ?></td></tr>
                        <tr><th><?=Yii::t('app','Booking Request Note')?></th><td><?= $model->booking_note ?></td></tr>
                        <tr><th><?=Yii::t('app','Request Received On')?></th><td><?=date('M d Y, H:i A',strtotime($model->created_datetime))?></td></tr>
                        </tbody>
                    </table>
                    <?php ActiveForm::end(); ?>
                </td>
                <td>
                    <table class="table table-striped table-bordered detail-view">
                        <tbody>
                            <tr>
                                <th><?=Yii::t('app','Customer Name')?></th><td>
                                    <?=$model->customer_name .' '. $model->customer_lastname; ?>
                                </td>
                            </tr>
                            <tr>
                                <th><?=Yii::t('app','Customer Email')?></th><td>
                                    <?=$model->customer_email?>
                                </td>
                            </tr>
                            <tr>
                                <th><?=Yii::t('app','Customer Mobile')?></th><td>
                                    <?=$model->customer_mobile?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>

        <table class="table table-bordered">
            <tbody>
            <tr>
                <td>
                    <div class="order_status_wrapper" data-id="<?= $model->booking_id ?>">
                        <?= Yii::t('frontend', 'Booking status') ?>:
                        <span>
                        <?= $model->statusName ?>
                        </span>
                    </div>
                </td>
                <td>
                    <?= Yii::t('frontend', 'Contact Email') ?>: <?= $model->vendor->vendor_public_email ?>
                </td>
            </tr>
            <tr>
                <td data-id="<?= $model->booking_id ?>">
                    Payment method :
                    <span class="payment_method_<?= $model->booking_id ?>"><?= $model->payment_method ?></span>
                    <?php if ($model->booking_status == Booking::STATUS_ACCEPTED && $model->transaction_id == '') { ?>
                        <a data-toggle="modal" href="#payment_method_modal" class="btn btn-default edit_payment pull-right">
                            <i class="glyphicon glyphicon-pencil"></i>
                        </a>
                    <?php } ?>
                </td>
                <td>
                    Transaction ID : <?= $model->transaction_id ?>
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
            <?php foreach ($model->bookingItems as $item) {
                $slug = '';
                if (isset($model->bookingItems[0]->item->slug)) {
                    $slug = $model->bookingItems[0]->item->slug;
                }
                ?>
                <tr>
                    <td align="left">

                        <a target="_blank"  href="<?=Yii::$app->urlManagerFrontend->createUrl(['browse/detail/'.$slug]); ?>"><b><?= $item->item_name ?></b></a>
                        <a target="_blank" class="pull-right" href="<?=Yii::$app->urlManagerFrontend->createUrl(['browse/detail/'.$slug]); ?>">View Item</a>
                        <?php

                        foreach ($item->bookingItemMenus as $key => $menu_item) {
                            echo '<div class="clearfix"></div> - <i class="cart_menu_item">'.$menu_item['menu_item_name'].' x '.$menu_item['quantity'];

                            $menu_item_total = $menu_item['quantity'] * $menu_item['price'];

                            if($menu_item_total) {
                                echo ' = '.CFormatter::format($menu_item_total);
                            }

                            echo '</i>';
                        }

                        if($model->bookingItemAnswers)
                        {
                            echo '<div class="clearfix"></div><b>Custom</b><br/>';

                            $q =1;
                            foreach($model->bookingItemAnswers as $answer) {
                                echo "Question $q: <i>".$answer->question.'</i>';
                                echo "<br/>answer $q: <i>".$answer->answer.'</i><br/>';
                                $q++;
                            }
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
                    </td>
                    <td aligh="left"><?= $item->delivery_address ?></td>
                    <td aligh="left"><?= $item->quantity ?></td>
                    <td align="right"><?= $item->price ?> KWD</td>
                    <td align="right"><?= $item->total ?> KWD</td>
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