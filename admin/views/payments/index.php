<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\VendorAccountPayableSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Vendor Account Payables | <small>Total Payable for current result: '.$total.'</small>';

$this->params['breadcrumbs'][] = 'Vendor Account Payables';
?>


<div class="vendor-account-payable-index">

    <p>
        <?= Html::a('Add payment', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],

            'payable_id',
            'vendorName',
            'amount',
            'description:ntext',
            'created_datetime',
            // 'modified_datetime',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
