<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\BlockedDateSearch */
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
$this->registerCssFile("@web/themes/default/plugins/bootstrap-datepicker/css/datepicker.css");
$this->registerJsFile("@web/themes/default/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js", ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJs("
var active_dates = ['5/1/2015', '10/1/2015'];
var today = new Date();

$('#dp5').datepicker({
	startDate: '01-01-2015',
	beforeShowDay:function(date){
    var d = date;
    var curr_date = d.getDate();
    var curr_month = d.getMonth() + 1; //Months are zero based
    var curr_year = d.getFullYear();
    var formattedDate = curr_date + \"/\" + curr_month + \"/\" + curr_year

    if ($.inArray(formattedDate, active_dates) != -1){
       return {
          classes: 'activeClass'
       };
    }
    
    return;
  }
});

/* $('#dp5').on('changeDate', function(ev){
    var path = '".Url::toRoute(['blocked-date/block'])."';
    $.ajax({
      type: 'POST',
      url: path, //url to be called
      data: { date: ev.format() ,_csrf : csrfToken}, //data to be send
      success: function( data ) {
        alert(data +'blocked');
	    }
    });
});	*/

");