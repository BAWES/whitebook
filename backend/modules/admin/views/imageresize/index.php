<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ImageresizeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Imageresizes';
$this->params['breadcrumbs'][] = $this->title;
?>		
<div class="row-fluid">
    <div class="span12">
        <div class="grid simple ">
			<div class="tools">
			<?= Html::a('Create image resize', ['create'], ['class' => 'btn btn-success']) ?>
			</div>

		<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'logo_width',
            'logo_height',    
            'item_list_width',
            'item_list_height',
            'item_detail_width',  
            'item_detail_height',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
	   </div>
	</div>
</div>
   

</div>
