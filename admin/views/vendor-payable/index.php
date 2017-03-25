<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Alert;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\web\View;
use yii\widgets\Pjax;
use admin\models\VendorItem;

$this->title = 'Vendor Payable';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="row-fluid">
   <div class="span12">
       <div class="grid simple">
		   
			
        	<?php Pjax::begin(['enablePushState' => false]); ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'vendor_name',
                    'vendor_contact_email',
                    [
                         'label'=>'items added',
                         'format'=>'raw',
                         'value'=>function ($model) {                      
                            return VendorItem::vendoritemcount($model->vendor_id);
                         },
                        'contentOptions' =>function ($model, $key, $index, $column){
                            return ['class' => 'text-center'];
                        },

                    ],
                    [
                        'attribute'=>'vendor_status',
                        'label'=>'Status',
                        'format'=>'raw',
                        'value'=>function($data,$model) {
        				    return HTML::a('<img src='.$data->statusImageurl($data->vendor_status).' id="image-'.$data->vendor_id.'" alt="Status Image" title='.$data->statusTitle($data->vendor_status).'>','javascript:void(0)',['id'=>'status',
                            'onclick'=>'change("'.$data->vendor_status.'","'.$data->vendor_id.'")']);
        				},
        				'filter' => \admin\models\Vendor::Activestatus(),
                        'contentOptions' =>function ($model, $key, $index, $column){
                            return ['class' => 'text-center'];
                        },
        			],
                    'vendor_payable',
        			[
        				'attribute'=>'created_datetime',
        				'format' => ['date', 'php:d/m/Y'],
        				'label'=>'created date',
                        'contentOptions' =>function ($model, $key, $index, $column){
                            return ['class' => 'text-center'];
                        },

        			],
                ],
            ]); ?>
            <?php Pjax::end(); ?>

		</div>
	</div>
</div>

<?php 

$this->registerJs("
    function change(status, id)
    {       
        var csrfToken = $('meta[name=\"csrf-token\"]').attr('content');       
        var path = '".Url::to(['/vendor/block'])."';

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

", View::POS_HEAD);

	