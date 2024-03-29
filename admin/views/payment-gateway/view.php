<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\PaymentGateway */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Payment Gateways', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payment-gateway-view">

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->gateway_id], ['class' => 'btn btn-primary']) ?>        
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'name_ar',
            'code',
            'percentage',
            'fees',
            [
             'attribute' => 'order_status',
             'value' => 'Pending'
            ],
            [
             'attribute' => 'under_testing',
             'value' => $model->under_testing?'Yes':'No'
            ],
            [
             'attribute' => 'status',
             'value' => $model->status?'Yes':'No'
            ]
        ],
    ]) ?>

</div>
