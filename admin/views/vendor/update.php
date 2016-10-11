<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Vendor */

$this->title = 'Update vendor: ' . ' ' . $model->vendor_name;
$this->params['breadcrumbs'][] = ['label' => 'Manage vendor', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="vendor-update">

   <?= $this->render('_update', [
        'model' => $model,
        'vendor_contact_number' => $vendor_contact_number,
        'day_off' => $day_off,
        'vendor_order_alert_emails' => $vendor_order_alert_emails,
        'packages' => $packages,
        'vendor_packages' => $vendor_packages
	]); ?>
</div>
