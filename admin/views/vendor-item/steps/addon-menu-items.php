<?php

use yii\web\view;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\VendorItemMenuItem;

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
	    <li>
	    	<a href="<?= Url::to(['vendor-item/menu-items', 'id' => $model->item_id]) ?>">
	    		Menu
	    	</a>
	    </li>
	    <li class="active">
	    	<a href="<?= Url::to(['vendor-item/addon-menu-items', 'id' => $model->item_id]) ?>">
	    		Addons
	    	</a>
	    </li>
	    <li>
	    	<a href="<?= Url::to(['vendor-item/item-approval', 'id' => $model->item_id]) ?>">
	    		Approval 
	    	</a>
	    </li>
	    <li>
	    	<a href="<?= Url::to(['vendor-item/item-images', 'id' => $model->item_id]) ?>">
	    		Images
	    	</a>
	    </li>
        <li>
            <a href="<?= Url::to(['vendor-item/item-questions', 'id' => $model->item_id]) ?>">
                <?=Yii::t('app','Questions')?>
            </a>
        </li>
	    <li>
	    	<a href="<?= Url::to(['vendor-item/item-themes-groups', 'id' => $model->item_id]) ?>">
	    		Other
	    	</a>
	    </li>
	</ul>

	<div class="tab-content">
		<div class="tab-pane clearfix active">

			<ul id="item_addon_menu_list">

				<?php $addon_menu_count = 0; foreach ($arr_addon_menu as $key => $value) { ?>

				<li>
					<table class="table table-bordered">
						<thead>
							<tr>
								<th colspan="2" class="heading">
									Addon Menu
									<button type="button" class="btn btn-danger btn-remove-menu">
										<i class="fa fa-trash-o"></i>
									</button>
								</th>
							</tr>
							<tr>
								<th>Name</th>
								<th>Name - Ar</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
									<input placeholder="Name" name="addon_menu_item[<?= $addon_menu_count ?>][menu_name]" value="<?= $value->menu_name ?>" class="form-control" />
								</td>
								<td>
									<input placeholder="Name - Arabic" name="addon_menu_item[<?= $addon_menu_count ?>][menu_name_ar]" value="<?= $value->menu_name_ar ?>" class="form-control" />
								</td>
							</tr>
						</tbody>
					</table>

					<table class="table table-bordered">
						<thead>
							<tr>
								<th colspan="7" class="heading">Menu Items</th>
							</tr>
							<tr>
								<th>Image</th>
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

							$arr_menu_item = VendorItemMenuItem::findAll(['menu_id' => $value->menu_id]);

							$addon_menu_count++;

							foreach ($arr_menu_item as $key => $menu_item) { ?>
							<tr>
								<td>									
									<a id="thumb-image<?= $addon_menu_count; ?>" data-toggle="image" class="thumbnail">
										<?php if($menu_item->image) { ?>
										<img src="<?= Yii::getAlias("@s3/vendor_menu_item/thumbnail/").$menu_item->image; ?>" />
										<?php } else { ?>
										<img src="<?= Url::to("@web/themes/default/img/no_image.jpg") ?>" />
										<?php } ?>
									</a>
                      				<input type="hidden" name="addon_menu_item[<?= $addon_menu_count ?>][image]" value="<?php echo $menu_item->image; ?>" id="input-image<?= $addon_menu_count; ?>" />
								</td>
								<td>
									<input placeholder="Name" name="addon_menu_item[<?= $addon_menu_count ?>][menu_item_name]" value="<?= $menu_item->menu_item_name ?>" class="form-control" />
								</td>
								<td>
									<input placeholder="Name - Arabic" name="addon_menu_item[<?= $addon_menu_count ?>][menu_item_name_ar]" value="<?= $menu_item->menu_item_name_ar ?>" class="form-control" />
								</td>
								<td>
									<input placeholder="Price" name="addon_menu_item[<?= $addon_menu_count ?>][price]" value="<?= $menu_item->price ?>" class="form-control" />
								</td>
								<td>
									<input placeholder="Hint" name="addon_menu_item[<?= $addon_menu_count ?>][hint]" value="<?= $menu_item->hint ?>" class="form-control" />
								</td>
								<td>
									<input placeholder="Hint - Ar" name="addon_menu_item[<?= $addon_menu_count ?>][hint_ar]" value="<?= $menu_item->hint_ar ?>" class="form-control" />
								</td>
								<td>
									<button type="button" class="btn btn-danger btn-remove-menu-item">
										<i class="fa fa-trash-o"></i>
									</button>
								</td>
							</tr>
							<?php $addon_menu_count++; } ?>
						</tbody>
						<tfoot>
							<tr>
								<td colspan="7">
									<button type="button" class="btn btn-primary btn-add-addon-menu-item">
										<i class="fa fa-plus"></i> Add addon item
									</button>
								</td>
							</tr>
						</tfoot>
					</table>
				</li>
				<?php } ?>
			</ul>
	
			<br />			

			<button type="button" class="btn btn-primary btn-add-addon-menu">
				<i class="fa fa-plus"></i> Add addon menu
			</button>

			<hr />

			
			<div class="row">
				<div class="col-md-4">
					<a href="<?= Url::to(['vendor-item/menu-items', 'id' => $model->item_id,'_u'=>Yii::$app->request->get('_u')]) ?>" class="btn btn-info pull-left">Prev</a>
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

echo Html::hiddenInput('no_image', Url::to("@web/themes/default/img/no_image.jpg"), ['id' => 'no_image']);

echo Html::hiddenInput('image_upload_url', Url::to(['vendor-item/upload-menu-image']), ['id' => 'image_upload_url']);

echo Html::hiddenInput('addon_menu_count', $addon_menu_count, ['id' => 'addon_menu_count']);

echo Html::hiddenInput('isNewRecord', 0, ['id'=>'isNewRecord']);
echo Html::hiddenInput('item_id', $model->item_id, ['id'=>'item_id']);

$this->registerJsFile("@web/themes/default/js/vendor_item_validation.js?v=1.23", ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerJsFile("@web/themes/default/js/vendor_item_steps/addon_menu.js?v=1.2", ['depends' => [\yii\web\JqueryAsset::className()]]);