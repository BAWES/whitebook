<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use common\models\VendorPayment;

/* @var $this yii\web\View */
/* @var $searchModel common\models\VendorPaymentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Vendor Payments';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vendor-payment-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Vendor Payment', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'payment_id',
            [
                'attribute' => 'vendorName',
                'value' => function($model) {
                    return $model->vendor->vendor_name;
                }
            ],
            //'booking_id',
            //'description:ntext',
            [
                'attribute' => 'type',
                'filter' => $searchModel::typeList(),
                'value' =>  function($model) {
                    return $model->typeName();
                }
            ],
            'amount',
            [
                'attribute'=>'created_datetime',
                'value'=>function($model) {
                    return date('Y-m-d',strtotime($model->created_datetime));
                }
            ],
            // 'modified_datetime',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{report} {view} {delete}',
                'buttons' => [            
                    'report' => function($url, $data) {

                        if($data->type == VendorPayment::TYPE_ORDER)
                            return; 

                        //only for transfer 

                        return HTML::a(
                            '<i class="glyphicon glyphicon glyphicon-file"></i>', 
                            Url::to(['vendor-payment/detail', 'id' => $data->payment_id]),
                            [
                                'title' => 'Report'
                            ]
                        );
                    },
                ]
            ],
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