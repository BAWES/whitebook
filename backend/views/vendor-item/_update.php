<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use kartik\file\FileInput;
//use dosamigos\fileupload\FileUploadUI;

function cmp($a, $b)
{
	return strcmp($a["vendorimage_sort_order"], $b["vendorimage_sort_order"]);
}

?>

<?= Html::csrfMetaTags() ?>

<div class="col-md-12 col-sm-12 col-xs-12">
	
	<?php $form = ActiveForm::begin(['options'=>['enctype'=>'multipart/form-data']]); ?>

	<div class="loadingmessage" style="display: none;">
		<p><?= Html::img(Yii::getAlias('@web/themes/default/img/loading.gif'), ['class'=>'','width'=>'64px','height'=>'64px','id'=>'loading','alt'=>'loading']);?></p>
	</div>

	<div class="tabbable">
		<ul class="nav nav-tabs">
			<li class="active"><a href="#1" data-toggle="tab" id="tab_1">Item Info </a></li>
			<li><a href="#2" id="tab_2">Item description</a></li>
			<li><a href="#3" id="tab_3"> Item price </a></li>
			<li><a href="#4" id="tab_4">Images</a></li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane clearfix" id="1">

				<input type="hidden" name="item_id" value="<?= $model->item_id ?>" />
				
				<?= $form->field($model, 'item_name')->textInput([
						'maxlength' => 128,
						'id' => 'vendoritem-item_name'
					]) ?>

				<?= $form->field($model, 'item_name_ar')->textInput([
						'maxlength' => 128,
						'id' => 'vendoritem-item_name_ar'
					]) ?>
				
				<?= $form->field($model, 'item_status')
						->dropDownList(
							['Active' => 'Yes','Deactive' => 'No'], 
							['id' => 'vendoritem-item_status']
						); ?>

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
					</table>				
				</div>
				
				<div class="row">
					<div class="col-lg-4">
						<?= Html::submitButton('Submit for Admin Approval', ['class' => 'btn btn-primary complete', 'style' => 'float:left;']) ?>
					</div>

					<div class="col-lg-4 text-center">
						<?= Html::button('Save as Draft/Complete Later', ['class' => 'btn btn-primary btn-save-draft']) ?>
					</div>

					<div class="col-lg-4">
						<input type="button" name="btnPrevious" class="btnNext btn btn-info" value="Next" />
					</div>
				</div>
			</div>
			<!--End First Tab -->

			<!--BEGIN second Tab -->
			<div class="tab-pane clearfix" id="2">
					
				<div class="alert alert-info">
					Inline css will be removed from editor for item description, item additional info, price description and customization description.
					<button class="close" data-dismiss="alert">x</button>
				</div>

				<?= $form->field($model, 'type_id')
						->dropDownList($itemtype, ['prompt'=>'Select...', 'id' => 'vendoritem-type_id']) ?>

				<?= $form->field($model, 'item_description')
						->label('Item description'.Html::tag('span', '*',['class'=>'required']))
						->textarea(['maxlength' => 128, 'id' => 'vendoritem-item_description']); ?>

				<?= $form->field($model, 'item_description_ar')
						->label('Item description - Arabic'.Html::tag('span', '*',['class'=>'required']))
						->textarea(['maxlength' => 128, 'id' => 'vendoritem-item_description_ar']); ?>

				<?= $form->field($model, 'item_additional_info')
						->textarea(['maxlength' => 128, 'id' => 'vendoritem-item_additional_info']); ?>

				<?= $form->field($model, 'item_additional_info_ar')
						->textarea(['maxlength' => 128, 'id' => 'vendoritem-item_additional_info_ar']); ?>

				<div class="row">
					<div class="col-lg-2">
						<input type="button" name="btnPrevious" class="btnPrevious btn btn-info" value="Prev" />
					</div>
					
					<div class="col-lg-8">

						<div class="col-lg-6 text-center">
							<?= Html::button('Save as Draft/Complete Later', ['class' => 'btn btn-primary btn-save-draft']) ?>
						</div>

						<div class="col-lg-6 text-center">
							<?= Html::submitButton('Submit for Admin Approval', ['class' => 'btn btn-primary complete']) ?>
						</div>
					</div>

					<div class="col-lg-2">
						<input type="button" name="btnNext" class="btnNext btn btn-info" value="Next" />
					</div>
				</div>
			</div>

			<!--End Second Tab -->

			<!--BEGIN Third Tab -->
			<div class="tab-pane clearfix" id="3">

				<input type="hidden" id="test" value="0" name="tests" />
				
				<?= $form->field($model, 'item_for_sale')->dropDownList(['Yes'=>'Yes', 'No'=>'No'], 
						['id' => 'vendoritem-item_for_sale']) ?>
				
				<?= $form->field($model, 'item_amount_in_stock')
						->label('Item Number of Stock '.Html::tag('span', '*',['class'=>'required mandatory']))
						->textInput(['maxlength' => 128, 'id' => 'vendoritem-item_amount_in_stock']); ?>

				<?= $form->field($model, 'item_default_capacity')
						->label('Item Default Capacity '.Html::tag('span', '*',['class'=>'required mandatory']))
						->textInput(['maxlength' => 128, 'id' => 'vendoritem-item_default_capacity']); ?>

				<?= $form->field($model, 'item_how_long_to_make')
						->label('No of days delivery '.Html::tag('span', '*',['class'=>'required mandatory']))
						->textInput(['maxlength' => 128, 'id' => 'vendoritem-item_how_long_to_make']); ?>

				<?= $form->field($model, 'item_minimum_quantity_to_order')
						->label('Item Minimum Quantity to Order '.Html::tag('span', '*',['class'=>'required mandatory']))
						->textInput(['maxlength' => 128, 'id' => 'vendoritem-item_minimum_quantity_to_order']); ?>

				<?= $form->field($model, 'item_price_per_unit')->textInput(['maxlength' => 128]); ?>

				<?php if($model->isNewRecord) { ?>
					<div class="form-group multiple_price" style="padding: 5px;  font-size: 14px;">
						<div class="multi_pricing">Price Chart </div>
						<div class="controls1">
							<input type="text" id="vendoritem-item_from" class="form-control from_range_1" name="vendoritem-item_price[from][]" multiple="multiple" placeholder="From Quantity" />

							<input type="text" id="vendoritem-item_to" class="form-control to_range_1" name="vendoritem-item_price[to][]" multiple="multiple" placeholder="To Quantity" />

							<input type="text" id="item_price_per_unit" class="form-control price_kd_1" name="vendoritem-item_price[price][]" multiple="multiple" placeholder="Price">KD

							<input type="button" name="remove" id="remove" value="Remove" class="remove_price" onclick="removePrice(this)" />
						</div>
						<input type="button" class="add_price" name="addprice" id="addprice" value="Add more" onClick="addPrice(this);" />
					</div>
				<?php } else { ?>
					<div class="form-group multiple_price" style="padding: 5px;  font-size: 14px;">
					<div class="multi_pricing">Price Chart</div>
					<?php $t=0;
					foreach ($pricing as $value) { ?>
					<div class="controls<?= $t; ?>">
						<input type="text" id="vendoritem-item_from" class="form-control from_range_<?= $t; ?>" name="vendoritem-item_price[from][]" multiple = "multiple" Placeholder="From Quantity" value="<?= $value['range_from'];?>" />

						<input type="text" id="vendoritem-item_to" class="form-control to_range_<?= $t; ?>" name="vendoritem-item_price[to][]" multiple = "multiple" Placeholder="To Quantity" value="<?= $value['range_to'];?>" />

						<input type="text" id="item_price_per_unit" class="form-control price_kd_<?= $t; ?>" name="vendoritem-item_price[price][]" multiple = "multiple" Placeholder="Price" value="<?= $value['pricing_price_per_unit'];?>">KD

						<input type="button" name="remove" id="remove" value="Remove" class="remove_price" onClick="removePrice(this)" />
					</div>
					<?php $t++; } ?>
					<input type="button" class="add_price" name="addprice" id="addprice" value="Add more" onClick="addPrice(this);" />
				</div>
				<?php } ?>

				<?= $form->field($model, 'item_price_description')->textarea([
						'id' => 'vendoritem-item_price_description'
					]) ?>
				
				<?= $form->field($model, 'item_price_description_ar')->textarea([
						'id' => 'vendoritem-item_price_description_ar'
					]) ?>

				<?= $form->field($model, 'item_customization_description')
						->textarea([
							'class' => 'form-group custom_description',
							'maxlength' => 128,
							'id' => 'vendoritem-item_customization_description'
						]); ?>

				<?= $form->field($model, 'item_customization_description_ar')
						->textarea([
							'class' => 'form-group custom_description_ar',
							'maxlength' => 128,
							'id' => 'vendoritem-item_customization_description_ar'
						]); ?>

				<div class="row">
					<div class="col-lg-2">
						<input type="button" name="btnPrevious" class="btnPrevious btn btn-info" value="Prev" />
					</div>
					
					<div class="col-lg-8">

						<div class="col-lg-6 text-center">
							<?= Html::button('Save as Draft/Complete Later', ['class' => 'btn btn-primary btn-save-draft']) ?>
						</div>

						<div class="col-lg-6 text-center">
							<?= Html::submitButton('Submit for Admin Approval', ['class' => 'btn btn-primary complete']) ?>
						</div>
					</div>

					<div class="col-lg-2">
						<input type="button" name="btnNext" class="btnNext btn btn-info" value="Next" />
					</div>
				</div>
			</div>
			<!--End Third Tab -->

			<div class="tab-pane" id="4">
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
							<?php $image_count = 0 ; foreach ($images as $key => $value) { ?>
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
					<div class="col-lg-4">
						<input type="button" name="btnPrevious" class="btnPrevious btn btn-info" value="Prev" />
					</div>
					
					<div class="col-lg-4 text-center">
						<?= Html::button('Save as Draft/Complete Later', ['class' => 'btn btn-primary btn-save-draft']) ?>
					</div>

					<div class="col-lg-4">
						<?= Html::submitButton('Submit for Admin Approval', ['class' => 'btn btn-info complete', 'style'=>'float:right;']) ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php ActiveForm::end(); ?>

<?php

$this->registerCssFile("@web/themes/default/plugins/bootstrap-multiselect/dist/css/bootstrap-multiselect.css");
$this->registerCssFile("@web/themes/default/plugins/bootstrap-fileinput/fileinput.min.css");
$this->registerCss("
	input#question{  margin: 10px 5px 10px 0px;  float: left;  width: 45%;}
	input#price{	margin: 10px 5px 10px 0px;  float: left;  width: 45%;}
	.price_val{  width: 100%;  float: left;}
	.question-section input[type=\"text\"] { margin:10px 0px;}

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

echo Html::hiddenInput('isNewRecord',$model->isNewRecord,['id'=>'isNewRecord']);
echo Html::hiddenInput('item_for_sale',$model->item_for_sale,['id'=>'item_for_sale']);
echo Html::hiddenInput('item_status',$model->item_status,['id'=>'item_status']);
echo Html::hiddenInput('item_id',Yii::$app->request->get('id'),['id'=>'item_id']);
echo Html::hiddenInput('load_sub_category_url',Url::to(['/priorityitem/loadsubcategory']),['id'=>'load_sub_category_url']);
echo Html::hiddenInput('load_child_category_url',Url::to(['/priorityitem/loadchildcategory']),['id'=>'load_child_category_url']);
echo Html::hiddenInput('image_delete_url',Url::to(['vendor-item/imagedelete']),['id'=>'image_delete_url']);
echo Html::hiddenInput('remove_question_url',Url::to(['vendor-item/removequestion']),['id'=>'remove_question_url']);
echo Html::hiddenInput('render_question_url',Url::to(['vendor-item/renderquestion']),['id'=>'render_question_url']);
echo Html::hiddenInput('item_name_check_url',Url::to(['/vendor-item/itemnamecheck']),['id'=>'item_name_check_url']);
echo Html::hiddenInput('image_order_url',Url::to(['/site/imageorder']),['id'=>'image_order_url']);
echo Html::hiddenInput('croped_image_upload_url',Url::to(['/vendor-item/upload-cropped-image']), ['id'=>'croped_image_upload_url']);
echo Html::hiddenInput('image_count', $image_count, ['id' => 'image_count']);

echo Html::hiddenInput('item_list_url', Url::to(['vendor-item/index']), ['id' => 'item_list_url']);

//ajax step urls 
echo Html::hiddenInput('item_info_url', Url::to(['vendor-item/item-info']), ['id' => 'item_info_url']);
echo Html::hiddenInput('item_description_url', Url::to(['vendor-item/item-description']), ['id' => 'item_description_url']);
echo Html::hiddenInput('item_price_url', Url::to(['vendor-item/item-price']), ['id' => 'item_price_url']);
echo Html::hiddenInput('item_validate_url', Url::to(['vendor-item/item-validate']), ['id' => 'item_validate_url']);

echo Html::hiddenInput('category_list_url', Url::to(['vendor-item/category-list']), ['id' => 'category_list_url']);

$this->registerJsFile('@web/themes/default/plugins/bootstrap-multiselect/dist/js/bootstrap-multiselect.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerJsFile('@web/themes/default/plugins/bootstrap-fileinput/fileinput.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerJsFile('@web/themes/default/plugins/ckeditor/ckeditor.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerJsFile("@web/themes/default/js/jquery.cropit.js", ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerJsFile('@web/themes/default/js/vendor_item_validation.js?v=1.14', ['depends' => [\yii\web\JqueryAsset::className()]]);
