<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use common\models\AdminSearch;

/* @var $this yii\web\View */
/* @var $searchModel common\models\AdminSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Admin';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row-fluid">
   <div class="span12">
       <div class="grid simple">
		   <div class="tools"> 
			<?= Html::a('Create admin', ['create'], ['class' => 'btn btn-success']) ?>
			</div>     

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'admin_name',
            'admin_email:email',
			[
				'attribute'=>'created_datetime',
				'format' => ['date', Yii::$app->params['dateFormat']],
				'label'=>'created date',			
			],
            ['class' => 'yii\grid\ActionColumn',
            'header'=>'Action'],
        ],
    ]); ?>
		</div>
	</div>
</div>
