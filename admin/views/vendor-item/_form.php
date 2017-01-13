<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use kartik\sortable\Sortable;
use admin\models\Vendor;
use common\models\VendorItemQuestion;
use yii\web\view;
use kartik\file\FileInput;
$request = Yii::$app->request;

if($model->isNewRecord){
	$categoryname = array();
	$subcategory = array();
	$childcategory = array();
	$exist_themes =array();
	$exist_groups = array();
}

?>

<style>
	#vendoritem-groups label,#vendoritem-themes label {
		float: left;
		min-width: 15%;
		margin-right: 43px;
	}
	.border-top{
		border-top: 1px solid;
	}
	.padding-top-bottom{padding: 36px 0;}
</style>

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
	    <li class="active">
	      <a href="#1" data-toggle="tab" id="tab_1">Item Info </a>
	    </li>
	    <li>
	      <a href="#2" id="tab_2">Item description</a>
	    </li>
	    <li>
	      <a href="#3" id="tab_3">Item price </a>
	    </li>
	    <li>
	      <a href="#4" id="tab_4">Menu items</a>
	    </li>
	    <li>
	      <a href="#5" id="tab_5">Approval </a>
	    </li>
	    <li>
	      <a href="#6" id="tab_6">Images</a>
	    </li>
	    <li>
	      <a href="#7" id="tab_7">Themes & Groups & Packages</a>
	    </li>
	    <?php 
	    /*if(!$model->isNewRecord && $model->item_for_sale =='Yes') {?>
	    <li>
	      <a href="#7" id="tab_7"> Questions </a>
	    </li>
	    <?php }*/ ?>
	</ul>

	<div class="tab-content">
		<div class="tab-pane active" id="1">

			<?= Html::activeHiddenInput($model, 'version', ['id' => 'version']); ?>

			<input type="hidden" name="item_id" value="<?= $model->item_id ?>" />

			<!-- vid - click create item button from item view page for the particular item view page-->
			<?php if($request->get('vid')) {	
			
			$vendor_name = Vendor::getvendorname($request->get('vid')); ?>
			
			<?= $form->field($model, 'vendor_id')->dropDownList($vendor_name) ?>

			<!-- if its update form disable particular vendor-->
			<?php } else if (!$model->isNewRecord) { ?>
			
				<?= $form->field($model, 'vendor_id')
					->dropDownList($vendorname, ['prompt'=>'Select...','disabled'=>'disabled']) ?>
			
				<?= $form->field($model,'vendor_id')->hiddenInput(); ?>
			
			<!-- if its create form-->
			<?php } else { ?>

				<?= $form->field($model, 'vendor_id')->dropDownList($vendorname, ['prompt'=>'Select...']) ?>
			<?php } ?>

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

			<div class="form-group" style="height: 10px;">
				<input type="button" name="btnPrevious" class="btnNext btn btn-info" value="Next" />
				<?= Html::a('Back', ['index', ], ['class' => 'btn btn-default']) ?>
			</div>
		</div>
	</div><!-- END .tab-content -->
</div><!-- END .tabbable -->

<?php ActiveForm::end(); ?>

<div class="modal fade" id="myModal" role="dialog">
      <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
      </div>
    </div>
</div>
<!-- END Dialog box for sales guide image -->


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

if(!empty($imagedata)) { 

	$this->registerJs("
		var imagedata = 1;
		var img = ".$img.";
		var action = ".$action.";
	", View::POS_HEAD);

}else {

	$this->registerJs("
		var imagedata = '';	
		var img = '';
		var action = '';
	", View::POS_HEAD);
} 

if(!empty($guideimagedata)) { 

	$this->registerJs("
		var guideimagedata = 1;
		var img1 = ".$img1.";
		var action1 = ".$action1.";
	", View::POS_HEAD);

}else{

	$this->registerJs("
		var guideimagedata = '';
		var img1 = '';
		var action1 = '';
	", View::POS_HEAD);
}

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
echo Html::hiddenInput('croped_image_upload_url',Url::to(['/vendor-item/upload-cropped-image']), ['id'=>'croped_image_upload_url']);
echo Html::hiddenInput('image_count', $image_count, ['id' => 'image_count']);

//ajax step urls 
echo Html::hiddenInput('item_info_url', Url::to(['vendor-item/item-info']), ['id' => 'item_info_url']);
echo Html::hiddenInput('item_description_url', Url::to(['vendor-item/item-description']), ['id' => 'item_description_url']);
echo Html::hiddenInput('item_price_url', Url::to(['vendor-item/item-price']), ['id' => 'item_price_url']);
echo Html::hiddenInput('item_approval_url', Url::to(['vendor-item/item-approval']), ['id' => 'item_approval_url']);
echo Html::hiddenInput('item_images_url', Url::to(['vendor-item/item-images']), ['id' => 'item_images_url']);

echo Html::hiddenInput('item_themes_groups', Url::to(['vendor-item/item-themes-groups']), ['id' => 'item_themes_groups']);

echo Html::hiddenInput('item_validate_url', Url::to(['vendor-item/item-validate']), ['id' => 'item_validate_url']);

echo Html::hiddenInput('category_add_url', Url::to(['vendor-item/add-category']), ['id' => 'category_add_url']);
echo Html::hiddenInput('category_list_url', Url::to(['vendor-item/category-list']), ['id' => 'category_list_url']);

$this->registerCssFile("@web/themes/default/plugins/bootstrap-fileinput/fileinput.min.css");

$this->registerCssFile("@web/themes/default/plugins/bootstrap-multiselect/dist/css/bootstrap-multiselect.css");

$this->registerCssFile("@web/themes/default/plugins/jquery-superbox/css/style.css");

$this->registerJsFile("@web/themes/default/plugins/jquery-superbox/js/superbox.js", ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerJsFile('@web/themes/default/plugins/ckeditor/ckeditor.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerJsFile("@web/themes/default/plugins/bootstrap-multiselect/dist/js/bootstrap-multiselect.js", ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerJsFile("@web/themes/default/js/jquery.cropit.js", ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerJsFile("@web/themes/default/js/vendor_item_validation.js?v=1.17", ['depends' => [\yii\web\JqueryAsset::className()]]);

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


