<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\BlockeddateSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Blocked date';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="blockeddate-index">
	<div class="row-fluid">
       <div class="span12">
         <div class="grid simple ">
		<div class="tools">
        <?= Html::a('Create blocked date', ['create'], ['class' => 'btn btn-success']) ?>

		<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
			[
				'attribute'=>'block_date',
				'format' => ['date', Yii::$app->params['dateFormat']],
				'label'=>'blocked date',
			],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
	   </div>
	 </div>
   </div>
<!--div id="dp5" data-date="12-02-2013" data-date-format="dd-mm-yyyy"></div-->
</div>

<!-- BEGIN PLUGIN CSS -->
<link href="<?= Url::to("@web/themes/default/plugins/bootstrap-datepicker/css/datepicker.css") ?>" rel="stylesheet" type="text/css" />
<!-- END PLUGIN CSS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="<?= Url::to("@web/themes/default/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js") ?>" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->


<script>
var active_dates = ["5/1/2015","10/1/2015"];
var today = new Date();
$('#dp5').datepicker({
	startDate: '01-01-2015',
	beforeShowDay:function(date){
         var d = date;
         var curr_date = d.getDate();
         var curr_month = d.getMonth() + 1; //Months are zero based
         var curr_year = d.getFullYear();
         var formattedDate = curr_date + "/" + curr_month + "/" + curr_year

       if ($.inArray(formattedDate, active_dates) != -1){
               return {
                  classes: 'activeClass'
               };
           }
     return;
  }
 });
 /* $('#dp5').on('changeDate', function(ev){
   var path = "<?php echo Url::toRoute(['blockeddate/block']); ?> ";
        $.ajax({
        type: 'POST',
        url: path, //url to be called
        data: { date: ev.format() ,_csrf : csrfToken}, //data to be send
        success: function( data ) {
             alert(data +'blocked');
		}
    })
})	*/
</script>
