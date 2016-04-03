<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ItemtypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Item type';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="itemtype-index">
<p>
        <?= Html::a('Create item type', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'type_name',
             ['class' => 'yii\grid\ActionColumn',
            'header'=>'Action',
            'template' => '{update} {link}',],
        ],
    ]); ?>

</div>
