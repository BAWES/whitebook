<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SmtpSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Smtps';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="smtp-index">
<p>
        <?= Html::a('Create Smtp', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'smtp_host',
            'smtp_username',
            'smtp_password',
            'smtp_port', 
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
