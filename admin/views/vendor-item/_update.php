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
	    <li class="active"><a href="#1" data-toggle="tab">Item Info </a></li>
	    <li><a href="#2" data-toggle="tab" id="validone1">Item description</a></li>
	    <li><a href="#3" data-toggle="tab" id="validtwo2"> Item price </a></li>
	    <li><a href="#4" data-toggle="tab" id="validthree3"> Approval </a></li>
	    <li><a href="#5" data-toggle="tab" id="validfour4">Images</a></li>
	    <li><a href="#6" data-toggle="tab" id="validfive5">Themes & Groups</a></li>

	    <?php if($model->item_for_sale =='Yes') {?>
	    	<li><a href="#7" data-toggle="tab" id="validsix6"> Questions </a></li>
	    <?php } ?>
	</ul>

	<div class="tab-content">
		<!-- Begin First Tab -->
		<div class="tab-pane active clearfix" id="1">
			<!-- vid - click create item button from item view page for the particular item view page-->
			
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
				<table class="table table-bordered table-category-list">
					<tbody>
						<?php foreach($vendor_item_to_category as $key => $value) { ?>
						<tr>	
							<td>
								<?= $value->category->category_title ?>
								<input value="<?= $value->category_id ?>" name="category[]" type="hidden" />	
							</td>	
							<td>		
								<button class="btn btn-danger" type="button">			
									<i class="glyphicon glyphicon-trash"></i>
								</button>	
							</td>
						</tr>
						<?php } ?>
					</tbody>
					<tfoot>
						<tr>
							<td>
								<select id="category_id">
									<option></option>
									<?php foreach($categories as $key => $value) { ?>
										<option value="<?= $value['category_id'] ?>">
											<?= $value['category_name'] ?>
										</option>
									<?php } ?>
								</select>
								<span class="help-block"></span>
							</td>
							<td>
								<button type="button" class="btn btn-primary btn-add-category">Add</button>
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

            	<?php if ($model->item_for_sale == 'No') { ?>
            	<div class="form-group guide_image" >
                <?php } else { ?>
                <div class="form-group guide_image" style="display: none;">
                    <?php } ?>

                    <?php
                    $initialPreview = [];
                    $initialPreviewConfig = [];
                    if(!empty($guideImages)) {

                        $i=0;
                        foreach ($guideImages as $value) {
                            $key = $value->image_id;
                            $initialPreview[] = Html::img(Yii::getAlias('@sales_guide_images/').$value->image_path, [ 'style'=>'width:143px;height:160px;','alt'=>'', 'data-key'=>$value->image_id,'title'=>'']);
                            $url = Url::to(["/vendor-item/deleteserviceguideimage", "id" => $key]);
                            $initialPreviewConfig[] = ["width" => "120px", 'url' => $url, 'key' => $key];
                            $i++;
                        }
                    }

                    // Usage with ActiveForm and model
                    echo $form->field($model, 'guide_image[]',['template' => "{label}<div class='controls append_address'>{input}</div> {hint} {error}"])->widget(FileInput::classname(), [
                        'options' => [
                            'accept' => 'image/*',
                            'multiple' => true,

                        ],
                        'pluginOptions'=>[
                            'browseClass' => 'btn btn-primary btn-block',
                            'browseIcon' => ' ',
                            'browseLabel' => 'Select Photo',
                            'showUpload'=>false,
							'showRemove'=>false,
                            'overwriteInitial'=> false,
                            'initialPreview' => $initialPreview,
                            'initialPreviewConfig' => $initialPreviewConfig,
                            //'uploadUrl' => '/dummy/dummy',
                        ]
                    ]);
                    ?>
                </div>
			<!-- END display exist images -->
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

		<div class="tab-pane clearfix" id="4">
			
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

		<div class="tab-pane clearfix" id="5">
			<div class="file-block" style="color:red; display: none;"> Please upload aleast a file</div>

			<div class="alert alert-info">
				<button class="close" data-dismiss="alert"></button>
				Steps 
				<ul>
					<li>Select image by clicking on "Choose File" from top left side.</li>
					<li>Move image in image preview area to get required image area, if image bigger than 530x530.</li>
					<li>
						Click on Upload button below preview area to upload image, wait for seconds. Image will get listed in right size.
					</li>
				</ul>
			</div>

			<div class="row">
				<div class="col-lg-7">
					
					<p>Select, crop and upload image.</p>

					<div class="image-editor">
				        <input type="file" class="cropit-image-input" />
				        <p style="color: red;">Minimum image size : 530 x 530</p>
				        <div class="cropit-preview"></div>
				        <div class="image-size-label">
				          Resize image
				        </div>
				        <input type="range" class="cropit-image-zoom-input">
				        <button type="button" class="btn btn-primary btn-crop-upload">Upload</button>
				    </div>
				</div>
				<div class="col-lg-5">
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

		<div class="tab-pane clearfix" id="6">
			<div class="form-group clearfix padding-top-bottom">
				<?php echo $form->field($model, 'themes')->checkboxlist($themelist);?>
				<?php //$form->field($model, 'themes',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"])->dropDownList($themelist , ['multiple'=>'multiple',]) ?>
			</div>
			<div class="border-top"></div>
			<div class="padding-top-bottom form-group clearfix">
				<?=$form->field($model, 'groups')->checkboxlist($grouplist);?>
				<?php //$form->field($model, 'groups',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"])->dropDownList($grouplist , ['multiple'=>'multiple']) ?>
			</div>
			<input type="button" name="btnPrevious" class="btnPrevious btn btn-info" value="Prev">
			
			<?= Html::submitButton('Complete', [
				'class' => 'btn btn-success complete', 
				'style'=>'float:right;']) ?>
		</div>
		<!--End sixth Tab -->

		<!-- Begin Question Answer Part -->
		<div class="tab-pane" id="7">
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
		</div><!-- END tab-7 -->
	</div><!-- END .tab-content -->
</div><!-- END .tabbable -->
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

$this->registerCssFile("@web/themes/default/plugins/bootstrap-fileinput/fileinput.min.css");
$this->registerCssFile("@web/themes/default/plugins/bootstrap-multiselect/dist/css/bootstrap-multiselect.css");
$this->registerCssFile("@web/themes/default/plugins/jquery-superbox/css/style.css");
$this->registerJsFile("@web/themes/default/plugins/jquery-superbox/js/superbox.js", ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('@web/themes/default/plugins/bootstrap-fileinput/fileinput.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('@web/themes/default/plugins/ckeditor/ckeditor.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile("@web/themes/default/plugins/bootstrap-multiselect/dist/js/bootstrap-multiselect.js", ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerJsFile("@web/themes/default/js/jquery.cropit.js", ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerJsFile("@web/themes/default/js/vendor_item_validation.js?v=1.5", ['depends' => [\yii\web\JqueryAsset::className()]]);

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
	#vendoritem-groups label,#vendoritem-themes label {float: left;min-width: 15%;margin-right: 43px;}
	.border-top{border-top: 1px solid;}
	.padding-top-bottom{padding: 36px 0;}
");


