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
<style>
    .delivery_days div{  border-right: 1px solid #f2f2f2;width: 147px!important;text-align: center;}
    .vendor-admin-new .day_head {  width: 145px;  }
    .delivery_days >div ul li {  margin-bottom: 8px;  }
</style>
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
        <li><a href="#4" data-toggle="tab">Vendor Item Details</a></li>
        <li><a href="#5" data-toggle="tab">Delivery Times</a></li>
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
                [
                    'label'=>'vendor_return_policy',
                    'value'=>strip_tags($model->vendor_return_policy)
                ],
                'vendor_public_email',
                'vendor_contact_name',
                'vendor_contact_email',
                'vendor_contact_number',
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
        <ul style="margin-left: 0px;padding: 8px 13px;">
            <?php
            $sun = \common\models\VendorWorkingTiming::findAll(['vendor_id'=>$model->vendor_id,'working_day'=>'Sunday']);
            foreach ($sun as $key => $value) {
            $start = date('g:ia', strtotime($value['working_start_time']));
            $end =  date('g:ia', strtotime($value['working_end_time']));
            echo '<li>'. $start .' - '. $end .'</li>';
            } ?>
        </ul>
        </div>
        <div class="mon">
            <ul style="margin-left: 0px;padding: 8px 13px;">
                <?php
                $sun = \common\models\VendorWorkingTiming::findAll(['vendor_id'=>$model->vendor_id,'working_day'=>'Monday']);
                foreach ($sun as $key => $value) {
                    $start = date('g:ia', strtotime($value['working_start_time']));
                    $end =  date('g:ia', strtotime($value['working_end_time']));
                    echo '<li>'. $start .' - '. $end .'</li>';
                } ?>
            </ul>
        </div>
        <div class="tue">
            <ul style="margin-left: 0px;padding: 8px 13px;">
                <?php
                $sun = \common\models\VendorWorkingTiming::findAll(['vendor_id'=>$model->vendor_id,'working_day'=>'Tuesday']);
                foreach ($sun as $key => $value) {
                    $start = date('g:ia', strtotime($value['working_start_time']));
                    $end =  date('g:ia', strtotime($value['working_end_time']));
                    echo '<li>'. $start .' - '. $end .'</li>';
                } ?>
            </ul>
        </div>
        <div class="wed">
            <ul style="margin-left: 0px;padding: 8px 13px;">
                <?php
                $sun = \common\models\VendorWorkingTiming::findAll(['vendor_id'=>$model->vendor_id,'working_day'=>'Wednesday']);
                foreach ($sun as $key => $value) {
                    $start = date('g:ia', strtotime($value['working_start_time']));
                    $end =  date('g:ia', strtotime($value['working_end_time']));
                    echo '<li>'. $start .' - '. $end .'</li>';
                } ?>
            </ul>
        </div>
        <div class="thu">
            <ul style="margin-left: 0px;padding: 8px 13px;">
                <?php
                $sun = \common\models\VendorWorkingTiming::findAll(['vendor_id'=>$model->vendor_id,'working_day'=>'Thursday']);
                foreach ($sun as $key => $value) {
                    $start = date('g:ia', strtotime($value['working_start_time']));
                    $end =  date('g:ia', strtotime($value['working_end_time']));
                    echo '<li>'. $start .' - '. $end .'</li>';
                } ?>
            </ul>
        </div>
        <div class="fri">
            <ul style="margin-left: 0px;padding: 8px 13px;">
                <?php
                $sun = \common\models\VendorWorkingTiming::findAll(['vendor_id'=>$model->vendor_id,'working_day'=>'Friday']);
                foreach ($sun as $key => $value) {
                    $start = date('g:ia', strtotime($value['working_start_time']));
                    $end =  date('g:ia', strtotime($value['working_end_time']));
                    echo '<li>'. $start .' - '. $end .'</li>';
                } ?>
            </ul>
        </div>
        <div class="sat">
            <ul style="margin-left: 0px;padding: 8px 13px;">
                <?php
                $sun = \common\models\VendorWorkingTiming::findAll(['vendor_id'=>$model->vendor_id,'working_day'=>'Saturday']);
                foreach ($sun as $key => $value) {
                    $start = date('g:ia', strtotime($value['working_start_time']));
                    $end =  date('g:ia', strtotime($value['working_end_time']));
                    echo '<li>'. $start .' - '. $end .'</li>';
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

$this->registerJsFile("@web/themes/default/js/vendor_view.js", ['depends' => [\yii\web\JqueryAsset::className()]]);
