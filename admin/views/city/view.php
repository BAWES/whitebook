<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\City */

$this->title = $model->city_id;
$this->params['breadcrumbs'][] = ['label' => 'Governorates', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="city-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
		<?= Html::a('View', ['index', ], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Update', ['update', 'city_id' => $model->city_id, 'country_id' => $model->country_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'city_id' => $model->city_id, 'country_id' => $model->country_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'city_id',
            'country_id',
            'city_name',
            'status',            
        ],
    ]) ?>

</div>
