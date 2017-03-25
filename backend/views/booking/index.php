<?php

use yii\grid\GridView;
use yii\web\View;

$this->title = Yii::t('app', 'Booking');

$this->params['breadcrumbs'][] = $this->title;

?>
<div class="order-request-status-index">

    <?= $this->render('_search', [
        'model' => $searchModel,
    ]) ?>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'booking_id',
                'label' => 'Booking ID',
            ],
            [
                'label' => 'Item',
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


<?php 

$this->registerJsFile('@web/themes/default/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerCssFile('@web/themes/default/plugins/bootstrap-datepicker/css/datepicker.min.css', ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerJs("
    jQuery('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    });
", View::POS_READY);
