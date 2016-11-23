<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = 'Update vendor: ' . ' ' . $model->vendor_name;
$this->params['breadcrumbs'][] = ['label' => 'Manage vendor', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';

$from_am = explode(':',$model->vendor_working_hours);
$to_am = explode(':',$model->vendor_working_hours_to);

$from_hour = (isset($from_am[0])) ? $from_am[0] : '';
$to_hour = (isset($to_am[0])) ? $to_am[0] : '';

$from_min = (isset($from_am[1])) ? $from_am[1] : '';
$to_min = (isset($to_am[1])) ? $to_am[1] : '';

$from = (isset($from_am[2])) ? $from_am[2] : '';
$to = (isset($to_am[2])) ? $to_am[2] : '';
$model->vendor_working_hours = $from_hour;
$model->vendor_working_min = $from_min;
$model->vendor_working_hours_to = $to_hour;
$model->vendor_working_min_to = $to_min;

?>
<div class="vendor-update">

   <?= $this->render('_form', [
        'model' => $model,
        'vendor_contact_number' => $vendor_contact_number,
        'day_off' => $day_off,
        'vendor_order_alert_emails' => $vendor_order_alert_emails,
        'packages' => $packages,
        'vendor_packages' => $vendor_packages,
        'phones' => $phones
	]); ?>
</div>
