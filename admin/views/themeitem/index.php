<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel common\models\FeaturegroupitemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Theme group items';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="featuregroupitem-index">
	<?php Pjax::begin(['enablePushState' => false]); ?> 

    <p>
        <?= Html::a('Create Theme group item', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
			 [
				'attribute'=>'theme_id',
				'label'=>'Theme',		
				'value'=>function($data){
				return $data->getThemeName($data->theme_id);
				},
			],
            [
				'attribute'=>'theme_start_date',
				'format' => ['date', 'php:d/m/Y'],
				'label'=>'theme start date',			
			],			
            [
				'attribute'=>'theme_end_date',
				'format' => ['date', 'php:d/m/Y'],
				'label'=>'theme start date',			
			],
			[
				'attribute'=>'created_datetime',
				'format' => ['date', 'php:d/m/Y'],
				'label'=>'created date',			
			],
            ['class' => 'yii\grid\ActionColumn',
            'header'=>'Action',
            'template' => '{update} {delete}{link}',
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>

</div>
