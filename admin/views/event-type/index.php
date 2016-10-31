<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ItemTypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Event type';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="eventtype-index">
 <p>
        <?= Html::a('Create event type', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'type_name',

            ['class' => 'yii\grid\ActionColumn',
                'header'=>'Action',
                'template' => '{delete}{update}'
            ],
        ],
    ]); ?>

</div>
