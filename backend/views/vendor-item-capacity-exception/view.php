<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\VendorItemCapacityException */

$this->title = $model->exception_id;
$this->params['breadcrumbs'][] = ['label' => 'vendor item capacity exceptions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vendor-item-capacity-exception-view">

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->exception_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->exception_id], [
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
            [
                'attribute'=>'item_id',
                'value' => $model->vendoritem->item_name
            ],
            'exception_date',
            'exception_capacity',
            'created_datetime',
            'modified_datetime',
        ],
    ]) ?>

</div>
