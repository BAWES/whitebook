<?php

use yii\web\View;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\City;
use common\models\Location;
use common\models\VendorLocation;

$this->title = 'Bulk edit Delivery Area';
$this->params['breadcrumbs'][] = ['label' => 'Manage Area', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$cities = City::find()
	->where(['status' => 'Active', 'trash' => 'Default'])
	->all();

?>

<div class="vendorlocation-create">

	<?php $form = ActiveForm::begin(); ?>

	<button type="button" class="chk_all btn btn-success">Check all</button>
	
	&nbsp;

	<button type="button" class="unchk_all btn btn-primary">Uncheck all</button>

	&nbsp;

	<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#myModal">Set common delivery charge</button>

	<button type="submit" class="btn btn-success pull-right">Save changes</button>

	<br />
	<br />

	<?php foreach($cities as $city) { ?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3><?= $city->city_name ?></h3>
		</div>
		<div class="panel-body" style="padding: 0;">
			<table class="table table-bordered">
				<thead>
					<tr>
						<th><input type="checkbox" class="chk_all_city_area" /></th>
						<th>Area</th>
						<th>Delivery charge</th>
					</tr>
				</thead>
				<tbody>			
					<?php 

					$locations = Location::find()
						->where(['status' => 'Active', 'trash' => 'Default', 'city_id' => $city->city_id])
						->all();

					foreach($locations as $location) { ?>
					<tr>
						<?php  

						$vl = VendorLocation::find()
							->where(['vendor_id' => $vendor_id, 'area_id' => $location->id])
							->one();

						if($vl) {
							$checked = 'checked';
							$delivery_price = $vl->delivery_price;
						}else{
							$checked = '';
							$delivery_price = '';
						}

						?>

						<td>
							<input type="checkbox" name="selected[<?= $location->id ?>]" value="1" <?= $checked ?>  class="chk_area" />
						</td>
						<td><?= $location->location ?></td>
						<td>
							<input type="text" name="area[<?= $location->id ?>]" value="<?= $delivery_price ?>" 
								class="txt_delivery_price" />
						</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>   
		</div>	
	</div><!-- END .box -->
	<?php } ?><!-- END foreach city -->

	<?php ActiveForm::end(); ?>

</div>

<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Set common delivery charge </h4>
      </div>
      <div class="modal-body">
        <input type="text" class="form-control" />
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary btn-set-common">Submit</button>
      </div>
    </div>

  </div>
</div>

<?php 

$this->registerJs("

	jQuery('.chk_all_city_area').click(function(){
		jQuery(this).parents('table').find('.chk_area').prop('checked', jQuery(this).prop('checked'));
	});

	jQuery('.chk_all').click(function(){
		jQuery('.chk_area').prop('checked', true);
	});

	jQuery('.unchk_all').click(function(){
		jQuery('.chk_area').prop('checked', false);
	});

	jQuery('.btn-set-common').click(function(){
		jQuery('.txt_delivery_price').val(jQuery('#myModal input').val());
		jQuery('#myModal').modal('hide');
		jQuery('#myModal input').val('');
	});

", View::POS_READY);


$this->registerCss("

	#myModal .modal-content{
		max-width: 400px;
    	margin: auto;
	}
    
	html{
		height: auto;
	}
");