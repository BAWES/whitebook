<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\OrderStatus */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Order Statuses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-status-view">

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->order_status_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->order_status_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'order_status_id',
            'name',
            'name_ar',
        ],
    ]) ?>

</div>
