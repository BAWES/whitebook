<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SiteinfoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Siteinfos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="siteinfo-index">

 <p><?php 
	$session = Yii::$app->session;
	$lang = $session->get('language');
    ?></p>
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Siteinfo', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'app_name',
            'app_desc:ntext',
            'meta_keyword',
            'meta_desc:ntext',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
