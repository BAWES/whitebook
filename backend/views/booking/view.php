<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model common\models\OrderRequestStatus */

$this->title = 'Booking Request ID : '. $model->booking_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Booking Request'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-request-status-view">

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
    <h4><?=Yii::t('app','Customer Detail');?></h4>
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

    <h4><?=Yii::t('app','Requested Item Detail');?></h4>
    <table class="table table-striped table-bordered detail-view">
        <tbody>
            <tr>
                <th><?=Yii::t('app','Area')?></th><td>
                    <?php
                    if (isset($model->bookingItems[0]->location->location)) {
                        echo $model->bookingItems[0]->location->location;
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <th><?=Yii::t('app','Delivery Date')?></th><td>
                    <?php
                    if (isset($model->bookingItems[0]->delivery_date)) {
                        echo $model->bookingItems[0]->delivery_date;
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <th><?=Yii::t('app','Delivery Time')?></th><td>
                    <?php
                    if (isset($model->bookingItems[0]->timeslot)) {
                        echo $model->bookingItems[0]->timeslot;
                    }?>
                </td>
            </tr>
            <tr>
                <th><?=Yii::t('app','Address')?></th><td>
                    <?php

                    if (isset($model->bookingItems[0]->delivery_address)) {
                        echo $model->bookingItems[0]->delivery_address;
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <th><?=Yii::t('app','Item Name')?></th><td>
                    <?php
                    if (isset($model->bookingItems[0]->item_name)) {
                        echo $model->bookingItems[0]->item_name;
                    }
                    $slug = '';
                    if (isset($model->bookingItems[0]->item->slug)) {
                        $slug = $model->bookingItems[0]->item->slug;
                    }
                    ?>
                    <a target="_blank" class="btn btn-default" href="<?=Yii::$app->urlManagerFrontend->createUrl(['browse/detail/'.$slug]); ?>">View Item</a>
                </td>
            </tr>
        </tbody>
    </table>
</div>