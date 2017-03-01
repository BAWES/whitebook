<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Vendor Payments | <small>Total Paid for current result: '.$total.'</small>';

$this->params['breadcrumbs'][] = 'Vendor Payments';
?>


<div class="vendor-account-payable-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],

            'payment_id',
            'amount',
            'description:ntext',
            'created_datetime',
            // 'modified_datetime',

            // ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
