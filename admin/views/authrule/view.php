<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Authrule */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Authrules', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="authrule-view">

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->name], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->name], [
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
            'name',
            'data:ntext',
        ],
    ]) ?>

</div>
