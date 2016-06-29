<?php $this->title = 'Signup | Whitebook';?>
 <section id="signup_whitebook">
	<div class="top_sections_titles">
		 <div class="container ">
			 <div class="col-md-12">
				<div class="common_titles">
					<div class="text-center"><span class="yellow_top"></span> </div>
					<h1> <b><?php echo Yii::t('frontend','Register');?></b></h1>
					<p class="col-md-12 text-center">Lorem ipsum</p>
				</div>
			</div>
		</div>
	</div>
	<!-- Account registration form -->
	<div class="container container_innner">
	   <div class="col-md-12 col-xs-12">
		<div class="signup_common">
			<form name="signup" id="signup" method="post">
				<input type="hidden" id="_csrf" name="_csrf" value="<?= Yii::$app->request->csrfToken; ?>" />
			  <div class="account_title MTB15 ">
					<h2><?php echo Yii::t('frontend','Login Info');?></h2>
				</div>
				<div class="signup_log_form  col-md-12 col-xs-12 MTB30 padding0">
				<p>Lorem ipsum dummy text</p>
					<label class="MT20 col-md-4"><?php echo Yii::t('frontend','Email Address');?></label>
					<div class="col-md-8 padding0">
					<input type="text" name="email" class="form-control required email" maxlength="75" title="<?php echo Yii::t('frontend','Enter your email address');?>" placeholder="<?php echo Yii::t('frontend','Enter your email address');?>" value="<?php if(isset($model['email'])) { echo $model['email']; } ?>">
					<p class="error"><?php if(isset($error['email'])){ echo $error['email'][0]; } ?></p>
					</div>
						<label class="MT20 col-md-4"><?php echo Yii::t('frontend','password');?></label>
					<div class="col-md-8 padding0">
						<input type="password" id="s_password" name="password" class="form-control required" maxlength="50"  title="<?php echo Yii::t('frontend','Enter your password'); ?>" placeholder="<?php echo Yii::t('frontend','Enter your password'); ?>" value="<?php if(isset($model['password'])) { echo $model['password']; } ?>">
						<p class="error"><?php if(isset($error['password'])){ echo $error['password'][0]; } ?></p>
					</div>
							<label class="MT20 col-md-4"><?php echo Yii::t('frontend','Confirm Password');?></label>
						<div class="col-md-8 padding0">
							<input type="password" name="confirm_password" maxlength="50" equalto="#s_password"  class="form-control required" title="<?php echo Yii::t('frontend','Confirm Password');?>" placeholder="<?php echo Yii::t('frontend','Enter your confirm password');?>" value="<?php if(isset($model['confirm_password'])) { echo $model['confirm_password']; } ?>">
							<p class="error"><?php if(isset($error['confirm_password'])){ echo $error['confirm_password'][0]; } ?></p>
						</div>
				</div>

				<div class="account_title MTB15 ">
					<h2><?php echo Yii::t('frontend','User Info'); ?></h2>
				</div>
				<div class="user_info col-md-12 col-xs-12 MTB15 padding0">
				<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry</p>
					<label class="MT20 col-md-4"><?php echo Yii::t('frontend','Name'); ?></label>
					<div class="col-md-8 padding0">
						 <input type="text" name="customer_name" maxlength="50" class="form-control required" placeholder="<?php echo Yii::t('frontend','Enter your name');?>" title="<?php echo Yii::t('frontend','Enter your name');?>" value="<?php if(isset($model['customer_name'])) { echo $model['customer_name']; } ?>">
						 <p class="error"><?php if(isset($error['customer_name'])){ echo $error['customer_name'][0]; } ?></p>
					</div>
					  <label class="MT20 col-md-4 "><?php echo Yii::t('frontend','Birth Date'); ?></label>
					<div class="col-md-8 padding0 birth_date_drop">
					<ul class="padding0">
						<li>
								 <select name="bday" id="bday" class="selectpicker" data-style="btn-primary" style="display: none;">
									  <option value="">Day</option>
										<?php for($i=1;$i<=31;$i++)
										{ ?>
											<option value="<?php echo $i; ?>" <?php if(isset($model['bday']) && $model['bday']==$i) { echo "selected=selected"; } ?>><?php echo $i; ?></option>
										<?php
										}
										?>
								</select>
						</li>
						<li>
								  <select name="bmonth"  id="bmonth" class="selectpicker" data-style="btn-primary" style="display: none;">
								  <option value="">Month</option>
								  <option value="1" <?php if(isset($model['bmonth']) && $model['bmonth']==1) { echo "selected=selected"; } ?>>Jan</option>
								  <option value="2" <?php if(isset($model['bmonth']) && $model['bmonth']==2) { echo "selected=selected"; } ?>>Feb</option>
								  <option value="3" <?php if(isset($model['bmonth']) && $model['bmonth']==3) { echo "selected=selected"; } ?>>Mar</option>
								  <option value="4" <?php if(isset($model['bmonth']) && $model['bmonth']==4) { echo "selected=selected"; } ?>>Apr</option>
								  <option value="5" <?php if(isset($model['bmonth']) && $model['bmonth']==5) { echo "selected=selected"; } ?>>May</option>
								  <option value="6" <?php if(isset($model['bmonth']) && $model['bmonth']==6) { echo "selected=selected"; } ?>>Jun</option>
								  <option value="7" <?php if(isset($model['bmonth']) && $model['bmonth']==7) { echo "selected=selected"; } ?>>Jul</option>
								  <option value="8" <?php if(isset($model['bmonth']) && $model['bmonth']==8) { echo "selected=selected"; } ?>>Aug</option>
								  <option value="9" <?php if(isset($model['bmonth']) && $model['bmonth']==9) { echo "selected=selected"; } ?>>Sep</option>
								  <option value="10" <?php if(isset($model['bmonth']) && $model['bmonth']==10) { echo "selected=selected"; } ?>>Oct</option>
								  <option value="11" <?php if(isset($model['bmonth']) && $model['bmonth']==11) { echo "selected=selected"; } ?>>Nov</option>
								  <option value="12" <?php if(isset($model['bmonth']) && $model['bmonth']==12) { echo "selected=selected"; } ?>>Dec</option>
							</select>
						</li>
						<li>
								<select  name="byear" id="byear" class="selectpicker" data-style="btn-primary" style="display: none;">
								  <option value="">Year</option>
								  <?php
									for($i=date('Y'); $i>1950; $i--) {
										if(isset($model['byear']) && $model['byear']==$i)
										{
											$sel= "selected=selected";
										}
										else
										{
											$sel='';
										}
										print('<option value="'.$i.'" '.$sel.' >'.$i.'</option>'."\n");
									}
									?>
							</select>
						</li>
						</ul>
						<label id="dob_er" class="error" style="display:none;"><?php echo Yii::t('frontend','Select Gender');?></label>
						<p class="error"><?php if(isset($error['bday'])){ echo $error['bday'][0]; } ?></p>
						<p class="error"><?php if(isset($error['bmonth'])){ echo $error['bmonth'][0]; } ?></p>
						<p class="error"><?php if(isset($error['byear'])){ echo $error['byear'][0]; } ?></p>
						</div>

						<label class="MT20 col-md-4"><?php echo Yii::t('frontend','Gender');?></label>
						<div class="col-md-8 padding0">

						<select name="gender" id="gender" class="selectpicker" data-style="btn-primary" style="display: none;">
							  <option value=""><?php echo Yii::t('frontend','Select Gender');?></option>
							  <option value="Male" <?php if(isset($model['gender']) && $model['gender']=='Male') { echo "selected=selected"; } ?>><?= Yii::t('frontend','Male');?></option>
							  <option value="Female" <?php if(isset($model['gender']) && $model['gender']=='Female') { echo "selected=selected"; } ?>><?= Yii::t('frontend','Female');?></option>
							  <option value="Other" <?php if(isset($model['gender']) && $model['gender']=='Other') { echo "selected=selected"; } ?>><?= Yii::t('frontend','Other');?></option>
						</select>
						<label id="gen_er" class="error" style="display:none;"><?php echo Yii::t('frontend','Select Gender');?></label>
						<p class="error"><?php if(isset($error['gender'])){ echo $error['gender'][0]; } ?></p>
						</div>
							<label class="MT20 col-md-4"><?php echo Yii::t('frontend','Phone');?></label>
							<div class="col-md-8 padding0">
								<input name="phone" type="text" maxlength="50" class="form-control required" placeholder="<?php echo Yii::t('frontend','Enter Phone Number');?>" title="<?php echo Yii::t('frontend','Enter Phone Number');?>" value="<?php if(isset($model['phone'])) { echo $model['phone']; } ?>">
								<p class="error"><?php if(isset($error['phone'])){ echo $error['phone'][0]; } ?></p>
							</div>
				</div>

				 <div class="col-md-12 signup_sav MT20">
				 <div class="MT40">
					 <label for="checkbox-01" class="label_check c_on"><input type="checkbox" checked="" value="1" id="checkbox-01" name="sample-checkbox-01"><?php echo Yii::t('frontend','Privacy Policy and Terms of Service');?></label><?= Url::to('Privacy Policy','#') ?> & <?= Url::to('Terms of service','#') ?></a>
					  </div>
				<div class="clearfix">
					<button type="button" name="signup_submit" onclick="submit_signup_form();" class="btn btn-default MTB15"><?php echo Yii::t('frontend','Create Account');?></button> </div>
					</div>
			</form>
			</div> <!-- account common ends -->
		  </div>
	  </div>
	</div> <!-- container ends-->
  <div class="common_space_content">   </div>
</section>
<script src="<?= Url::to("@web/js/bootstrap-select.js") ?>"></script>
<link href="<?= Url::to("@web/css/bootstrap-select.min.css") ?>" rel="stylesheet">
<script src="<?= Url::to("@web/js/script.js") ?>"></script>
