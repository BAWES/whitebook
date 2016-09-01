<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\OrderStatus */

$this->title = 'Create Order Status';
$this->params['breadcrumbs'][] = ['label' => 'Order Statuses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-status-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
