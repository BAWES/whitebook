<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SubOrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Sub Orders';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sub-order-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            
            'suborder_id',
            'statusName',
            'suborder_delivery_charge',
            'suborder_total_with_delivery',
            'suborder_commission_percentage',
            'suborder_commission_total',
            'suborder_vendor_total',
            'created_datetime',
            
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {invoice}',
                'buttons' => [
                    'invoice' => function ($url, $model) {     
                        return Html::a('<span class="glyphicon glyphicon-print"></span>', $url, [
                                'title' => Yii::t('yii', 'Invoice'),
                        ]);  
                    }
                ]
            ],
        ],
    ]); ?>
</div>
