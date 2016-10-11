<?php

use common\models\Addresstype;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\web\view;

/* @var $searchModel common\models\AddressQuestionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Address Questions';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="address-question-index">
<p>        
    <div class="row-fluid">
       <div class="span12">
         <div class="grid simple ">
        <div class="tools">
        <?= Html::a('Create Address Question', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'typeName',
            [
				'attribute' => 'question',
				'label' => 'Question',	
				'value' => function($data){
				return ucfirst($data->question);
				}	
			],

			[
				'attribute' => 'question_ar',
				'label' => 'Question - Arabic',	
				'value' => function($data){
					return ucfirst($data->question_ar);
				}	
			],

			[
				'attribute' => 'sort',
				'label' => 'Sort Order',	
				'format' => 'raw',		
				'value' => function($data){
					return '<b><input type="hidden" id="hidden_'.$data->ques_id.'" value="'.$data->sort.'"><input type="text" value="'.$data->sort.'" onblur="change_sort_order(this.value,'.$data->ques_id.')"></b>';
					},
				'contentOptions' => ['class'=>'sort','style'=>'max-width: 100px;']					
			],
			
			[
				'attribute' => 'created_datetime',
				'format' => ['date', Yii::$app->params['dateFormat']],
				'label' => 'created date',			
			],

			[
			  'header' => 'Status',
			  'format' => 'raw',
			  'value' => function($data) {
				return HTML::a('<img 
				src='.$data->statusImageurl($data->status).' id="image-'.$data->ques_id.'" title='.$data->statusTitle($data->status).'>','javascript:void(0)',['id'=>'status',
				'onclick'=>'change("'.$data->status.'","'.$data->ques_id.'")']);
				},
			],
          	
          	[
          		'class' => 'yii\grid\ActionColumn',
            	'header' => 'Action',
            	'template' => '{view} {update} {delete} {link}',
            ],
        ],
    ]); ?>
       </div>
     </div>
   </div>

</div>

<?php 

$this->registerJs("
	
	function change_sort_order(sort_val,ques_id)
    {
		var exist_sort=$('#hidden_'+ques_id).val();
		 
		if(sort_val != exist_sort || exist_sort == 0)
		{
			if(sort_val <= 0 && sort_val != '')
			{
				alert('Please enter greater than 0!');
				return false;
			}
			
			if(isNumeric(sort_val))
			{
				var csrfToken = $('meta[name=\"csrf-token\"]').attr('content');		
				var path = '".Url::to(['/addressquestion/sort_addressquestion'])."';
				
				$.ajax({  
					type: 'POST',      
					url: path, //url to be called
					data: { sort_val: sort_val,ques_id: ques_id,_csrf : csrfToken}, //data to be send
					success: function(data) {
						if(data)
						{
							location.reload();
						}
					}
				});
			}
			else
			{
				if(sort_val!='')
				{
					alert('Enter only integer values!');
					return false;
				}
			}
		}
	}

	function isNumeric(n)
	{
		return !isNaN(parseFloat(n)) && isFinite(n);
	}
	
	function change(status, cid)
	{				
		var csrfToken = $('meta[name=\"csrf-token\"]').attr('content');		
        var path = '".Url::to(['/addressquestion/block'])."';
        
        $.ajax({  
	        type: 'POST',      
	        url: path, //url to be called
	        data: { status: status, cid: cid, _csrf : csrfToken}, //data to be send
	        success: function(data) {
				var status1 = (status == 'Active') ? 'Deactive' : 'Active'; 
				$('#image-'+cid).attr('src',data);
				$('#image-'+cid).parent('a').attr('onclick', 
				\"change('\"+status1+\"', '\"+cid+\"')\");
	        }
        });
     }     
", View::POS_HEAD);
