<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\PaymentGatewaySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Payment Gateways';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payment-gateway-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'gateway_id',
            'name',
            'name_ar',
            //'code',
            'percentage',
            // 'order_status_id',
            // 'under_testing',
            // 'status',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update}'
            ],
        ],
    ]); ?>
</div>
