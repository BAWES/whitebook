<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\web\view;

if($model->isNewRecord) {
	$this->title = 'Create vendor item';
}else{
	$this->title = 'Update vendor item';	
}

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
		<?php if($model->isNewRecord) { ?>
	    <li class="active">
	    	<a href="javascript::void();">
	    		Item Info 
	    	</a>
	    </li>
	    <li>
	    	<a href="javascript::void();">
	    		Description
	    	</a>
	    </li>
	    <li>
	    	<a href="javascript::void();">
	    		Price and Inventory
	    	</a>
	    </li>
	    <li>
	    	<a href="javascript::void();">
	    		Menu
	    	</a>
	    </li>
	    <li>
	    	<a href="javascript::void();">
	    		Addons
	    	</a>
	    </li>
	    <li>
	    	<a href="javascript::void();">
	    		Images
	    	</a>
	    </li>
	    <?php } else { ?>
	    <li class="active">
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
	    <li>
	    	<a href="<?= Url::to(['vendor-item/addon-menu-items', 'id' => $model->item_id]) ?>">
	    		Addons
	    	</a>
	    </li>
	    <li>
	    	<a href="<?= Url::to(['vendor-item/item-images', 'id' => $model->item_id]) ?>">
	    		Images
	    	</a>
	    </li>
	    <?php } ?>
	</ul>

	<div class="tab-content">
		<div class="tab-pane clearfix active">

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

			<hr />
			
			<div class="row">
				<div class="col-md-6">
					<input type="submit" name="complete" class="btn btn-info" value="Complete" />
				</div>
				<div class="col-md-6">
					<input type="submit" name="btnNext" class="btn btn-info pull-right" value="Next" />
				</div>
			</div>
			
		</div><!-- END .tab-pane -->
	</div><!-- END .tab-content -->
</div><!-- END .tabbable -->

<?php 

ActiveForm::end(); 

echo Html::hiddenInput('appImageUrl',Yii::getAlias('appImageUrl'),['id'=>'appImageUrl']);
echo Html::hiddenInput('image_order_url',Url::to(['/image/imageorder']),['id'=>'image_order_url']);
echo Html::hiddenInput('isNewRecord', 0, ['id'=>'isNewRecord']);
echo Html::hiddenInput('item_id', $model->item_id, ['id'=>'item_id']);

echo Html::hiddenInput('category_list_url', Url::to(['vendor-item/category-list']), ['id' => 'category_list_url']);

$this->registerJsFile("@web/themes/default/js/vendor_item_validation.js?v=1.22", ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerJsFile("@web/themes/default/js/vendor_item_steps/info.js", ['depends' => [\yii\web\JqueryAsset::className()]]);