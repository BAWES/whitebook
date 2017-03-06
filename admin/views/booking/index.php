<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\web\View;

$this->title = 'Bookings';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="booking-index">

    <?= $this->render('_search', [
        'model' => $searchModel,
    ]) ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,

        'columns' => [
           // ['class' => 'yii\grid\SerialColumn'],

            'booking_id',
            'customer_name',
            'total_delivery_charge',
            'total_with_delivery',
            'commission_total',
            'payment_method',
            'transaction_id',
            //'gateway_percentage',
            // 'order_datetime',
            // 'order_ip_address',
            'created_datetime',
            // 'modified_datetime',
            // 'trash',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {invoice} {delete}',
                'buttons' => [
                    'invoice' => function ($url, $model) {     
                        return Html::a('<span class="glyphicon glyphicon-print"></span>', $url, [
                                'title' => Yii::t('yii', 'Invoice'),
                        ]);  
                    }
                ]
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
    });
", View::POS_READY);
