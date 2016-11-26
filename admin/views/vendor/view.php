<?php
use yii\helpers\Url;
use common\models\VendorItem;
use common\models\ItemType;
use admin\models\Category;
use common\models\Package;
use common\models\VendorPackages;
use common\models\DeliveryTimeSlot;
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
//                  ['label'=>'package id',
//                 'value'=> isset($model->package->package_name) ? $model->package->package_name : 'Not set' ,
//                ],

                [
                    'label'=>'vendor_return_policy',
                    'value'=>strip_tags($model->vendor_return_policy)
                ],
                'vendor_public_email',
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
            <th>Status</th>
            <th>Priority</th>
            <th>Item Approved</th>
            <th>Action</th>
        </tr>
        <?php foreach ($dataProvider->query as $log) { ?>
        <tr>
            <td><?= ItemType::itemtypename($log['type_id']); ?></td>
            <td><?= VendorItem::vendoritemname($log['item_id']); ?></td>
            <td><?= ($log['item_status']); ?></td>
            <td><?= ($log['priority']); ?></td>
            <td><?= ($log['item_approved']); ?></td>
            <td>
                <?php
                $url = Url::to(['vendor-item/view', 'id' => $log['item_id']]);
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
    $delivery_data = DeliveryTimeSlot::vendor_delivery_details($model->vendor_id);
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
            $sun = DeliveryTimeSlot::vendor_deliverytimeslot($model->vendor_id,'Sunday');
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
                <?php  $mon = DeliveryTimeSlot::vendor_deliverytimeslot($model->vendor_id,'Monday');
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
                <?php  $tue = DeliveryTimeSlot::vendor_deliverytimeslot($model->vendor_id,'Tuesday');

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
                <?php  $wed = DeliveryTimeSlot::vendor_deliverytimeslot($model->vendor_id,'Wednesday');
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
                <?php  $thu = DeliveryTimeSlot::vendor_deliverytimeslot($model->vendor_id,'Thursday');
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
                <?php  $fri = DeliveryTimeSlot::vendor_deliverytimeslot($model->vendor_id,'Friday');
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
                <?php  $sat = DeliveryTimeSlot::vendor_deliverytimeslot($model->vendor_id,'Saturday');
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

echo Html::hiddenInput('change_package_url', Url::to(['/vendor/changepackage']), ['id' => 'change_package_url']);
echo Html::hiddenInput('package_delete_url', Url::to(['/vendor/packagedelete']), ['id' => 'package_delete_url']);
echo Html::hiddenInput('package_update_url', Url::to(['/vendor/packageupdate']), ['id' => 'package_update_url']);
echo Html::hiddenInput('change_edit_package_url', Url::to(['/vendor/changeeditpackage']), ['id' => 'change_edit_package_url']);
echo Html::hiddenInput('load_package_date_url', Url::to(['/vendor/loadpackagedate']), ['id' => 'load_package_date_url']);

$this->registerCssFile("@web/themes/default/plugins/bootstrap-datepicker/css/datepicker.css");

$this->registerJsFile("@web/themes/default/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js", ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerJsFile("@web/themes/default/js/vendor_view.js", ['depends' => [\yii\web\JqueryAsset::className()]]);
