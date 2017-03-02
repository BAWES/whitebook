<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\VendorAccountPayable */

$this->title = 'Create Vendor Account Payable';
$this->params['breadcrumbs'][] = ['label' => 'Vendor Account Payables', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vendor-account-payable-create">

    <?= $this->render('_form', [
        'model' => $model,
        'vendors' => $vendors
    ]) ?>

</div>
