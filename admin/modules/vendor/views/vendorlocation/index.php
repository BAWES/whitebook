<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\vendorlocationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Vendorlocations';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vendorlocation-index">
 <p>
 <div class="row-fluid">
       <div class="span12">
         <div class="grid simple ">
		<div class="tools">
        <?= Html::a('Create Vendorlocation', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'vendor_id',
            'city_id',
            'area_id',
            'created_datetime',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
	   </div>
	 </div>
   </div>

</div>
