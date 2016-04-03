<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Featuregroupitem */

$this->title = 'Feature group item';
$this->params['breadcrumbs'][] = ['label' => 'Featuregroupitems', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="featuregroupitem-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->featured_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->featured_id], [
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
            'featured_id',
            'group_id',
            'item_id',
            'featured_start_date',
            'featured_end_date',
            'featured_sort',
            'group_item_status',
            'created_by',
            'modified_by',
            'created_datetime',
            'modified_datetime',
            'trash',
        ],
    ]) ?>

</div>
