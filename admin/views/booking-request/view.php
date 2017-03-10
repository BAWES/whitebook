<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model common\models\OrderRequestStatus */

$this->title = 'Request ID : '. $model->booking_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Order Request Statuses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="order-request-status-view">

    <?php $form = ActiveForm::begin(); ?>
    <table class="table table-striped table-bordered detail-view">
        <tbody>
        <tr><th><?=Yii::t('app','Booking ID')?></th><td><?=$model->booking_id?></td></tr>
        <tr><th><?=Yii::t('app','Booking Status')?></th><td><?= $model->getStatusName(); ?></td></tr>
        <tr>
            <th><?=Yii::t('app','Booking Note')?></th>
            <td><?= $model->booking_note ?></td></tr>
        <tr><th><?=Yii::t('app','Booking Received On')?></th><td><?=date('M d Y, H:i A',strtotime($model->created_datetime))?></td></tr>
        </tbody>
    </table>
    <?php ActiveForm::end(); ?>
    <h4><?=Yii::t('app','Customer Detail');?></h4>
    <table class="table table-striped table-bordered detail-view">
        <tbody>
            <tr>
                <th><?=Yii::t('app','Customer Name')?></th><td><?=$model->customer_name .' '. $model->customer_lastname?></td>
            </tr>
            <tr>
                <th><?=Yii::t('app','Customer Email')?></th><td><?=$model->customer_email?></td>
            </tr>
            <tr>
                <th><?=Yii::t('app','Customer Mobile')?></th><td><?=$model->customer_mobile?></td>
            </tr>
        </tbody>
    </table>

    <h4><?=Yii::t('app','Requested Item Detail');?></h4>
    <table class="table table-striped table-bordered detail-view">
        <tbody>
            <tr>
                <th><?=Yii::t('app','Area')?></th><td><?=$model->bookingItems[0]->location->location?></td>
            </tr>
            <tr>
                <th><?=Yii::t('app','Delivery Date')?></th><td><?=$model->bookingItems[0]->delivery_date?></td>
            </tr>
            <tr>
                <th><?=Yii::t('app','Time Slot')?></th><td><?=$model->bookingItems[0]->timeslot?></td>
            </tr>
            <tr>
                <th><?=Yii::t('app','Address')?></th><td><?=$model->bookingItems[0]->delivery_address?></td>
            </tr>
            <tr>
                <th><?=Yii::t('app','Item Name')?></th><td>
                    <?=$model->bookingItems[0]->item->item_name?>
                    <a target="_blank" class="btn btn-default" href="<?=Yii::$app->urlManagerFrontend->createUrl(['browse/detail/'.$model->bookingItems[0]->item->slug]); ?>">View Item</a>
                </td>
            </tr>
        </tbody>
    </table>
</div>