<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SubscribeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Email Subscribes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="subscribe-index">
    <p>
		
	<div class="row-fluid">
       <div class="span12">
         <div class="grid simple">
		<div class="tools">
        <?= Html::a('Create Email Subscribe', ['create'], ['class' => 'btn btn-success']) ?>
    <?php if($count>0){?>    
        <?= Html::a('Export Email Subscribe list', ['/subscribe/export'], ['class' => 'btn btn-info','id'=>'export', 'style'=>'float:right;']) ?>			
    <?php }?>    
    </p>
    
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            'email:email',
            ['class' => 'yii\grid\ActionColumn',
            'header'=>'Action',
            'template' => '{update} {delete}{link}',],
        ],
    ]); ?>
	   </div>
	 </div>
   </div>

</div>
