<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\VendoritemquestionguideSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Vendor item question guides';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vendoritemquestionguide-index">
<p>
<?= Html::a('Create Vendor item question guide', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'question_id',
            'guide_caption:ntext',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
