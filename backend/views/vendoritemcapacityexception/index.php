<?php
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\VendoritemcapacityexceptionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Exception dates';
$this->params['breadcrumbs'][] = $this->title;
//$this->exception_date='';
?>
<div class="vendoritemcapacityexception-index">
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
          return $data->getItemName($data->item_id);
          }
      ],

			[
				'attribute'=>'exception_date',
				'format' => ['date', Yii::$app->params['dateFormat']],
				'label'=>'exception date',
			],
            'exception_capacity',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
	   </div>
	 </div>
   </div>

</div>


<!-- BEGIN PLUGIN CSS -->
<link href="<?= Url::to("@web/themes/default/plugins/bootstrap-datepicker/css/datepicker.css") ?>" rel="stylesheet" type="text/css" />
<!-- END PLUGIN CSS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="<?= Url::to("@web/themes/default/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js") ?>" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->

<script>
$("[name='VendoritemcapacityexceptionSearch[exception_date]']").datepicker({
	startDate: "<?php echo $startdate;?>",
    autoclose:true,
	format: 'dd-mm-yyyy',
});
</script>
