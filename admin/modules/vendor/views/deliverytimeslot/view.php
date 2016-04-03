<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Deliverytimeslot */

$this->title = $model->timeslot_id;
$this->params['breadcrumbs'][] = ['label' => 'Deliverytimeslots', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="deliverytimeslot-view">

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->timeslot_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->timeslot_id], [
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
            'vendor_id',
            'timeslot_day',
            'timeslot_start_time',
            'timeslot_end_time',
            'timeslot_maximum_orders:datetime',
        ],
    ]) ?>

</div>
