<?php

use yii\web\view;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\VendorDraftItemMenuItem;

$this->title = 'Update vendor item';
$this->params['breadcrumbs'][] = ['label' => 'Vendor items', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';

?>

<div class="vendoritem-update">

<div class="col-md-12 col-sm-12 col-xs-12">

<?php $form = ActiveForm::begin(['options' => ['enctype'=>'multipart/form-data']]); ?>

<div class="loadingmessage" style="display: none;">
	<p>
    	<?= Html::img(Yii::getAlias('@web/themes/default/img/loading.gif'), ['class'=>'','width'=>'64px','height'=>'64px','id'=>'loading','alt'=>'loading']);?>
	</p>
</div>

<div class="tabbable">
	<ul class="nav nav-tabs">
	    <li>
	    	<a href="<?= Url::to(['vendor-item/update', 'id' => $model->item_id]) ?>">
	    		Item Info 
	    	</a>
	    </li>
	    <li>
	    	<a href="<?= Url::to(['vendor-item/item-description', 'id' => $model->item_id]) ?>">
	    		Description
	    	</a>
	    </li>
	    <li>
	    	<a href="<?= Url::to(['vendor-item/item-price', 'id' => $model->item_id]) ?>">
	    		Price and Inventory
	    	</a>
	    </li>
	    <li class="active">
	    	<a href="<?= Url::to(['vendor-item/menu-items', 'id' => $model->item_id]) ?>">
	    		Menu
	    	</a>
	    </li>
	    <li>
	    	<a href="<?= Url::to(['vendor-item/addon-menu-items', 'id' => $model->item_id]) ?>">
	    		Addons
	    	</a>
	    </li>
        <li>
            <a href="<?= Url::to(['vendor-item/item-questions', 'id' => $model->item_id]) ?>">
                <?=Yii::t('app','Questions')?>
            </a>
        </li>
	    <li>
	    	<a href="<?= Url::to(['vendor-item/item-images', 'id' => $model->item_id]) ?>">
	    		Images
	    	</a>
	    </li>
	</ul>

	<div class="tab-content">
		<div class="tab-pane clearfix active">

			<?= $form->field($model, 'allow_special_request')->checkbox(); ?>

			<?= $form->field($model, 'have_female_service')->checkbox(); ?>
			
			<ul id="item_menu_list">
				<?php $menu_count = 0; foreach ($arr_menu as $key => $value) { ?>

				<li>
					<table class="table table-bordered">
						<thead>
							<tr>
								<th colspan="5" class="heading">
									Menu
									<button type="button" class="btn btn-danger btn-remove-menu">
										<i class="fa fa-trash-o"></i>
									</button>
								</th>
							</tr>
							<tr>
								<th>Name</th>
								<th>Name - Ar</th>
								<th>Min Qty</th>
								<th>Max Qty</th>
								<th>Qty Type</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td class="required">
									<input name="menu_item[<?= $menu_count ?>][menu_id]" value="<?= $value->menu_id ?>" type="hidden" />

									<input placeholder="Name" name="menu_item[<?= $menu_count ?>][menu_name]" value="<?= $value->menu_name ?>" class="form-control txt_menu_name" />
								</td>
								<td class="required">
									<input placeholder="Name - Arabic" name="menu_item[<?= $menu_count ?>][menu_name_ar]" value="<?= $value->menu_name_ar ?>" class="form-control txt_menu_name_ar" />
								</td>
								<td>
									<input placeholder="Min. Qty" name="menu_item[<?= $menu_count ?>][min_quantity]" value="<?= $value->min_quantity ?>" class="form-control txt_min_quantity" />
								</td>
								<td>
									<input placeholder="Max. Qty" name="menu_item[<?= $menu_count ?>][max_quantity]" value="<?= $value->max_quantity ?>" class="form-control txt_max_quantity" />
								</td>
								<td>
									<select name="menu_item[<?= $menu_count ?>][quantity_type]" class="form-control">
										<?php if($value->quantity_type == 'selection') { ?>
										<option selected>selection</option>
										<option>checkbox</option>
										<?php } else { ?>
										<option>selection</option>
										<option selected>checkbox</option>
										<?php } ?>
									</select>
								</td>
							</tr>
						</tbody>
					</table>

					<table class="table table-bordered">
						<thead>
							<tr>
								<th colspan="6" class="heading">Menu Items</th>
							</tr>
							<tr>
								<th>Name</th>
								<th>Name - Ar</th>
								<th>Price</th>
								<th>Hint</th>
								<th>Hint - Ar</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<?php 

							$arr_menu_item = VendorDraftItemMenuItem::findAll(['draft_menu_id' => $value->draft_menu_id]);

							$menu_count++;

							foreach ($arr_menu_item as $key => $menu_item) { ?>
							<tr>
								<td class="required">

									<input name="menu_item[<?= $menu_count ?>][menu_item_id]" value="<?= $menu_item->menu_item_id ?>" type="hidden" />

									<input placeholder="Name" name="menu_item[<?= $menu_count ?>][menu_item_name]" value="<?= $menu_item->menu_item_name ?>" class="txt_menu_item_name form-control" />
								</td>
								<td class="required">
									<input placeholder="Name - Arabic" name="menu_item[<?= $menu_count ?>][menu_item_name_ar]" value="<?= $menu_item->menu_item_name_ar ?>" class="form-control txt_menu_item_name_ar" />
								</td>
								<td>
									<input placeholder="Hint" name="menu_item[<?= $menu_count ?>][price]" value="<?= $menu_item->price ?>" class="form-control txt_price" />
								</td>
								<td>
									<input placeholder="Hint" name="menu_item[<?= $menu_count ?>][hint]" value="<?= $menu_item->hint ?>" class="form-control txt_hint" />
								</td>
								<td>
									<input placeholder="Hint - Ar" name="menu_item[<?= $menu_count ?>][hint_ar]" value="<?= $menu_item->hint_ar ?>" class="form-control txt_hint_ar" />
								</td>
								<td>
									<button type="button" class="btn btn-danger btn-remove-menu-item">
										<i class="fa fa-trash-o"></i>
									</button>
								</td>
							</tr>
							<?php $menu_count++; } ?>
						</tbody>
						<tfoot>
							<tr>
								<td colspan="6">
									<button type="button" class="btn btn-primary btn-add-menu-item">
										<i class="fa fa-plus"></i> Add Item
									</button>
								</td>
							</tr>
						</tfoot>
					</table>
				</li>
				<?php } ?>
			</ul>
	
			<br />			

			<button type="button" class="btn btn-primary btn-add-menu">
				<i class="fa fa-plus"></i> Add menu
			</button>

			<hr />

			<div class="row">
				<div class="col-md-4">
					<a href="<?= Url::to(['vendor-item/item-price', 'id' => $model->item_id]) ?>" class="btn btn-info pull-left">Prev</a>
				</div>
				<div class="col-md-4 text-center">
					<input type="submit" name="complete" class="btn btn-info" value="Complete" />
				</div>
				<div class="col-md-4">
					<input type="submit" name="btnNext" class="btn btn-info pull-right" value="Next" />
				</div>
			</div>


		</div><!-- END .tab-pane -->
	</div><!-- END .tab-content -->
</div><!-- END .tabbable -->

<?php 

ActiveForm::end(); 

echo Html::hiddenInput('menu_count', $menu_count, ['id' => 'menu_count']);

echo Html::hiddenInput('isNewRecord', 0, ['id'=>'isNewRecord']);
echo Html::hiddenInput('item_id', $model->item_id, ['id'=>'item_id']);

$this->registerJsFile("@web/themes/default/js/vendor_item_validation.js?v=1.24", ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerJsFile("@web/themes/default/js/vendor_item_steps/menu.js?v=1.3", ['depends' => [\yii\web\JqueryAsset::className()]]);