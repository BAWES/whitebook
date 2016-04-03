<?php
use yii\helpers\Url;
use backend\models\Image;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Adverthome */

$this->title = 'Home ads';
$this->params['breadcrumbs'][] = ['label' => 'Home ads', 'url' => ['index']];
?>
<div class="adverthome-view">
   <p>
        <?= Html::a('Update', ['update', 'id' => $model->advert_id], ['class' => 'btn btn-primary']) ?>  
    </p>
    <?php $imagedata = Image::find()->where('module_type = :status AND item_id = :id', [':id' => $model->advert_id, ':status' => 'home_ads'])->orderby(['vendorimage_sort_order'=>SORT_ASC])->all(); 
     echo DetailView::widget([
        'model' => $model,
        'attributes' => [
            'advert_code:ntext',
        ],
    ]);
     ?>
<link rel="stylesheet" href="<?php echo Yii::$app->themeURL->createAbsoluteUrl(''); ?>plugins/jquery-superbox/css/style.css" rel="stylesheet" type="text/css" media="screen">
<script src="<?php echo Yii::$app->themeURL->createAbsoluteUrl(''); ?>plugins/jquery-superbox/js/superbox.js" type="text/javascript"></script>
</div>

<script type="text/javascript">
$(function() {		
			$('.superbox').SuperBox();		
		});
		
	var csrfToken = $('meta[name="csrf-token"]').attr("content");
	
	$(document).ready(function(){			
		var path = "<?php echo Url::to(['/admin/image/imageorder']); ?> ";
	<!-- Begin Sortable images -->		
    $(".superbox").sortable({
        stop : function(event, ui){    
		var newArray = $(this).sortable("toArray",{key:'s'});	
		var id = newArray.filter(function(v){return v!==''});	
		$.ajax({  
        type: 'POST',      
        url: path,
        data: { id: id ,_csrf : csrfToken}, //data to be send
        success: function( data ) {
         }          	
		})  
	  }
	})
	$(".superbox").disableSelection();
	<!-- End Sortable images -->
	
	<!-- Begin Select all checkbox images -->	
	$('.check:button').toggle(function(){ 
        $('input:checkbox').attr('checked','checked');
			$(this).val('Uncheck all');
		},function(){
			$('input:checkbox').removeAttr('checked');
			$(this).val('Check all');        
		});
		return false;
	<!-- End Select all checkbox images -->	
 });
 
 function changeStatus(action){	
	var ids = $("input[name=photo]:checked").map(function() {
    return this.value;
	}).get().join(",");	
	if(ids.length == 0) { alert ('Select atleast one image'); return false;}	
	var loc = $("input[name=photo]:checked").map(function() {
    return $(this).attr('id');
	}).get().join(",");		
	
	var scenario='home';	
	var path = "<?php echo Url::to(['/vendor/vendoritem/']); ?>/"+action;
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
     })  
      return false; 
}
 </script>
