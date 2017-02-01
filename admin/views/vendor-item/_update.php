<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use kartik\sortable\Sortable;
use admin\models\Vendor;
use common\models\VendorItemQuestion;
use common\models\VendorItemMenuItem;

use yii\web\view;
use kartik\file\FileInput;

$request = Yii::$app->request;
$itemType  = \yii\helpers\ArrayHelper::map($itemType,'type_id','type_name');
$themelist = \yii\helpers\ArrayHelper::map($themes,'theme_id','theme_name');

function cmp($a, $b)
{
	return strcmp($a["vendorimage_sort_order"], $b["vendorimage_sort_order"]);
}

?>
<div class="col-md-12 col-sm-12 col-xs-12">

<?php $form = ActiveForm::begin(['options'=>['enctype'=>'multipart/form-data']]); ?>

<div class="loadingmessage" style="display: none;">
	<p>
    	<?= Html::img(Yii::getAlias('@web/themes/default/img/loading.gif'), ['class'=>'','width'=>'64px','height'=>'64px','id'=>'loading','alt'=>'loading']);?>
	</p>
</div>

<!-- Begin Twitter Tabs-->
<div class="tabbable">
	<ul class="nav nav-tabs">
	    <li class="active"><a href="#1" data-toggle="tab" id="tab_1">Item Info </a></li>
	    <li><a href="#2" id="tab_2">Item description</a></li>
	    <li><a href="#3" id="tab_3">Item price </a></li>
	    <li><a href="#4" id="tab_4">Menu items</a></li>
	    <li><a href="#5" id="tab_5">Addons</a></li>
	    <li><a href="#6" id="tab_6">Approval </a></li>
	    <li><a href="#7" id="tab_7">Images</a></li>
	    <li><a href="#8" id="tab_8">Other</a></li>
	   
	    <?php if($model->item_for_sale =='Yes') {?>
	    	<li><a href="#9" id="tab_9"> Questions </a></li>
	    <?php } ?>
	</ul>

	<div class="tab-content">
		<!-- Begin First Tab -->
		<div class="tab-pane active clearfix" id="1">

			<?= Html::activeHiddenInput($model, 'version', ['id' => 'version']); ?>
			
			<input type="hidden" name="item_id" value="<?= $model->item_id ?>" />
			
			<?= $form->field($model, 'vendor_id')
					->dropDownList([
						$model->vendor->vendor_id => $model->vendor->vendor_name
					], 
					[
						'prompt' => 'Select...',
						'disabled' => 'disabled'
					]) ?>

			<?= $form->field($model,'vendor_id')->hiddenInput()->label(false); ?>

			<?= $form->field($model, 'item_name')->textInput(['maxlength' => 128,'autocomplete' => 'off']); ?>

			<?= $form->field($model, 'item_name_ar')->textInput(['maxlength' => 128,'autocomplete' => 'off']); ?>
			
			<div class="field-category-list">
				<label>Categories</label>
				<table class="table table-bordered table-item-category-list">
					<thead>
						<tr>
							<td>Main categories</td>
							<td>Sub categories</td>
							<td>Child categories</td>
							<td></td>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($item_child_categories as $key => $value) { 

							$child_category = $value->category;
							$sub_category = $child_category->parentCategory;
							$main_category = $sub_category->parentCategory;							

							?>
						<tr>
							<td>
								<?php if($main_category) { ?>
									<?= $main_category->category_name ?>	
									<input type="hidden" name="category[]" value="<?= $main_category->category_id ?>" />
								<?php } ?>
							</td>
							<td>
								<?php if($sub_category) { ?>
									<?= $sub_category->category_name ?>	
									<input type="hidden" name="category[]" value="<?= $sub_category->category_id ?>" />
								<?php } ?>
							</td>
							<td>
								<?php if($child_category) { ?>
									<?= $child_category->category_name ?>	
									<input type="hidden" name="category[]" value="<?= $child_category->category_id ?>" />
								<?php } ?>
							</td>
							<td>
								<button class="btn btn-danger btn-remove-cat"><i class="fa fa-trash-o"></i></button>
							</td>
						</tr>
						<?php } ?>
					</tbody>
				</table>

				<table class="table table-bordered table-category-list">
					<thead>
						<tr>
							<td>Main categories</td>
							<td>Sub categories</td>
							<td>Child categories</td>
						</tr>
						<tr>
							<td>
								<input placeholder="Search" class="form-control txt-main-cat-search" />
							</td>
							<td>
								<input placeholder="Search" class="form-control txt-sub-cat-search" />
							</td>
							<td>
								<input placeholder="Search" class="form-control txt-child-cat-search" />
							</td>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class="main-category-list">
								<div class="chk_wrapper">
									<?php foreach($main_categories as $key => $value) { ?>
									<div class="radio" data-name="<?= $value['category_name'] ?>"> 
										<input type="radio" name="main_category" value="<?= $value['category_id'] ?>" id="main_cat_<?= $value['category_id'] ?>" /> 
										<label for="main_cat_<?= $value['category_id'] ?>"> 
											<?= $value['category_name'] ?>
										</label> 
									</div> 
									<?php } ?>
								</div>
							</td>
							<td class="sub-category-list">
								<div class="chk_wrapper">
								</div>
							</td>
							<td class="child-category-list">
								<div class="chk_wrapper">
								</div>
							</td>
						</tr>
					</tbody>
					<tfoot>
						<tr>
							<td></td>
							<td>
								<button type="button" class="btn btn-primary btn_sub_category_modal" type="button">
									<i class="fa fa-plus"></i> Add 
								</button>
							</td>
							<td>
								<button type="button" class="btn btn-primary btn_child_category_modal" type="button">
									<i class="fa fa-plus"></i> Add 
								</button>
							</td>
						</tr>
					</tfoot>
				</table>				
			</div>

			<div class="form-group" >
			<div class="col-lg-6 text-center"><?= Html::submitButton($model->isNewRecord ? 'Complete' : 'Complete', ['class' => $model->isNewRecord ? 'btn btn-success complete' : 'btn btn-primary complete pull-left']) ?></div>
			<div class="col-lg-6"><input type="button" name="btnPrevious" class="btnNext btn btn-info" value="Next"></div>
			</div>
		</div>
		<!--End First Tab -->

		<!--BEGIN second Tab -->
		<div class="tab-pane clearfix" id="2">
			
			<div class="alert alert-info">
				Inline css will be removed from editor for item description, item additional info, price description and customization description.
				<button class="close" data-dismiss="alert">x</button>
			</div>

			<?= $form->field($model, 'type_id')->dropDownList($itemType, ['prompt'=>'Select...']) ?>

			<?= $form->field($model, 'item_description')
					->label('Item description'.Html::tag('span', '*',['class'=>'required']))
					->textarea(['maxlength' => 128]); ?>
	
			<?= $form->field($model, 'item_description_ar')
					->label('Item description - Arabic '.Html::tag('span', '*',['class'=>'required']))
					->textarea(['maxlength' => 128]); ?>

			<?= $form->field($model, 'item_additional_info')->textarea(['maxlength' => 128]); ?>

			<?= $form->field($model, 'item_additional_info_ar')->textarea(['maxlength' => 128]); ?>

			<div class="col-lg-4">
				<input type="button" name="btnPrevious" class="btnPrevious btn btn-info" value="Prev" />
			</div>
			<div class="col-lg-4 text-center">
				<?= Html::submitButton($model->isNewRecord ? 'Complete' : 'Complete', ['class' => $model->isNewRecord ? 'btn btn-success complete' : 'btn btn-primary complete']) ?>
			</div>
			<div class="col-lg-4">
				<input type="button" name="btnNext" class="btnNext btn btn-info" value="Next" />
			</div>
		</div>
		<!--End Second Tab -->

		<!--BEGIN Third Tab -->
		<div class="tab-pane clearfix" id="3">
			<input type="hidden" id="test" value="0" name="tests">
			
			<?php
                $model->item_for_sale = ($model->item_for_sale == 'Yes') ? 1:0;
                echo $form->field($model, 'item_for_sale')->checkbox(['Yes' => 'Yes']); ?>

			<?= $form->field($model, 'item_amount_in_stock')
				->label('Item Number of Stock '.Html::tag('span', '*',['class'=>'required mandatory']))
				->textInput(['maxlength' => 128]); ?>

			<?= $form->field($model, 'item_default_capacity')
				->label('Item Default Capacity '.Html::tag('span', '*',['class'=>'required mandatory']))
				->textInput(['maxlength' => 128]); ?>

			<?= $form->field($model, 'item_how_long_to_make')
				->label('No of days delivery '.Html::tag('span', '*',['class'=>'required mandatory']))
				->textInput(['maxlength' => 128]); ?>

			<?= $form->field($model, 'item_minimum_quantity_to_order')
				->label('Item Minimum Quantity to Order '.Html::tag('span', '*',['class'=>'required mandatory']))
				->textInput(['maxlength' => 128]); ?>

			<?= $form->field($model, 'item_price_per_unit', 
				['options' => [
					'class' => 'single_price'
				]])
				->textInput([
					'maxlength' => 128
				]); ?>

			<div class="form-group multiple_price" style="padding: 5px;  font-size: 14px;"><div class="multi_pricing">Price Chart </div>

					<?php $t=0;
					foreach ($itemPricing as $value) {  ?>

					<div class="controls<?= $t; ?>">
						<input type="text" id="vendoritem-item_from" class="form-control from_range_<?= $t; ?>" name="vendoritem-item_price[from][]" multiple = "multiple" Placeholder="From Quantit" value="<?= $value['range_from'];?>" />

						<input type="text" id="vendoritem-item_to" class="form-control to_range_<?= $t; ?>" name="vendoritem-item_price[to][]" multiple = "multiple" Placeholder="To Quantity" value="<?= $value['range_to'];?>" />

						<input type="text" id="item_price_per_unit" class="form-control price_kd_<?= $t; ?>" name="vendoritem-item_price[price][]" multiple = "multiple" Placeholder="Price" value="<?= $value['pricing_price_per_unit'];?>">KD

						<input type="button" name="remove" id="remove" value="Remove" class="remove_price" onClick="removePrice(this)" />
					</div>

					<?php $t++; }?>
					<input type="button" class="add_price" name="addprice" id="addprice" value="Add more" onClick="addPrice(this);" />
				</div>

				<?= $form->field($model, 'item_price_description')->textarea(['maxlength' => 128]); ?>

				<?= $form->field($model, 'item_price_description_ar')->textarea(['maxlength' => 128]); ?>

				<?= $form->field($model, 'item_customization_description',
						['options' => ['class' => 'form-group custom_description']]
					)->textarea(
						['maxlength' => 128]
					); ?>

				<?= $form->field($model, 'item_customization_description_ar',
						['options' => ['class' => 'form-group custom_description_ar']]
					)->textarea(
						['maxlength' => 128]
					); ?>

			<div class="col-lg-4">
				<input type="button" name="btnPrevious" class="btnPrevious btn btn-info" value="Prev" />
			</div>

			<div class="col-lg-4 text-center">
				<?= Html::submitButton($model->isNewRecord ? 'Complete' : 'Complete', ['class' => $model->isNewRecord ? 'btn btn-success complete' : 'btn btn-primary complete']) ?>
			</div>
			
			<div class="col-lg-4">
				<input type="button" name="btnNext" class="btnNext btn btn-info" value="Next" />
			</div>

		</div>
		<!--End third Tab -->

		<div class="tab-pane clearfix tab_menu_items" id="4">

			<?= $form->field($model, 'quantity_label')->dropDownList([
					'Quantity' => 'Quantity',
					'Serve' => 'Serve'
				]); ?>

			<?= $form->field($model, 'set_up_time'); ?>

			<?= $form->field($model, 'set_up_time_ar'); ?>

			<?= $form->field($model, 'max_time'); ?>

			<?= $form->field($model, 'max_time_ar'); ?>

			<?= $form->field($model, 'requirements'); ?>

			<?= $form->field($model, 'requirements_ar'); ?>

			<?= $form->field($model, 'min_order_amount'); ?>

			<?= $form->field($model, 'allow_special_request')->checkbox(); ?>

			<?= $form->field($model, 'have_female_service')->checkbox(); ?>
			
			<ul id="item_menu_list">
				<?php $menu_count = 0; foreach ($arr_menu as $key => $value) { ?>

				<li>
					<table class="table table-bordered">
						<thead>
							<tr>
								<th colspan="4" class="heading">
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
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
									<input placeholder="Name" name="menu_item[<?= $menu_count ?>][menu_name]" value="<?= $value->menu_name ?>" class="form-control" />
								</td>
								<td>
									<input placeholder="Name - Arabic" name="menu_item[<?= $menu_count ?>][menu_name_ar]" value="<?= $value->menu_name_ar ?>" class="form-control" />
								</td>
								<td>
									<input placeholder="Min. Qty" name="menu_item[<?= $menu_count ?>][min_quantity]" value="<?= $value->min_quantity ?>" class="form-control" />
								</td>
								<td>
									<input placeholder="Max. Qty" name="menu_item[<?= $menu_count ?>][max_quantity]" value="<?= $value->max_quantity ?>" class="form-control" />
								</td>
							</tr>
						</tbody>
					</table>

					<table class="table table-bordered">
						<thead>
							<tr>
								<th colspan="5" class="heading">Menu Items</th>
							</tr>
							<tr>
								<th>Name</th>
								<th>Name - Ar</th>
								<th>Hint</th>
								<th>Hint - Ar</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<?php 

							$arr_menu_item = VendorItemMenuItem::findAll(['menu_id' => $value->menu_id]);

							$menu_count++;

							foreach ($arr_menu_item as $key => $menu_item) { ?>
							<tr>
								<td>
									<input placeholder="Name" name="menu_item[<?= $menu_count ?>][menu_item_name]" value="<?= $menu_item->menu_item_name ?>" class="form-control" />
								</td>
								<td>
									<input placeholder="Name - Arabic" name="menu_item[<?= $menu_count ?>][menu_item_name_ar]" value="<?= $menu_item->menu_item_name_ar ?>" class="form-control" />
								</td>
								<td>
									<input placeholder="Hint" name="menu_item[<?= $menu_count ?>][hint]" value="<?= $menu_item->hint ?>" class="form-control" />
								</td>
								<td>
									<input placeholder="Hint - Ar" name="menu_item[<?= $menu_count ?>][hint_ar]" value="<?= $menu_item->hint_ar ?>" class="form-control" />
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
								<td colspan="5">
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

			<div class="col-lg-4">
				<input type="button" name="btnPrevious" class="btnPrevious btn btn-info" value="Prev" />
			</div>
			<div class="col-lg-4 text-center">
				<?= Html::submitButton($model->isNewRecord ? 'Complete' : 'Complete', ['class' => $model->isNewRecord ? 'btn btn-success complete' : 'btn btn-primary complete']) ?>
			</div>
			<div class="col-lg-4">
				<input type="button" name="btnNext" class="btnNext btn btn-info" value="Next" />
			</div>
		</div><!-- END .tab_menu_items -->

		<div class="tab-pane clearfix tab_addon_menu_items" id="5">

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

							$arr_menu_item = VendorItemMenuItem::findAll(['menu_id' => $value->menu_id]);

							$addon_menu_count++;

							foreach ($arr_menu_item as $key => $menu_item) { ?>
							<tr>
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
								<td colspan="6">
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

			<div class="col-lg-4">
				<input type="button" name="btnPrevious" class="btnPrevious btn btn-info" value="Prev" />
			</div>
			<div class="col-lg-4 text-center">
				<?= Html::submitButton($model->isNewRecord ? 'Complete' : 'Complete', ['class' => $model->isNewRecord ? 'btn btn-success complete' : 'btn btn-primary complete']) ?>
			</div>
			<div class="col-lg-4">
				<input type="button" name="btnNext" class="btnNext btn btn-info" value="Next" />
			</div>

		</div><!-- END .tab_addon_menu_items -->

		<div class="tab-pane clearfix" id="6">
			
			<?= $form->field($model, 'item_approved')
					->dropDownList([ 'Pending' => 'Pending','Yes' => 'Yes', 'Rejected'=>'Rejected']); ?>

			<?php 

				$model->item_status = ($model->item_status == 'Active') ? 1 : 0;
                
                echo $form->field($model, 'item_status')->checkbox(['Value' => true]); ?>

			<div class="col-lg-4">
				<input type="button" name="btnPrevious" class="btnPrevious btn btn-info" value="Prev" />
			</div>
			<div class="col-lg-4 text-center">
				<?= Html::submitButton($model->isNewRecord ? 'Complete' : 'Complete', ['class' => $model->isNewRecord ? 'btn btn-success complete' : 'btn btn-primary complete']) ?>
			</div>
			<div class="col-lg-4">
				<input type="button" name="btnNext" class="btnNext btn btn-info" value="Next" />
			</div>
		</div>
		<!--End fourth Tab -->

		<div class="tab-pane clearfix" id="7">
			<div class="file-block alert alert-danger" style="color:red; display: none;"> Please upload aleast a file</div>

			<div class="alert alert-info">
				<button class="close" data-dismiss="alert"></button>
				Steps 
				<ul>
					<li>Select image by clicking on "Choose File" from top left side.</li>
					<li>Move image in image preview area to get required image area, if image bigger than 450x450.</li>
					<li>
						Click on Upload button below preview area to upload image, wait for seconds. Image will get listed in right size.
					</li>
				</ul>
			</div>

			<div class="row">
				<div class="col-lg-6">
					
					<p>Select, crop and upload image.</p>

					<div class="image-editor">
				        <input type="file" class="cropit-image-input" />
				        <p style="color: red;">Minimum image size : 450 x 450</p>
				        <div class="cropit-preview"></div>
				        <div class="image-size-label">
				          Resize image
				        </div>
				        <input type="range" class="cropit-image-zoom-input">
				        <button type="button" class="btn btn-primary btn-crop-upload">Upload</button>
				    </div>
				</div>
				<div class="col-lg-6">
					<p>Uploaded image list</p>
					<table class="table table-bordered table-item-image">
						<thead>
							<tr>
								<th>Image</th>
								<th>Sort order</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
						<?php $image_count = 0 ; foreach ($model->images as $key => $value) { ?>
						<tr>
							<td>
								<div class="vendor_image_preview">
									<img src="<?= Yii::getAlias("@s3/vendor_item_images_210/").$value->image_path ?>" />
								</div>
								<input type="hidden" name="images[<?= $image_count ?>][image_path]" value="<?= $value->image_path ?>" />
							</td>
							<td>
								<input type="text" name="images[<?= $image_count ?>][vendorimage_sort_order]" value="<?= $value->vendorimage_sort_order ?>" />
							</td>
							<td>
								<button class="btn btn-danger btn-delete-image">
									<i class="fa fa-trash"></i>
								</button>
							</td>
						</tr>
						<?php $image_count++; } ?>
						</tbody>
					</table>
				</div>
			</div>

			<hr />

			<div class="row">
				<div class="col-lg-4"><input type="button" name="btnPrevious" class="btnPrevious btn btn-info" value="Prev"></div>
				<div class="col-lg-4 text-center"><?= Html::submitButton($model->isNewRecord ? 'Complete' : 'Complete', ['class' => $model->isNewRecord ? 'btn btn-success complete' : 'btn btn-primary complete']) ?></div>
				<div class="col-lg-4"><input type="button" name="btnNext" class="btnNext btn btn-info" value="Next"></div>
			</div>
		</div>
		<!--End fifth Tab -->

		<div class="tab-pane clearfix" id="8">
			<div class="form-group clearfix padding-top-bottom">
				<?php echo $form->field($model, 'themes')->checkboxlist($themelist); ?>
				<div class="clearfix"></div>
				<div class="theme_form_wrapper row hidden">
					<hr />
					<div class="col-lg-4">
						<div class="form-group">
							<label class="control-label">Theme Name</label>
							<div class="controls">
								<input type="text" class="form-control" name="theme_name" maxlength="128">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label">Theme Name - Arabic</label>
							<div class="controls">
								<input type="text" class="form-control" name="theme_name_ar" maxlength="128">
							</div>
						</div>
						<button class="btn btn-primary btn-add-theme" type="button">Add</button>
						<button class="btn btn-default btn-add-theme-calcle" type="button">Cancel</button>
					</div>
				</div>
				<button class="btn btn-xs btn-default btn_theme_form_wrapper" type="button">
					<i class="fa fa-plus"></i> Add new theme
				</button>
			</div>
			<div class="border-top"></div>
			<div class="padding-top-bottom form-group clearfix">
				<?= $form->field($model, 'groups')->checkboxlist($grouplist); ?>		
				<div class="clearfix"></div>
				<div class="group_form_wrapper row hidden">
					<hr />
					<div class="col-lg-4">
						<div class="form-group">
							<label class="control-label">Group Name</label>
							<div class="controls">
								<input type="text" class="form-control" name="group_name" maxlength="128">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label">Group Name - Arabic</label>
							<div class="controls">
								<input type="text" class="form-control" name="group_name_ar" maxlength="128">
							</div>
						</div>
						<button class="btn btn-primary btn-add-group" type="button">Add</button>
						<button class="btn btn-default btn-add-group-calcle" type="button">Cancel</button>
					</div>
				</div>

				<button class="btn btn-xs btn-default btn_group_form_wrapper" type="button">
					<i class="fa fa-plus"></i> Add new group
				</button>
			</div>

			<div class="border-top"></div>

			<div class="padding-top-bottom form-group clearfix">
				<?php echo $form->field($model, 'packages')->checkboxlist($packagelist);?>
			</div>

			<input type="button" name="btnPrevious" class="btnPrevious btn btn-info" value="Prev">
			
			<?= Html::submitButton('Complete', [
				'class' => 'btn btn-success complete', 
				'style'=>'float:right;']) ?>
		</div>
		<!--End seventh Tab -->

		<!-- Begin Question Answer Part -->
		<div class="tab-pane" id="9">
			<div class="questionanswer" >
			<?php
				 $exist_question = VendorItemQuestion::find()->where( [ 'item_id' => $model->item_id ] )->count();

				if($exist_question >= 1) {
				$count_q=(count($model_question)); // for initial count questions used in javascript;
				 $t=0;
				 foreach($model_question as $question_records) { ?>
				 	<div class="form-group superbox-s" id="delete_<?= $t;?>">
					    <li id="question-section_0" class="parent_question_<?= $question_records['question_id']; ?>"> <span class="question_title"> <?= $question_records['question_text']; ?></span> <span class="plus"><a href="#" onclick="questionView('<?= $question_records['question_id']; ?>',this)" ></a></span><div class="show_ques<?= $question_records['question_id']; ?>"></div></li>
				    </div>
				<?php $t++;
                 }	?>
				<input type="button" name="add" id="add" value="Add Question" onclick="addAddress(this)" style="margin:10px 0px;">
			<?php

			} else {
				$count_q=1;
				$h_id =0;
				?>
			<div class="form-group">
				<div id="question-section" class="question-section">
					<input type="hidden" name="parent_id" id="adds" value="0" class="form-control temp_qa">
					Question <input type="text" id="question_text_0" class="form-control temp_qa" name="VendorItemQuestion[0][question_text][]" style="margin:10px 0px;"> Question Type
					<div class="append_address">
						<select id="vendoritemquestion-question_answer_type0" class="form-control vendoritemquestion-question_answer_type temp_qa" name="VendorItemQuestion[0][question_answer_type][]" parent_id="0" style="margin:10px 0px;">
						<option value="">Choose type</option>
						<option value="text">Text</option>
						<option value="image">Image</option>
						<option value="selection">Selection</option></select>
					</div>
				</div>
			</div>
			<div class="question"></div>
			<input type="button" name="add" id="add" value="Add Question" onclick="addAddress(this)" style="margin:10px 0px;">
			<?php } ?>
			<!-- Question Answer Part	End	-->
			<div class="form-groups" >
				<input type="button" name="btnPrevious" class="btnPrevious btn btn-info" value="Prev" />
				<?= Html::a('Back to Manage', ['index', ], ['class' => 'btn btn-info', 'style'=>'float:right;']) ?>
			</div>
			</div><!-- END .questionanswer -->
		</div><!-- END tab-9 -->
	</div><!-- END .tab-content -->
</div><!-- END .tabbable -->
<?php ActiveForm::end(); ?>


<?php $form = ActiveForm::begin(['options' => ['enctype'=>'multipart/form-data', 'id' => 'child_category_form']]); ?>
<div id="child_category_modal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add child category</h4>
      </div>
      <div class="modal-body">

      		<div class="msg_wrapper"></div>

      		<?= Html::hiddenInput('Category[parent_category_id]', 0, ['id' => 'hdn_child_cat_parent']); ?>

			<?= $form->field($category_model, 'category_name') ?>

			<?= $form->field($category_model, 'category_name_ar') ?>

			<?= $form->field($category_model, 'category_meta_title')->textArea(['maxlength' => 250])?>

			<?= $form->field($category_model, 'category_meta_keywords')->textArea(['maxlength' => 250])?>

			<?= $form->field($category_model, 'category_meta_description')->textArea(['maxlength' => 250])?>

      </div>
      <div class="modal-footer">
        <button class="btn btn-primary">Submit</button>
      </div>
    </div>
  </div>
</div>
<?php ActiveForm::end(); ?>

<?php $form = ActiveForm::begin(['options' => ['enctype'=>'multipart/form-data', 'id' => 'sub_category_form']]); ?>
<div id="sub_category_modal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add sub category</h4>
      </div>
      <div class="modal-body">

      		<div class="msg_wrapper"></div>

      		<?= Html::hiddenInput('Category[parent_category_id]', 0, ['id' => 'hdn_sub_cat_parent']); ?>

			<?= $form->field($category_model, 'category_name') ?>

			<?= $form->field($category_model, 'category_name_ar') ?>

			<?= $form->field($category_model, 'category_meta_title')->textArea(['maxlength' => 250])?>

			<?= $form->field($category_model, 'category_meta_keywords')->textArea(['maxlength' => 250])?>

			<?= $form->field($category_model, 'category_meta_description')->textArea(['maxlength' => 250])?>

      </div>
      <div class="modal-footer">
        <button class="btn btn-primary">Submit</button>
      </div>
    </div>
  </div>
</div>
<?php ActiveForm::end(); ?>

<div class="modal fade" id="myModal" role="dialog"><div class="modal-dialog"><div class="modal-content"></div></div></div>

<?php 

if(isset($model->item_id)) { 
	$item_id = $model->item_id;
} else { 
	$item_id = 0;
}

if($model->isNewRecord) {
	$isNewRecord = 1;
} else {
	$isNewRecord = 0;
}


echo Html::hiddenInput('addon_menu_count', $addon_menu_count, ['id' => 'addon_menu_count']);
echo Html::hiddenInput('menu_count', $menu_count, ['id' => 'menu_count']);

echo Html::hiddenInput('count_q',$count_q,['id'=>$count_q]);
echo Html::hiddenInput('appImageUrl',Yii::getAlias('appImageUrl'),['id'=>'appImageUrl']);
echo Html::hiddenInput('image_order_url',Url::to(['/image/imageorder']),['id'=>'image_order_url']);
echo Html::hiddenInput('deletequestionoptions_url',Url::to(['/vendor-item-question-answer-option/deletequestionoptions']),['id'=>'deletequestionoptions_url']);
echo Html::hiddenInput('salesguideimage_url',Url::to(['/vendor-item/salesguideimage']),['id'=>'salesguideimage_url']);
echo Html::hiddenInput('request_create',$request->get('create'), ['id'=>'request_create']);
echo Html::hiddenInput('isNewRecord',$isNewRecord, ['id'=>'isNewRecord']);
echo Html::hiddenInput('item_for_sale',$model->item_for_sale, ['id'=>'item_for_sale']);
echo Html::hiddenInput('item_status',$model->item_status, ['id'=>'item_status']);
echo Html::hiddenInput('item_id',$item_id, ['id'=>'item_id']);
echo Html::hiddenInput('item_name_check',Url::to(['/vendor-item/itemnamecheck']), ['id'=>'item_name_check']);;
echo Html::hiddenInput('add_question_url',Url::to(['/vendor-item/addquestion']), ['id'=>'add_question_url']);
echo Html::hiddenInput('guideimage_url',Url::to(['/vendor-item/guideimage']), ['id'=>'guideimage_url']);
echo Html::hiddenInput('exist_question',$exist_question, ['id'=>'exist_question']);
echo Html::hiddenInput('removequestion_url',Url::to(['/vendor-item/removequestion']), ['id'=>'removequestion_url']);
echo Html::hiddenInput('vendorcategory_url',Url::to(['/category/vendorcategory']), ['id'=>'vendorcategory_url']);
echo Html::hiddenInput('loadsubcategory_url',Url::to(['/priority-item/loadsubcategory']), ['id'=>'loadsubcategory_url']);
echo Html::hiddenInput('loadchildcategory_url',Url::to(['/priority-item/loadchildcategory']), ['id'=>'loadchildcategory_url']);
echo Html::hiddenInput('renderquestion_url',Url::to(['/vendor-item/renderquestion']), ['id'=>'renderquestion_url']);
echo Html::hiddenInput('croped_image_upload_url', Url::to(['/vendor-item/upload-cropped-image']), ['id'=>'croped_image_upload_url']);
echo Html::hiddenInput('image_count', $image_count, ['id' => 'image_count']);

//ajax step urls 
echo Html::hiddenInput('item_info_url', Url::to(['vendor-item/item-info']), ['id' => 'item_info_url']);
echo Html::hiddenInput('item_description_url', Url::to(['vendor-item/item-description']), ['id' => 'item_description_url']);
echo Html::hiddenInput('item_price_url', Url::to(['vendor-item/item-price']), ['id' => 'item_price_url']);
echo Html::hiddenInput('item_approval_url', Url::to(['vendor-item/item-approval']), ['id' => 'item_approval_url']);
echo Html::hiddenInput('item_images_url', Url::to(['vendor-item/item-images']), ['id' => 'item_images_url']);

echo Html::hiddenInput('item_themes_groups', Url::to(['vendor-item/item-themes-groups']), ['id' => 'item_themes_groups']);

echo Html::hiddenInput('addon-menu-items', Url::to(['vendor-item/addon-menu-items']), ['id' => 'addon_menu_items_url']);

echo Html::hiddenInput('menu_items_url', Url::to(['vendor-item/menu-items']), ['id' => 'menu_items_url']);

echo Html::hiddenInput('item_validate_url', Url::to(['vendor-item/item-validate']), ['id' => 'item_validate_url']);

echo Html::hiddenInput('add_theme_url', Url::to(['vendor-item/add-theme']), ['id' => 'add_theme_url']);
echo Html::hiddenInput('add_group_url', Url::to(['vendor-item/add-group']), ['id' => 'add_group_url']);

echo Html::hiddenInput('category_add_url', Url::to(['vendor-item/add-category']), ['id' => 'category_add_url']);
echo Html::hiddenInput('category_list_url', Url::to(['vendor-item/category-list']), ['id' => 'category_list_url']);

$this->registerCssFile("@web/themes/default/plugins/bootstrap-fileinput/fileinput.min.css");
$this->registerCssFile("@web/themes/default/plugins/bootstrap-multiselect/dist/css/bootstrap-multiselect.css");
$this->registerCssFile("@web/themes/default/plugins/jquery-superbox/css/style.css");
$this->registerJsFile("@web/themes/default/plugins/jquery-superbox/js/superbox.js", ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('@web/themes/default/plugins/bootstrap-fileinput/fileinput.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('@web/themes/default/plugins/ckeditor/ckeditor.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile("@web/themes/default/plugins/bootstrap-multiselect/dist/js/bootstrap-multiselect.js", ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerJsFile("@web/themes/default/js/jquery.cropit.js", ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerJsFile("@web/themes/default/js/vendor_item_validation.js?v=1.20", ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerCss("
	input#question{  margin: 10px 5px 10px 0px;  float: left;  width: 45%;}
	input#price, input#image,{	margin: 10px 5px 10px 0px;    width: 45%;}
	.selection_delete{	margin:15px 5px 10px 5px; }
	.price_val{  width: 100%;  float: left;}
	.image_val{  width: 100%;  float: left;}
	.question-section input[type=\"text\"] { margin:10px 0px;}
	.superbox{ min-height:250px;}
	.questionanswer li.parent_question{padding: 5px;  list-style: none;  border: 1px solid #000;}
	.questionanswer li.level1_question{  padding: 5px;  list-style: none;  border: 1px solid #000;  margin: 5px 0px 0px 10px;}
	.question_toggle{padding: 5px;  list-style: none;   margin: 5px 0px 0px 10px;}
	.viewbutton,.savebutton{margin-right: 10px;float: left; }
	.form-group li { list-style: none;}
	.form-groups  { margin-top: 10px;}
	.add_question, .save, .saves { float: left;  margin-right: 10px;}
	.question_success{  color: green;  line-height: 5px;  font-weight: bold; display:none; float: left;  margin: 10px 5px 10px 0;}
	.superbox-s > li > b { margin:10px 0px 5px 0px;}
	.question_title{font-weight: bold;  margin-top: 15px;  line-height: 31px;  font-size: 15px;}
	.upimage {margin: 5px 0px 10px 0px;}
	#vendoritem-groups label,#vendoritem-themes label, #vendoritem-packages label {
		float: left;min-width: 15%;margin-right: 43px;
	}
	.border-top{border-top: 1px solid;}
	.padding-top-bottom{padding: 36px 0;}
	.btn-xs, .btn-group-xs>.btn {
	    padding: 1px 5px !important;
	    margin-top: 5px;
	}

	.table-category-list .checkbox {
	    margin-left: 20px;
	}
	
	.main-category-list .chk_wrapper,
	.sub-category-list .chk_wrapper,
	.child-category-list .chk_wrapper{
		max-height: 200px;
    	overflow-y: scroll;
	}
");

