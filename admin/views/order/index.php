<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $searchModel common\models\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Orders';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
           // ['class' => 'yii\grid\SerialColumn'],

            'order_id',
            'customerName',
            'order_total_delivery_charge',
            'order_total_with_delivery',
            // 'order_payment_method',
            // 'order_transaction_id',
            // 'order_gateway_percentage',
            // 'order_gateway_total',
            // 'order_datetime',
            // 'order_ip_address',
            // 'created_by',
            // 'modified_by',
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

$this->registerJs("
    $('input[name=\"OrderSearch[order_id]\"]').css('width', '50px');
", View::POS_READY);
