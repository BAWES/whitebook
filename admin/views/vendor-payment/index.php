<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\VendorPaymentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Vendor Payments';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vendor-payment-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Vendor Payment', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'payment_id',
            'vendorName',
            //'booking_id',
            'description:ntext',
            [
                'attribute' => 'type',
                'filter' => $searchModel::typeList(),
                'value' =>  function($model) {
                    return $model->typeName();
                }
            ],
            'amount',
            
            'created_datetime',
            // 'modified_datetime',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
