<?php
use yii\bootstrap\ButtonGroup;
use yii\bootstrap\Button;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\grid\Column;
use yii\widgets\Pjax;
use yii\base;
use yii\base\Object;
use yii\helpers\ArrayHelper;
use common\models\PriorityItem;
use yii\grid\CheckboxColumn;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $searchModel common\models\PriorityitemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Priority items';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="priorityitem-index">

<div class="loadingmessage" style="display: none;">
    <p>
    <?= Html::img(Yii::getAlias('@web/themes/default/img/loading.gif'), ['class'=>'','width'=>'64px','height'=>'64px','id'=>'loading','alt'=>'loading']);?>
    </p>
</div>

<p>        
    <?= Html::a('Create Priority item', ['create'], ['class' => 'btn btn-success']) ?>

    <?= Html::a('Normal', [''], ['class' => 'btn btn-info','id'=>'Normal','onclick'=>'return Status("Normal")', 'style'=>'float:right;']) ?>

	<?= Html::a('Super', [''], ['class' => 'btn btn-info','id'=>'Super','onclick'=>'return Status("Super")', 'style'=>'float:right;']) ?>
</p>

<div class="filter-date">

	<input type="text" name="filter_start" id="filter_start"  placeholder='Priority start date'class="filter" style="margin-left:10px;" />

 	<input type="text" name="filter_end" id="filter_end"  placeholder='Priority end date' class="filter" style="margin-left:10px;" />

	<select id="status" style="width:100px;">
		<option value="All">All</option>
		<option value="Active">Active</option>
		<option value="Inactive">Inactive</option>
	</select>


    <select id="level" style="width:100px;">
        <option value="All">All</option>
        <option value="Normal">Normal</option>
        <option value="Super">Super</option>
    </select>

    <input type="button" name="filter" id="filter" value="Filter" onClick="prioritydatefilter()" class="btn btn-info" style="margin-left:10px; margin-top: -6px;"/>
    
    <input type="button" name="clear" id="clear" value="clear" class="btn btn-info" style="margin-left:10px; margin-top: -6px;"/>

    </div>

    <br>

	<?php Pjax::begin(['enablePushState' => false]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'id'=>'priority',
        'columns' => [
			[ 'class' => 'yii\grid\CheckboxColumn'],
            ['class' => 'yii\grid\SerialColumn'],
         ['attribute'=>'item_name',
         'value'=>'vendoritem.item_name',
            ],
            'priority_level',
            [
				'attribute'=>'priority_start_date',
				'format' => ['date', 'php:d/m/Y'],
				'label'=>'Priority start date',
			],
            [
				'attribute'=>'priority_end_date',
				'format' => ['date', 'php:d/m/Y'],
				'label'=>'Priority end date',
			],

			[
				'attribute'=>'created_datetime',
				'format' => ['date', 'php:d/m/Y'],
				'label'=>'created date',
			],
			[
			  'header'=>'status',
			  'format' => 'raw',
			  'value'=>function($data) {
				return HTML::a('<img src='.$data->statusImageurl($data->status).' id="image-'.$data->priority_id.'" title='.$data->statusTitle($data->status).'>','javascript:void(0)',['id'=>'status',
				'onclick'=>'change("'.$data->status.'","'.$data->priority_id.'")']);
				},

			 ],

            ['class' => 'yii\grid\ActionColumn',
            'header'=>'Action',
            'template' => ' {update} {delete}',
			],
			],
    ]); ?>

    <?php Pjax::end(); ?>
</div>
<!-- Filter items append this div-->
<div id="filteritems"></div>

<?php 

$this->registerJs("
    var priority_item_url = '".Url::to(['/priority-item/index'])."';
    var priority_item_status = '".Url::to(['/priority-item/status'])."';
    var block_priority_url = '".Url::to(['/priority-item/blockpriority'])."';
", View::POS_HEAD);

$this->registerCssFile("@web/themes/default/plugins/bootstrap-datepicker/css/datepicker.css");

$this->registerJsFile("@web/themes/default/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js", ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerJsFile("@web/themes/default/js/priority-item-list.js", ['depends' => [\yii\web\JqueryAsset::className()]]);
