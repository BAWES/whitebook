<?php
use yii\bootstrap\ButtonGroup;
use yii\bootstrap\Button;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\grid\Column;
use yii\base;
use yii\base\Object;
use yii\helpers\ArrayHelper;
use common\models\Priorityitem;
use yii\grid\CheckboxColumn;

/* @var $this yii\web\View */
/* @var $searchModel common\models\PriorityitemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Priority items';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="priorityitem-index">
<p>
        <?= Html::a('Create priority item', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
				'attribute'=>'item_id',
				'label'=>'Item Name',			
				'value'=>function($data){
					return $data->getItemName($data->item_id);
					}				
			],
            [
				'attribute'=>'priority_start_date',
				'format' => ['date', 'php:d/m/Y'],
				'label'=>'Feature start date',			
			],			
            [
				'attribute'=>'priority_end_date',
				'format' => ['date', 'php:d/m/Y'],
				'label'=>'Feature start date',			
			],
            'priority_level',
            ['class' => 'yii\grid\ActionColumn'],
			],    
    ]); ?>
    
</div>
<script>
    $('#one').click(function() {
		alert(1);
        var names = [];
        $('#selection input:checked').each(function() {
            names.push(this.name);
            alert (names);
        });
    });
</script>

