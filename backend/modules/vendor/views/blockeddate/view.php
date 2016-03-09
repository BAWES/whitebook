<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Blockeddate */

$this->title = $model->block_id;
$this->params['breadcrumbs'][] = ['label' => 'Blockeddates', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="blockeddate-view">

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->block_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->block_id], [
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
            'block_id',
            'vendor_id',
            'block_date',
            'created_by',
            'modified_by',
            'created_datetime',
            'modified_datetime',
            'trash',
        ],
    ]) ?>

</div>
