<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\OrderStatus */

$this->title = 'Update Order Status: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Order Statuses', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->order_status_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="order-status-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
