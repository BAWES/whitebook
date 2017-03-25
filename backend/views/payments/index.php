<?php

use yii\helpers\Html;
use yii\grid\GridView;

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
            'description:ntext',
            [
                'attribute'=>'created_datetime',
                'value'=>function($model) {
                    return date('Y-m-d',strtotime($model->created_datetime));
                }
            ]
            // 'modified_datetime',

            // ['class' => 'yii\grid\ActionColumn'],
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