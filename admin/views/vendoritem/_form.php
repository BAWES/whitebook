<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use kartik\sortable\Sortable;
use admin\models\Vendor;
use common\models\Vendoritemquestion;
use dosamigos\fileupload\FileUploadUI;
use yii\web\view;

$request = Yii::$app->request;

if($model->isNewRecord){
	$categoryname = array();
	$subcategory = array();
	$childcategory = array();
	$exist_themes =array();
	$exist_groups = array();
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
	    <li class="active">
	      <a href="#1" data-toggle="tab">Item Info </a>
	    </li>
	    <li>
	      <a href="#2" data-toggle="tab" id="validone1">Item description</a>
	    </li>
	    <li>
	      <a href="#3" data-toggle="tab" id="validtwo2"> Item price </a>
	    </li>
	    <li>
	      <a href="#4" data-toggle="tab" id="validthree3"> Approval </a>
	    </li>
	    <li>
	      <a href="#5" data-toggle="tab" id="validfour4">Images</a>
	    </li>
	    <li>
	      <a href="#6" data-toggle="tab" id="validfive5">Themes & Groups</a>
	    </li>
	    <!-- BEGIN if item type sales question and answer tab will be display -->
	    <?php if(!$model->isNewRecord && $model->item_for_sale =='Yes') {?>
	    <li>
	      <a href="#7" data-toggle="tab" id="validsix6"> Questions </a>
	    </li>
	    <?php } ?>
	    <!-- END if item type sales question and answer tab will be display -->
	</ul>

	<div class="tab-content">
		<!-- Begin First Tab -->
		<div class="tab-pane active" id="1">
			<!-- vid - click create item button from item view page for the particular item view page-->
			<?php if($request->get('vid')) {	
			$vendor_name = Vendor::getvendorname($request->get('vid')); ?>
			<div class="form-group"><?= $form->field($model, 'vendor_id',['template' => "{label}<div class='controls'>{input}</div>{hint}
			{error}"])->dropDownList($vendor_name) ?></div>

			<!-- if its update form disable particular vendor-->
			<?php } else if (!$model->isNewRecord) { ?>
			<div class="form-group"><?= $form->field($model, 'vendor_id',['template' => "{label}<div class='controls'>{input}</div>{hint}
			{error}"])->dropDownList($vendorname, ['prompt'=>'Select...','disabled'=>'disabled']) ?></div>
			<?= $form->field($model,'vendor_id')->hiddenInput(); ?>
			<?php } else { ?>

			<!-- if its create form-->
			<div class="form-group"><?= $form->field($model, 'vendor_id',['template' => "{label}<div class='controls'>{input}</div>{hint}
			{error}"])->dropDownList($vendorname, ['prompt'=>'Select...']) ?></div>
			<?php } ?>

			<div class="form-group">
				<?= $form->field($model, 'item_name',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"])->textInput(['maxlength' => 128,'autocomplete' => 'off'])?>
			</div>
			<div class="form-group"><?= $form->field($model, 'category_id',['template' => "{label}<div class='controls'>{input}</div>{hint}
			{error}"])->dropDownList($categoryname, ['prompt'=>'Select...']) ?></div>

			<div class="form-group"><?= $form->field($model, 'subcategory_id',['template' => "{label}<div class='controls'>{input}</div>{hint}
			{error}"])->dropDownList($subcategory, ['prompt'=>'Select...']) ?></div>

			<div class="form-group"><?= $form->field($model, 'child_category',['template' => "{label}<div class='controls'>{input}</div>{hint}
			{error}"])->dropDownList($childcategory, ['prompt'=>'Select...']) ?></div>

			<div class="form-group" style="height: 10px;">
			<input type="button" name="btnPrevious" class="btnNext btn btn-info" value="Next">
			</div>
		</div>
		<!--End First Tab -->

		<!--BEGIN second Tab -->
		<div class="tab-pane" id="2">

			<!-- BEGIN ITEM TYPE -->

			<div class="form-group"><?= $form->field($model, 'type_id',['template' => "{label}<div class='controls'>{input}</div>{hint}
			{error}"])->dropDownList($itemtype, ['prompt'=>'Select...']) ?></div>

			<!-- END ITEM TYPE -->

			<div class="form-group">
				<?= $form->field($model, 'item_description',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"])
				->label('Item description'.Html::tag('span', '*',['class'=>'required']))->textarea(['maxlength' => 128])?>
			</div>

			<div class="form-group">
				<?= $form->field($model, 'item_additional_info',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"])->textarea(['maxlength' => 128])?>
			</div>

			<input type="button" name="btnPrevious" class="btnPrevious btn btn-info" value="Prev">
			<input type="button" name="btnNext" class="btnNext btn btn-info" value="Next">
		</div>
		<!--End Second Tab -->

		<!--BEGIN Third Tab -->
		<div class="tab-pane" id="3">

			<input type="hidden" id="test" value="0" name="tests">
			<div class="form-group">
			<?= $form->field($model, 'item_for_sale',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}"
			])->checkbox(['Yes' => 'Yes'])?>
			</div>

			<div class="form-group">
				<?= $form->field($model, 'item_amount_in_stock',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"])
				->label('Item Number of Stock '.Html::tag('span', '*',['class'=>'required mandatory']))->textInput(['maxlength' => 128])?>
			</div>

			<div class="form-group">
				<?= $form->field($model, 'item_default_capacity',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"])
				->label('Item Default Capacity '.Html::tag('span', '*',['class'=>'required mandatory']))->textInput(['maxlength' => 128])?>
			</div>

			<div class="form-group">
				<?= $form->field($model, 'item_how_long_to_make',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"])
				->label('No of days delivery '.Html::tag('span', '*',['class'=>'required mandatory']))->textInput(['maxlength' => 128])?>
			</div>

			<div class="form-group">
				<?= $form->field($model, 'item_minimum_quantity_to_order',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"])
				->label('Item Minimum Quantity to Order '.Html::tag('span', '*',['class'=>'required mandatory']))->textInput(['maxlength' => 128])?>
			</div>

			<?php if($model->isNewRecord) { ?>
			<!-- BEGIN if type is sale -->
			<div class="form-group single_price">
				<?= $form->field($model, 'item_price_per_unit',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"])->textInput(['maxlength' => 128])?>
			</div>
			<!-- END if type is sale -->

			<!-- BEGIN if type is rental or service -->
			<div class="form-group multiple_price" style="padding: 5px;  font-size: 14px;">
				<div class="multi_pricing">Price range From - To </div>
				<div class="controls1"><input type="text" id="vendoritem-item_from" class="form-control from_range_1" name="vendoritem-item_price[from][]" multiple="multiple" placeholder="From range"><input type="text" id="vendoritem-item_to" class="form-control to_range_1" name="vendoritem-item_price[to][]" multiple="multiple" placeholder="To range"><input type="text" id="item_price_per_unit" class="form-control price_kd_1" name="vendoritem-item_price[price][]" multiple="multiple" placeholder="Price">KD<input type="button" name="remove" id="remove" value="Remove" class="remove_price" onclick="removePrice(this)"></div>
				<input type="button" class="add_price" name="addprice" id="addprice" value="Add more" onClick="addPrice(this);" />
			</div>

			<div class="form-group">
				<?= $form->field($model, 'item_price_description',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"])->textarea(['maxlength' => 128])?>
			</div>

			<div class="form-group custom_description">
				<?= $form->field($model, 'item_customization_description',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"])->textarea(['maxlength' => 128])?>
			</div>

			<div class="form-group guide_image">

			<?= $form->field($model, 'guide_image[]',['template' => "{label}<div class='controls append_address'>{input}</div> {hint} {error}"
					])->fileInput(['multiple' => true]) ?>

			</div>

			<?php } else if(!$model->isNewRecord){ ?>

			<div class="form-group single_price">
				<?= $form->field($model, 'item_price_per_unit',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"])->textInput(['maxlength' => 128])?>
			</div>

			<div class="form-group multiple_price" style="padding: 5px;  font-size: 14px;">
				<div class="multi_pricing">Price  From - To </div>

				<?php $t=0;
				foreach ($loadpricevalues as $value) {  ?>

				<div class="controls<?= $t; ?>"><input type="text" id="vendoritem-item_from" class="form-control from_range_<?= $t; ?>" name="vendoritem-item_price[from][]" multiple = "multiple" Placeholder="From range" value="<?= $value['range_from'];?>"><input type="text" id="vendoritem-item_to" class="form-control to_range_<?= $t; ?>" name="vendoritem-item_price[to][]" multiple = "multiple" Placeholder="To range" value="<?= $value['range_to'];?>"><input type="text" id="item_price_per_unit" class="form-control price_kd_<?= $t; ?>" name="vendoritem-item_price[price][]" multiple = "multiple" Placeholder="Price" value="<?= $value['pricing_price_per_unit'];?>">KD<input type="button" name="remove" id="remove" value="Remove" class="remove_price" onClick="removePrice(this)" /></div>

				<?php $t++; }?>
				<input type="button" class="add_price" name="addprice" id="addprice" value="Add more" onClick="addPrice(this);" />
			</div>

			<div class="form-group">
				<?= $form->field($model, 'item_price_description',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"])->textarea(['maxlength' => 128])?>
			</div>

			<div class="form-group custom_description">
				<?= $form->field($model, 'item_customization_description',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"])->textarea(['maxlength' => 128])?>
			</div>

			<!-- guide image -->
			<div class="form-group guide_image">
			<?= $form->field($model, 'guide_image[]',['template' => "{label}<div class='controls append_address'>{input}</div> {hint} {error}"
					])->fileInput(['multiple' => true]) ?>

			</div>
			<!-- BEGIN display exist images -->
			<?php
			 if(!empty($guideimagedata)) {

			         	$img1 = $action1 = '';
			         	foreach ($guideimagedata as $value) {
						$img1 .= '"<img src='.Yii::getAlias('@slider_uploads/').$value->image_path.' width=\'175\' height=\'125\' data-key='.$value->image_id.'>"'.',';
						$action1 .='{
						        url: "'.Url::to(['/vendoritem/deleteserviceguideimage']).'",
						        key: '.$value->image_id.',
						    }'.',';
							}

						$img1 = rtrim($img1,',');
						$action1 = rtrim($action1,',');
						}
			 }?>
			<!-- END display exist images -->

			<input type="button" name="btnPrevious" class="btnPrevious btn btn-info" value="Prev">
			<input type="button" name="btnNext" class="btnNext btn btn-info" value="Next">
		</div>
		<!--End third Tab -->

		<div class="tab-pane" id="4">
			<div class="form-group">
				<?= $form->field($model, 'item_approved',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"])->dropDownList([ 'Pending' => 'Pending','Yes' => 'Yes', 'Rejected'=>'Rejected']) ?>
			</div>

			<div class="form-group">

			<?= $form->field($model, 'item_status',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}"
			])->checkbox(['Value' => true])?>
			</div>

			<input type="button" name="btnPrevious" class="btnPrevious btn btn-info" value="Prev">
			<input type="button" name="btnNext" class="btnNext btn btn-info" value="Next">
		</div>
		<!--End fourth Tab -->

		<div class="tab-pane" id="5">
			<div class="file-block" style="color:red"> Please upload aleast a file</div>
			<div class="form-group">
				<?= $form->field($model, 'image_path[]',['template' => "{label}<div class='controls append_address'>{input}</div> {hint} {error}"
					])->fileInput(['multiple' => true]) ?>
			</div>

			<?php if(!$model->isNewRecord){
			 if(!empty($imagedata)) {
			         	$img= $action = '';
			         	foreach ($imagedata as $value) {
			       			$img .= '"<img src='.Yii::getAlias('@vendor_item_images_210/').$value->image_path.' width=\'175\' height=\'125\' data-key='.$value->image_id.'>"'.',';
			       			$action .='{
			       			        url: "'.Url::to(['/vendoritem/deleteitemimage']).'",
			       			        key: '.$value->image_id.',
			       			    }'.',';
			       				}

						$img = rtrim($img,',');
						$action = rtrim($action,',');
						}
			 }?>
			<input type="button" name="btnPrevious" class="btnPrevious btn btn-info" value="Prev">
			<input type="button" name="btnNext" class="btnNext btn btn-info" value="Next">
		</div>
		<!--End fifth Tab -->

		<div class="tab-pane" id="6">
			<!-- BEGIN Exist THEME list displayed on JSON format-->
			<div class="form-group"><?= $form->field($model, 'themes',['template' => "{label}<div class='controls'>{input}</div>{hint}
			{error}"])->dropDownList($themelist , ['multiple'=>'multiple',]) ?></div>
			 <!-- BEGIN Exist theme list displayed on JSON format-->
			 <div class="form-group"><?= $form->field($model, 'groups',['template' => "{label}<div class='controls'>{input}</div>{hint}
			{error}"])->dropDownList($grouplist , ['multiple'=>'multiple']) ?></div>

			 <!-- BEGIN Exist theme list displayed on JSON format-->

			<input type="button" name="btnPrevious" class="btnPrevious btn btn-info" value="Prev">

			<?= Html::submitButton($model->isNewRecord ? 'Complete' : 'Complete', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-success', 'style'=>'float:right;']) ?>
		</div>
		<!--End sixth Tab -->

		<!-- Begin Question Answer Part -->
		<div class="tab-pane" id="7">
			<div class="questionanswer" >
			<?php
				 $exist_question = Vendoritemquestion::find()->where( [ 'item_id' => $model->item_id ] )->count();

				if($exist_question >= 1) {
				$count_q=(count($model_question)); // for initial count questions used in javascript;
				 $t=0;
				 foreach($model_question as $question_records)
				 {
				?>
				 	<div class="form-group superbox-s" id="delete_<?= $t;?>">
					<li id="question-section_0" class="parent_question_<?= $question_records['question_id']; ?>"> <span class="question_title"> <?= $question_records['question_text']; ?></span> <span class="plus"><a href="#" onclick="questionView('<?= $question_records['question_id']; ?>',this)" ></a></span><div class="show_ques<?= $question_records['question_id']; ?>"></div></li>
				</div>
				<?php $t++;}	?>
				<input type="button" name="add" id="add" value="Add Question" onclick="addAddress(this)" style="margin:10px 0px;">
			<?php

			} else {
				$count_q=1;
				$h_id =0;
				?>
			<div class="form-group">
				<div id="question-section" class="question-section">
				<input type="hidden" name="parent_id" id="adds" value="0" class="form-control temp_qa">
				Question <input type="text" id="question_text_0" class="form-control temp_qa" name="Vendoritemquestion[0][question_text][]" style="margin:10px 0px;"> Question Type
					<div class="append_address">
						<select id="vendoritemquestion-question_answer_type0" class="form-control vendoritemquestion-question_answer_type temp_qa" name="Vendoritemquestion[0][question_answer_type][]" parent_id="0" style="margin:10px 0px;">
						<option value="">Choose type</option>
						<option value="text">Text</option>
						<option value="image">Image</option>
						<option value="selection">Selection</option></select>
					</div>
					</div>
				</div>
					<div class="question">
					</div>
				<input type="button" name="add" id="add" value="Add Question" onclick="addAddress(this)" style="margin:10px 0px;">
			<?php } ?>

			<!-- Question Answer Part	End	-->
			<div class="form-groups" >
				<input type="button" name="btnPrevious" class="btnPrevious btn btn-info" value="Prev" />
				<?= Html::a('Back to Manage', ['index', ], ['class' => 'btn btn-info', 'style'=>'float:right;']) ?>
			</div>

			</div><!-- END .questionanswer -->
		</div><!-- END tab-7 -->
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

$this->registerJs("
	var count_q = '".$count_q."';
	var appImageUrl = '".Yii::getAlias('appImageUrl')."';
	var image_order_url = '".Url::to(['/image/imageorder'])."';
	var deletequestionoptions_url = '".Url::to(['/vendoritemquestionansweroption/deletequestionoptions'])."';
	var salesguideimage_url = '".Url::to(['/vendoritem/salesguideimage'])."';
	var request_create = '".$request->get('create')."';
	var isNewRecord = ".$isNewRecord.";
	var item_for_sale = '".$model->item_for_sale."';
	var item_status = '".$model->item_status."';
	var item_id = ".$item_id.";
	var item_name_check = '".Url::to(['/vendoritem/itemnamecheck'])."';
	var add_question_url = '".Url::to(['/vendoritem/addquestion'])."';
	var guideimage_url = '".Url::to(['/vendoritem/guideimage'])."';
	var exist_question = '".$exist_question."'; 
	var removequestion_url = '".Url::to(['/vendoritem/removequestion'])."';
	var vendorcategory_url = '".Url::to(['/category/vendorcategory'])."';
	var loadsubcategory_url = '".Url::to(['/priorityitem/loadsubcategory'])."';
	var loadchildcategory_url = '".Url::to(['/priorityitem/loadchildcategory'])."';
	var renderquestion_url = '".Url::to(['/vendoritem/renderquestion'])."';
",View::POS_HEAD);

$this->registerCssFile("@web/themes/default/plugins/bootstrap-fileinput/fileinput.min.css");

$this->registerCssFile("@web/themes/default/plugins/bootstrap-multiselect/dist/css/bootstrap-multiselect.css");

$this->registerCssFile("@web/themes/default/plugins/jquery-superbox/css/style.css");

$this->registerJsFile("@web/themes/default/plugins/jquery-superbox/js/superbox.js", ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerJsFile('@web/themes/default/plugins/ckeditor/ckeditor.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerJsFile("@web/themes/default/plugins/bootstrap-fileinput/fileinput.min.js", ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerJsFile("@web/themes/default/plugins/bootstrap-multiselect/dist/js/bootstrap-multiselect.js", ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerJsFile("@web/themes/default/js/vendor_item_validation.js", ['depends' => [\yii\web\JqueryAsset::className()]]);

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
");


