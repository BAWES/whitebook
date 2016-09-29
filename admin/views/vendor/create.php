<?php

use yii\helpers\Html;

$this->title = 'Create vendor';
$this->params['breadcrumbs'][] = ['label' => 'Manage vendor', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="vendor-create">
    
    <?= $this->render('_form', [
        'model' => $model,
        'package' => $package,
        'vendor_order_alert_emails' => [],
        'day_off' => []
    ]) ?>

</div>
