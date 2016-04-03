<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\FeaturegroupitemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Feature group items';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="featuregroupitem-index">
 <p>
        <?= Html::a('Create feature group item', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
                  [
				'attribute'=>'item_id',
				'label'=>'Group Name',			
				'value'=>function($data){
					return $data->getGroupName($data->group_id);
					}				
			],
            [
				'attribute'=>'featured_start_date',
				'format' => ['date', 'php:d/m/Y'],
				'label'=>'Feature start date',			
			],			
            [
				'attribute'=>'featured_end_date',
				'format' => ['date', 'php:d/m/Y'],
				'label'=>'Feature start date',			
			],
            ['class' => 'yii\grid\ActionColumn',
            'header'=>'Action',
            'template' => '{update} {delete}{link}',
            ],
        ],
    ]); ?>

</div>
