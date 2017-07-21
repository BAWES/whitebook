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
	    <li class="active">
	    	<a href="<?= Url::to(['vendor-item/item-videos', 'id' => $model->item_id]) ?>">
	    		Videos
	    	</a>
	    </li>
	</ul>

	<div class="tab-content">
		<div class="tab-pane clearfix active">

			<div class="alert alert-info">
				<button class="close" data-dismiss="alert"></button>
				<a href="https://docs.joeworkman.net/rapidweaver/stacks/youtube/video-id" target="_blank">
					<u>Click here</u>
				</a> 
				to learn how to find your YouTube video url.	 
			</div>

			<div class="row">				
				<div class="col-lg-12">
					<table class="table table-bordered table-item-video">
						<thead>
							<tr>
								<th>YouTube Video ID</th>
								<th>Sort order</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
						<?php $video_count = 0 ; foreach ($model->videos as $key => $value) { ?>
						<tr>
							<td>
								<input type="text" name="videos[<?= $video_count ?>][video]" value="<?= $value->video ?>" />
							</td>
							<td>
								<input type="text" name="videos[<?= $video_count ?>][video_sort_order]" value="<?= $value->video_sort_order ?>" />
							</td>
							<td>
								<button class="btn btn-danger btn-delete-video" type="button">
									<i class="fa fa-trash"></i>
								</button>
							</td>
						</tr>
						<?php $video_count++; } ?>
						</tbody>
						<tfoot>
							<tr>
								<td colspan="3">
									<button class="btn btn-primary btn-add-video" type="button">+ Add new video</button>
								</td>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>

			<hr />
			
			<div class="row">
				<div class="col-md-6">
					<a href="<?= Url::to(['vendor-item/item-images', 'id' => $model->item_id]) ?>" class="btn btn-info pull-left">Prev</a>
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

echo Html::hiddenInput('isNewRecord', 0, ['id'=>'isNewRecord']);
echo Html::hiddenInput('item_id', $model->item_id, ['id'=>'item_id']);

echo Html::hiddenInput('video_count', $video_count, ['id' => 'video_count']);

$this->registerJsFile("@web/themes/default/js/vendor_item_validation.js?v=1.24", ['depends' => [\yii\web\JqueryAsset::className()]]);
