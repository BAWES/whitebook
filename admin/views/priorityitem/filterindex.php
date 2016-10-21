<?php
use yii\bootstrap\ButtonGroup;
use yii\bootstrap\Button;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\grid\Column;
use yii\widgets\Pjax;
use yii\base;
use yii\base\Object;
use yii\helpers\ArrayHelper;
use common\models\PriorityItem;
use yii\grid\CheckboxColumn;
?>
<?php Pjax::begin(['enablePushState' => false]); ?>



    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'id'=>'priority',
        'columns' => [

			[ 'class' => 'yii\grid\CheckboxColumn'],
            ['class' => 'yii\grid\SerialColumn'],
            ['attribute'=>'item_name',
         'value'=>'vendoritem.item_name',
            ],
            'priority_level',
            [
				'attribute'=>'priority_start_date',
				'format' => ['date', 'php:d/m/Y'],
				'label'=>'Priority start date',			
			],			
            [
				'attribute'=>'priority_end_date',
				'format' => ['date', 'php:d/m/Y'],
				'label'=>'Priority end date',			
			],
			
			[
				'attribute'=>'created_datetime',
				'format' => ['date', 'php:d/m/Y'],
				'label'=>'created date',			
			],
			[
			  'header'=>'status',			
			  'format' => 'raw',
			  'value'=>function($data) {
				return HTML::a('<img src='.$data->statusImageurl($data->status).' id="image-'.$data->priority_id.'" title='.$data->statusTitle($data->status).'>','javascript:void(0)',['id'=>'status', 
				'onclick'=>'change("'.$data->status.'","'.$data->priority_id.'")']);
				},
			
			 ],
			
			
           ['class' => 'yii\grid\ActionColumn',
            'header'=>'Action',
            'template' => ' {update} {delete}',
			],  
		], 
    ]); ?>
    
    <?php Pjax::end(); ?>