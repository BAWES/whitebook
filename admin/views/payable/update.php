<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\VendorAccountPayable */

$this->title = 'Update Vendor Account Payable: ' . $model->payable_id;
$this->params['breadcrumbs'][] = ['label' => 'Vendor Account Payables', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->payable_id, 'url' => ['view', 'id' => $model->payable_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="vendor-account-payable-update">

    <?= $this->render('_form', [
        'model' => $model,
        'vendors' => $vendors
    ]) ?>

</div>
