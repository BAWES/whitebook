<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
//use dosamigos\fileupload\FileUploadUI;
use common\models\Vendoritemquestion;
use yii\web\View;

/* @var $model common\models\Vendoritem */
/* @var $form yii\widgets\ActiveForm */
use kartik\file\FileInput;

if($model->isNewRecord){
	$childcategory = array();
	$exist_themes = array();
	$exist_groups = array();
}

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
			<li class="active"><a href="#1" data-toggle="tab">Item Info </a></li>
			<li><a href="#2" data-toggle="tab" id="validone1">Item description</a></li>
			<li><a href="#3" data-toggle="tab" id="validtwo2"> Item price </a></li>
			<li><a href="#5" data-toggle="tab" id="validthree3">Images</a></li>
		</ul>
		<div class="tab-content">

			<div class="tab-pane" id="1" >

				<div class="form-group"><?= $form->field($model, 'item_name',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"])->textInput(['maxlength' => 128]) ?></div>
				<div class="form-group"><?= $form->field($model, 'item_name_ar',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"])->textInput(['maxlength' => 128]) ?></div>
				<div class="form-group"><?= $form->field($model, 'category_id', ['template' => "{label}<div class='controls'>{input}</div>{hint} {error}"])->dropDownList($categoryname, ['prompt'=>'Select...']) ?></div>
				<div class="form-group"><?= $form->field($model, 'subcategory_id',['template' => "{label}<div class='controls'>{input}</div>{hint} {error}"])->dropDownList($subcategory, ['prompt'=>'Select...']) ?></div>
				<div class="form-group"><?= $form->field($model, 'child_category',['template' => "{label}<div class='controls'>{input}</div>{hint} {error}"])->dropDownList($childcategory, ['prompt'=>'Select...']) ?></div>
				<div class="form-group"><?= $form->field($model, 'item_status',['template' => "{label}<div class='controls'>{input}</div>{hint} {error}"])->dropDownList(['Active'=>'Yes','Deactive'=>'No']) ?></div>
				<div class="form-group" style="height: 10px;"><input type="button" name="btnPrevious" class="btnNext btn btn-info" value="Next" /></div>
			</div>
			<!--End First Tab -->

			<!--BEGIN second Tab -->
			<div class="tab-pane" id="2">
				<div class="form-group"><?= $form->field($model, 'type_id',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"])->dropDownList($itemtype, ['prompt'=>'Select...']) ?></div>
				<div class="form-group"><?= $form->field($model, 'item_description',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"])->label('Item description'.Html::tag('span', '*',['class'=>'required']))->textarea(['maxlength' => 128])?></div>
				<div class="form-group"><?= $form->field($model, 'item_description_ar',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"])->label('Item description - Arabic'.Html::tag('span', '*',['class'=>'required']))->textarea(['maxlength' => 128])?></div>
				<div class="form-group"><?= $form->field($model, 'item_additional_info',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"])->textarea(['maxlength' => 128])?></div>
				<div class="form-group"><?= $form->field($model, 'item_additional_info_ar',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"])->textarea(['maxlength' => 128])?></div>
				<input type="button" name="btnPrevious" class="btnPrevious btn btn-info" value="Prev">
				<input type="button" name="btnNext" class="btnNext btn btn-info" value="Next">
			</div>

			<!--End Second Tab -->

			<!--BEGIN Third Tab -->
			<div class="tab-pane" id="3">
				<input type="hidden" id="test" value="0" name="tests">
				<div class="form-group">
					<?= $form->field($model, 'item_for_sale',['template' => "{label}<div class='controls'>{input}</div>{hint} {error}"])->dropDownList(['Yes'=>'Yes','No'=>'No']) ?>
				</div>

				<div class="form-group"><?= $form->field($model, 'item_amount_in_stock',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"])->label('Item Number of Stock '.Html::tag('span', '*',['class'=>'required mandatory']))->textInput(['maxlength' => 128])?></div>
				<div class="form-group"><?= $form->field($model, 'item_default_capacity',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"])->label('Item Default Capacity '.Html::tag('span', '*',['class'=>'required mandatory']))->textInput(['maxlength' => 128])?></div>
				<div class="form-group"><?= $form->field($model, 'item_how_long_to_make',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"])->label('No of days delivery '.Html::tag('span', '*',['class'=>'required mandatory']))->textInput(['maxlength' => 128])?></div>
				<div class="form-group"><?= $form->field($model, 'item_minimum_quantity_to_order',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"])->label('Item Minimum Quantity to Order '.Html::tag('span', '*',['class'=>'required mandatory']))->textInput(['maxlength' => 128])?></div>
				<div class="form-group"><?= $form->field($model, 'item_price_per_unit',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"])->textInput(['maxlength' => 128])?></div>

				<?php if($model->isNewRecord) { ?>
					<div class="form-group multiple_price" style="padding: 5px;  font-size: 14px;">
						<div class="multi_pricing">Price range From - To </div>
						<div class="controls1"><input type="text" id="vendoritem-item_from" class="form-control from_range_1" name="vendoritem-item_price[from][]" multiple="multiple" placeholder="From range"><input type="text" id="vendoritem-item_to" class="form-control to_range_1" name="vendoritem-item_price[to][]" multiple="multiple" placeholder="To range"><input type="text" id="item_price_per_unit" class="form-control price_kd_1" name="vendoritem-item_price[price][]" multiple="multiple" placeholder="Price">KD<input type="button" name="remove" id="remove" value="Remove" class="remove_price" onclick="removePrice(this)"></div>
						<input type="button" class="add_price" name="addprice" id="addprice" value="Add more" onClick="addPrice(this);" />
					</div>
				<?php } else { ?>
					<div class="form-group multiple_price" style="padding: 5px;  font-size: 14px;">
					<div class="multi_pricing">Price  From - To </div>
					<?php $t=0;
					foreach ($loadpricevalues as $value) { ?>
					<div class="controls<?= $t; ?>"><input type="text" id="vendoritem-item_from" class="form-control from_range_<?= $t; ?>" name="vendoritem-item_price[from][]" multiple = "multiple" Placeholder="From range" value="<?= $value['range_from'];?>"><input type="text" id="vendoritem-item_to" class="form-control to_range_<?= $t; ?>" name="vendoritem-item_price[to][]" multiple = "multiple" Placeholder="To range" value="<?= $value['range_to'];?>"><input type="text" id="item_price_per_unit" class="form-control price_kd_<?= $t; ?>" name="vendoritem-item_price[price][]" multiple = "multiple" Placeholder="Price" value="<?= $value['pricing_price_per_unit'];?>">KD<input type="button" name="remove" id="remove" value="Remove" class="remove_price" onClick="removePrice(this)" /></div>
					<?php $t++; } ?>
					<input type="button" class="add_price" name="addprice" id="addprice" value="Add more" onClick="addPrice(this);" />
				</div>
				<?php } ?>

				<div class="form-group"><?= $form->field($model, 'item_price_description',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"])->textarea([])?></div>
				<div class="form-group"><?= $form->field($model, 'item_price_description_ar',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"])->textarea([])?></div>
				<div class="form-group custom_description"><?= $form->field($model, 'item_customization_description',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"])->textarea(['maxlength' => 128])?></div>
				<div class="form-group custom_description_ar"><?= $form->field($model, 'item_customization_description_ar',['template' => "{label}<div class='controls'>{input}</div>{hint}{error}"])->textarea(['maxlength' => 128])?></div>

				<!-- guide image -->
				<?php if ($model->item_for_sale == 'No') { ?>
					<div class="form-group guide_image" >
				<?php } else { ?>
						<div class="form-group guide_image" style="display: none;">
				<?php } ?>

					<?php
					$initialPreview = [];
					$initialPreviewConfig = [];
					if(!empty($guide_images)) {

						$i=0;
						foreach ($guide_images as $value) {
							$key = $value->image_id;
							$initialPreview[] = Html::img(Yii::getAlias('@sales_guide_images/').$value->image_path, [ 'style'=>'width:143px;height:160px;','alt'=>'', 'data-key'=>$value->image_id,'title'=>'']);
							$url = Url::to(["/vendoritem/delete-service-guide-image", "id" => $key]);
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

				<input type="button" name="btnPrevious" class="btnPrevious btn btn-info" value="Prev">
				<input type="button" name="btnNext" class="btnNext btn btn-info" value="Next">
			</div>
			<!--End Third Tab -->

			<div class="tab-pane" id="4">
				<div class="file-block" style="color:red"> Please upload aleast one file</div>
				<input type="button" name="btnPrevious" class="btnPrevious btn btn-info" value="Prev">
				<input type="button" name="btnNext" class="btnNext btn btn-info" value="Next">
			</div>
			<!--End Third Tab -->

			<div class="tab-pane" id="5">
				<div class="form-group">
					<div class="file-block" style="color:red"> Please upload aleast one file</div>
					<?php

					$order = [];
					$initialPreview = [];
					$initialPreviewConfig = [];
						if(!empty($images)) {
							$i=0;
							foreach ($images as $value) {
								$order[] = ['image_id'=>$value->image_id,'vendorimage_sort_order'=>$value->vendorimage_sort_order,'image_path'=>$value->image_path];
								usort($order, "cmp");

							}
							foreach ($order as $value) {
								$key = $value['image_id'];
								$initialPreview[] = Html::img(Yii::getAlias('@vendor_item_images_210/').$value['image_path'], [ 'style'=>'width:143px;height:160px;','alt'=>'', 'data-key'=>$value['image_id'],'title'=>'']);
								$url = Url::to(["/vendoritem/delete-item-image", "id" => $key]);
								$initialPreviewConfig[] = ["width" => "120px", 'url' => $url, 'key' => $key];
								$i++;
							}
						}

					// Usage with ActiveForm and model
					echo $form->field($model, 'image_path[]')->widget(FileInput::classname(), [
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

				<div class="form-group"><?= Html::submitButton($model->isNewRecord ? 'Complete' : 'Complete', ['class' => $model->isNewRecord ? 'btn btn-success complete' : 'btn btn-primary complete','style'=>'float:right;']) ?></div>
				<input type="button" name="btnPrevious" class="btnPrevious btn btn-info" value="Prev">
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
");
echo Html::hiddenInput('isNewRecord',$model->isNewRecord,['id'=>'isNewRecord']);
echo Html::hiddenInput('item_for_sale',$model->item_for_sale,['id'=>'item_for_sale']);
echo Html::hiddenInput('item_status',$model->item_status,['id'=>'item_status']);
echo Html::hiddenInput('item_id',Yii::$app->request->get('id'),['id'=>'item_id']);

echo Html::hiddenInput('load_sub_category_url',Url::to(['/priorityitem/loadsubcategory']),['id'=>'load_sub_category_url']);
echo Html::hiddenInput('load_child_category_url',Url::to(['/priorityitem/loadchildcategory']),['id'=>'load_child_category_url']);

echo Html::hiddenInput('image_delete_url',Url::to(['vendoritem/imagedelete']),['id'=>'image_delete_url']);
echo Html::hiddenInput('remove_question_url',Url::to(['vendoritem/removequestion']),['id'=>'remove_question_url']);

echo Html::hiddenInput('render_question_url',Url::to(['vendoritem/renderquestion']),['id'=>'render_question_url']);
echo Html::hiddenInput('item_name_check_url',Url::to(['/vendoritem/itemnamecheck']),['id'=>'item_name_check_url']);
echo Html::hiddenInput('image_order_url',Url::to(['/site/imageorder']),['id'=>'image_order_url']);

$this->registerJsFile('@web/themes/default/plugins/bootstrap-multiselect/dist/js/bootstrap-multiselect.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('@web/themes/default/plugins/bootstrap-fileinput/fileinput.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('@web/themes/default/plugins/ckeditor/ckeditor.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('@web/themes/default/js/vendor_item_validation.js?v=1.1', ['depends' => [\yii\web\JqueryAsset::className()]]);

