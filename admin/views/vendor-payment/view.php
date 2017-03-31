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
        <?= Html::a('Detail Report', ['detail', 'id' => $model->payment_id], ['class' => 'btn btn-primary']) ?>

        <?= Html::a('Delete', ['delete', 'id' => $model->payment_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="tabbable">
        <ul class="nav nav-tabs">
            <li class="active">
              <a href="#tab_1" data-toggle="tab">Payment Info </a>
            </li>

            <?php if($bookings) { ?>
            <li>
              <a href="#tab_2" data-toggle="tab"> Bookings </a>
            </li>
            <?php } ?>

        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="tab_1">

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

            <div class="tab-pane" id="tab_2">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>Amount</th>
                            <th>Paid by customer on</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bookings as $key => $value) { ?>
                        <tr>
                            <td><?= $value['booking_id'] ?></td>
                            <td><?= $value['amount'] ?></td>
                            <td><?= date('Y-m-d', strtotime($value['created_datetime'])) ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>
