<?php

use yii\grid\GridView;
use yii\base;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchEvents*/
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'User Events';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="customer-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'showFooter'=>true,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'event_name',
            [
                'attribute'=>'customer_id',
                'value' => function($model){
                    return $model->customer->customer_name.' '.$model->customer->customer_last_name;
                }
            ],
            [
                'attribute'=>'event_type',
                'filter' => \yii\helpers\ArrayHelper::map(\admin\models\EventType::findAll(['trash'=>'Default']),'type_name','type_name'),
            ],
            'slug',
            [
				'attribute'=>'event_date',
				'format' => ['date', 'php:d/m/Y'],
				'label'=>'created date',			
			],
            ['class' => 'yii\grid\ActionColumn'],
        ]
    ]); ?>

</div>

<?php

$this->registerJsFile('@web/themes/default/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerCssFile('@web/themes/default/plugins/bootstrap-datepicker/css/datepicker.min.css', ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerJs("
	jQuery('input[name=\"EventsSearch[event_date]\"]').datepicker({
		format: 'yyyy-mm-dd',
	});
", View::POS_READY);
