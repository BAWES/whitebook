<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\OrderRequestStatusSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Request');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-request-status-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'request_id',
            [
                'attribute'=>'request_status',
                'filter'=>['Approved'=>'Approved','Pending'=>'Pending','Declined'=>'Declined']
            ],
            'created_datetime:date',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' =>'{view}'
            ],
        ],
    ]); ?>
</div>