<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\web\view;

$this->title = 'Update vendor item';
$this->params['breadcrumbs'][] = ['label' => 'Vendor items', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';

$model->item_status = ($model->item_status == 'Active') ? 1 : 0;

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
	<?php if(!$model->isNewRecord) { ?>
	    <li class="active">
	    	<a href="<?= Url::to(['vendor-item/update', 'id' => $model->item_id]) ?>">
	    		Item Info 
	    	</a>
	    </li>
	    <li>
	    	<a href="<?= Url::to(['vendor-item/item-description', 'id' => $model->item_id]) ?>">
	    		Item description
	    	</a>
	    </li>
	    <li>
	    	<a href="<?= Url::to(['vendor-item/item-price', 'id' => $model->item_id]) ?>">
	    		Item price 
	    	</a>
	    </li>
	    <li>
	    	<a href="<?= Url::to(['vendor-item/menu-items', 'id' => $model->item_id]) ?>">
	    		Menu items
	    	</a>
	    </li>
	    <li>
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
	    	<a href="<?= Url::to(['vendor-item/item-themes-groups', 'id' => $model->item_id]) ?>">
	    		Other
	    	</a>
	    </li>
	
	<?php } else { ?>
		<li class="active">
	    	<a href="javascript::void();">
	    		Item Info 
	    	</a>
	    </li>
	    <li>
	    	<a href="javascript::void();">
	    		Item description
	    	</a>
	    </li>
	    <li>
	    	<a href="javascript::void();">
	    		Item price 
	    	</a>
	    </li>
	    <li>
	    	<a href="javascript::void();">
	    		Menu items
	    	</a>
	    </li>
	    <li>
	    	<a href="javascript::void();">
	    		Addons
	    	</a>
	    </li>
	    <li>
	    	<a href="javascript::void();">
	    		Approval 
	    	</a>
	    </li>
	    <li>
	    	<a href="javascript::void();">
	    		Images
	    	</a>
	    </li>
	    <li>
	    	<a href="javascript::void();">
	    		Other
	    	</a>
	    </li>
	<?php } ?>
	</ul>

	<div class="tab-content">
		<div class="tab-pane clearfix active">

			<?= $form->field($model, 'vendor_id')->dropDownList($vendors) ?>

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
			
			<hr />

			<div class="row">
				<div class="col-md-6">
					<?php if(!$model->isNewRecord) { ?>
					<input type="submit" name="complete" class="btn btn-info" value="Complete" />
					<?php } ?>
				</div>
				<div class="col-md-6">
					<input type="submit" name="btnNext" class="btn btn-info pull-right" value="Next" />
				</div>
			</div>
		</div>
	</div>
</div>

<?php 

ActiveForm::end(); 

//category models 

$form = ActiveForm::begin(['options' => ['enctype'=>'multipart/form-data', 'id' => 'child_category_form']]); ?>

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

</div>

</div><!-- END .vendoritem-update -->

<?php 

echo Html::hiddenInput('isNewRecord', 0, ['id'=>'isNewRecord']);
echo Html::hiddenInput('item_id', $model->item_id, ['id'=>'item_id']);

echo Html::hiddenInput('category_add_url', Url::to(['vendor-item/add-category']), ['id' => 'category_add_url']);
echo Html::hiddenInput('category_list_url', Url::to(['vendor-item/category-list']), ['id' => 'category_list_url']);

$this->registerJsFile("@web/themes/default/js/vendor_item_validation.js?v=1.21", ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerJsFile("@web/themes/default/js/vendor_item_steps/info.js", ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerCss("
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

