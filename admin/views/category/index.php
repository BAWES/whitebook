<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\web\View;

/* @var $searchModel common\models\SearchCategory */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Category';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-index">
	<p>
		<?= Html::a('Create category', ['create'], ['class' => 'btn btn-success']) ?>
	</p>

	<?= GridView::widget([
		'dataProvider' => $provider,
		'columns' => [
			['class' => 'yii\grid\SerialColumn'],
			[
				'attribute'=>'category_name',
				'format' => 'html'
			],
			'sort',
			['class' => 'yii\grid\ActionColumn',
				'header'=>'Action',
				'template' => '{move} {update} {delete}',
				'buttons' => [
					'move' => function ($url, $model) {
						if($model['category_level'] >= 2) {
							return '<a class="btn-move-items" data-parent-id="'.$model['parent_category_id'].'" data-id="'.$model['ID'].'"><span class="glyphicon glyphicon-share"></span></a>';	
						} 						
					},
					'update' => function ($url, $model) {
						$url = Url::toRoute(['category/update','id'=>$model['ID']]);
						return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url,
							[
								'title' => Yii::t('app', 'update'),
							]);
					},
					'delete' => function ($url, $model) {
						$url = Url::toRoute(['category/delete','id'=>$model['ID']]);
						return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url,
							[
								'title' => Yii::t('app', 'update'),
							]);
					}
				],
			],
		],
	]); ?>

</div>

<div id="modal_move_cat_items" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Select category</h4>
      </div>
      <div class="modal-body">

            <?= Html::hiddenInput('from_id', 0, ['id' => 'from_id']); ?>

            <select class="form-control" id="to_id">
            </select>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary btn-move-submit" type="button">Submit</button>
      </div>
    </div>
  </div>
</div>

<?php 

echo Html::hiddenInput('load_sub_category_url', Url::to(['category/loadsubcategory']), ['id' => 'load_sub_category_url']);

echo Html::hiddenInput('move_url', Url::to(['category/move']), ['id' => 'move_url']);

$this->registerJsFile("@web/themes/default/js/category.js?v=1.0", ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerJs("

	function change(status, cid)
	{		
		var csrfToken = $('meta[name=\"csrf-token\"]').attr(\"content\");		
        var path = '".Url::to(['/category/block'])."';
        $.ajax({  
        type: 'POST',      
        url: path, //url to be called
        data: { status: status, cid: cid, _csrf : csrfToken}, //data to be send
        success: function(data) {
			var status1 = (status == 'yes') ? 'no' : 'yes'; 
			$('#image-'+cid).attr('src',data);
			$('#image-'+cid).parent('a').attr('onclick', 
			\"change('\"+status1+\"', '\"+cid+\"')\");
         }
        });
     }
	 
	 function change_sort_order(sort_val,cat_id)
     {
		 var exist_sort=$('#hidden_'+cat_id).val();
		 if(sort_val!=exist_sort || exist_sort==0)
		 {
			if(sort_val<=0 && sort_val!='')
			{
				alert('Please enter greater than 0!');
				return false;
			}
			
			if(isNumeric(sort_val))
			{
				var csrfToken = $('meta[name=\"csrf-token\"]').attr('content');		
				var path = '".Url::to(['/category/sort_category'])."';

				$.ajax({  
					type: 'POST',      
					url: path, //url to be called
					data: { sort_val: sort_val,cat_id: cat_id,_csrf : csrfToken}, //data to be send
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

", View::POS_HEAD);