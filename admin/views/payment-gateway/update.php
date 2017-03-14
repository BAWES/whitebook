<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\PaymentGateway */

$this->title = 'Update Payment Gateway: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Payment Gateways', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->gateway_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="payment-gateway-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
