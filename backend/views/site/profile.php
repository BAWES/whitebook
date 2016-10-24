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
    <?php $form = ActiveForm::begin(['options'=>['enctype'=>'multipart/form-data']]); ?>
		<div class="loadingmessage" style="display: none;"><p><?= Html::img(Yii::getAlias('@web/themes/default/img/loading.gif'), ['class'=>'','width'=>'64px','height'=>'64px','id'=>'loading','alt'=>'loading']);?></p></div>
		<div class="tabbable">
			<ul class="nav nav-tabs">
				<li class="active"><a href="#1" data-toggle="tab">Basic Info </a></li>
				<li><a href="#2" data-toggle="tab" class="onevalid1">Main Info</a></li>
				<li><a href="#3" data-toggle="tab" class="twovalid2">Additional Info</a></li>
				<li><a href="#4" data-toggle="tab" class="twovalid2">Social Info</a></li>
				<li><a href="#5" data-toggle="tab" class="twovalid2">Email addresses</a></li>
			</ul>
			<div class="tab-content">
				<!-- Begin First Tab -->
				<div class="tab-pane" id="1">

					<?php 

					$lbl = 'Vendor logo'.Html::tag('span', '*',['class'=>'required']);

					if(isset($model->vendor_logo_path)) {						
						$lbl .= '<br />';
						$lbl .= Html::img(Yii::getAlias('@vendor_logo/').$model->vendor_logo_path, [
									'class' => '',
									'width' => '125px',
									'height' => '125px',
									'alt' => 'Logo',
									'style' => 'margin-top: 10px;'
								]);
					} ?>
											
					<?= $form->field($model, 'vendor_logo_path')
							->label($lbl)
							->fileInput(['class' => 'form-group vendor_logo'])
							->hint('Logo Size 150 * 250'); ?>


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

					<div class="row margin-left-2">
						<div class="form-group" style="width: 150px; float: left;"><?php
                            $model->vendor_working_hours = $from_hour;
                            echo $form->field($model, 'vendor_working_hours',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}"])->dropDownList(['01'=>'01','02'=>'02','03'=>'03','04'=>'04','05'=>'05','06'=>'06','07'=>'07','08'=>'08','09'=>'09','10'=>'10','11'=>'11','12'=>'12'])->label(); ?></div>
						<div class="form-group" style="width: 150px; float: left;  margin-left: 25px;"><?php
                            $model->vendor_working_min = $from_min;
                            echo $form->field($model, 'vendor_working_min',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}"])->dropDownList(['00'=>'00','01'=>'01','02'=>'02','03'=>'03','04'=>'04','05'=>'05','06'=>'06','07'=>'07','08'=>'08','09'=>'09','10'=>'10','11'=>'11','12'=>'12','13'=>'13','14'=>'14','15'=>'15','16'=>'16','17'=>'17','18'=>'18','19'=>'19','20'=>'20','21'=>'21','22'=>'22','23'=>'23','24'=>'24','25'=>'25','26'=>'26','27'=>'27','28'=>'28','29'=>'29','30'=>'30','31'=>'31','32'=>'32','33'=>'33','34'=>'34','35'=>'35','36'=>'36','37'=>'37','38'=>'38','39'=>'39','40'=>'40','41'=>'41','42'=>'42','43'=>'43','44'=>'44','45'=>'45','46'=>'46','47'=>'47','48'=>'48','49'=>'49','50'=>'50','51'=>'51','52'=>'52','53'=>'53','54'=>'54','55'=>'55','56'=>'56','57'=>'57','58'=>'58','59'=>'59'])->label(); ?></div>
						<div class="form-group" style="width: 150px; float: left;  margin-left: 25px;">
							<label for="vendor-vendor_working_min" class="control-label">&nbsp;</label>
							<div class="controls">
								<?= Html::dropDownList( 'vendor_working_am_pm_from',$from,['am'=>'AM','pm'=>'PM'],['class'=>'form-control']); ?>
							</div>
						</div>
					</div>
					
					<div class="row margin-left-2">
						<div class="form-group" style="width: 150px; float: left;"><?php
                            $model->vendor_working_hours_to = $to_hour;
                            echo $form->field($model, 'vendor_working_hours_to',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}"])->dropDownList(['01'=>'01','02'=>'02','03'=>'03','04'=>'04','05'=>'05','06'=>'06','07'=>'07','08'=>'08','09'=>'09','10'=>'10','11'=>'11','12'=>'12'])->label(); ?></div>
						<div class="form-group" style="width: 150px; float: left;  margin-left: 25px;"><?php
                            $model->vendor_working_min_to = $to_min;
                            echo $form->field($model, 'vendor_working_min_to',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}"])->dropDownList(['00'=>'00','01'=>'01','02'=>'02','03'=>'03','04'=>'04','05'=>'05','06'=>'06','07'=>'07','08'=>'08','09'=>'09','10'=>'10','11'=>'11','12'=>'12','13'=>'13','14'=>'14','15'=>'15','16'=>'16','17'=>'17','18'=>'18','19'=>'19','20'=>'20','21'=>'21','22'=>'22','23'=>'23','24'=>'24','25'=>'25','26'=>'26','27'=>'27','28'=>'28','29'=>'29','30'=>'30','31'=>'31','32'=>'32','33'=>'33','34'=>'34','35'=>'35','36'=>'36','37'=>'37','38'=>'38','39'=>'39','40'=>'40','41'=>'41','42'=>'42','43'=>'43','44'=>'44','45'=>'45','46'=>'46','47'=>'47','48'=>'48','49'=>'49','50'=>'50','51'=>'51','52'=>'52','53'=>'53','54'=>'54','55'=>'55','56'=>'56','57'=>'57','58'=>'58','59'=>'59'])->label(); ?></div>
						<div class="form-group" style="width: 150px; float: left;  margin-left: 25px;">
							<label for="vendor-vendor_working_min" class="control-label">&nbsp;</label>
							<div class="controls">
								<?= Html::dropDownList( 'vendor_working_am_pm_to',$to,['am'=>'AM','pm'=>'PM'],['class'=>'form-control']); ?>
							</div>
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
					<div class="form-group" style="height: 10px;"><input type="button" name="btnPrevious" class="btnNext btn btn-info" value="Next"></div>
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

					<input type="button" name="btnPrevious" class="btnPrevious btn btn-info" value="Prev">
					<input type="button" name="btnNext" class="btnNext btn btn-info" value="Next">
				</div>
				<!--End Third Tab -->

				<div class="tab-pane" id="3">
				
					<?= $form->field($model, 'vendor_public_phone')->textInput(['maxlength' => 100]); ?>

					<?= $form->field($model, 'vendor_public_email')->textInput(['maxlength' => 100]); ?>

					<?= $form->field($model, 'vendor_emergency_contact_email')
							->textInput(['maxlength' => 100]); ?>

					<?= $form->field($model, 'vendor_website')->textInput(['maxlength' => 100]); ?>

					<input type="button" name="btnPrevious" class="btnPrevious btn btn-info" value="Prev">
					<input type="button" name="btnNext" class="btnNext btn btn-info" value="Next">
				</div>

				<div class="tab-pane" id="4">

					<?= $form->field($model, 'vendor_facebook')->textInput(['maxlength' => 100]); ?>

					<?= $form->field($model, 'vendor_twitter')->textInput(['maxlength' => 100]); ?>

					<?= $form->field($model, 'vendor_instagram')->textInput(['maxlength' => 100]); ?>

					<?= $form->field($model, 'vendor_googleplus')->textInput(['maxlength' => 100]); ?>
					
					<?= $form->field($model, 'vendor_skype')->textInput(['maxlength' => 100]); ?>

					<div class="form-group">
						<input type="button" name="btnPrevious" class="btnPrevious btn btn-info" value="Prev">
						<input type="button" name="btnNext" class="btnNext btn btn-info" value="Next">
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

$this->registerJsFile('@web/themes/default/plugins/ckeditor/ckeditor.js');
$this->registerJsFile('@web/themes/default/js/profile.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
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
	};
	if (error) {
		return false;
	} else {
		return true;
	}

});

',\yii\web\View::POS_READY);
