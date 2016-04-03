<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\SocialinfoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Social info';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="socialinfo-index">
  <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('Create social info', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'store_social_id',
            'store_id',
            'store_facebook_share:ntext',
            'store_twitter_share:ntext',
            'store_google_share:ntext',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
