<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\PaymentGateway */

$this->title = 'Create Payment Gateway';
$this->params['breadcrumbs'][] = ['label' => 'Payment Gateways', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payment-gateway-create">

    <?= $this->render('_form', [
        'model' => $model,
        'order_status' => $order_status
    ]) ?>

</div>
