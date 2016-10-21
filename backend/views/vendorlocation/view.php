<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\VendorLocation */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Vendorlocations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vendorlocation-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
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
            'id',
            'vendor_id',
            'city_id',
            'area_id',
            'delivery_price',
            'created_datetime',
            'modified_datetime',
            'created_by',
            'modified_by',
        ],
    ]) ?>

</div>
