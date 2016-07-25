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
use common\models\Priorityitem;
use yii\grid\CheckboxColumn;

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

<!-- BEGIN PLUGIN CSS -->
<link href="<?= Url::to("@web/themes/default/plugins/bootstrap-datepicker/css/datepicker.css") ?>" rel="stylesheet" type="text/css" />
<!-- END PLUGIN CSS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="<?= Url::to("@web/themes/default/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js") ?>" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->
<script>
    $('#one').click(function() {
        var names = [];
        $('#selection input:checked').each(function() {
            names.push(this.name);
            alert (names);
        });
    });

    $('#clear').click(function()
    {
			$('#filter_start').val("");
			$('#filter_end').val("");
			$('#status').val("All");
            $('#level').val("All");

    var csrfToken = $('meta[name="csrf-token"]').attr("content");
        // start and end date values
        var start = $('#filter_start').val();
        var start = start.split("-").reverse().join("-");
        var end = $('#filter_end').val();
        var end = end.split("-").reverse().join("-");
        var status = $('#status').val();
        var level = $('#level').val();
        $('.loadingmessage').show();
        var path = "<?php echo Url::to(['/priorityitem/index']); ?> ";
        $.ajax({
        type: 'POST',
        url: path, //url to be called
        data: { start:start, end:end, status: status, level: level, _csrf : csrfToken}, //data to be send
        success: function( data ) {
            $('.loadingmessage').hide();
             $('#w0').remove();
            $('#filteritems').html(data);
         }
        })



	});

</script>

<script type="text/javascript">
	var csrfToken = $('meta[name="csrf-token"]').attr("content");
	var txt;

	/* Change status for respective vendor items */
		function Status(status){
		var keys = $('#priority').yiiGridView('getSelectedRows');
		var pathUrl = "<?php echo Url::to(['/priorityitem/status']); ?>";
		if(keys.length == 0) { alert ('Select Your priority item'); return false;}
		var r = confirm("Are you sure want to " +status+ "?");
		if (r == true) {
			$.ajax({
			   url: pathUrl,
			   type : 'POST',
			   data: {keylist: keys, status:status},
			   success : function(data)
			   {
					window.location.reload(true);
			   }

			});
			return false;
        }
		return false;
    }

/* BEGIN priority start and end date picker & filter */

$('input#filter_start,input#filter_end').datepicker({
    format: 'dd-mm-yyyy',
    autoclose: true,
});

function prioritydatefilter()
{
    var csrfToken = $('meta[name="csrf-token"]').attr("content");
        // start and end date values
        var start = $('#filter_start').val();
        var end = $('#filter_end').val();
        var status = $('#status').val();
        var level = $('#level').val();
        $('.loadingmessage').show();
        var path = "<?php echo Url::to(['/priorityitem/index']); ?> ";
        $.ajax({
        type: 'POST',
        url: path, //url to be called
        data: { start:start, end:end, status: status, level: level, _csrf : csrfToken}, //data to be send
        success: function( data ) {
            $('.loadingmessage').hide();
             $('#w0').remove();
            $('#filteritems').html(data);
         }
        })

}
/* END priority start and end date picker & filter */

function change(status, aid)
	{
		var csrfToken = $('meta[name="csrf-token"]').attr("content");
        var path = "<?php echo Url::to(['/priorityitem/blockpriority']); ?> ";
        $.ajax({
        type: 'POST',
        url: path, //url to be called
        data: { status: status, aid: aid, _csrf : csrfToken}, //data to be send
        success: function(data) {
			var status1 = (status == 'Active') ? 'Deactive' : 'Active';
			$('#image-'+aid).attr('src',data);
			$('#image-'+aid).parent('a').attr('onclick',
			"change('"+status1+"', '"+aid+"')");
         }
        });
     }

</script>
