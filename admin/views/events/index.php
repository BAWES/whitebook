<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\grid\Column;
use yii\grid\DataColumn;
use yii\base;
/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchEvents*/
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'User Events';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="customer-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'showFooter'=>true,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'event_name',
            [
                'attribute'=>'event_type',
                'filter' => \yii\helpers\ArrayHelper::map(\admin\models\EventType::findAll(['trash'=>'Default']),'type_name','type_name'),
            ],
            'slug',
            [
				'attribute'=>'event_date',
				'format' => ['date', 'php:d/m/Y'],
				'label'=>'created date',			
			],
            ['class' => 'yii\grid\ActionColumn'],
        ]
    ]); ?>

</div>

