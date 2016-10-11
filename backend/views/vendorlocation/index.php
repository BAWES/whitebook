<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Manage Area';

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vendorlocation-index">

    <p>
        <?= Html::a('Add delivery area', ['create'], ['class' => 'btn btn-success']) ?>

        <?= Html::a('Bulk edit delivery area', ['bulk'], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'cityName',
            'locationName',
            'delivery_price',
            
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}'
            ],
        ],
    ]); ?>
</div>
