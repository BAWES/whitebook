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
	    	<a href="<?= Url::to(['vendor-item/item-videos', 'id' => $model->item_id]) ?>">
	    		Videos
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

			<?= $form->field($model, 'item_approved')
					->dropDownList([ 'Pending' => 'Pending','Yes' => 'Yes', 'Rejected'=>'Rejected']); ?>

			<?= $form->field($model, 'item_status')->checkbox(['Value' => true]); ?>

			<hr />

			<div class="row">
				<div class="col-md-4">
					<a href="<?= Url::to(['vendor-item/addon-menu-items', 'id' => $model->item_id,'_u'=>Yii::$app->request->get('_u')]) ?>" class="btn btn-info pull-left">Prev</a>
				</div>
				<div class="col-md-4 text-center">
					<input type="submit" name="complete" class="btn btn-info" value="Complete" />
				</div>
				<div class="col-md-4">
					<input type="submit" name="btnNext" class="btn btn-info pull-right" value="Next" />
				</div>
			</div>

		</div>
	</div>
</div>

<?php 

ActiveForm::end(); 


echo Html::hiddenInput('isNewRecord', 0, ['id'=>'isNewRecord']);
echo Html::hiddenInput('item_id', $model->item_id, ['id'=>'item_id']);

$this->registerJsFile("@web/themes/default/js/vendor_item_validation.js?v=1.23", ['depends' => [\yii\web\JqueryAsset::className()]]);

?>

</div>

</div><!-- END .vendoritem-update -->
