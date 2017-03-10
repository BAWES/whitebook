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
    <?= $form->errorSummary($model); ?>
    <table class="table table-striped table-bordered detail-view">
        <tbody>
        <tr><th><?=Yii::t('app','Booking Request ID')?></th><td><?=$model->booking_id?></td></tr>
        <tr><th><?=Yii::t('app','Booking Request Status')?></th><td><?= $form->field($model, 'booking_status')->dropDownList([ '0' => 'Please Select Status', '1' => 'Accept', '2' => 'Reject'])->label(false) ?></td></tr>
        <tr>
            <th><?=Yii::t('app','Booking Request Note')?></th>
            <td>
                <?= $form->field($model, 'booking_note')->textarea(['rows' => 6])->label(false) ?>
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
                <th><?=Yii::t('app','Time Slot')?></th><td>
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

<?php

$this->registerJs('

$("#w0").off("submit").on("submit",function(){
    $("div.field-booking-booking_note").removeClass("has-error");
    $("div.field-booking-booking_note .help-block").removeClass("error");
        
    if ($("#booking-booking_status").val() == 2) {
        $("div.field-booking-booking_note").addClass("has-error");
        $("div.field-booking-booking_note .help-block").addClass("error");
        $("div.field-booking-booking_note .help-block").html("Please mention reason for rejection");
        return false;
    }
})

',\yii\web\View::POS_READY)
?>