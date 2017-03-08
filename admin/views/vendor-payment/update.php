<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\VendorPayment */

$this->title = 'Update Vendor Payment: ' . $model->payment_id;
$this->params['breadcrumbs'][] = ['label' => 'Vendor Payments', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->payment_id, 'url' => ['view', 'id' => $model->payment_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="vendor-payment-update">

    <?= $this->render('_form', [
        'model' => $model,
        'vendors' => $vendors
    ]) ?>

</div>
