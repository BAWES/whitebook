<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\web\View;

$this->title = 'Packages';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="package-index">
    <p>
        <?= Html::a('Create package', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
				'attribute' => 'package_name',
				'format' => 'raw',
				'value' => function ($data) {                      
						return substr($data->package_name, 0, 25);
					},
    		],				
            'package_max_number_of_listings',
            'package_pricing',
               [
               'attribute'=>'package_status',
             'label'=>'Status',
             'format'=>'raw',
			  'value'=>function($data) {
				return HTML::a('<img src='.$data->statusImageurl($data->package_status).' id="image-'.$data->package_id.'" alt="Status Image" title='.$data->statusTitle($data->package_status).'>','javascript:void(0)',['id'=>'status', 
				'onclick'=>'change("'.$data->package_status.'","'.$data->package_id.'")']);
				},
				'filter' => \admin\models\Package::Activestatus()
			],
			
			     [
                 'label'=>'Package added',
                 'format'=>'raw',
                 'value'=>function ($model) {                      
                            return \admin\models\Package::packagecount($model->package_id);
                        },         
             ],
            ['class' => 'yii\grid\ActionColumn',
            'header'=>'Action',
            'template' => '{update} {delete}{link}',
            ],
        ],
    ]); ?>

</div>

<?php 

$this->registerJs("

    function change(status, id)
    {   
        var csrfToken = $('meta[name=\"csrf-token\"]').attr('content');       
        var path = '".Url::to(['/package/block'])."';

        $.ajax({  
            type: 'POST',      
            url: path, //url to be called
            data: { status: status, id: id, _csrf : csrfToken}, //data to be send
            success: function(data) {
                var status1 = (status == 'Active') ? 'Deactive' : 'Active'; 
                $('#image-'+id).attr('src',data);
                $('#image-'+id).parent('a').attr('onclick', 
                \"change('\" + status1 + \"', '\" + id + \"')\");
            }
        });
     }   
", View::POS_HEAD);
	