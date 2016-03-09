<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Alert;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\VendorSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Vendors contact details';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row-fluid">
   <div class="span12">
       <div class="grid simple">
		   <div class="tools"> 	
			<?= Html::a('Create vendor contact details', ['create'], ['class' => 'btn btn-success']) ?>
			</div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
             'vendor_contact_no',
             'address_text',
            ['class' => 'yii\grid\ActionColumn',
            'header'=>'Action'],
        ],
    ]); ?>

		</div>
	</div>
</div>

<script type="text/javascript">
	function change(status, id)
	{		
		var csrfToken = $('meta[name="csrf-token"]').attr("content");		
        var path = "<?php echo Url::to(['/admin/vendor/block']); ?> ";
        $.ajax({  
        type: 'POST',      
        url: path, 
        data: { status: status, id: id,_csrf : csrfToken},
        success: function(data) {	
         }
        });
     }
</script>
