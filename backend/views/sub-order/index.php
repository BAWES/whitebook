<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\web\View;

$this->title = 'Sub Orders';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sub-order-index">

    <?= $this->render('_search', [
        'model' => $searchModel,
        'status' => $status
    ]) ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            
            'suborder_id',
            'statusName',
            'suborder_delivery_charge',
            'suborder_total_with_delivery',
            //'suborder_commission_percentage',
            'suborder_commission_total',
            'suborder_vendor_total',
            'profit',
            'created_datetime',
            
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {invoice}',
                'buttons' => [
                    'invoice' => function ($url, $model) {     
                        return Html::a('<span class="glyphicon glyphicon-print"></span>', $url, [
                                'title' => Yii::t('yii', 'Invoice'),
                        ]);  
                    }
                ]
            ],
        ],
    ]); ?>
</div>

<?php 

$this->registerJsFile('@web/themes/default/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerCssFile('@web/themes/default/plugins/bootstrap-datepicker/css/datepicker.min.css', ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerJs("
    jQuery('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
    });
", View::POS_READY);
