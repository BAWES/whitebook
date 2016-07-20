<?php

use yii\helpers\Url;

$this->title ='Account Setting | Whitebook';

?>

<!-- coniner start -->
<section id="inner_pages_sections">
	<div class="container">
		<div class="title_main">
			<h1><?php echo Yii::t('frontend','Account Settings'); ?></h1>
		</div>

		<div class="account_setings_sections">
			<div class="col-md-2 hidde_res"></div>
			<div class="col-md-8">
				<div class="accont_informations">
					<div class="accont_info">
						<div class="account_title">
							<div id="acc_status" style="color:green;margin-bottom: 10px;"></div>
							<h4><?= Yii::t('frontend','Account Info'); ?></h4>
						</div>
						<div class="account_form">
							<div class="bs-example" data-example-id="basic-forms">
								<form method="POST" action="<?php echo Url::toRoute('users/edit_profile'); ?>" name="account_setting" id="account_setting" name="account_setting">
									<div class="col-md-6 paddingleft0">
										<div class="form-group">
											<label for="exampleInputEmail1"><?= Yii::t('frontend','First Name');?></label>
											<input type="hidden" id="_csrf" name="_csrf" value="<?= Yii::$app->request->csrfToken; ?>" />
											<input type="text" name="first_name" id="first_name"  maxlength="50" class="form-control required" placeholder="<?php echo Yii::t('frontend','Enter First Name');?>" title="<?php echo Yii::t('frontend','Enter First Name');?>" value="<?php echo $user_detail[0]['customer_name'];?>">
										</div></div>
										<div class="col-md-6 paddingright0">
											<div class="form-group">
												<label for="exampleInputPassword1"><?= Yii::t('frontend','Last Name');?></label>
												<input type="text" class="form-control required" maxlength="50" name="last_name" id="last_name" placeholder="<?php echo Yii::t('frontend','Enter Last Name');?>" title="<?= Yii::t('frontend','Enter Last Name');?>" value="<?php echo $user_detail[0]['customer_last_name'];?>">
											</div>
										</div>
										<div class="col-md-6 paddingleft0">
											<div class="form-group">
												<label for="exampleInputEmail1"><?= Yii::t('frontend','Email Address');?></label>
												<input type="text" class="form-control required email" maxlength="100" name="customer_email" id="customer_email" placeholder="<?php echo Yii::t('frontend','Enter your email address');?>" title="<?php echo Yii::t('frontend','Enter your email address');?>" value="<?php echo $user_detail[0]['customer_email'];?>" readonly>
											</div></div>
											<?php $dob=$user_detail[0]['customer_dateofbirth'];
											$year= date('Y', strtotime($dob));
											$month= date('m', strtotime($dob));
											$day= date('d', strtotime($dob));
											?>
											<div class="col-md-6 paddingright0">
												<div class="form-group">
													<label for="exampleInputPassword1"><?= Yii::t('frontend','Date of Birth'); ?></label>
													<div class="col-md-3 col-xs-3 paddingleft0">
														<div class="select_boxes">
															<select name="bday_detail" id="bday_detail" class="selectpicker" data-size="10" data-style="btn-primary">
																<option value="">Day</option>
																<?php for($i=1;$i<=31;$i++)
																{ ?>
																	<option value="<?php echo $i; ?>" <?php if(isset($day) && $day==$i) { echo "selected=selected"; } ?>><?php echo $i; ?></option>
																	<?php
																}
																?>
															</select>
														</div>
													</div>
													<div class="col-md-4 col-xs-4 paddingcommon"><div class="select_boxes">
														<select class="selectpicker" data-style="btn-primary" data-size="10" id="bmonth_detail" name="bmonth_detail">
															<option value=""><?= Yii::t('frontend','Month'); ?></option>
															<option value="1" <?php if(isset($month) && $month==1) { echo "selected=selected"; } ?>>Jan</option>
															<option value="2" <?php if(isset($month) && $month==2) { echo "selected=selected"; } ?>>Feb</option>
															<option value="3" <?php if(isset($month) && $month==3) { echo "selected=selected"; } ?>>Mar</option>
															<option value="4" <?php if(isset($month) && $month==4) { echo "selected=selected"; } ?>>Apr</option>
															<option value="5" <?php if(isset($month) && $month==5) { echo "selected=selected"; } ?>>May</option>
															<option value="6" <?php if(isset($month) && $month==6) { echo "selected=selected"; } ?>>Jun</option>
															<option value="7" <?php if(isset($month) && $month==7) { echo "selected=selected"; } ?>>Jul</option>
															<option value="8" <?php if(isset($month) && $month==8) { echo "selected=selected"; } ?>>Aug</option>
															<option value="9" <?php if(isset($month) && $month==9) { echo "selected=selected"; } ?>>Sep</option>
															<option value="10" <?php if(isset($month) && $month==10) { echo "selected=selected"; } ?>>Oct</option>
															<option value="11" <?php if(isset($month) && $month==11) { echo "selected=selected"; } ?>>Nov</option>
															<option value="12" <?php if(isset($month) && $month==12) { echo "selected=selected"; } ?>>Dec</option>

														</select>
													</div></div>
													<div class="col-md-5 col-xs-5 paddingright0"><div class="select_boxes">
														<select class="selectpicker" id="byear_detail" name="byear_detail" data-size="10" data-style="btn-primary">
															<option value=""><?= Yii::t('frontend','Year'); ?></option>
															<?php
															$current= date('Y');
															$current= $current-5;
															for($i=$current; $i>1950; $i--) {
																if(isset($year) && $year==$i)
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

													</div>  </div>
													<div id="dob_error" class="error"></div>
												</div>
											</div>
											<div class="col-md-6 paddingleft0">
												<div class="form-group">
													<label for="exampleInputEmail1"><?= Yii::t('frontend','Gender'); ?></label>
													<div class="select_boxes">
														<select class="selectpicker" data-style="btn-primary" id="gender_detail" name="gender_detail">
															<option value=""><?php echo Yii::t('frontend','Select Gender');?></option>
															<option value="Male" <?php if(isset($user_detail[0]['customer_gender']) && $user_detail[0]['customer_gender']=='Male') { echo "selected=selected"; } ?>><?= Yii::t('frontend','Male'); ?></option>
															<option value="Female" <?php if(isset($user_detail[0]['customer_gender']) && $user_detail[0]['customer_gender']=='Female') { echo "selected=selected"; } ?>><?= Yii::t('frontend','Female'); ?></option>
														</select>
													</div>

												</div>
												<div id="gen_error" class="error"></div>
											</div>

											<div class="col-md-6 paddingright0">
												<div class="form-group">
													<label for="exampleInputPassword1"><?= Yii::t('frontend','Mobile Number');?></label>
													<input type="text" class="form-control required" name="mobile_number_detail" id="mobile_number_detail" placeholder="<?php echo Yii::t('frontend','Enter Mobile Number');?>" title="<?php echo Yii::t('frontend','Enter Mobile Number');?>" value="<?php echo $user_detail[0]['customer_mobile'];?>">
												</div>
											</div>


											<div class="address_informations">
												<div class="address_title">
													<h3><?= Yii::t('frontend','Address Information');?></h3>
												</div>


												<div class="col-md-6 paddingleft0">
													<div class="form-group">
														<label for="exampleInputEmail1"><?= Yii::t('frontend','Country');?></label>
														<select class="selectpicker" data-style="btn-primary"  data-size="10" id="country" name="country">
															<option value=""><?= Yii::t('frontend','Select country');?></option>
															<?php
															foreach($loadcountry as $key=>$val)
																{$count= isset($user_detail[0]['country_id']) ? $user_detail[0]['country_id'] : '';
															if(isset($count) && $count==$key) { $selected='selected'; }else {$selected='';}
															echo  '<option value="'.$key.'" '.$selected.'>'.$val.'</option>';
														}?>
													</select>
													<div id="country_er"  class="error"></div>
												</div>
											</div>

											<div class="col-md-6 paddingright0">
												<div class="form-group">
													<label for="exampleInputPassword1"><?= Yii::t('frontend','Area');?></label>
													<div class="select_boxes">
														<select class="selectpicker" data-size="10" data-style="btn-primary" id="city" name="city" >
															<option value=""><?= Yii::t('frontend','Select city');?></option>
															<?php foreach($loadcity as $key=>$val)
															{
																$city_count=isset($user_detail[0]['city_id']) ? $user_detail[0]['city_id'] : '';
																if(isset($city_count) && $city_count==$key) { $selected='selected'; }else {$selected='';}
																echo  '<option value="'.$key.'" '.$selected.'>'.$val.'</option>';
															}?>
														</select>
														<div id="city_er" class="error"></div>
													</div>
												</div>

<!--select class="selectpicker" data-style="btn-primary" id="city" name="cityy">
<option value="">Select</option>
</select-->


</div>
</div>


<div class="submitt_buttons">

	<button class="btn btn-default" type="button" title="Save Changes" id="saved" name="saved">
		<?= Yii::t('frontend','Save Changes');?>
	</button>
</div>
</form>
</div>
<div id="login_loader" style="display:none;text-align:center;margin-bottom: 10px;"><img src="<?php echo Url::to("@web/images/ajax-loader.gif");?>" title="Loader"></div>
<div class="save_address">
<!--          <div class="account_title">
<h4>Saved Addresses</h4>
</div>
<div class="save_address_inner">
<ul>
<li>
<div class="col-md-11 col-xs-10">
<h2>Address Name Goes here</h2>
<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum <br/> has been the industry's standard dummy text ever since.</p>
</div>
<div class="col-md-1 col-xs-2">
<a href="#" title="" class="setings">&nbsp;</a>
<a href="#" title="" class="close_set">&nbsp;</a>
</div>
</li>
<li>
<div class="col-md-11 col-xs-10">
<h2>Address Name Goes here</h2>
<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum <br/> has been the industry's standard dummy text ever since.</p>
</div>
<div class="col-md-1 col-xs-2">
<a href="#" title="" class="setings">&nbsp;</a>
<a href="#" title="" class="close_set">&nbsp;</a>
</div>
</li>
<li>
<div class="col-md-11 col-xs-10">
<h2>Address Name Goes here</h2>
<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum <br/> has been the industry's standard dummy text ever since.</p>
</div>
<div class="col-md-1 col-xs-2">
<a href="#" title="" class="setings">&nbsp;</a>
<a href="#" title="" class="close_set">&nbsp;</a>
</div>
</li>
</ul>
<div class="submitt_buttons_save">

<button title="Save" type="submit" class="btn btn-default"> Save</button>
</div>
</div>
</div>-->

</div>
</div>
</div>
</div>
<div class="col-md-2 hidde_res"></div>
</div>
</div>

</section>



<!-- continer end -->


<script>
/* jQuery(document).ready(function () {
jQuery('#login_loader141').hide();
});*/
</script>



<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/jquery.validate.min.js">
	
</script>
<script type="text/javascript">
	$('#saved').on('click', function() {

		jQuery.noConflict();
		var gender=jQuery('#gender').val().length;
		var country=jQuery('#country').val().length;
		var city=jQuery('#city').val().length;


		var bday=jQuery("#bday_detail").val();
		var bmonth=jQuery("#bmonth_detail").val();
		var byear=jQuery("#byear_detail").val();
		var gender=jQuery("#gender_detail").val();

		var country=jQuery('#country').val();
		var city=jQuery('#city').val();
		var i=j=K=l=m=0;
		if(gender==0)
		{
			jQuery('#gen_error').show();
			jQuery('#gen_error').html('Select gender');
		}
		else
		{
			jQuery('#gen_error').hide();
			i=1;
		}
		
		if((country==0)&&(country==''))
		{
			jQuery('#country_er').html('Select country');
		}
		else
		{
			jQuery('#country_er').hide();
			l=1;
		}
		if((city==0)&&(city==''))
		{
			jQuery('#city_er').html('Select City');
		}
		else
		{
			jQuery('#city_er').hide();
			m=1;
		}
		if(bday=='' && bmonth=='' && byear=='')
		{
			jQuery('#dob_error').show();
			jQuery('#dob_error').text("CHOOSE DATE OF BIRTH");
		}
		else if(bday=='' && bmonth=='')
		{
			jQuery('#dob_error').show();
			jQuery('#dob_error').text("CHOOSE DATE AND MONTH OF BIRTH");
		}
		else if(bday=='' && byear=='')
		{
			jQuery('#dob_error').show();
			jQuery('#dob_error').text("CHOOSE DATE AND YEAR OF BIRTH");
		}
		else if(bmonth=='' && byear=='')
		{
			jQuery('#dob_error').show();
			jQuery('#dob_error').text("CHOOSE MONTH AND YEAR OF BIRTH");
		}
		else if(bmonth=='')
		{
			jQuery('#dob_error').show();
			jQuery('#dob_error').text("CHOOSE MONTH OF BIRTH");
		}
		else if(byear=='')
		{
			jQuery('#dob_error').show();
			jQuery('#dob_error').text("CHOOSE YEAR OF BIRTH");
		}
		else if(bday=='')
		{
			jQuery('#dob_error').show();
			jQuery('#dob_error').text("CHOOSE DAY OF BIRTH");
		}
		else
		{
			jQuery('#dob_error').hide();
			j=1;
		}

		if(jQuery('#account_setting').valid() && i==1 && j==1&& l==1&& m==1)
		{
			jQuery('#login_loader').show();

			//jQuery("#loader1").show();
			//var x=document.getElementById("customer_email").value;
			var fname=jQuery("#first_name").val();

			var lname=jQuery("#last_name").val();
			var mobile_number=jQuery("#mobile_number_detail").val();
			var password=jQuery("#customer_password").val();
			var conPassword=jQuery("#customer_password").val();
			var customer_address=jQuery("#customer_address").val();

			var judda=jQuery("#judda").val();
			var myphone=jQuery("#phone_detail1").val();

			var block=jQuery("#block").val();
			var street=jQuery("#street").val();
			var extra=jQuery("#extra").val();

			jQuery.ajax({
				url:"<?= Url::toRoute('/users/edit_profile'); ?>",
				type:"post",
				data:"first_name="+fname+"&last_name="+lname+"&bday="+bday+"&bmonth="+bmonth+"&byear="+byear+"&phone="+myphone+"&juda="+judda+"&gender="+gender+"&mobile_number="+mobile_number+"&address_name="+customer_address+"&country="+country+"&city="+city+"&block="+block+"&street="+street+"&extra="+extra+"&customer_password="+password+"&customer_password="+conPassword,
				async: false,
				success:function(data)
				{
					if(data==1)
					{
						jQuery('#login_loader').hide();
						jQuery('#login_success').modal('show');
						jQuery('#success').html('<span class="sucess_close">&nbsp;</span><span class="msg-success" style="margin-top: 5px; width: 320px; float: left; text-align: left;">Account information saved successfully!</span>');

						// window.setTimeout(function(){location.reload()},2000)
					}
				}
			});

		}
	});


	function validateEmail(mail)
	{
		if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(form1.useremail.value))
		{
			return (1);
		}

		return (0);
	}

jQuery("#phone_detail1").keypress(function (e) {
	//if the letter is not digit then display error and don't type anything
	if (  e.which  != 43   && e.which  != 45 && e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57 )) {
	//display error message
	jQuery("#phone_detail1").find('.error').html('Contact number digits only+.');
	return false;
	}
});

jQuery("#mobile_number_detail").keypress(function (e) {
	//if the letter is not digit then display error and don't type anything
	if (  e.which  != 43   && e.which  != 45 && e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57 )) {
		//display error message
		jQuery("#mobile_number_detail").find('.error').html('Contact number digits only+.');
		return false;
	}
});

</script>

<script type="text/javascript">
	$(function (){
		$("#country").change(function (){
			jQuery('#country_er').html('');
			var csrfToken = jQuery('#_csrf').val();
			var country_id = jQuery('#country').val();
			var path = "<?php echo Url::to(['/site/city']); ?> ";
			jQuery.ajax({
				type: 'POST',
				asynch: false,
				url: path, //url to be called
				data: { country_id: country_id ,_csrf : csrfToken}, //data to be send
				success: function( data ) {
					jQuery('#city').html(data);
					jQuery('#city').selectpicker('refresh');
				}
			});
		});

		$("#city").change(function (){
			jQuery('#city_er').html('');
		});

	});
</script>
