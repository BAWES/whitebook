<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use common\models\Vendor;

$this->title = 'Vendor Draft Items';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="vendoritem-index">
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'id'=>'items',
        'columns' => [
			['class' => 'yii\grid\CheckboxColumn'],
            ['class' => 'yii\grid\SerialColumn'],
			['attribute'=>'vendor_name',
				 'value'=>'vendor.vendor_name',
			],
			[
				'attribute'=>'item_name',
				'value'=>function($data){
					return (strlen($data->item_name)>30) ? substr($data->item_name,0,30) : $data->item_name;
				},
			],  
			[
				'class' => 'yii\grid\ActionColumn',
            	'header'=>'Action',
            	'template' => ' {view} {approve}',
            	'buttons' => [
            		'view' => function($url, $data) {
            			return HTML::a(
            				'<i class="glyphicon glyphicon-eye-open"></i>', 
            				Url::to(['vendor-item/view', 'id' => $data->item_id]),
            				[
            					'title' => 'View',
            					'target' => '_blank'
            				]
            			);
            		},
            		'approve' => function($url, $data) {
            			return HTML::a(
            				'<i class="glyphicon glyphicon-ok"></i>', 
            				Url::to(['vendor-draft-item/approve', 'id' => $data->draft_item_id]),
            				[
            					'title' => 'Approve'
            				]
            			);
            		}
            	]
			],
        ],
    ]); ?>

</div>