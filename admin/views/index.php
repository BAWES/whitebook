<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Access controls';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="accesscontrol-index">
<p>
	<div class="row-fluid">
       <div class="span12">
         <div class="grid simple ">
		<div class="tools">
        <?= Html::a('Create access control', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
				'attribute'=>'role_id',
				'label'=>'Role',			
				'value'=>'role.role_name'		
			], 
			 [
				'attribute'=>'admin_id',
				'label'=>'User',		
				'value'=>function($data){
				return $data->getAdminName($data->admin_id);
				},
			],
			
			[
				'attribute'=>'created_datetime',
				'format' => ['date', 'php:d/m/Y'],
				'label'=>'created date',			
			],
            
            ['class' => 'yii\grid\ActionColumn',
            'header'=>'Action',
            'template' => '{view}{update} {delete}{link}',],
        ],
    ]); ?>
	   </div>
	 </div>
   </div>

</div>
