<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Activitylog */

$this->title = $model->log_id;
$this->params['breadcrumbs'][] = ['label' => 'Activitylogs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="activitylog-view">

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->log_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->log_id], [
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
            'log_id',
            'log_user_id',
            'log_user_type',
            'log_action:ntext',
            'log_datetime',
        ],
    ]) ?>

</div>
