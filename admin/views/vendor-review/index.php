<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\VendorReviewSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Vendor Reviews';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vendor-review-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'customerName',
            'vendorName',
            'rating',
            'review:ntext',
            // 'approved',
            // 'created_at',
            // 'updated_at',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{approve} {view} {delete}',
                'buttons' => [            
                    'approve' => function($url, $data) {
                        return HTML::a(
                            '<i class="glyphicon glyphicon-ok"></i>', 
                            Url::to(['vendor-review/approve', 'id' => $data->review_id]),
                            [
                                'title' => 'Approve'
                            ]
                        );
                    },
                ]
            ],
        ],
    ]); ?>
</div>
