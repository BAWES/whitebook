<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\web\View;

$this->title = 'Request';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sub-order-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            
            'request_id',
            'request_status',
            'request_note',
            'created_datetime:date',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update}',
                'buttons' => [
                    'view' => '',
                    'update' => '',
                ]
            ],
        ],
    ]); ?>
</div>