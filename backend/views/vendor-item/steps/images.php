<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\web\view;

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
	    <li>
	    	<a href="<?= Url::to(['vendor-item/addon-menu-items', 'id' => $model->item_id]) ?>">
	    		Addons
	    	</a>
	    </li>
	    <li class="active">
	    	<a href="<?= Url::to(['vendor-item/item-images', 'id' => $model->item_id]) ?>">
	    		Images
	    	</a>
	    </li>
	</ul>

	<div class="tab-content">
		<div class="tab-pane clearfix active">

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
				<div class="col-md-6">
					<a href="<?= Url::to(['vendor-item/addon-menu-items', 'id' => $model->item_id]) ?>" class="btn btn-info pull-left">Prev</a>
				</div>
				<div class="col-md-6">
					<input type="submit" name="complete" class="btn btn-info pull-right" value="Complete" />
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

echo Html::hiddenInput('croped_image_upload_url', Url::to(['/vendor-item/upload-cropped-image']), ['id'=>'croped_image_upload_url']);

echo Html::hiddenInput('image_count', $image_count, ['id' => 'image_count']);

$this->registerJsFile("@web/themes/default/js/jquery.cropit.js", ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerJsFile("@web/themes/default/js/vendor_item_validation.js?v=1.21", ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerJsFile("@web/themes/default/js/vendor_item_steps/images.js", ['depends' => [\yii\web\JqueryAsset::className()]]);