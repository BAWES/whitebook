<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel common\models\FeatureGroupSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $model->theme_name . ' : Item Assigned';
$this->params['breadcrumbs'][] = ['label' => 'Themes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="featuregroup-index">

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->theme_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->theme_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'itemName',
            'vendorName'
        ]
    ]); ?>
</div>