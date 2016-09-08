<?php 

use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;

$this->title = 'Commission Report';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="package-search">

    <?php $form = ActiveForm::begin([
        'action' => ['commission'],
        'method' => 'get',
    ]); ?>

    <div class="row">
	    <div class="col-md-3">
		    <div class="form-group">
				<label class="control-label">Date start</label>
				<div class="controls">
					<input type="text" class="form-control" name="date_start" value="<?= $date_start ?>" />
				</div>
			</div>
		</div>

		<div class="col-md-3">
			<div class="form-group">
				<label class="control-label">Date end</label>
				<div class="controls">
					<input type="text" class="form-control" name="date_end" value="<?= $date_end ?>" />
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
				<label class="control-label">Group by</label>
				<div class="controls">
					<select class="form-control" name="group_by">
						<?php 

						foreach ($groups as $key => $value) { 
					
							if($key == $group_by) {
								$selected = 'selected';
							} else {
								$selected = '';
							} ?>
							<option value="<?= $key ?>" <?= $selected ?>>
								<?= $value ?>
							</option>	
						<?php } ?>
					</select>
				</div>
			</div>

		    <div class="form-group">
		        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
		    </div>
	    </div><!-- END .col-md-3 -->
	</div><!-- END .row -->

    <?php ActiveForm::end(); ?>

</div>

<div class="package-index">

	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<th>Date Start</th>
				<th>Date End</th>
				<th>Total Suborder</th>
				<th>Total Commission</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($result as $row) { ?>
			<tr>
				<td><?= date('Y-m-d', strtotime($row['date_start'])) ?></td>
				<td><?= date('Y-m-d', strtotime($row['date_end'])) ?></td>
				<td><?= $row['suborder_count'] ?></td>
				<td><?= $row['commission'] ?> KWD</td>
			</tr>
			<?php } ?>
		</tbody>
	</table>

	<div class="pull-right">
		<?= LinkPager::widget([
			'pagination' => $pagination,
		]); ?>
	</div>
</div>	