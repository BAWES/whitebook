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
	    <li class="active">
	    	<a href="<?= Url::to(['vendor-item/item-themes-groups', 'id' => $model->item_id]) ?>">
	    		Other
	    	</a>
	    </li>
	</ul>

	<div class="tab-content">
		<div class="tab-pane clearfix active">
			<div class="form-group clearfix padding-top-bottom">
				<?php echo $form->field($model, 'themes')->checkboxlist($themes); ?>
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
				<?= $form->field($model, 'groups')->checkboxlist($groups); ?>		
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
				<?php echo $form->field($model, 'packages')->checkboxlist($packages);?>
			</div>

			<hr />

			<div class="row">
				<div class="col-md-6">
					<a href="<?= Url::to(['vendor-item/item-videos', 'id' => $model->item_id,'_u'=>Yii::$app->request->get('_u')]) ?>" class="btn btn-info pull-left">Prev</a>
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

$this->registerJsFile("@web/themes/default/js/jquery.cropit.js", ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerJsFile("@web/themes/default/js/vendor_item_validation.js?v=1.23", ['depends' => [\yii\web\JqueryAsset::className()]]);

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


