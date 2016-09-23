<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Alert;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\Pjax;
use admin\models\Vendoritem;
/* @var $this yii\web\View */
/* @var $searchModel common\models\VendorSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Manage vendor';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="row-fluid">
   <div class="span12">
       <div class="grid simple">
		   <div class="tools"> 	
			<?= Html::a('Create vendor', ['create'], ['class' => 'btn btn-success']) ?>
			</div>
			
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
                            return Vendoritem::vendoritemcount($model->vendor_id);
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
				'filter' => \admin\models\Vendor::Activestatus()
			],
			[
				'attribute'=>'created_datetime',
				'format' => ['date', 'php:d/m/Y'],
				'label'=>'created date',			
			],
            [
                'class' => 'yii\grid\ActionColumn',
                'header'=>'Action',
                'template' => '{update} {password} {delete} {view} {link}',
                'buttons' => [            
                    'link' => function ($url, $model) {

                        $url = Url::to(['vendoritem/index', 'VendoritemSearch[vendor_name]' => $model->vendor_name]); 

                        return  Html::a('<span class="fa fa-bars"></span>', $url, [
                                'title' => Yii::t('app', 'View items'),'data-pjax'=>"0",
                        ]);
                    },
                    'password' => function ($url, $model) {

                        $url = Url::to(['vendor/password', 'id' => $model->vendor_id]); 

                        return  Html::a('<span class="fa fa-key fa-rotate-90"></span>', $url, [
                            'title' => Yii::t('app', 'Change Password'), 'data-pjax'=>"0",
                        ]);
                    }            
                ],
            ],
        ],
    ]); ?>
     <?php Pjax::end(); ?>

		</div>
	</div>
</div>

<script type="text/javascript">
	function change(status, id)
	{		
		var csrfToken = $('meta[name="csrf-token"]').attr("content");		
        var path = "<?php echo Url::to(['/vendor/block']); ?> ";
        $.ajax({  
        type: 'POST',      
        url: path, //url to be called
        data: { status: status, id: id, _csrf : csrfToken}, //data to be send
        success: function(data) {
			var status1 = (status == 'Active') ? 'Deactive' : 'Active'; 
			$('#image-'+id).attr('src',data);
			$('#image-'+id).parent('a').attr('onclick', 
			"change('"+status1+"', '"+id+"')");
         }
        });
     }
</script>
