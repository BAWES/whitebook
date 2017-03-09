<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\VendorItemCapacityExceptionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Exception dates';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="vendor-item-capacity-exception-index">
<p>
	<div class="row-fluid">
       <div class="span12">
         <div class="grid simple ">
		<div class="tools">
        <?= Html::a('Create exception dates', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute'=>'item_name',
                    'label'=>'Item Name',
                    'value'=>function($data){
                        return (isset($data->vendoritem->item_name)) ? $data->vendoritem->item_name : '-';
                    }
                ],
                [
                    'attribute'=>'exception_date',
                    'format' => ['date', Yii::$app->params['dateFormat']],
                    'label'=>'exception date',
                ],
                'exception_capacity',
                [
                  'class' => 'yii\grid\ActionColumn',
                  'template' => '{update} {delete}'
                ],
            ],
        ]); ?>
	   </div>
	 </div>
   </div>
</div>
<?php
$this->registerCssFile('@web/themes/default/plugins/bootstrap-datepicker/css/datepicker.css');
$this->registerJsFile('@web/themes/default/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJs("
  $(\"[name='VendorItemCapacityExceptionSearch[exception_date]']\").datepicker({
  	startDate: '".$startdate."',
    autoclose:true,
  	format: 'yyyy-mm-dd',
  });
");