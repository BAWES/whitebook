<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PrioritylogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Prioritylogs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="prioritylog-index">
<p>
		
	<div class="row-fluid">
       <div class="span12">
         <div class="grid simple ">
		<div class="tools">
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'vendor.vendor_name',
            'item.item_name',
            'priority_level',
            [
				'attribute'=>'priority_start_date',
				'format' => ['date', 'php:d/m/Y'],
				'label'=>'Start date',			
			],
			[
				'attribute'=>'priority_end_date',
				'label'=>'End date',		
				'value'=>function($data){
				return $data->getEndDate($data->log_id);
				},
			],
			]
    ]); ?>
	   </div>
	 </div>
   </div>

</div>
