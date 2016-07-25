<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\LocationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Area';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row-fluid">
   <div class="span12">
       <div class="grid simple">
	
            <div class="tools">   
    		  <?= Html::a('Create Area', ['create'], ['class' => 'btn btn-success']) ?>
    	    </div>

    		<?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'label'=>'country name',
                        'value'=>'country.country_name',
                    ],
                    [
                        'label'=>'city name',
                        'value'=>'city.city_name',
                    ],            
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header'=>'Action'
                    ]
                ],
            ]); ?>
    	</div>
	</div>
</div>

<?php $this->registerJs("

	function change(status, lid)
	{				
		var csrfToken = $('meta[name=\"csrf-token\"]').attr('content');		
        var path = '".Url::to(['/location/block'])."';
        
        $.ajax({  
            type: 'POST',      
            url: path, 
            data: { status: status, lid: lid, _csrf : csrfToken}, 
            success: function(data) {				         
            }
        });
    }

");