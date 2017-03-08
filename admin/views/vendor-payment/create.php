<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\VendorPayment */

$this->title = 'Create Vendor Payment';
$this->params['breadcrumbs'][] = ['label' => 'Vendor Payments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vendor-payment-create">

    <?= $this->render('_form', [
        'model' => $model,
        'vendors' => $vendors
    ]) ?>

</div>
