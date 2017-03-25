<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\VendorPayment */

$this->title = $model->payment_id;
$this->params['breadcrumbs'][] = ['label' => 'Vendor Payments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vendor-payment-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->payment_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->payment_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'payment_id',
            'vendor.vendor_name',
            'booking_id',
            'type',
            'amount',
            'description:ntext',
            'created_datetime',
            'modified_datetime',
        ],
    ]) ?>

</div>
