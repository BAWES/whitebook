<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\SearchCustomerAddress */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Customer Addresses';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="customer-address-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('Create Customer Address', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'address_id',
            'customer_id',
            'address_type_id',
            'country_id',
            'city_id',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
