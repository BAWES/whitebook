<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\EmailtemplateSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Email templates';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row-fluid">
   <div class="span12">
       <div class="grid simple">
		   <div class="tools">    
			<?= Html::a('Create template', ['create'], ['class' => 'btn btn-success']) ?>
			</div>	
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
           'email_title',           
            'email_subject',
            ['class' => 'yii\grid\ActionColumn',
            'header'=>'Action',
		  ],
		],
    ]); ?>   
		</div>
	</div>
</div>
