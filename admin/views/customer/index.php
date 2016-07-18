<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\grid\Column;
use yii\grid\DataColumn;
use yii\base;
/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchCustomer */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Customers';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="customer-index">
   <?= Html::a('Create customer', ['create'], ['class' => 'btn btn-success']) ?>
    <?php $a=1; if($count>0){?>
    <?= Html::a('Export customer list', ['/customer/export'], ['class' => 'btn btn-info','id'=>'export', 'style'=>'float:right;']) ?>
    <?php }?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'rowOptions' => function ($data) {
                            if ($data->status($data->customer_id)) {
                                return ['class' => 'danger'];
                            } else {return [];
                            }
                         },
        'filterModel' => $searchModel,
         'showFooter'=>true,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'customer_name',
            'customer_email:email',
             'customer_mobile',
             'created_datetime',
            [
             'label'=>'Status',
             'format'=>'raw',
			  'value'=>function($data) {
				return HTML::a('<img src='.$data->statusImageurl($data->customer_status).' id="image-'.$data->customer_id.'" alt="Status Image" 
				title='.$data->statusTitle($data->customer_status).'>','javascript:void(0)',['id'=>'status', 
				'onclick'=>'change("'.$data->customer_status.'","'.$data->customer_id.'")']);
				},
			],
			[
				'attribute'=>'created_datetime',
				'format' => ['date', 'php:d/m/Y'],
				'label'=>'created date',			
			],
            [
                'class' => 'yii\grid\ActionColumn',
                'header'=>'Action',
                'buttons' => [
                    'address' => function ($url, $model) {
                        
                        $url = Url::toRoute(['customer/address', 'id'=> $model->customer_id]);

                        return Html::a('<span class="glyphicon glyphicon-book"></span>', $url, [
                                    'title' => \Yii::t('yii', 'Address'),
                                    'data-pjax' => '0',
                        ]);
                    }
                ],
                'template' => '{view} {update} {delete} {address}'],
            ],
    ]); ?>

</div>

<?php 

$this->registerJs("
    function change(status, id)
    {       
        var csrfToken = $('meta[name=\"csrf-token\"]').attr('content');       
        var path = '".Url::to(['/customer/block'])."';
        $.ajax({  
            type: 'POST',      
            url: path, //url to be called
            data: { status: status, id: id, _csrf : csrfToken}, //data to be send
            success: function(data) {
                var status1 = (status == 'Active') ? 'Deactive' : 'Active'; 
                $('#image-'+id).attr('src',data);
                $('#image-'+id).parent('a').attr('onclick', 
                \"change('\"+status1+\"', '\"+id+\"')\");
            }
        });
    }
");
