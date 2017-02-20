<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\bootstrap\Alert;
use dosamigos\ckeditor\CKEditor;

$this->title = 'My Profile';
$this->params['breadcrumbs'][] = $this->title;

$from_am = explode(':',$model->vendor_working_hours);
$to_am = explode(':',$model->vendor_working_hours_to);

$from_hour = (isset($from_am[0])) ? $from_am[0] : '';
$to_hour = (isset($to_am[0])) ? $to_am[0] : '';

$from_min = (isset($from_am[1])) ? $from_am[1] : '';
$to_min = (isset($to_am[1])) ? $to_am[1] : '';

$from = (isset($from_am[2])) ? $from_am[2] : '';
$to = (isset($to_am[2])) ? $to_am[2] : '';

?>

<style>
	.margin-left-2{margin-left: 2px}
</style>
<div class="col-md-12 col-sm-12 col-xs-12">
    <?php $form = ActiveForm::begin(['options' => ['enctype'=>'multipart/form-data']]); ?>
    	<div class="loadingmessage" style="display: none;"><p><?= Html::img(Yii::getAlias('@web/themes/default/img/loading.gif'), ['class'=>'','width'=>'64px','height'=>'64px','id'=>'loading','alt'=>'loading']);?></p></div>
		
		<div class="message_wrapper"></div>

		<div class="tabbable">
			<ul class="nav nav-tabs">
				<li class="active">
			      <a href="#0" data-toggle="tab">Vendor Logo </a>
			    </li>
				<li><a href="#1" data-toggle="tab">Basic Info </a></li>
				<li><a href="#2" data-toggle="tab" class="onevalid1">Main Info</a></li>
				<li><a href="#3" data-toggle="tab" class="twovalid2">Additional Info</a></li>
				<li><a href="#4" data-toggle="tab" class="twovalid2">Social Info</a></li>
				<li><a href="#5" data-toggle="tab" class="emails twovalid2">Email addresses</a></li>
			</ul>
			<div class="tab-content">

			  	<div class="tab-pane" id="0">
			    	<label class="control-label">Vendor logo</label>
		    		<div class="image-editor">
				        <input type="file" class="cropit-image-input">
				        <p style="color: red;">
				    		Minimum image dimension : 565px x 565px
				    	</p>
				        <div class="cropit-preview" style="width: 565px; height: 565px;">
				        	<?php

				        	if($model->vendor_logo_path) {
					        	$src = Yii::getAlias('@vendor_logo') . '/' . $model->vendor_logo_path;
					        }else{
					        	$src = 'https://placeholdit.imgix.net/~text?txtsize=20&txt=Drag%20and%20Drop%20Image%20Here
					        	&w=565&h=565';
					        } ?>

				        	<img class="cropit-preview-image" alt="" src="<?= $src ?>" style="transform-origin: left top 0px; will-change: transform;" />
				        </div>
				        <div class="image-size-label">
				          Resize image
				        </div>
				        <input type="range" class="cropit-image-zoom-input" style="width: 565px;">
				        <input type="hidden" name="image" />
				    </div>

				    <div class="clearfix"></div>

				    <div class="form-group" style="height: 10px;">
						<input type="button" class="btn btn-info btnNext" value="Next" />
						<?= Html::a('Back', ['index'], ['class' => 'btn btn-default']) ?>
					</div>
			  	</div>

                <!-- Begin First Tab -->
				<div class="tab-pane" id="1">
                    <?= $form->field($model, 'vendor_name')
				    		->textInput(['maxlength' => 100]); ?>

					<?= $form->field($model, 'vendor_name_ar')
							->textInput(['maxlength' => 100]); ?>

					<?= $form->field($model, 'vendor_contact_name')
							->textInput(['maxlength' => 100]); ?>

					<?= $form->field($model, 'vendor_contact_email')
							->textInput(['maxlength' => 100, 'readonly' => true]) ?>

					<div class="form-group" style="border: 1px solid #ccc;  padding: 5px;  font-size: 14px;">
						<label class="control-label" for="vendor-vendor_contact_number">Contact Phone Number</label>
						<?php
						$i =1;
						$count_vendor =  count($vendor_contact_number);
						foreach($vendor_contact_number as $contact_numbers)
						{
							?>
						<?= $form->field($model, 'vendor_contact_number[]',[  'template' => "<div class='controls".$i."'>{input}<input type='button' name='remove' id='remove' value='Remove' onClick='removePhone(".$i.")' style='margin:5px;' /></div> {hint} {error}"])->textInput(['multiple' => 'multiple','autocomplete' => 'off','value'=>$contact_numbers]) ?>

						<?php $i++; } ?>
						<input type="button" name="add_item" id="addnumber" value="Add phone numbers" onClick="addPhone('current');" style="margin:5px;" />

					</div>

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

					<div class="form-group" style="clear:both;"><?= $form->field($model, 'vendor_contact_address',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}"])->textArea() ?></div>
					<div class="form-group" style="clear:both;"><?= $form->field($model, 'vendor_contact_address_ar',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}"])->textArea() ?></div>
					<div class="clearfix">
						<div class="col-md-6"><?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success submit_btn' : 'btn btn-primary submit_btn','style'=>'float:right;']) ?></div>
						<div class="col-md-6"><div class="form-group" style="height: 10px;"><input type="button" name="btnPrevious" class="btnNext btn btn-info" value="Next"></div></div>
					</div>
				</div>

				<!--End First Tab -->

				<div class="tab-pane" id="2">

					<div class="form-group">
					<p style="font-size:14px;"> Category</p><p style="font-weight:bold; border:1px solid #ccc;padding:5px"> <?php echo implode(' , ', $vendor_categories); ?> </p>
					</div>

					<input type="hidden" id="test1" value="0" name="tests">

					<?= $form->field($model, 'vendor_return_policy')->textArea(['id'=>'text-editor']); ?>

					<?= $form->field($model, 'vendor_return_policy_ar')->textArea(['id'=>'text-editor-2']); ?>

					<?= $form->field($model, 'vendor_fax'); ?>

					<?= $form->field($model, 'short_description')->textArea(); ?>

					<?= $form->field($model, 'short_description_ar')->textArea() ?>

					<?= $form->field($model, 'vendor_bank_name'); ?>

					<?= $form->field($model, 'vendor_bank_branch'); ?>

					<?= $form->field($model, 'vendor_account_no'); ?>
					<div class="clearfix">
						<div class="col-md-4"><input type="button" name="btnPrevious" class="btnPrevious btn btn-info" value="Prev"></div>
						<div class="col-md-4 text-center"><?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success submit_btn' : 'btn btn-primary submit_btn']) ?></div>
						<div class="col-md-4"><input type="button" name="btnNext" class="btnNext btn btn-info" value="Next"></div>
					</div>
				</div>
				<!--End Third Tab -->

				<div class="tab-pane" id="3">

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

					<?= $form->field($model, 'vendor_public_email')->textInput(['maxlength' => 100]); ?>

					<?= $form->field($model, 'vendor_emergency_contact_email')
							->textInput(['maxlength' => 100]); ?>

					<?= $form->field($model, 'vendor_website')->textInput(['maxlength' => 100]); ?>

					<div class="clearfix">
					<div class="col-md-4"><input type="button" name="btnPrevious" class="btnPrevious btn btn-info" value="Prev"></div>
					<div class="col-md-4 text-center"><?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success submit_btn' : 'btn btn-primary submit_btn']) ?></div>
					<div class="col-md-4"><input type="button" name="btnNext" class="btnNext btn btn-info" value="Next"></div>
					</div>
				</div>

				<div class="tab-pane" id="4">
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

					<div class="form-group clearfix">
						<div class="col-md-4" ><input type="button" name="btnPrevious" class="btnPrevious btn btn-info" value="Prev"></div>
						<div class="col-md-4 text-center"><?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success submit_btn' : 'btn btn-primary submit_btn']) ?></div>
						<div class="col-md-4" ><input type="button" name="btnNext" class="btnNext btn btn-info" value="Next"></div>
					</div>
				</div>
                <div class="tab-pane" id="5">

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
									<span class="error"></span>
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
						<?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success submit_btn' : 'btn btn-primary submit_btn','style'=>'float:right;']) ?>
					</div>
				</div>

			</div>
		</div>
    <?php ActiveForm::end(); ?>
</div>

<?php

$this->registerCss('
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
');

$this->registerCssFile('@web/themes/default/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css');

$this->registerJsFile("@web/themes/default/plugins/bootstrap-datetimepicker/js/moment.js", ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerJsFile("@web/themes/default/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js", ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerJsFile('@web/themes/default/plugins/ckeditor/ckeditor.js');
$this->registerJsFile("@web/themes/default/js/jquery.cropit.js", ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('@web/themes/default/js/profile.js?v=1.4', ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerJs('

	$(".submit_btn").click(function(){
		
		var filter = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;
		var error = false;
		
		if(($(".table-email-list input").length)>0) {
			$(".table-email-list input").each(function(i,data) {
			$(this).next().empty();
				if (!filter.test($(this).val())) {
					$(this).next().html("Invalid Email address");
					error = true;
				}
			})
		}
		
		if (error) {
			$(\'.nav-tabs a[href="#5"]\').tab(\'show\') // Select tab by name
			return false;
		} else {
			return true;
		}
	});

',\yii\web\View::POS_READY);
