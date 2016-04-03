<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\AuthitemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Authitems';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="authitem-index">
   <p>
		
	<div class="row-fluid">
       <div class="span12">
         <div class="grid simple ">
		<div class="tools">
        <?= Html::a('Create Authitem', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            'type',
            'description:ntext',
            'rule_name',
            'data:ntext',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
	   </div>
	 </div>
   </div>

</div>
