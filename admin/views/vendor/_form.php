<?php

use yii\web\View;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="col-md-12 col-sm-12 col-xs-12">

	<?php $form = ActiveForm::begin(['options'=>['enctype'=>'multipart/form-data']]);?>

		<input type="hidden" name="vendor_id" value="<?= $model->vendor_id ?>" />

		<div class="loadingmessage" style="display: none;">
		<p>
		<?= Html::img(Yii::getAlias('@web/themes/default/img/loading.gif'), ['class'=>'','width'=>'64px','height'=>'64px','id'=>'loading','alt'=>'loading']);?>
		</p>
		</div>

		<div class="message_wrapper"></div>

		<!-- Begin Twitter Tabs-->
		<div class="tabbable">
		  <ul class="nav nav-tabs">
		    <li class="active">
		      <a href="#1" data-toggle="tab" id="tab_1">Vendor Logo </a>
		    </li>
		    <li>
		      <a href="#2" id="tab_2">Basic Info </a>
		    </li>
		    <li>
		      <a href="#3" id="tab_3">Main Info</a>
		    </li>
		    <li>
		      <a href="#4" id="tab_4">Additional Info</a>
		    </li>
		    <li>
		      <a href="#5" id="tab_5">Social Info</a>
		    </li>
		    <li>
		    	<a href="#6" id="tab_6">Email addresses</a>
		    </li>
		  </ul>
		  <div class="tab-content">

		  	<div class="tab-pane" id="1">
		    	<label class="control-label">Vendor logo</label>

		    	<div class="pull-left">
		    		<div class="image-editor">
				        <input type="file" class="cropit-image-input">
				        <p style="color: red;">
				    		Minimum image dimension : 450px x 450px
				    	</p>
				        <div class="cropit-preview" style="width: 450px; height: 450px;">
				        	<img class="cropit-preview-image" alt="" src="https://placeholdit.imgix.net/~text?txtsize=20&txt=Drag%20and%20Drop%20Image%20Here
					        	&w=450&h=450" style="transform-origin: left top 0px; will-change: transform;" />
				        </div>
				        <div class="image-size-label">
				          Resize image
				        </div>
				        <input type="range" class="cropit-image-zoom-input" style="width: 450px;" />

				        <button type="button" class="btn btn-primary btn-crop-upload">Upload</button>
				    </div>
			    </div>
			    <div class="pull-right">
			    	<h3>Your uploaded logo will display here</h3>
			    	<input type="hidden" name="Vendor[vendor_logo_path]" id="inpt_vendor_logo_path" value="<?= $model->vendor_logo_path ?>" />
			    	<div class="thumbnail vendor-logo-thumbnail">
			    		<?php if($model->vendor_logo_path) { ?>
			    		<img src="<?= Yii::getAlias('@vendor_logo') . '/' . $model->vendor_logo_path ?>" />
			    		<?php }else { ?>
			    		<img src="https://placeholdit.imgix.net/~text?txtsize=20&txt=uploaded logo will display here &w=450&h=450" />
			    		<?php } ?>
			    	</div>
			    </div>

			    <div class="clearfix"></div>

			    <hr />

			    <div class="form-group" style="height: 10px;">
					<input type="button" class="btn btn-info btnNext" value="Next" />
					<?= Html::a('Back', ['index'], ['class' => 'btn btn-default']) ?>
				</div>
		  	</div>

		    <div class="tab-pane" id="2" >

		    	<?= $form->field($model, 'vendor_name')
		    			->textInput(['maxlength' => 100, 'autocomplete' => 'off']) ?>

				<?= $form->field($model, 'vendor_name_ar')
						->textInput(['maxlength' => 100, 'autocomplete' => 'off']) ?>

				<?= $form->field($model, 'vendor_contact_email')
						->textInput(['maxlength' => 100, 'autocomplete' => 'off']); ?>

				<?php if($model->isNewRecord) { ?>
					<?= $form->field($model, 'vendor_password')->passwordInput() ?>
					<?= $form->field($model, 'confirm_password')->passwordInput() ?>
				<?php } ?>

				<input type="hidden" name="email_valid" value="" />
			 	<div class="form-group"><?= $form->field($model, 'vendor_contact_name',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}"])->textInput(['maxlength' => 100,'autocomplete' => 'off']) ?></div>

				<?php if($model->isNewRecord) { $count_vendor = 1;?>
				<div class="form-group" style="border: 1px solid #ccc;  padding: 5px;  font-size: 14px;">
				<?= $form->field($model, 'vendor_contact_number[]',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}"
				])->textInput(['multiple' => 'multiple','autocomplete' => 'off']) ?>
				<input type="button" name="add_item" id="addnumber" value="Add phone numbers" onClick="addPhone('current');" />
				</div>
				<?php } else { ?>
				<div class="form-group" style="border: 1px solid #ccc;  padding: 5px;  font-size: 14px;">
					<label class="control-label" for="vendor-vendor_contact_number">Contact Phone Number</label>
				<?php
				$i =1;
				$count_vendor =  count($vendor_contact_number);
				foreach($vendor_contact_number as $contact_numbers)
				{ ?>
				<?= $form->field($model, 'vendor_contact_number[]',[  'template' => "<div class='controls".$i."'>{input}<input type='button' name='remove' id='remove' value='Remove' onClick='removePhone(".$i.")' style='margin:5px;' /></div> {hint} {error}"])->textInput(['multiple' => 'multiple','autocomplete' => 'off','value'=>$contact_numbers]) ?>

				<?php $i++; } ?>
				<input type="button" name="add_item" id="addnumber" value="Add phone numbers" onClick="addPhone('current');" style="margin:5px;" />
				</div>
				<?php } ?>

				<?= $form->field($model, 'vendor_contact_address')->textArea(); ?>

				<?= $form->field($model, 'vendor_contact_address_ar')->textArea(); ?>

				<div class="working_hours_wrapper row">
					<label class="col-md-12">Working hours</label>
					<div class="col-md-2">
						<?= $form->field($model, 'vendor_working_hours', [
								'template' => '{input}'
							])->textInput([
								'placeholder' => 'From'
							]); ?>
					</div>
					<div class="col-md-2">
						<?= $form->field($model, 'vendor_working_hours_to', [
								'template' => '{input}'
							])->textInput([
								'placeholder' => 'To'
							]); ?>
					</div>
				</div>

				<div class="form-group">
					<label>Days off</label>
					<div class="checkbox-inline">
						<label for="day_1">
							<input type="checkbox" name="vendor_day_off[]" value="1" id="day_1" <?php if(in_array('1', $day_off)) echo 'checked'; ?> />
							Monday
						</label>
					</div>
					<div class="checkbox-inline">
						<label for="day_2">
							<input type="checkbox" name="vendor_day_off[]" value="2" id="day_2" <?php if(in_array('2', $day_off)) echo 'checked'; ?> />
							Tuesday
						</label>
					</div>
					<div class="checkbox-inline">
						<label for="day_3">
							<input type="checkbox" name="vendor_day_off[]" value="3" id="day_3" <?php if(in_array('3', $day_off)) echo 'checked'; ?> />
							Wednesday
						</label>
					</div>
					<div class="checkbox-inline">
						<label for="day_4">
							<input type="checkbox" name="vendor_day_off[]" value="4" id="day_4" <?php if(in_array('4', $day_off)) echo 'checked'; ?> />
							Thirsday
						</label>
					</div>
					<div class="checkbox-inline">
						<label for="day_5">
							<input type="checkbox" name="vendor_day_off[]" value="5" id="day_5" <?php if(in_array('5', $day_off)) echo 'checked'; ?> />
							Friday
						</label>
					</div>
					<div class="checkbox-inline">
						<label for="day_6">
							<input type="checkbox" name="vendor_day_off[]" value="6" id="day_6" <?php if(in_array('6', $day_off)) echo 'checked'; ?> />
							Saturday
						</label>
					</div>
					<div class="checkbox-inline">
						<label for="day_0">
							<input type="checkbox" name="vendor_day_off[]" value="0" id="day_0" <?php if(in_array('0', $day_off)) echo 'checked'; ?> />
							Sunday
						</label>
					</div>
				</div>

				<?= $form->field($model, 'vendor_public_email'); ?>

				<?= $form->field($model, 'vendor_website') ?>

				<div class="form-group">
					<label>Vendor public phone</label>
					<table class="table table-bordered table-phone-list">
						<thead>
							<tr>
								<th>Phone no</th>
								<th>Type</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($phones as $key => $value) { ?>
							<tr>
								<td>
									<input value="<?= $value->phone_no ?>" name="phone[<?= $key ?>][phone_no]" class="form-control" />
								</td>
								<td>
									<select name="phone[<?= $key ?>][type]" class="form-control">
										<option <?php if($value->type == 'Office') echo 'selected'; ?>
											value="Office">Office</option>
									 	<option <?php if($value->type == 'Mobile') echo 'selected'; ?>
									 		value="Mobile">Mobile
									 	</option>
									 	<option <?php if($value->type == 'Fax') echo 'selected'; ?>
									 		value="Fax">Fax
									 	</option>
									 	<option <?php if($value->type == 'Whatsapp') echo 'selected'; ?>
									 		value="Whatsapp">Whatsapp
									 	</option>
									</select>
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
								<td colspan="3">
									<button type="button" class="btn btn-primary btn-add-phone-no">
										Add new phone no
									</button>
								</td>
							</tr>
						</tfoot>
					</table>
				</div>

				<div class="form-group" style="height: 10px;">
					<input type="button" class="btn btn-info btnNext" value="Next" />
					<?= Html::a('Back', ['index', ], ['class' => 'btn btn-default']) ?>
				</div>
			</div>

			<div class="tab-pane" id="3">
				<input type="hidden" id="test1" value="0" name="tests">
				<input type='hidden' id='test' value='0' name='tests1'>

				<?= $form->field($model, 'vendor_status')->checkbox(['Active' => 'Active']); ?>

				<hr />

				<?= $form->field($model, 'category_id')->checkboxList($main_categories)->label('Categories'); ?>

				<input type="button" name="btnPrevious" class="btnPrevious btn btn-info" value="Prev" />
				<input type="button" name="btnNext" class="btnNext btn btn-info" value="Next" />

			</div>

		    <div class="tab-pane" id="4">

				<?= $form->field($model, 'vendor_return_policy')->textArea(['id'=>'text-editor']); ?>

				<?= $form->field($model, 'vendor_return_policy_ar')->textArea(['id'=>'text-editor-2']); ?>

				<?= $form->field($model, 'vendor_fax')->textInput(); ?>

				<?= $form->field($model, 'short_description')->textArea(); ?>

				<?= $form->field($model, 'short_description_ar')->textArea(); ?>

				<?= $form->field($model, 'vendor_bank_name')->textInput(); ?>

				<?= $form->field($model, 'vendor_bank_branch')->textInput(); ?>

				<?= $form->field($model, 'vendor_account_no')->textInput(); ?>

				<?= $form->field($model, 'vendor_emergency_contact_name'); ?>

				<?= $form->field($model, 'vendor_emergency_contact_email'); ?>

				<?= $form->field($model, 'vendor_emergency_contact_number'); ?>

				<input type="button" name="btnPrevious" class="btnPrevious btn btn-info" value="Prev" />
				<input type="button" name="btnNext" class="btnNext btn btn-info" value="Next" />
			</div>

			<div class="tab-pane" id="5">

				<table class="table table-bordered table-social">
					<thead>
						<tr>
							<th></th>
							<th>Text</th>
							<th>Link</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Instagram</td>
							<td>
								<?= $form->field($model, 'vendor_instagram_text', [
										'template' => '{input}{error}'
									])->textInput([
										'placeholder' => 'Instagram link text'
									]); ?>
							</td>
							<td>
								<?= $form->field($model, 'vendor_instagram', [
										'template' => '{input}{error}'
									])->textInput([
										'placeholder' => 'Instagram link url'
									]); ?>
							</td>
						</tr>
						<tr>
							<td>Twitter</td>
							<td>
								<?= $form->field($model, 'vendor_twitter_text', [
										'template' => '{input}{error}'
									])->textInput([
										'placeholder' => 'Twitter link text'
									]); ?>
							</td>
							<td>
								<?= $form->field($model, 'vendor_twitter', [
										'template' => '{input}{error}'
									])->textInput([
										'placeholder' => 'Twitter link url'
									]); ?>
							</td>
						</tr>
						<tr>
							<td>Facebook</td>
							<td>
								<?= $form->field($model, 'vendor_facebook_text', [
										'template' => '{input}{error}'
									])->textInput([
										'placeholder' => 'Facebook link text'
									]); ?>
							</td>
							<td>
								<?= $form->field($model, 'vendor_facebook', [
										'template' => '{input}{error}'
									])->textInput([
										'placeholder' => 'Facebook link url'
									]); ?>
							</td>
						</tr>
						<tr>
							<td>Youtube</td>
							<td>
								<?= $form->field($model, 'vendor_youtube_text', [
										'template' => '{input}{error}'
									])->textInput([
										'placeholder' => 'Youtube link text'
									]); ?>
							</td>
							<td>
								<?= $form->field($model, 'vendor_youtube', [
										'template' => '{input}{error}'
									])->textInput([
										'placeholder' => 'Youtube link url'
									]); ?>
							</td>
						</tr>
					</tbody>
				</table>

				<div class="form-groups">
					<input type="button" name="btnPrevious" class="btnPrevious btn btn-info" value="Prev">
					<input type="button" name="btnNext" class="btnNext btn btn-info" value="Next">
				</div>
		 	</div>

			<div class="tab-pane" id="6">
				Email address list to get order notification
				<br />
				<br />

				<table class="table table-bordered table-email-list">
					<tbody>
						<tr>
							<th>Email address</th>
							<th></th>
						</tr>
						<?php foreach ($vendor_order_alert_emails as $key => $value) { ?>
						<tr>
							<td>
								<input value="<?= $value->email_address ?>" name="vendor_order_alert_emails[]" class="form-control" />
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
							<td colspan="2">
								<button type="button" class="btn btn-primary btn-add-address">Add new address</button>
							</td>
						</tr>
					</tfoot>
				</table>

				<div class="form-group">
					<input type="button" name="btnPrevious" class="btnPrevious btn btn-info" value="Prev">

					<?= Html::submitButton('Complete', ['class' => 'btn btn-complete btn-primary', 'style'=>'float:right;']) ?>

				</div>
			</div>

		<?php ActiveForm::end(); ?>
		</div>
		<!--End Third Tab -->
	</div>

<?php

$this->registerCss('
	.field-vendor-category_id .dropdown-toggle{
		width: 100%;
	}
	.table-social .form-group {
		margin-bottom: 0px;
	}
	.table-social td {
		vertical-align: middle !important;
	}
	.table-social .help-block{
		margin-bottom:0px;
		margin-top:2px;
	}
	.working_hours_wrapper .col-md-2 {
		padding-right: 0px;
	}
');

if($model->isNewRecord) {
	echo Html::hiddenInput('isNewRecord', 1, ['id' => 'isNewRecord']);
} else {
	echo Html::hiddenInput('isNewRecord', 0, ['id' => 'isNewRecord']);
}

echo Html::hiddenInput('logo_url', Url::to(['vendor/vendor-logo']), ['id' => 'logo_url']);
echo Html::hiddenInput('basic_info_url', Url::to(['vendor/basic-info']), ['id' => 'basic_info_url']);
echo Html::hiddenInput('main_info_url', Url::to(['vendor/main-info']), ['id' => 'main_info_url']);
echo Html::hiddenInput('additional_info_url', Url::to(['vendor/additional-info']), ['id' => 'additional_info_url']);
echo Html::hiddenInput('social_info_url', Url::to(['vendor/social-info']), ['id' => 'social_info_url']);
echo Html::hiddenInput('email_addresses_url', Url::to(['vendor/email-addresses']), ['id' => 'email_addresses_url']);
echo Html::hiddenInput('vendor_validate_url', Url::to(['vendor/vendor-validate']), ['id' => 'vendor_validate_url']);
echo Html::hiddenInput('croped_image_upload_url', Url::to(['vendor/croped-image-upload']), ['id' => 'croped_image_upload_url']);

$this->registerJs('
	var validate_vendor_url = "'.Url::to(['vendor/validate-vendor']).'";
	var vendornamecheck_url = "'.Url::to(['/vendor/vendornamecheck']).'";
	var emailcheck_url = "'.Url::to(['/vendor/emailcheck']).'";
	var vendor_status = "'.$model->vendor_status.'";
	var approve_status = "'.$model->approve_status.'";
', View::POS_HEAD);

$this->registerCssFile('@web/themes/default/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css');
$this->registerCssFile('@web/themes/default/plugins/bootstrap-multiselect/dist/css/bootstrap-multiselect.css');

$this->registerJsFile("@web/themes/default/plugins/bootstrap-multiselect/dist/js/bootstrap-multiselect.js", ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerJsFile("@web/themes/default/plugins/bootstrap-datetimepicker/js/moment.js", ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerJsFile("@web/themes/default/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js", ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerJsFile("@web/themes/default/plugins/ckeditor/ckeditor.js", ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerJsFile("@web/themes/default/js/jquery.cropit.js", ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerJsFile("@web/themes/default/js/vendor.js?V=1.10", ['depends' => [\yii\web\JqueryAsset::className()]]);
