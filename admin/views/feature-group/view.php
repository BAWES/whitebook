<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Featuregroup */

$this->title = $model->group_name;
$this->params['breadcrumbs'][] = ['label' => 'Feature Groups', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="featuregroup-view">

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->group_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->group_id], [
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
            'group_id',
            'group_name',
            'group_name_ar',
            'created_by',
            'modified_by',
            'created_datetime',
            'modified_datetime',
            'trash',
        ],
    ]) ?>

</div>
