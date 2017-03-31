<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use common\models\VendorPayment;

$this->title = 'Vendor Payments | Total Paid for current result: '.$total;

$this->params['breadcrumbs'][] = 'Vendor Payments';

?>

<div class="vendor-account-payable-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],

            'payment_id',
            'amount',
            [
                'attribute' => 'type',
                'filter' => $searchModel::typeList(),
                'value' =>  function($model) {
                    return $model->typeName();
                }
            ],
            'description:ntext',
            [
                'attribute'=>'created_datetime',
                'value'=>function($model) {
                    return date('Y-m-d',strtotime($model->created_datetime));
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{report} {view}',
                'buttons' => [            
                    'report' => function($url, $data) {

                        if($data->type == VendorPayment::TYPE_ORDER)
                            return; 

                        //only for transfer 

                        return HTML::a(
                            '<i class="glyphicon glyphicon glyphicon-file"></i>', 
                            Url::to(['payments/detail', 'id' => $data->payment_id]),
                            [
                                'title' => 'Report'
                            ]
                        );
                    },
                ]
            ]
        ],
    ]); ?>
</div>
<?php
$this->registerCssFile('@web/themes/default/plugins/bootstrap-datepicker/css/datepicker.css');
$this->registerJsFile('@web/themes/default/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJs("
  $(\"[name='VendorPaymentSearch[created_datetime]']\").datepicker({
    autoclose:true,
  	format: 'yyyy-mm-dd',
  });
");