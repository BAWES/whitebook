<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Log */

$this->title = $model->message;
$this->params['breadcrumbs'][] = 'Logs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="log-view">

    <h1><?= $model->message ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'level',
            'category',
            'log_time',
            'prefix:ntext',
            'message:ntext',
        ],
    ]) ?>

</div>
