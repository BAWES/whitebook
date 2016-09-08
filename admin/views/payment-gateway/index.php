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
            'name',
            'name_ar',
            //'code',
            'percentage',
            'fees',
            // 'order_status_id',
            // 'under_testing',
            // 'status',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update}'
            ],
        ],
    ]); ?>

    <p class="error">KNET will charge fixed fees only, Creditcard will charge as per commission(%) set above in Tap Gateway.</p>
</div>
