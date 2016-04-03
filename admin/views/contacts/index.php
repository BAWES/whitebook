<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ContactsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Contacts';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row-fluid">
    <div class="span12">
        <div class="grid simple ">
		    <div class="tools">
        

		<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

          //  'id',
            'contact_name',
            'contact_email:email',
            'contact_phone',
            'subject',
			[
				'attribute'=>'created_datetime',
				'format' => ['date', 'php:d/m/Y'],
				'label'=>'created date',			
			],
            ['class' => 'yii\grid\ActionColumn',
            'header'=>'Action',
            'template' => '{view},{delete}',
            ],
        ],
    ]); ?>	     
      </div>
   </div>
</div>
