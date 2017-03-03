<?php

use yii\grid\GridView;

$this->title = Yii::t('app', 'Booking Request');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-request-status-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'booking_id',
                'label' => 'Booking Request ID',
            ],
            [
                'label' => 'Requested Item',
                'value' => function ($model) {
                    if (isset($model->bookingItems[0]->item_name)) {
                        return $model->bookingItems[0]->item_name;
                    }
                }
            ],
            [
                'label' => 'Delivery Date',
                'format' => 'html',
                'value' => function ($model) {
                    return date('d/m/Y', strtotime($model->bookingItems[0]->delivery_date))
                    .'<br/>'.$model->bookingItems[0]->timeslot;
                }
            ],
            [
                'attribute'=>'booking_status',
                'value' => function ($model) {
                    return $model->getStatusName();
                }
            ],
            [
                'attribute'=>'total_with_delivery',
                'label' => 'Total',
                'value' => function ($model) {
                    return 'KD '.$model->total_with_delivery;
                }
            ],
            [
                'attribute'=>'created_datetime',
                'label' => 'Sent On',
                'value' => function ($model) {
                    return $model->created_datetime;
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' =>'{view}'
            ],
        ],
    ]); ?>
</div>