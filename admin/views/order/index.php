<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\web\View;

$this->title = 'Orders';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="order-index">

    <?= $this->render('_search', [
        'model' => $searchModel,
    ]) ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,

        'columns' => [
           // ['class' => 'yii\grid\SerialColumn'],

            'order_id',
            'customerName',
            'order_total_delivery_charge',
            'order_total_with_delivery',
            'order_gateway_total',
            'commission',

            // 'order_payment_method',
            // 'order_transaction_id',
            // 'order_gateway_percentage',
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
