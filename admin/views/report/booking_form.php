<?php 

use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;
use yii\web\View;

$this->title = 'Booking Report';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="package-search">

    <?php $form = ActiveForm::begin([
        'action' => ['booking-report'],
        'method' => 'get',
        'options' => [
        	'target' => '_blank'
        ]
    ]); ?>

    <div class="row">
	    <div class="col-md-3">
		    <div class="form-group">
				<label class="control-label">Date start</label>
				<div class="controls">
					<input type="text" class="form-control datepicker" name="date_start" value="<?= $date_start ?>" />
				</div>
			</div>
		</div>

		<div class="col-md-3">
			<div class="form-group">
				<label class="control-label">Date end</label>
				<div class="controls">
					<input type="text" class="form-control datepicker" name="date_end" value="<?= $date_end ?>" />
				</div>
			</div>
		</div>

		<div class="col-md-3">
			<div class="form-group">
				<label class="control-label">Vendor</label>
				<div class="controls">
					<select class="form-control" name="vendor_id">
						<option></option>
						<?php 

						foreach ($vendors as $vendor) { 
							if($vendor->vendor_id == $vendor_id) {
								$selected = 'selected';
							} else {
								$selected = '';
							} ?>
							<option value="<?= $vendor->vendor_id ?>" <?= $selected ?>>
								<?= $vendor->vendor_name ?>
							</option>	
						<?php } ?>
					</select>
				</div>
			</div>
		</div>

		<div class="col-md-3">
			<div class="form-group">
				<label>&nbsp;</label>
		        <?= Html::submitButton('Download', ['class' => 'btn btn-primary']) ?>
		    </div>
	    </div><!-- END .col-md-3 -->
	</div><!-- END .row -->

    <?php ActiveForm::end(); ?>

</div>

<div class="package-index">

</div>	

<?php 

$this->registerJsFile('@web/themes/default/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerCssFile('@web/themes/default/plugins/bootstrap-datepicker/css/datepicker.min.css', ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerJs("
	jQuery('.datepicker').datepicker({
		format: 'yyyy-mm-dd',
	});
", View::POS_READY);
