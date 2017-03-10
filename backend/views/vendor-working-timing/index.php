<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\VendorWorkingTimingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Vendor Working Timings');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vendor-working-timing-index">

    <p>
        <?= Html::a(Yii::t('app', 'Create Vendor Working Timing'), ['create'], ['class' => 'btn btn-success pull-right']) ?>
    </p>
    <?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute'=>'working_day',
                'filter'=>['Sunday'=>'Sunday','Monday'=>'Monday','Tuesday'=>'Tuesday','Wednesday'=>'Wednesday','Thursday'=>'Thursday','Friday'=>'Friday','Saturday'=>'Saturday']
            ],
            [
                'attribute'=>'working_start_time',
                'filter'=> false,
                'value'=>function($model) {
                    return date('h:i A',strtotime($model->working_start_time));
                }
            ],
            [
                'attribute'=>'working_end_time',
                'filter'=> false,
                'value'=>function($model) {
                    return date('h:i A',strtotime($model->working_end_time));
                }
            ],
            'trash',
            [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{delete}'
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?></div>