<?php
use yii\helpers\Url;
use common\models\Vendoritem;
use common\models\Itemtype;
use admin\models\Category;
use common\models\Package;
use common\models\Vendorpackages;
use common\models\Deliverytimeslot;
use common\models\DeliverytimeslotSearch;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model common\models\Vendor */

$this->title = $model->vendor_name.' info ';
$this->params['breadcrumbs'][] = ['label' => 'Manage Vendors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="loadingmessage" style="display: none;">
<p>
<?= Html::img(Yii::getAlias('@web/themes/default/img/loading.gif'), ['class'=>'','width'=>'64px','height'=>'64px','id'=>'loading','alt'=>'loading']);?>
</p>
</div>
<?= Html::csrfMetaTags() ?>
<div class="col-md-12 col-sm-12 col-xs-12">
<!-- Begin Twitter Tabs-->
<div class="tabbable">
    <ul class="nav nav-tabs">
        <li class="active"><a href="#1" data-toggle="tab">Vendor Info </a></li>
        <li><a href="#3" data-toggle="tab">Package Log</a></li>
        <li><a href="#4" data-toggle="tab">Vendor Item Details</a></li>
        <li><a href="#5" data-toggle="tab">Delivery timeslot</a></li>
        <li><a href="#6" data-toggle="tab">Exception dates</a></li>
        <li><a href="#7" data-toggle="tab">Email addresses</a></li>
    </ul>
<div class="tab-content">
<!-- Begin First Tab -->
<div class="tab-pane" id="1" ><div class="admin" style="text-align: center;padding:0px 0px 25px 0px;">
    <?php
    if(isset($model->vendor_logo_path)) {
		echo Html::img(Yii::getAlias('@s3/vendor_logo/').$model->vendor_logo_path, ['class'=>'','width'=>'125px','height'=>'125px','alt'=>'Logo']);
    }
    ?>
		</div>
    <div class="form-group">
        <?= DetailView::widget([ 'model' => $model,
            'attributes' => [
                'vendor_name',
                'vendor_name_ar',
                'vendor_brief',
                [
                    'label'=>'vendor_return_policy',
                    'value'=>strip_tags($model->vendor_return_policy)
                ],
                'vendor_public_email',
                'vendor_public_phone',
                'vendor_working_hours',
                'vendor_contact_name',
                'vendor_contact_email',
                'vendor_contact_number',
                [
                    'attribute'=>'package_start_date',
                    'format' => ['date', 'php:d/m/Y'],
                ],
                [
                    'attribute'=>'package_end_date',
                    'format' => ['date', 'php:d/m/Y'],
                ],
                'vendor_emergency_contact_name',
                'vendor_emergency_contact_email',
                'vendor_emergency_contact_number',
                'vendor_website',
                'vendor_status'
            ]
        ]);?>
    </div>
</div>
<!--End First Tab -->

<!--End Second Tab -->
<div class="tab-pane" id="3">
<table class="table table-striped  detail-view">
	<tbody>
		<tr class="add">
            <td>
                <?php   $package = Package::loadpackage();
	                    $form = ActiveForm::begin([]); $model->package_id='';$model->package_start_date='';$model->package_end_date='';
	echo $form->field($model, 'package_id')->dropdownList($package,['prompt'=>'Select Package',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"],'style' => 'margin-top:10px;'])->label(false); ?></td>
	<td><?= $form->field($model, 'package_start_date',['template' => "{label}<div class='controls mystart'>{input}</div>{hint}{error}"])->textInput(['maxlength' => 128,'placeholder' => 'Start date',])->label(false);?></td>
	<td><?= $form->field($model, 'package_end_date',['template' => "{label}<div class='controls myend'>{input}</div>{hint}{error}"])->textInput(['maxlength' => 128,'placeholder' => 'End date',])->label(false);?></td>
	<td style="float:left;"><?php echo Html::Button($model->isNewRecord ? 'Add' : 'Add', [ 'onclick' => 'return check_validation();','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary-ads','style'=>'float:right;margin-top:10px;']);
	echo $form->field($model, 'vendor_id')->hiddenInput()->label('');
	ActiveForm::end();
			?></td><td></td><td></td>
			<div id="result"></div>
			<div id="information" style="color:green; margin-top:8px;"></div>
			<div id="information_fail" style="color:red; margin-top:8px;"></div>
			</tr>

		<tr class="edit"><td><?php $package = Package::loadpackage();
			   $model->package_id='';$model->package_start_date='';$model->package_end_date='';
	echo $form->field($model, 'package_id')->dropdownList($package,['prompt'=>'Select Package',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"], 'class'=>'form-control edit_package','style' => 'margin-top:10px;'])->label(false); ?></td>
	<td><?= $form->field($model, 'package_start_date',['template' => "{label}<div class='controls mystart1'>{input}</div>{hint}{error}"])->textInput(['maxlength' => 128,'placeholder' => 'Start date','class'=>'edit_start'])->label(false);?></td>
	<td><?= $form->field($model, 'package_end_date',['template' => "{label}<div class='controls myend1'>{input}</div>{hint}{error}"])->textInput(['maxlength' => 128,'placeholder' => 'End date','class'=>'edit_end'])->label(false);?></td>
	<td style="float:left;"><?php echo Html::Button($model->isNewRecord ? 'Update' : 'Update', [ 'onclick' => 'return check_edit_validation();','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary','style'=>'float:right;margin-top:10px;']);
	echo $form->field($model, 'vendor_id')->hiddenInput()->label(''); ?></td>
	<td><?php echo Html::Button($model->isNewRecord ? 'Cancel' : 'Cancel', [ 'onclick' => 'return cancel();','class' => $model->isNewRecord ? 'btn btn-info' : 'btn btn-info','style'=>'float:right;margin-top:10px;']);
	echo $form->field($model, 'vendor_id')->hiddenInput()->label(''); ?></td><td></td><td></td>

		<div id="update_information" style="color:green; margin-top:8px;"></div>
		<div id="update_information_fail" style="color:red; margin-top:8px;"></div>
			</tr>
	</tbody>
	</table>
			<table class="table table-striped table-bordered detail-view" id="myTable">
	<tbody>
		<th>Package Name</th><th>Start Date</th><th>End Date</th><th>Package Price</th><th>Action</th>
			</tr>
			<?php
   
      $i=0;
      
    foreach ($vendorPackage as $log) {
        $sel = ($i==0)?'':'';
        ?>

        <tr id="tr-<?php echo $log['id']; ?>">
            <td><?= Package::PackageData($log['package_id']);  ?></td>
            <td><?php $sd=($log['package_start_date']); echo date("d/m/Y", strtotime($sd));?></td>
            <td><?php $sd=($log['package_end_date']);echo date("d/m/Y", strtotime($sd)); ?></td>
            <td>
                <?php print_r($log['package_price']); ?>
                <input type="hidden" id="packedit" value="<?=$log['id'];?>">
            </td>
            <td>
                <?php
                $url = Url::to(['package/packagedelete', 'id' => $log['package_id']]);
                echo Html::a('<span class="glyphicon glyphicon-trash"></span>','#', ['onclick' => 'packagedelete('.$log['id'].');','title'=>'Delete','class' =>$sel]);
                echo Html::a('<span class="glyphicon glyphicon-pencil"></span>','#', ['onclick' => 'packageedit('.$log['id'].');','title'=>'Edit','class' =>$sel]);
                ?>
            </td>
			</tr>
			<?php $i++; } ?>
	</tbody>
</table>

<div id="output"></div>
<!--End Third Tab -->

</div>

<!--Start Fourth Tab -->
<div class="tab-pane" id="4">

<table class="table table-striped table-bordered detail-view">
	<tbody>
		<tr>
			<th>Item Type</th>
            <th>Item Name</th>
            <th>Category</th>
            <th>Status</th>
            <th>Priority</th>
            <th>Item Approved</th>
            <th>Action</th>
        </tr>
        <?php foreach ($dataProvider->query as $log) { ?>
        <tr>
            <td><?= Itemtype::itemtypename($log['type_id']); ?></td>
            <td><?= Vendoritem::vendoritemname($log['item_id']); ?></td>
            <td><?= Category::viewcategoryname($log['category_id']); ?></td>
            <td><?= ($log['item_status']); ?></td>
            <td><?= ($log['priority']); ?></td>
            <td><?= ($log['item_approved']); ?></td>
            <td>
                <?php
                $url = Url::to(['vendoritem/view', 'id' => $log['item_id']]);
                echo Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['title' => Yii::t('app', 'View')]);
                ?>
            </td>
        </tr>
        <?php } ?>
	</tbody>
</table>
<!--End Third Tab -->
</div>

<div class="tab-pane" id="5">

	<?php
    $timeslot_val = array();
    $delivery_data = Deliverytimeslot::vendor_delivery_details($model->vendor_id);
    if ($delivery_data>0) { ?>

	<div class="vendor-admin-new">
	<div class="day_head">SUNDAY</div>
    <div class="day_head">MONDAY</div>
    <div class="day_head">TUESDAY</div>
    <div class="day_head">WEDNESDAY</div>
    <div class="day_head">THURSDAY</div>
    <div class="day_head">FRIDAY</div>
    <div class="day_head">SATURDAY</div>

    <div class="delivery_days">
        <div class="sun">
        <ul>
            <?php
            $sun = Deliverytimeslot::vendor_deliverytimeslot($model->vendor_id,'Sunday');
            foreach ($sun as $key => $value) {
            $timeslot_id = array_push($timeslot_val, $value['timeslot_id']);
            $start = date('g:ia', strtotime($value['timeslot_start_time']));
            $end =  date('g:ia', strtotime($value['timeslot_end_time']));
            $orders =  $value['timeslot_maximum_orders'];
            echo '<div class="one_slot">';
            echo '<li>'. $start .' - '. $end .'</li>'.'</a>';
            echo '<li><span class="timeslot_orders">'.$orders.'</span></li>';
            echo '</div>';
            } ?>
        </ul>
        </div>
        <div class="mon">
            <ul>
                <?php  $mon = Deliverytimeslot::vendor_deliverytimeslot($model->vendor_id,'Monday');
                foreach ($mon as $key => $value) {
                $timeslot_id = array_push($timeslot_val, $value['timeslot_id']);
                $start = date('g:ia', strtotime($value['timeslot_start_time']));
                $end =  date('g:ia', strtotime($value['timeslot_end_time']));
                $orders =  $value['timeslot_maximum_orders'];
                echo '<div class="one_slot">';
                echo '<li>'. $start .' - '. $end .'</li>';
                echo '<li><span class="timeslot_orders">'.$orders.'</span></li>';
                echo '</div>';
                } ?>
            </ul>
        </div>
        <div class="tue">
            <ul>
                <?php  $tue = Deliverytimeslot::vendor_deliverytimeslot($model->vendor_id,'Tuesday');

                foreach ($tue as $key => $value) {
                $timeslot_id = array_push($timeslot_val, $value['timeslot_id']);
                $start = date('g:ia', strtotime($value['timeslot_start_time']));
                $end =  date('g:ia', strtotime($value['timeslot_end_time']));
                $orders =  $value['timeslot_maximum_orders'];
                echo '<div class="one_slot">';
                echo '<li>'. $start .' - '. $end .'</li>';
                echo '<li><span class="timeslot_orders">'.$orders.'</span></li>';
                echo '</div>';
                } ?>
            </ul>
        </div>
        <div class="wed">
            <ul>
                <?php  $wed = Deliverytimeslot::vendor_deliverytimeslot($model->vendor_id,'Wednesday');
                foreach ($wed as $key => $value) {
                $timeslot_id = array_push($timeslot_val, $value['timeslot_id']);
                $start = date('g:ia', strtotime($value['timeslot_start_time']));
                $end =  date('g:ia', strtotime($value['timeslot_end_time']));
                $orders =  $value['timeslot_maximum_orders'];
                echo '<div class="one_slot">';
                echo '<li>'. $start .' - '. $end .'</li>';
                echo '<li><span class="timeslot_orders">'.$orders.'</span></li>';
                echo '</div>';
                } ?>
            </ul>
        </div>
        <div class="thu">
            <ul>
                <?php  $thu = Deliverytimeslot::vendor_deliverytimeslot($model->vendor_id,'Thursday');
                foreach ($thu as $key => $value) {
                $timeslot_id = array_push($timeslot_val, $value['timeslot_id']);
                $start = date('g:ia', strtotime($value['timeslot_start_time']));
                $end =  date('g:ia', strtotime($value['timeslot_end_time']));
                $orders =  $value['timeslot_maximum_orders'];
                echo '<div class="one_slot">';
                echo '<li>'. $start .' - '. $end .'</li>'.'</a>';
                echo '<li><span class="timeslot_orders">'.$orders.'</span></li>';
                echo '</div>';
                } ?>
            </ul>
        </div>
        <div class="fri">
            <ul>
                <?php  $fri = Deliverytimeslot::vendor_deliverytimeslot($model->vendor_id,'Friday');
                foreach ($fri as $key => $value) {
                $timeslot_id = array_push($timeslot_val, $value['timeslot_id']);
                $start = date('g:ia', strtotime($value['timeslot_start_time']));
                $end =  date('g:ia', strtotime($value['timeslot_end_time']));
                $orders =  $value['timeslot_maximum_orders'];
                echo '<div class="one_slot">';
                echo '<li>'. $start .' - '. $end .'</li>';
                echo '<li><span class="timeslot_orders">'.$orders.'</span></li>';
                echo '</div>';
                } ?>
            </ul>
        </div>
        <div class="sat">
            <ul>
                <?php  $sat = Deliverytimeslot::vendor_deliverytimeslot($model->vendor_id,'Saturday');
                foreach ($sat as $key => $value) {
                $timeslot_id = array_push($timeslot_val, $value['timeslot_id']);
                $start = date('g:ia', strtotime($value['timeslot_start_time']));
                $end =  date('g:ia', strtotime($value['timeslot_end_time']));
                $orders =  $value['timeslot_maximum_orders'];
                echo '<div class="one_slot">';
                echo '<li>'. $start .' - '. $end .'</li>';
                echo '<li><span class="timeslot_orders">'.$orders.'</span></li>';
                echo '</div>';
                } ?>
            </ul>
        </div>
    </div>
</div>

<?php } else {
	echo 'No data Found';
}
	?>
<!--End fourth Tab -->
</div>

<!--Start sixth Tab -->
<div class="tab-pane" id="6">

    <?= GridView::widget([
        'dataProvider' => $dataProvider3,
        'filterModel' => $searchModel3,
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
        ],
    ]); ?>
</div>
<!--End sixth Tab -->


<div class="tab-pane" id="7">

    Email address list to get order notification 

    <br />
    <br />

    <table class="table table-bordered table-email-list">
        <tbody>
            <?php foreach ($vendor_order_alert_emails as $key => $value) { ?>
            <tr>
                <td>
                    <?= $value->email_address ?>           
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php 

$this->registerCssFile("@web/themes/default/plugins/bootstrap-datepicker/css/datepicker.css");

$this->registerJsFile("@web/themes/default/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js", ['depends' => [\yii\web\JqueryAsset::className()]]);

?>

<script>

	/* Begin Tabs NEXT & PRVE buttons */
	$('.btnNext').click(function(){
	  $('.nav-tabs > .active').next('li').find('a').trigger('click');
	});

	  $('.btnPrevious').click(function(){
	  $('.nav-tabs > .active').prev('li').find('a').trigger('click');
	});
	/* End Tabs NEXT & PRVE buttons */

 $(function (){

 	/* Begin when loading page first tab opened */
  $('.nav-tabs li:first').addClass("active");
 	$(".tab-content div:first").addClass("active");
 	/* End when loading page first tab opened */

 	/* For themes and groups list checkbox alignment*/
 	$(".themelists:last-child").css({"clear" : "both","float" :"inherit"});
 	/* For themes and groups list checkbox alignment*/

	$('#option').hide();

  $(document).delegate(".vendoritemquestion-question_answer_type", 'change', function (){
		
    var type = $(this).val();

		if(type =='selection')
		{
			$(this).next('.price_val').remove();
			
      var j = $(this).attr('id').replace(/vendoritemquestion-question_answer_type/, '');
			
      $('#option').show();
			
      $(this).after('<div class="selection"><input type="text" class="form-control" name="Vendoritemquestion['+j+'][text][0][]" placeholder="Question" id="question" style="width:50%;float:left;"><input type="text" class="form-control" name="Vendoritemquestion['+j+'][price][0][]" placeholder="Price (Optional)" id="price" style="width:40%;float:left;"><input type="button" class="add_question" id="add_question'+j+'" name="Addss" value="Add Selection"></div>');

		} else if(type =='image' ||  type =='text') {

			$(this).next('.selection').remove();
			
      $(this).next('.price_val').remove();
			
      var j = $(this).attr('id').replace(/vendoritemquestion-question_answer_type/, '');
			
      $('#option').show();

			$(this).after('<div class="price_val"><input type="text" class="form-control" name="Vendoritemquestion['+j+'][price][]" placeholder="Price (Optional)" id="price" style="width:40%;float:left;"></div>');
		}

  	// Add selection for questions //
  });

		var p = 1;

		$(document).delegate('.add_question', 'click', function(){
			
      var j = $(this).attr('id').replace(/add_question/, '');
			
      $(this).before('<div class="selection"><input type="text" class="form-control" name="Vendoritemquestion['+j+'][text]['+p+'][]" placeholder="Question" id="question" style="width:50%;float:left;"><input type="text" class="form-control" name="Vendoritemquestion['+j+'][price]['+p+'][]" placeholder="Price (Optional)" id="price" style="width:40%;float:left;"></div>');	
      
      p++;

		});

});

</script>
<script type="text/javascript">
	$(function (){
	$(".edit").hide();

	var start_date = $('#vendor-package_start_date').val();
	var end_date = $('#vendor-package_end_date').val();
	if(start_date=='0000-00-00'){
		$('#vendor-package_start_date').val('');
	}
	if(end_date=='0000-00-00'){
		$('#vendor-package_end_date').val('');
	}
	});


 function check_validation()
 {
  	var csrfToken = $('meta[name="csrf-token"]').attr("content");
  	var id = $('#vendor-package_id').val();
  	var vid = $('#vendor-vendor_id').val();
  	var start_dat = $('#vendor-package_start_date').val(); //
  	var start_date = start_dat.split("-").reverse().join("-");	// change date format
  	var end_dat = $('#vendor-package_end_date').val(); //
  	var end_date = end_dat.split("-").reverse().join("-");	// change date format
  	var package_pricing = $('#vendor-package_pricing').val();
  	
    if(!(id && start_date && end_date)) {      	
      $("#result").html('<div class="alert alert-failure"><button type="button" data-dismiss="alert" class="close"></button>Kindly Enter Valid value!</div>');
      return false;
  	}

    var path = "<?php echo Url::to(['/vendor/changepackage']); ?> ";

    $('.loadingmessage').show();
    
    $.ajax({
      type: 'POST',
      url: path, //url to be called
      data: { 
        id: id,
        vid: vid,
        start_date: start_date,
        end_date: end_date,
        package_pricing: package_pricing,
        _csrf : csrfToken
      }, //data to be send
      success: function(json){
	        
          $('.loadingmessage').hide();
			    
          if(json.errors.length > 0) {
             
              $msg = '';

              $.each(json.errors, function(index, value) {
                  $msg += value + '<br />';
              }); 

              $("#result").html('<div class="alert alert-failure"><button type="button" class="close"></button>' + $msg + '</div>');

              exit;
          }else{
              $('#vendor-package_id').val('');
              $('#vendor-package_start_date').val('');
              $('#vendor-package_end_date').val('');
              $("#result").html('<div class="alert alert-success"><button type="button" class="close"></button>Package added successfully!</div>');
              $("#vendor-package_start_date").attr("disabled","disabled");
              
              $("#vendor-package_end_date").attr("disabled","disabled");
                  $('.alert .close').on("click", function(e){
                        $(this).parent().fadeTo(500, 0).slideUp(500);
                 });
              $('#myTable tr').removeClass("update_row");
              $("#myTable tbody tr:first").after(json['html']);
          }
      }
    });
}

function packagedelete(id)
	{
		var r = confirm("Are you sure want to delete?");
        if (r == true) {

		var vid = $('#vendor-vendor_id').val();
		var csrfToken = $('meta[name="csrf-token"]').attr("content");
        var path = "<?php echo Url::to(['/package/packagedelete']); ?> ";
        $('.loadingmessage').show();
        $.ajax({
        type: 'POST',
        url: path, //url to be called
        data: { packid: id,vid:vid,_csrf : csrfToken}, //data to be send
        success: function(data) {
		$('.loadingmessage').hide();
		$('#myTable tr#tr-'+id).remove();
		$('#myTable tr').removeClass("update_row");
		$("#result").html('<div class="alert alert-success"><button type="button" class="close"></button>Package deleted successfully!</div>');
          $('.alert .close').on("click", function(e){
                $(this).parent().fadeTo(500, 0).slideUp(500);
             });
		$(".add").show();
		$(".edit").hide();
			$('.edit_package').attr('value','');
			$('.edit_start').val('');
			$('.edit_end').val('');

			$("#vendor-package_start_date").attr("disabled","disabled");
			$("#vendor-package_end_date").attr("disabled","disabled");

			$('#vendor-package_id').val('');
			$('#vendor-package_start_date').val('');
			$('#vendor-package_end_date').val('');
         }
        });
     }
 }


function packageedit(id)
	{
		$('#information_fail').html('');
		$("#information").html('');
		$('#update_information_fail').html('');
		$("#update_information").html('');

		$(".add").hide();
		$(".edit").show();
		$("#information").html('');
		$("#vendor-package_start_date").datepicker("refresh");
		var vid = $('#vendor-vendor_id').val();
		var csrfToken = $('meta[name="csrf-token"]').attr("content");
        var path = "<?php echo Url::to(['/vendor/packageupdate']); ?> ";
        $('.loadingmessage').show();
        $.ajax({
        type: 'POST',
        url: path, //url to be called
        data: { packid: id,vid:vid,_csrf : csrfToken}, //data to be send
        success: function(data) {
			obj = JSON.parse(data);
			$('.loadingmessage').hide();
			var packageid=obj.packid;
			var start=obj.start;
			var start = start.split("-").reverse().join("-");
			var end=obj.end;
			var end = end.split("-").reverse().join("-");
			var forbidden=obj.date;
			var input1=obj.input1;
			var input2=obj.input2;
			$('#packedit').val(id);
			$('.mystart').html(data.input1);
			$('.myend').html(data.input2);
            $('#vendor-package_id option[value="'+packageid+'"]').attr("selected", "selected");
			$('.edit_start').remove();
			$('.mystart1').html('<input type="text" id="vendor-package_start_date" class="edit_start" name="Vendor[package_start_date]" value="" maxlength="128" placeholder="Start">');
			$('.edit_start').val(start);
			$('.edit_end').remove();
			$('.myend1').html('<input type="text" id="vendor-package_end_date" class="edit_end" name="Vendor[package_end_date]" value="" maxlength="128" placeholder="End date">');
			$('.edit_end').val(end);
			$('.edit_start,.edit_end').datepicker({
				format: 'dd-mm-yyyy',
				autoclose: true,
				startDate:'d',
				/*beforeShowDay:function(Date){
					var curr_date = Date.toJSON().substring(0,10);
					if (forbidden.indexOf(curr_date)>-1) return false;
				}*/
      });
    }
  });
 }


$(function (){
    $(document).delegate("#vendor-package_id", "change", function (){

		  var csrfToken = $('meta[name="csrf-token"]').attr("content");
      var id = $('#vendor-vendor_id').val();
      var path = "<?php echo Url::to(['/vendor/loadpackagedate']); ?> ";
      $('.loadingmessage').show();

      $.ajax({
        type: 'POST',
		    dataType:"json",
        url: path, //url to be called
        data: { id: id ,_csrf : csrfToken}, //data to be send
        success: function( data ) {
        	$('.loadingmessage').hide();
    			$('.mystart').html(data.input1);
    			$('.myend').html(data.input2);
    			var forbidden=data.date;

          if($("#vendor-package_id").val()!=''){

            $('#vendor-package_start_date,#vendor-package_end_date').datepicker({
            	format: 'dd-mm-yyyy',
            	autoclose: true,
            	startDate:'d',
              /* beforeShowDay:function(Date){
                    var curr_date = Date.toJSON().substring(0,10);
                    if (forbidden.indexOf(curr_date)>-1) return false;
                }*/
            });

          }
        }//success 
      });
    });//on change
});//on ready 


 function check_edit_validation()
 {
	var csrfToken = $('meta[name="csrf-token"]').attr("content");
	var id = $('.edit_package').val();
	var vid = $('#vendor-vendor_id').val();
	var packedit = $('#packedit').val();
	var start_date = $('.edit_start').val();
	var end_date = $('.edit_end').val();


	if((id==null)||(id=='')||(start_date==null)||(start_date=='')||(end_date==null)||(end_date=='')){
			$("#result").html('<div class="alert alert-failure"><button type="button" class="close"></button>Kindly enter valid value!</div>');
          $('.alert .close').on("click", function(e){
                $(this).parent().fadeTo(500, 0).slideUp(500);
             });
    return false;
	}else
	{
	var start_date = start_date.split("-").reverse().join("-");	// change date format
	var end_date = end_date.split("-").reverse().join("-");	// change date format

        var path = "<?php echo Url::to(['/vendor/changeeditpackage']); ?> ";
        $('.loadingmessage').show();
        $.ajax({
        type: 'POST',
        url: path, //url to be called
        data: { id: id ,vid: vid ,packedit:packedit,start_date: start_date ,end_date: end_date ,_csrf : csrfToken}, //data to be send
        success: function( data ){
        	$('.loadingmessage').hide();
			if(data==1){
			$("#result").html('<div class="alert alert-failure"><button type="button" class="close"></button>Blocked dates available in between start date and end date!</div>');
          $('.alert .close').on("click", function(e){
                $(this).parent().fadeTo(500, 0).slideUp(500);
             });
			return false;
			}
			if(data==2){
			$("#result").html('<div class="alert alert-failure"><button type="button" class="close"></button>Start date and end date are different ranges!</div>');
          $('.alert .close').on("click", function(e){
                $(this).parent().fadeTo(500, 0).slideUp(500);
             });

			return false;
			}
			else{
			$("#result").html('<div class="alert alert-success"><button type="button" class="close"></button>Package updates successfully!</div>');
            $('.alert .close').on("click", function(e){
            $(this).parent().fadeTo(500, 0).slideUp(500);
             });
			$("#vendor-package_start_date").attr("disabled","disabled");
			$("#vendor-package_end_date").attr("disabled","disabled");
			$('#vendor-package_id').val('');
			$('#vendor-package_start_date').val('');
			$('#vendor-package_end_date').val('');
			$('#myTable tr#tr-'+packedit).remove();

			$('#myTable tr').removeClass("update_row");

			$("#myTable tbody tr:first").after(data);
			$(".add").show();
			$(".edit").hide();
			$('.edit_package').attr('value','');
			$('.edit_start').val('');
			$('.edit_end').val('');
			}
}
});
}
}

 function cancel()
 {
	 	$(".add").show();
			$('#vendor-package_id').val('');
			$('#vendor-package_start_date').val('');
			$('#vendor-package_end_date').val('');
		$(".edit").hide();

			$('.edit_package').attr('value','');
			$('.edit_start').val('');
			$('.edit_end').val('');
 }
</script>
