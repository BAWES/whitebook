<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Home ads';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="row-fluid">
    <div class="span12">
        <div class="grid simple">
		    <div class="tools"> 		   
		    <?php 

			foreach($model as $key=>$val)
			{
				$first_id = $val['advert_id'];
			}		      
	  		
	  		if(count($model) != 1) {
				echo Html::a('Create home ads', ['create'], ['class' => 'btn btn-success']); 
			}

			?>
		</div>       
		
		<?= GridView::widget([
        		'dataProvider' => $dataProvider,
        		'columns' => [
            	['class' => 'yii\grid\SerialColumn'],
	    		[
					'attribute'=>'ads_type',
					'value'=>function($data){
						return 'Home Ads';
					},
				],
		     	['class' => 'yii\grid\ActionColumn',
		            'header'=>'Action'],
		        ],
		    ]); ?>
		</div>
	</div>
</div>
