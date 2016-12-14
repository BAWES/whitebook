<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\PackageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Packages';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="package-index">

    <p>
        <?= Html::a('Create Package', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'package_id',
            'package_name',
            // 'package_background_image',
            'package_description:ntext',
            'package_avg_price',
            // 'package_number_of_guests',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
