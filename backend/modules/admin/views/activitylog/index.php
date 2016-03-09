<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\ActivitylogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Activity logs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="activitylog-index">
<p>		
	<div class="row-fluid">
       <div class="span12">
         <div class="grid simple ">
		<div class="tools">       
	<?php Pjax::begin(['enablePushState' => false]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
         			['attribute'=>'log_user_type',			 
			          'filter' => \backend\models\Activitylog::usertype(),
            ],
            'log_username',            
            'log_action:ntext',
            'log_ip',
            ['attribute'=>'log_datetime',			 
			               'filter' => '',
            ],

            ['class' => 'yii\grid\ActionColumn',
            'template' => '{delete}',
			],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
	   </div>
	 </div>
   </div>

</div>
