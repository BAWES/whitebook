<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\VendoritemquestionansweroptionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Vendor item question answer';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vendoritemquestionansweroption-index">
 <p>
        <?= Html::a('Create Vendor item question answer', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'answer_id',
            'question_id',
            'answer_text',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
