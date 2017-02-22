<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model common\models\OrderRequestStatus */

$this->title = 'Request ID : '. $model->request_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Order Request Statuses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-request-status-view">

    <?php $form = ActiveForm::begin(); ?>
    <table class="table table-striped table-bordered detail-view">
        <tbody>
        <tr><th><?=Yii::t('app','Request ID')?></th><td><?=$model->request_id?></td></tr>
        <tr><th><?=Yii::t('app','Request Status')?></th><td><?= $form->field($model, 'request_status')->dropDownList([ 'Pending' => 'Pending', 'Approved' => 'Approved', 'Declined' => 'Declined', ], ['prompt' => ''])->label(false) ?></td></tr>
        <tr>
            <th><?=Yii::t('app','Request Note')?></th>
            <td>
                <?= $form->field($model, 'request_note')->textarea(['rows' => 6])->label(false) ?>
                <note><?=Yii::t('app','In case of Declined request please mention reason for it.')?></note>
            </td></tr>
        <tr><th><?=Yii::t('app','Request Received On')?></th><td><?=date('M d Y, H:i A',strtotime($model->created_datetime))?></td></tr>
        <tr><td align="right" colspan="2"><?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?></td></tr>
        </tbody>
    </table>
    <?php ActiveForm::end(); ?>
    <h4><?=Yii::t('app','Customer Detail');?></h4>
    <table class="table table-striped table-bordered detail-view">
        <tbody>
            <tr>
                <th><?=Yii::t('app','Customer Name')?></th><td><?=$model->orderDetail->customer->customer_name .' '. $model->orderDetail->customer->customer_last_name?></td>
            </tr>
            <tr>
                <th><?=Yii::t('app','Customer Email')?></th><td><?=$model->orderDetail->customer->customer_email?></td>
            </tr>
            <tr>
                <th><?=Yii::t('app','Customer Mobile')?></th><td><?=$model->orderDetail->customer->customer_mobile?></td>
            </tr>
        </tbody>
    </table>

    <h4><?=Yii::t('app','Requested Item Detail');?></h4>
    <table class="table table-striped table-bordered detail-view">
        <tbody>
            <tr>
                <th><?=Yii::t('app','Area')?></th><td><?=$model->orderDetail->subOrder->itemPurchased->location->location?></td>
            </tr>
            <tr>
                <th><?=Yii::t('app','Delivery Date')?></th><td><?=$model->orderDetail->subOrder->itemPurchased->purchase_delivery_date?></td>
            </tr>
            <tr>
                <th><?=Yii::t('app','Time Slot')?></th><td><?=date('H:i A',strtotime($model->orderDetail->subOrder->itemPurchased->timeslot->working_start_time))?> - <?=date('H:i A',strtotime($model->orderDetail->subOrder->itemPurchased->timeslot->working_end_time))?></td>
            </tr>
            <tr>
                <th><?=Yii::t('app','Address')?></th><td><?=$model->orderDetail->subOrder->itemPurchased->purchase_delivery_address?></td>
            </tr>
            <tr>
                <th><?=Yii::t('app','Item Name')?></th><td>
                    <?=$model->orderDetail->subOrder->itemPurchased->item->item_name?>
                    <?=Html::a('View Item',['vendor-item/view','id'=>$model->orderDetail->subOrder->itemPurchased->item->item_id],['target'=>'_blank','class'=>'btn btn-default'])?>
                </td>
            </tr>
        </tbody>
    </table>
</div>