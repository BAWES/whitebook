<?php
use yii\helpers\Url;
use common\models\Image;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Adverthome */

$this->title = 'Home ads';
$this->params['breadcrumbs'][] = ['label' => 'Home ads', 'url' => ['index']];
?>

<div class="adverthome-view">
   	
   	<p>
        <?= Html::a('Update', ['update', 'id' => $model->advert_id], ['class' => 'btn btn-primary']) ?>
    </p>

    <?php 

    $imagedata = Image::find()->where('module_type = :status AND item_id = :id', [':id' => $model->advert_id, ':status' => 'home_ads'])->orderby(['vendorimage_sort_order'=>SORT_ASC])->all();
    
    echo DetailView::widget([
        'model' => $model,
        'attributes' => [
            'advert_code:ntext',
        ],
    ]);
     
    ?>

</div>

<?php 

$this->registerCssFile('@web/themes/default/plugins/jquery-superbox/css/style.css');

$this->registerJsFile('@web/themes/default/plugins/jquery-superbox/js/superbox.js');

$this->registerJs("

	var csrfToken = $('meta[name=\"csrf-token\"]').attr('content');

	$(function() {
		$('.superbox').SuperBox();
	
		var path = '".Url::to(['/image/imageorder'])."';

	    $('.superbox').sortable({
	        stop : function(event, ui){
				var newArray = $(this).sortable('toArray', {key:'s'});
				var id = newArray.filter(function(v){return v!==''});
				$.ajax({
			        type: 'POST',
			        url: path,
			        data: { id: id ,_csrf : csrfToken}, //data to be send
			        success: function( data ) {
			        }
				})
			  }
		});

		$('.superbox').disableSelection();

		$('.check:button').toggle(function(){
	        $('input:checkbox').attr('checked','checked');
				$(this).val('Uncheck all');
			},function(){
				$('input:checkbox').removeAttr('checked');
				$(this).val('Check all');
			});
			return false;
		});
	});

	function changeStatus(action){
		
		var ids = $('input[name=photo]:checked').map(function() {
	    	return this.value;
		}).get().join(',');

		if(ids.length == 0) { 
			alert ('Select atleast one image'); 
			return false;
		}
		
		var loc = $('input[name=photo]:checked').map(function() {
	    	return $(this).attr('id');
		}).get().join(",");

		var scenario = 'home';

		var path = '".Url::to(['/vendor/vendoritem/'])."' + action;
	        
	    $.ajax({
	        type: 'POST',
	        url: path, //url to be called
	        data: { id: ids ,scenario:scenario,loc : loc,_csrf : csrfToken}, //data to be send
	        success: function(data) {
				if(data == 'Deleted')
				{
					window.location.reload();
				}
				return false;
	        }
	    });
	     
	    return false;
	}
");
	

