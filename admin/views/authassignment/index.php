<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\AuthassignmentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Authassignments';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="authassignment-index">
<p>		
	<div class="row-fluid">
       <div class="span12">
         <div class="grid simple ">
		<div class="tools">
        <?= Html::a('Create AuthAssignment', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'item_name',
            'user_id',
            'created_datetime',
            'modified_datetime',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
	   </div>
	 </div>
   </div>

</div>
