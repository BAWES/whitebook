<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = 'Update vendor: ' . ' ' . $model->vendor_name;
$this->params['breadcrumbs'][] = ['label' => 'Manage vendor', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="vendor-update">

   <?= $this->render('_form', [
        'model' => $model,
        'main_categories' => $main_categories,
        'vendor_contact_number' => $vendor_contact_number,
        'vendor_order_alert_emails' => $vendor_order_alert_emails,
        'phones' => $phones
	]); ?>
</div>
